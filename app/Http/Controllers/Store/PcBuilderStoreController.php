<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\PcBuilderCategory;
use App\Models\PcBuilderProduct;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PcBuilderStoreController extends Controller
{
    public function index()
    {
        $categories = PcBuilderCategory::withCount('pcBuilderProducts')
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();

        $delivery_charges = [
            'inside_dhaka' => \App\Models\Setting::where('key', 'delivery_charge_inside_dhaka')->first()->value ?? 60,
            'outside_dhaka' => \App\Models\Setting::where('key', 'delivery_charge_outside_dhaka')->first()->value ?? 120,
        ];

        return Inertia::render('PcBuilder', [
            'categories' => $categories,
            'delivery_charges' => $delivery_charges,
        ]);
    }

    public function categoryProducts(PcBuilderCategory $category, Request $request)
    {
        $query = PcBuilderProduct::where('pc_builder_category_id', $category->id)
            ->with(['product' => function ($q) {
                $q->where('is_active', true);
            }]);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20);

        // Filter out null products (inactive)
        $products->getCollection()->transform(function ($item) {
            if (!$item->product) return null;
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'wattage' => $item->wattage,
                'product' => $item->product,
            ];
        })->filter();

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'selections' => 'required|array|min:1',
            'selections.*.category_id' => 'required|exists:pc_builder_categories,id',
            'selections.*.product_id' => 'required|exists:products,id',
            'selections.*.wattage' => 'integer|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'delivery_location' => 'required|string|in:Inside Dhaka,Outside Dhaka',
            'delivery_charge' => 'required|numeric',
            'customer_remarks' => 'nullable|string|max:1000',
        ]);

        // Validate required categories
        $requiredCategories = PcBuilderCategory::where('is_required', true)->pluck('id');
        $selectedCategoryIds = collect($validated['selections'])->pluck('category_id');
        $missing = $requiredCategories->diff($selectedCategoryIds);

        if ($missing->isNotEmpty()) {
            $missingNames = PcBuilderCategory::whereIn('id', $missing)->pluck('name')->join(', ');
            return back()->withErrors(['selections' => "Required components missing: {$missingNames}"]);
        }

        // Build the pc_build_data for the order
        $pcBuildData = [];
        $totalAmount = 0;
        $totalWattage = 0;

        foreach ($validated['selections'] as $selection) {
            $product = Product::findOrFail($selection['product_id']);
            $category = PcBuilderCategory::findOrFail($selection['category_id']);
            $wattage = $selection['wattage'] ?? 0;

            $price = $product->discounted_price;
            $totalAmount += $price;
            $totalWattage += $wattage;

            $pcBuildData[] = [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price' => $price,
                'wattage' => $wattage,
            ];
        }

        $totalAmount += $validated['delivery_charge'];

        $userId = Auth::id();
        if (!$userId && !empty($validated['customer_email'])) {
            $user = \App\Models\User::where('email', $validated['customer_email'])->first();
            if ($user) $userId = $user->id;
        }

        $order = Order::create([
            'id' => Order::generateUniqueId(),
            'user_id' => $userId,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'village' => $validated['village'],
            'total_amount' => $totalAmount,
            'delivery_charge' => $validated['delivery_charge'],
            'delivery_location' => $validated['delivery_location'],
            'payment_method' => 'cod',
            'status' => 'pending',
            'is_active' => true,
            'customer_remarks' => $validated['customer_remarks'] ?? null,
            'pc_build_data' => [
                'total_wattage' => $totalWattage,
                'components' => $pcBuildData,
            ],
        ]);

        // Create order items for each selected product
        foreach ($validated['selections'] as $selection) {
            $product = Product::findOrFail($selection['product_id']);

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->discounted_price,
                'buying_price' => $product->buying_price,
                'package_cost' => $product->package_cost,
                'quantity' => 1,
            ]);

            // Reduce stock
            if ($product->stock >= 1) {
                $product->decrement('stock', 1);
            }
        }

        // Notify Admin
        try {
            $adminEmail = \App\Models\Setting::where('key', 'footer_email')->value('value_en');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)
                    ->send(new \App\Mail\OrderCreated($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send PC build order email: ' . $e->getMessage());
        }

        return redirect()->route('thank-you', $order->id);
    }
}

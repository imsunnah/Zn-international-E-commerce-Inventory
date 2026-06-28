<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::select('id', 'name_en', 'name_bn')->get();
        
        $query = Product::with(['category', 'brand'])->latest();

        // Handle Active vs Catalog modes
        if ($request->mode === 'active') {
            $query->whereNotNull('discount_type')->where('discount_value', '>', 0);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name_en', 'like', '%' . $request->search . '%')
                  ->orWhere('name_bn', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(24)->withQueryString();

        return Inertia::render('Admin/Discount/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id', 'mode'])
        ]);
    }

    public function applyBulkDiscount(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'discount_type' => 'required|in:percentage,flat,none',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'discount_type' => $validated['discount_type'] === 'none' ? null : $validated['discount_type'],
            'discount_value' => $validated['discount_type'] === 'none' ? 0 : ($validated['discount_value'] ?? 0),
        ];

        Product::whereIn('id', $validated['product_ids'])->update($updateData);

        return back()->with('success', 'Bulk discount applied successfully to ' . count($validated['product_ids']) . ' products.');
    }
}

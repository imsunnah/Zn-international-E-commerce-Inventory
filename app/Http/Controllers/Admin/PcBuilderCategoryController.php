<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PcBuilderCategory;
use App\Models\PcBuilderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PcBuilderCategoryController extends Controller
{
    public function index()
    {
        $categories = PcBuilderCategory::withCount('pcBuilderProducts')
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/PcBuilder/Categories', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_required' => 'boolean',
            'group' => 'required|in:core,peripherals',
        ]);

        PcBuilderCategory::create($validated);

        return redirect()->route('admin.pc-builder-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, PcBuilderCategory $pc_builder_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_required' => 'boolean',
            'group' => 'required|in:core,peripherals',
        ]);

        $pc_builder_category->update($validated);

        return redirect()->route('admin.pc-builder-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(PcBuilderCategory $pc_builder_category)
    {
        $pc_builder_category->delete();

        return redirect()->route('admin.pc-builder-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function products(PcBuilderCategory $category)
    {
        $linkedProducts = PcBuilderProduct::where('pc_builder_category_id', $category->id)
            ->with('product')
            ->get();

        $allProducts = Product::where('is_active', true)
            ->whereNotIn('id', $linkedProducts->pluck('product_id'))
            ->orderBy('name_en')
            ->get();

        return Inertia::render('Admin/PcBuilder/Products', [
            'category' => $category,
            'linkedProducts' => $linkedProducts,
            'availableProducts' => $allProducts,
        ]);
    }

    public function addProduct(Request $request, PcBuilderCategory $category)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'wattage' => 'integer|min:0',
        ]);

        PcBuilderProduct::updateOrCreate(
            [
                'pc_builder_category_id' => $category->id,
                'product_id' => $validated['product_id'],
            ],
            [
                'wattage' => $validated['wattage'] ?? 0,
            ]
        );

        return redirect()->route('admin.pc-builder-categories.products', $category->id)
            ->with('success', 'Product linked successfully.');
    }

    public function removeProduct(PcBuilderCategory $category, PcBuilderProduct $product)
    {
        $product->delete();

        return redirect()->route('admin.pc-builder-categories.products', $category->id)
            ->with('success', 'Product removed from category.');
    }
}

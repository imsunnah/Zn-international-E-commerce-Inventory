<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PcBuilderCategory;
use App\Models\PcBuilderProduct;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PcBuilderSeeder extends Seeder
{
    public function run()
    {
        // 1. Define PC Builder Categories
        $categories = [
            // Core Components (group: core)
            [
                'name' => 'Processor / CPU',
                'icon' => 'cpu',
                'sort_order' => 1,
                'is_required' => true,
                'group' => 'core'
            ],
            [
                'name' => 'Motherboard',
                'icon' => 'circuit-board',
                'sort_order' => 2,
                'is_required' => true,
                'group' => 'core'
            ],
            [
                'name' => 'RAM / Memory',
                'icon' => 'memory-stick',
                'sort_order' => 3,
                'is_required' => true,
                'group' => 'core'
            ],
            [
                'name' => 'Storage (SSD / HDD)',
                'icon' => 'hard-drive',
                'sort_order' => 4,
                'is_required' => true,
                'group' => 'core'
            ],
            [
                'name' => 'Power Supply / PSU',
                'icon' => 'power',
                'sort_order' => 5,
                'is_required' => true,
                'group' => 'core'
            ],
            [
                'name' => 'Casing / Shell',
                'icon' => 'box',
                'sort_order' => 6,
                'is_required' => true,
                'group' => 'core'
            ],

            // Peripherals & Optional (group: peripherals)
            [
                'name' => 'Graphics Card / GPU',
                'icon' => 'monitor',
                'sort_order' => 7,
                'is_required' => false,
                'group' => 'peripherals'
            ],
            [
                'name' => 'Monitor',
                'icon' => 'monitor',
                'sort_order' => 8,
                'is_required' => false,
                'group' => 'peripherals'
            ],
            [
                'name' => 'Keyboard',
                'icon' => 'keyboard',
                'sort_order' => 9,
                'is_required' => false,
                'group' => 'peripherals'
            ],
            [
                'name' => 'Mouse',
                'icon' => 'mouse',
                'sort_order' => 10,
                'is_required' => false,
                'group' => 'peripherals'
            ],
            [
                'name' => 'Sound / Headphones',
                'icon' => 'headphones',
                'sort_order' => 11,
                'is_required' => false,
                'group' => 'peripherals'
            ],
        ];

        $catMap = [];
        foreach ($categories as $catData) {
            $catMap[$catData['name']] = PcBuilderCategory::updateOrCreate(
                ['name' => $catData['name']],
                $catData
            );
        }

        // Get key storefront categories and brands
        $mainAccessoriesCat = Category::where('name_en', 'Accessories')->first() ?? Category::first();
        $mainGamingCat = Category::where('name_en', 'Gaming Gear')->first() ?? Category::first();
        $intelBrand = Brand::where('name_en', 'Intel')->first();
        $amdBrand = Brand::where('name_en', 'AMD')->first();
        $asusBrand = Brand::where('name_en', 'ASUS')->first();
        $msiBrand = Brand::where('name_en', 'MSI')->first();
        $gigabyteBrand = Brand::where('name_en', 'Gigabyte')->first();
        $corsairBrand = Brand::where('name_en', 'Corsair')->first();
        $razerBrand = Brand::where('name_en', 'Razer')->first();
        $samsungBrand = Brand::where('name_en', 'Samsung')->first();

        // 2. Define Products to seed/link
        $components = [
            // Processors (already in database, query and match or seed)
            'Processor / CPU' => [
                ['name' => 'Intel Core i9-14900K Processor', 'brand' => $intelBrand, 'price' => 72000, 'watt' => 125],
                ['name' => 'AMD Ryzen 9 7950X3D Desktop Processor', 'brand' => $amdBrand, 'price' => 78000, 'watt' => 120],
                ['name' => 'Intel Core i7-14700K 20-Core Processor', 'brand' => $intelBrand, 'price' => 52000, 'watt' => 125],
                ['name' => 'AMD Ryzen 5 7600 Desktop Processor', 'brand' => $amdBrand, 'price' => 24000, 'watt' => 65],
            ],
            // Motherboards
            'Motherboard' => [
                ['name' => 'ASUS ROG STRIX Z790-F GAMING WIFI II', 'brand' => $asusBrand, 'price' => 45000, 'watt' => 80],
                ['name' => 'MSI MAG B760M MORTAR WIFI Motherboard', 'brand' => $msiBrand, 'price' => 22500, 'watt' => 60],
                ['name' => 'Gigabyte B650 AORUS ELITE AX Motherboard', 'brand' => $gigabyteBrand, 'price' => 28000, 'watt' => 70],
            ],
            // RAM
            'RAM / Memory' => [
                ['name' => 'Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 'brand' => $corsairBrand, 'price' => 14500, 'watt' => 15],
                ['name' => 'G.Skill Trident Z5 Neo 16GB DDR5 5600MHz RAM', 'brand' => $asusBrand, 'price' => 8500, 'watt' => 10],
                ['name' => 'Kingston FURY Beast 8GB DDR4 3200MHz RAM', 'brand' => $corsairBrand, 'price' => 3800, 'watt' => 5],
            ],
            // Storage
            'Storage (SSD / HDD)' => [
                ['name' => 'Samsung 990 Pro 2TB NVMe PCIe 4.0 M.2 SSD', 'brand' => $samsungBrand, 'price' => 22000, 'watt' => 10],
                ['name' => 'Corsair MP600 Core XT 4TB Gen4 PCIe SSD', 'brand' => $corsairBrand, 'price' => 35000, 'watt' => 12],
                ['name' => 'Gigabyte Aorus Gen5 10000 2TB SSD', 'brand' => $gigabyteBrand, 'price' => 42000, 'watt' => 15],
            ],
            // PSUs
            'Power Supply / PSU' => [
                ['name' => 'Corsair RM850x 850W 80+ Gold Modular PSU', 'brand' => $corsairBrand, 'price' => 16500, 'watt' => 0], // PSUs have 0 wattage consumption
                ['name' => 'Thermaltake Toughpower GF1 750W Gold PSU', 'brand' => $corsairBrand, 'price' => 11000, 'watt' => 0],
                ['name' => 'Antec Atom V550 550W Power Supply', 'brand' => $msiBrand, 'price' => 4200, 'watt' => 0],
            ],
            // Casings
            'Casing / Shell' => [
                ['name' => 'NZXT H9 Flow Dual-Chamber Mid-Tower Case', 'brand' => $corsairBrand, 'price' => 18500, 'watt' => 0],
                ['name' => 'Corsair 4000D Airflow Tempered Glass Case', 'brand' => $corsairBrand, 'price' => 9550, 'watt' => 0],
                ['name' => 'Antec AX20 Mid Tower Gaming Case', 'brand' => $msiBrand, 'price' => 3800, 'watt' => 0],
            ],
            // GPUs
            'Graphics Card / GPU' => [
                ['name' => 'NVIDIA GeForce RTX 4090 Founders Edition', 'brand' => $asusBrand, 'price' => 210000, 'watt' => 450],
                ['name' => 'ROG Strix RTX 4080 Super Dual Fan GPU', 'brand' => $asusBrand, 'price' => 145000, 'watt' => 320],
                ['name' => 'GeForce RTX 4070 Ti Ventus 3X Graphics Card', 'brand' => $msiBrand, 'price' => 95000, 'watt' => 285],
                ['name' => 'Aorus GeForce RTX 4060 Elite Graphics Card', 'brand' => $gigabyteBrand, 'price' => 42000, 'watt' => 200],
            ],
            // Monitors
            'Monitor' => [
                ['name' => 'Samsung Odyssey Neo G9 49-inch Ultrawide Monitor', 'brand' => $samsungBrand, 'price' => 195000, 'watt' => 90],
                ['name' => 'ASUS ROG Swift PG32UCDM 32-inch 4K OLED', 'brand' => $asusBrand, 'price' => 155000, 'watt' => 60],
                ['name' => 'MSI Optix MAG274QRF-QD Gaming Monitor', 'brand' => $msiBrand, 'price' => 45000, 'watt' => 45],
            ],
            // Keyboards
            'Keyboard' => [
                ['name' => 'Razer BlackWidow V4 Pro Mechanical Keyboard', 'brand' => $razerBrand, 'price' => 22000, 'watt' => 5],
                ['name' => 'Corsair K55 RGB PRO Keyboard', 'brand' => $corsairBrand, 'price' => 5500, 'watt' => 3],
            ],
            // Mice
            'Mouse' => [
                ['name' => 'Logitech G502 LIGHTSPEED Wireless Gaming Mouse', 'brand' => $razerBrand, 'price' => 12500, 'watt' => 2],
                ['name' => 'Razer DeathAdder Essential Wired Gaming Mouse', 'brand' => $razerBrand, 'price' => 2200, 'watt' => 2],
            ],
            // Sound
            'Sound / Headphones' => [
                ['name' => 'Razer BlackShark V2 Pro Gaming Headset', 'brand' => $razerBrand, 'price' => 18500, 'watt' => 3],
                ['name' => 'Corsair HS65 SURROUND Stereo Headset', 'brand' => $corsairBrand, 'price' => 8800, 'watt' => 2],
            ],
        ];

        foreach ($components as $catName => $items) {
            $pbCat = $catMap[$catName];

            foreach ($items as $item) {
                // Ensure main product exists in products table or seed it first
                $product = Product::where('name_en', $item['name'])->first();

                if (!$product) {
                    $brandId = $item['brand'] ? $item['brand']->id : Brand::first()->id;
                    $categoryId = ($catName === 'Keyboard' || $catName === 'Mouse' || $catName === 'Sound / Headphones') 
                        ? $mainGamingCat->id 
                        : $mainAccessoriesCat->id;

                    // If it is a Processor or Graphics Card or Monitor/Storage, map to its respective store category if possible
                    if ($catName === 'Processor / CPU') {
                        $pCat = Category::where('name_en', 'Processors')->first();
                        if ($pCat) $categoryId = $pCat->id;
                    } elseif ($catName === 'Graphics Card / GPU') {
                        $pCat = Category::where('name_en', 'Graphics Cards')->first();
                        if ($pCat) $categoryId = $pCat->id;
                    } elseif ($catName === 'Monitor') {
                        $pCat = Category::where('name_en', 'Monitors')->first();
                        if ($pCat) $categoryId = $pCat->id;
                    } elseif ($catName === 'Storage (SSD / HDD)') {
                        $pCat = Category::where('name_en', 'Storages')->first();
                        if ($pCat) $categoryId = $pCat->id;
                    }

                    $product = Product::create([
                        'category_id' => $categoryId,
                        'brand_id' => $brandId,
                        'name_en' => $item['name'],
                        'name_bn' => $item['name'],
                        'slug' => Str::slug($item['name']) . '-' . uniqid(),
                        'sku' => 'ZN-' . rand(1000, 9999),
                        'description_en' => "Professional grade Technical PC component: {$item['name']}",
                        'description_bn' => "পেশাদার গ্রেড পিসি উপাদান: {$item['name']}",
                        'price' => $item['price'],
                        'buying_price' => $item['price'] * 0.75,
                        'stock' => rand(10, 30),
                        'is_active' => true,
                        'image' => 'https://placehold.co/600x600/002013/ffffff?text=' . urlencode($item['name']),
                    ]);
                }

                // Link to PC Builder Category
                PcBuilderProduct::updateOrCreate(
                    [
                        'pc_builder_category_id' => $pbCat->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'wattage' => $item['watt']
                    ]
                );
            }
        }
    }
}

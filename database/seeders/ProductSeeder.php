<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        if ($categories->isEmpty() || $brands->isEmpty()) {
            return;
        }

        $products = [
            // Laptops
            ['cat' => 'Laptops', 'brand' => 'ASUS', 'name' => 'ROG Zephyrus G14', 'price' => 185000, 'desc' => 'Powerful gaming laptop with AMD Ryzen 9 and RTX 4070.'],
            ['cat' => 'Laptops', 'brand' => 'MSI', 'name' => 'Raider GE78 HX', 'price' => 245000, 'desc' => 'High-end gaming laptop with 13th Gen Intel Core i9.'],
            ['cat' => 'Laptops', 'brand' => 'Razer', 'name' => 'Blade 16', 'price' => 320000, 'desc' => 'Premium aluminum chassis with Mini-LED display.'],
            ['cat' => 'Laptops', 'brand' => 'HP', 'name' => 'Victus 15', 'price' => 85000, 'desc' => 'Budget-friendly gaming laptop for enthusiasts.'],
            
            // Graphics Cards
            ['cat' => 'Graphics Cards', 'brand' => 'NVIDIA', 'name' => 'RTX 4090 Founders Edition', 'price' => 210000, 'desc' => 'The ultimate enthusiast GPU for 4K gaming.'],
            ['cat' => 'Graphics Cards', 'brand' => 'ASUS', 'name' => 'ROG Strix RTX 4080 Super', 'price' => 145000, 'desc' => 'Premium cooling and performance for serious gamers.'],
            ['cat' => 'Graphics Cards', 'brand' => 'MSI', 'name' => 'GeForce RTX 4070 Ti Ventus', 'price' => 95000, 'desc' => 'Triple-fan cooling for stable performance.'],
            ['cat' => 'Graphics Cards', 'brand' => 'Gigabyte', 'name' => 'Aorus RTX 4060 Elite', 'price' => 42000, 'desc' => 'Best value for 1080p high-refresh gaming.'],

            // Processors
            ['cat' => 'Processors', 'brand' => 'Intel', 'name' => 'Core i9-14900K', 'price' => 72000, 'desc' => '24 cores of pure processing power.'],
            ['cat' => 'Processors', 'brand' => 'AMD', 'name' => 'Ryzen 9 7950X3D', 'price' => 78000, 'desc' => 'The worlds fastest gaming processor with 3D V-Cache.'],
            ['cat' => 'Processors', 'brand' => 'Intel', 'name' => 'Core i7-14700K', 'price' => 52000, 'desc' => 'Balanced performance for work and play.'],
            ['cat' => 'Processors', 'brand' => 'AMD', 'name' => 'Ryzen 5 7600', 'price' => 24000, 'desc' => 'Efficient 6-core processor for modern builds.'],

            // Monitors
            ['cat' => 'Monitors', 'brand' => 'Samsung', 'name' => 'Odyssey Neo G9', 'price' => 195000, 'desc' => '49-inch Super Ultrawide Curved Gaming Monitor.'],
            ['cat' => 'Monitors', 'brand' => 'ASUS', 'name' => 'ROG Swift PG32UCDM', 'price' => 155000, 'desc' => '32-inch 4K QD-OLED 240Hz monitor.'],
            ['cat' => 'Monitors', 'brand' => 'MSI', 'name' => 'Optix MAG274QRF-QD', 'price' => 45000, 'desc' => '27-inch 1440p Quantum Dot display.'],

            // Storages
            ['cat' => 'Storages', 'brand' => 'Samsung', 'name' => '990 Pro 2TB NVMe', 'price' => 22000, 'desc' => 'Fastest Gen4 SSD available on the market.'],
            ['cat' => 'Storages', 'brand' => 'Corsair', 'name' => 'MP600 Core XT 4TB', 'price' => 35000, 'desc' => 'Massive storage with PCIe Gen4 speeds.'],
            ['cat' => 'Storages', 'brand' => 'Gigabyte', 'name' => 'Aorus Gen5 10000 SSD', 'price' => 42000, 'desc' => 'Beyond speed with PCIe Gen5 support.'],
        ];

        // Fill up to 50 products with variations
        foreach ($products as $pData) {
            $cat = Category::where('name_en', $pData['cat'])->first();
            $brand = Brand::where('name_en', $pData['brand'])->first();
            
            if ($cat && $brand) {
                Product::create([
                    'category_id' => $cat->id,
                    'brand_id' => $brand->id,
                    'name_en' => $pData['name'],
                    'name_bn' => $pData['name'], // Can be updated to BN if needed
                    'slug' => Str::slug($pData['name']) . '-' . uniqid(),
                    'sku' => 'ZN-' . rand(1000, 9999),
                    'description_en' => $pData['desc'],
                    'description_bn' => $pData['desc'], // Can be updated to BN if needed
                    'price' => $pData['price'],
                    'buying_price' => $pData['price'] * 0.75,
                    'stock' => rand(5, 20),
                    'is_active' => true,
                    'image' => 'https://placehold.co/600x600/002013/ffffff?text=' . urlencode($pData['name']),
                    'discount_type' => rand(0, 5) > 3 ? 'percentage' : null,
                    'discount_value' => rand(5, 15),
                ]);
            }
        }

        // Add 20 more random products to reach 40+
        for ($i = 0; $i < 25; $i++) {
            $cat = $categories->random();
            $brand = $brands->random();
            $name = $brand->name_en . ' ' . $cat->name_en . ' Series ' . rand(100, 999);
            $price = rand(5000, 150000);
            
            Product::create([
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'name_en' => $name,
                'name_bn' => $name,
                'slug' => Str::slug($name) . '-' . uniqid(),
                'sku' => 'ZN-' . rand(1000, 9999),
                'description_en' => "Professional grade technical hardware from {$brand->name_en}.",
                'description_bn' => "{$brand->name_bn} থেকে পেশাদার মানের টেকনিক্যাল হার্ডওয়্যার।",
                'price' => $price,
                'buying_price' => $price * 0.8,
                'stock' => rand(1, 50),
                'is_active' => true,
                'image' => 'https://placehold.co/600x600/002013/ffffff?text=' . urlencode($name),
            ]);
        }
    }
}

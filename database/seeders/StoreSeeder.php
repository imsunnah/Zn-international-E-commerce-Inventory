<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('brand_category')->truncate();
        DB::table('brand_sub_category')->truncate();
        DB::table('combo_product')->truncate();
        DB::table('combos')->truncate();
        DB::table('products')->truncate();
        DB::table('sub_categories')->truncate();
        DB::table('categories')->truncate();
        DB::table('brands')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Premium Categories (10+)
        $categories = [
            'Laptops' => 'ল্যাপটপ',
            'Desktops' => 'ডেস্কটপ',
            'Graphics Cards' => 'গ্রাফিক্স কার্ড',
            'Processors' => 'প্রসেসর',
            'Monitors' => 'মনিটর',
            'Storages' => 'স্টোরেজ',
            'Networking' => 'নেটওয়ার্কিং',
            'Printers' => 'প্রিন্টার',
            'Gaming Gear' => 'গেমিং গিয়ার',
            'Accessories' => 'এক্সেসরিজ',
        ];

        $catIds = [];
        foreach ($categories as $en => $bn) {
            $catIds[$en] = DB::table('categories')->insertGetId([
                'name_en' => $en,
                'name_bn' => $bn,
                'slug' => Str::slug($en),
                'is_active' => 1,
                'image' => 'https://placehold.co/800x800/002013/ffffff?text=' . urlencode($en),
                'description_en' => "High-performance {$en} from world-leading tech brands.",
                'description_bn' => "বিশ্বসেরা টেক ব্র্যান্ডের হাই-পারফর্মেন্স {$bn}।",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Global Brands
        $brandList = [
            'ASUS' => 'আসুস',
            'MSI' => 'এমএসআই',
            'Intel' => 'ইন্টেল',
            'AMD' => 'এএমডি',
            'Samsung' => 'স্যামসাং',
            'Gigabyte' => 'গিগাবাইট',
            'HP' => 'এইচপি',
            'Dell' => 'ডেল',
            'Razer' => 'রেজার',
            'TP-Link' => 'টিপি-লিঙ্ক',
            'NVIDIA' => 'এনভিডিয়া',
            'Corsair' => 'কোরসেয়ার',
        ];

        $brandIds = [];
        foreach ($brandList as $en => $bn) {
            $brandIds[$en] = DB::table('brands')->insertGetId([
                'name_en' => $en,
                'name_bn' => $bn,
                'slug' => Str::slug($en),
                'is_active' => 1,
                'image' => 'https://placehold.co/400x200/ffffff/002013?text=' . urlencode($en),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Brand-Category Linking (Strategic Mapping)
        $mapping = [
            'Laptops' => ['ASUS', 'MSI', 'HP', 'Dell', 'Razer'],
            'Desktops' => ['HP', 'Dell', 'ASUS', 'MSI'],
            'Graphics Cards' => ['MSI', 'Gigabyte', 'ASUS', 'NVIDIA'],
            'Processors' => ['Intel', 'AMD'],
            'Monitors' => ['Samsung', 'ASUS', 'MSIHP', 'Dell'],
            'Storages' => ['Samsung', 'Gigabyte', 'Corsair'],
            'Networking' => ['TP-Link', 'ASUS'],
            'Printers' => ['HP', 'Samsung'],
            'Gaming Gear' => ['Razer', 'Corsair', 'ASUS', 'MSI'],
            'Accessories' => ['Razer', 'Corsair', 'TP-Link'],
        ];

        foreach ($mapping as $catName => $brands) {
            foreach ($brands as $brandName) {
                if (isset($catIds[$catName]) && isset($brandIds[$brandName])) {
                    DB::table('brand_category')->insert([
                        'brand_id' => $brandIds[$brandName],
                        'category_id' => $catIds[$catName],
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@zninternational.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Create 9 more Users (making 10 total)
        $usersData = [
            ['name' => 'Test User', 'email' => 'test@example.com', 'role' => 'user'],
            ['name' => 'Saad Al Ghamdi', 'email' => 'saad@example.com', 'role' => 'user'],
            ['name' => 'Abdur Rahman', 'email' => 'abdur@example.com', 'role' => 'user'],
            ['name' => 'Mishary Rashid', 'email' => 'mishary@example.com', 'role' => 'user'],
            ['name' => 'Omar Al-Mukhtar', 'email' => 'omar@example.com', 'role' => 'user'],
            ['name' => 'Sufian Ahmed', 'email' => 'sufian@example.com', 'role' => 'user'],
            ['name' => 'Farhan Akhtar', 'email' => 'farhan@example.com', 'role' => 'user'],
            ['name' => 'ZN International Admin', 'email' => 'sadman@example.com', 'role' => 'user'],
            ['name' => 'Jamil Hasan', 'email' => 'jamil@example.com', 'role' => 'user'],
        ];

        foreach ($usersData as $ud) {
            User::updateOrCreate(
                ['email' => $ud['email']],
                [
                    'name' => $ud['name'],
                    'password' => Hash::make('password'),
                    'role' => $ud['role'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Setup Default Settings
        $settings = [
            'site_name' => ['en' => 'ZN International', 'bn' => 'জেডএন ইন্টারন্যাশনাল'],
            'contact_email' => ['en' => 'sales@zninternational.com', 'bn' => 'sales@zninternational.com'],
            'contact_phone' => ['en' => '+8802-123456', 'bn' => '+8802-123456'],
            'currency' => ['en' => '৳', 'bn' => '৳'],
            'address' => ['en' => 'Multiplan Center, New Elephant Road, Dhaka', 'bn' => 'মাল্টিপ্ল্যান সেন্টার, নিউ এলিফ্যান্ট রোড, ঢাকা'],
            'footer_about' => ['en' => 'Leading computer and tech shop in Bangladesh offering high performance PCs and accessories.', 'bn' => 'বাংলাদেশের অন্যতম কম্পিউটার এবং টেক শপ যা হাই পারফরম্যান্স পিসি এবং এক্সেসরিজ সরবরাহ করে।'],
            'footer_address' => ['en' => 'Multiplan Center, Dhaka', 'bn' => 'মাল্টিপ্ল্যান সেন্টার, ঢাকা'],
            'footer_phone' => ['en' => '+8802-123456', 'bn' => '+8802-123456'],
            'footer_email' => ['en' => 'sales@zninternational.com', 'bn' => 'sales@zninternational.com'],
            'site_facebook_url' => ['en' => 'https://facebook.com/zninternational', 'bn' => 'https://facebook.com/zninternational'],
            'site_instagram_url' => ['en' => 'https://instagram.com/zninternational', 'bn' => 'https://instagram.com/zninternational'],
            'shipping_policy_summary' => ['en' => 'Fast delivery across Bangladesh. Inside Dhaka 24-48 hours, Outside Dhaka 3-5 days.', 'bn' => 'সারা বাংলাদেশে দ্রুত ডেলিভারি। ঢাকার ভেতরে ২৪-৪৮ ঘণ্টা, ঢাকার বাইরে ৩-৫ দিন।'],
            'return_policy_summary' => ['en' => '7-day easy warranty return policy for all components.', 'bn' => 'সকল কম্পোনেন্টের জন্য ৭ দিনের সহজ ওয়ারেন্টি রিটার্ন পলিসি।'],
            'hero_slider_text' => ['en' => '<h1>ZN INTERNATIONAL</h1><p>THE NEW ERA OF COMPUTING</p>', 'bn' => '<h1>জেডএন ইন্টারন্যাশনাল</h1><p>কম্পিউটিংয়ের নতুন যুগ</p>'],
            'hero_slider_text_show' => ['en' => '1', 'bn' => '1'],
            'slider_images' => ['en' => json_encode([
                'https://placehold.co/1920x600/00a651/ffffff?text=Premium+Gaming+Computers',
                'https://placehold.co/1920x600/e12e2e/ffffff?text=Latest+Laptops+and+Tablets',
                'https://placehold.co/1920x600/000000/ffffff?text=High+Resolution+Monitors'
            ]), 'bn' => json_encode([
                'https://placehold.co/1920x600/00a651/ffffff?text=Premium+Gaming+Computers',
                'https://placehold.co/1920x600/e12e2e/ffffff?text=Latest+Laptops+and+Tablets',
                'https://placehold.co/1920x600/000000/ffffff?text=High+Resolution+Monitors'
            ])]
        ];

        foreach ($settings as $key => $values) {
            Setting::updateOrCreate(
                ['key' => $key], 
                [
                    'value_en' => $values['en'],
                    'value_bn' => $values['bn']
                ]
            );
        }

        // 4. Run Store Seeder and Page Seeder
        $this->call([
            StoreSeeder::class,
            PageSeeder::class,
            ProductSeeder::class,
            PcBuilderSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class ScannerSeeder extends Seeder
{
    public function run()
    {
        $categoryId = 6; // Scanner category ID

        $products = [
            // --- Page 1 ---
            [
                'brand' => 'Canon',
                'name'  => 'Canon CanoScan LiDE 300 Flatbed Scanner',
                'price' => 7500,
                'desc'  => 'Compact and sleek A4 flatbed scanner with 2400 x 2400 dpi optical resolution and USB bus power.',
                'short' => "Type: Flatbed Scanner\nResolution: 2400 x 2400 dpi\nScan Speed: 10 sec (A4 300dpi)\nInterface: USB 2.0 Mini-B",
                'long'  => "Brand: Canon\nModel: CanoScan LiDE 300\nType: Flatbed Document & Photo Scanner\nScanning Element: CIS\nLight Source: 3-color (RGB) LED\nOptical Resolution: 2400 x 2400 dpi\nSelectable Resolution: 25 - 19200 dpi\nScanning Speed (Color): 10 seconds (A4, 300dpi)\nMaximum Document Size: A4 / Letter (216 x 297 mm)\nQuick Buttons: 4 Buttons (PDF, AUTOSCAN, COPY, SEND)\nInterface: USB 2.0 Mini-B\nPower Supply: Supplied via USB port\nPower Consumption: 4.5W (In operation), 0.3W (Standby)"
            ],
            [
                'brand' => 'Canon',
                'name'  => 'Canon CanoScan LiDE 400 Flatbed Scanner',
                'price' => 10800,
                'desc'  => 'High-speed flatbed scanner with 4800 dpi resolution and USB Type-C connectivity for fast document scanning.',
                'short' => "Type: Flatbed Scanner\nResolution: 4800 x 4800 dpi\nScan Speed: 8 sec (A4 300dpi)\nInterface: USB Type-C",
                'long'  => "Brand: Canon\nModel: CanoScan LiDE 400\nType: Flatbed Document & Photo Scanner\nScanning Element: CIS\nLight Source: 3-color (RGB) LED\nOptical Resolution: 4800 x 4800 dpi\nSelectable Resolution: 25 - 19200 dpi\nScanning Speed (Color): 8 seconds (A4, 300dpi)\nMaximum Document Size: A4 / Letter (216 x 297 mm)\nQuick Buttons: 5 Buttons (PDF x 2, AUTOSCAN, COPY, SEND)\nInterface: USB Type-C (USB 3.0 High-Speed compatible)\nPower Supply: Supplied via USB port"
            ],
            [
                'brand' => 'Canon',
                'name'  => 'Canon imageFORMULA DR-C240 Sheet-Fed Document Scanner',
                'price' => 55000,
                'desc'  => 'High-speed sheet-fed scanner offering up to 45 ppm/90 ipm scanning with a 60-sheet ADF.',
                'short' => "Type: Sheet-Fed ADF Scanner\nResolution: 600 dpi\nScan Speed: 45 ppm / 90 ipm\nADF Capacity: 60 Sheets",
                'long'  => "Brand: Canon\nModel: imageFORMULA DR-C240\nType: Desktop Sheet-Fed Scanner\nScanning Sensor: CMOS CIS 1-Line Sensor\nOptical Resolution: 600 dpi\nLight Source: RGB LED\nScanning Side: Simplex / Duplex / Skip Blank Page\nScan Speed: 45 ppm (Simplex), 90 ipm (Duplex)\nADF Capacity: 60 Sheets\nDaily Duty Cycle: Approx. 4,000 scans\nInterface: High-Speed USB 2.0\nSupported Media: Business card, Passport, Plastic Card, Embossed Card"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Pro 2600 f1 Flatbed and Sheet Fed Scanner',
                'price' => 33000,
                'desc'  => 'Versatile scanner with 60-page ADF, 25 ppm scanning speed, and single-pass duplex capability.',
                'short' => "Type: Flatbed & ADF Scanner\nResolution: Up to 1200 dpi\nScan Speed: 25 ppm / 50 ipm\nADF Capacity: 60 Sheets",
                'long'  => "Brand: HP\nModel: ScanJet Pro 2600 f1\nScanner Type: Flatbed, ADF\nScan Resolution Optical: Up to 600 dpi (ADF), Up to 1200 dpi (Flatbed)\nScan Speed ADF: Up to 25 ppm / 50 ipm\nAutomatic Document Feeder Capacity: Standard 60 sheets\nDuplex ADF Scanning: Single-pass duplex\nMedia Types: Paper (banner, inkjet, photo, plain), envelopes, labels, cards\nDaily Duty Cycle: Recommended 1,500 pages per day\nConnectivity: High-Speed USB 2.0"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Pro 3000 s4 Sheet-feed Scanner',
                'price' => 62000,
                'desc'  => 'Fast sheet-fed document scanner delivering 40 ppm/80 ipm scanning speeds with USB 3.0 support.',
                'short' => "Type: Sheet-Fed Scanner\nResolution: 600 dpi\nScan Speed: 40 ppm / 80 ipm\nADF Capacity: 50 Sheets",
                'long'  => "Brand: HP\nModel: ScanJet Pro 3000 s4\nScanner Type: Sheet-fed\nScan Resolution Optical: Up to 600 dpi\nScan Speed: Up to 40 ppm / 80 ipm\nADF Capacity: 50 sheets\nDaily Duty Cycle: Up to 4,000 pages\nConnectivity: USB 3.0\nFeatures: One-pass duplex scanning, Auto-color detect, Auto-crop, Auto-exposure"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Pro 3600 f1 Document Scanner',
                'price' => 60000,
                'desc'  => 'Professional desktop scanner featuring a 60-sheet ADF, up to 30 ppm scan speed, and 1200 dpi resolution.',
                'short' => "Type: Flatbed & ADF Scanner\nResolution: 1200 dpi\nScan Speed: 30 ppm / 60 ipm\nADF Capacity: 60 Sheets",
                'long'  => "Brand: HP\nModel: ScanJet Pro 3600 f1\nScanner Type: Flatbed, ADF\nScan Resolution: Up to 600 dpi (ADF), Up to 1200 dpi (Flatbed)\nScan Speed: Up to 30 ppm / 60 ipm\nADF Capacity: Standard 60 sheets\nDuty Cycle: Recommended 3,000 pages per day\nConnectivity: USB 3.0 High-Speed"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Pro N4600 fnw1 Scanner',
                'price' => 107000,
                'desc'  => 'Network-ready scanner featuring Ethernet, Wi-Fi Direct, 40 ppm scan speed, and 100-sheet ADF capacity.',
                'short' => "Type: Network Flatbed & ADF\nResolution: 1200 dpi\nScan Speed: 40 ppm / 80 ipm\nConnectivity: USB, Ethernet, Wi-Fi",
                'long'  => "Brand: HP\nModel: ScanJet Pro N4600 fnw1\nScanner Type: Flatbed, ADF\nScan Speed: Up to 40 ppm / 80 ipm\nADF Capacity: 100 sheets\nResolution: Up to 600 dpi (ADF), Up to 1200 dpi (Flatbed)\nConnectivity: Ethernet 10/100/1000 Base-TX, USB 3.0, Wi-Fi 802.11 b/g/n, Wi-Fi Direct\nDisplay: 2.8-in (7.11-cm) Touchscreen"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP Scanjet Professional 1000 Mobile Scanner',
                'price' => 21500,
                'desc'  => 'Ultra-portable compact mobile scanner designed for scanning documents on the go via USB power.',
                'short' => "Type: Mobile Sheet-Fed\nResolution: 600 dpi\nScan Speed: Up to 5 ppm\nPower: USB Powered",
                'long'  => "Brand: HP\nModel: Scanjet Professional 1000\nScanner Type: Mobile Sheet-fed\nScan Resolution Optical: Up to 600 dpi\nScan Speed: Up to 5 ppm (Simplex), 8 ipm (Duplex)\nBit Depth: 24-bit\nMedia Size: A4, Receipts, Business Cards, Embossed plastic cards\nInterface: Hi-Speed USB 2.0\nPower: USB Bus powered"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Enterprise Flow 7000 s3 Sheet-feed Scanner',
                'price' => 115000,
                'desc'  => 'Enterprise-grade sheet-fed scanner with 75 ppm/150 ipm speed and heavy-duty daily volume capacity.',
                'short' => "Type: Enterprise Sheet-Fed\nResolution: 600 dpi\nScan Speed: 75 ppm / 150 ipm\nADF Capacity: 80 Sheets",
                'long'  => "Brand: HP\nModel: ScanJet Enterprise Flow 7000 s3\nScanner Type: Sheet-fed\nOptical Resolution: Up to 600 dpi\nScan Speed: Up to 75 ppm / 150 ipm\nADF Capacity: 80 sheets\nDaily Duty Cycle: Recommended 7,500 pages per day\nConnectivity: USB 2.0 and USB 3.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson DS-1630 Flatbed & Sheet fed Color Document Scanner',
                'price' => 27500,
                'desc'  => 'Compact flatbed scanner with 50-page ADF, auto duplex scanning, and versatile media compatibility.',
                'short' => "Type: Flatbed & ADF Scanner\nResolution: 1200 dpi\nScan Speed: 25 ppm / 10 ipm\nADF Capacity: 50 Sheets",
                'long'  => "Brand: Epson\nModel: DS-1630\nScanner Type: Flatbed color image scanner\nOptical Resolution: 1200 dpi (Flatbed), 600 dpi (ADF)\nScan Speed: 25 ppm (Simplex), 10 ipm (Duplex)\nADF Capacity: 50 sheets\nDaily Duty Cycle: 1,500 pages\nInterface: USB 3.0"
            ],

            // --- Page 2 ---
            [
                'brand' => 'Epson',
                'name'  => 'Epson DS-530 II Color Duplex Document Scanner',
                'price' => 46000,
                'desc'  => 'Reliable document scanner with 35 ppm/70 ipm speed and 50-sheet ADF for efficient office scanning.',
                'short' => "Type: Sheet-Fed Duplex\nResolution: 600 dpi\nScan Speed: 35 ppm / 70 ipm\nADF Capacity: 50 Sheets",
                'long'  => "Brand: Epson\nModel: DS-530 II\nScanner Type: Sheet-fed, 1-pass duplex color scanner\nOptical Resolution: 600 dpi\nScan Speed: 35 ppm / 70 ipm\nADF Capacity: 50 sheets\nDaily Duty Cycle: 4,000 pages per day\nConnectivity: SuperSpeed USB 3.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson DS-770 II Color Duplex Document Scanner',
                'price' => 62000,
                'desc'  => 'High-speed workgroup document scanner offering 45 ppm/90 ipm speeds and peak daily duty cycles.',
                'short' => "Type: Sheet-Fed Duplex\nResolution: 600 dpi\nScan Speed: 45 ppm / 90 ipm\nADF Capacity: 100 Sheets",
                'long'  => "Brand: Epson\nModel: DS-770 II\nScanner Type: Sheet-fed, 1-pass duplex color scanner\nOptical Resolution: 600 dpi\nScan Speed: 45 ppm / 90 ipm\nADF Capacity: 100 sheets\nDaily Duty Cycle: 7,000 pages per day\nConnectivity: SuperSpeed USB 3.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson DS-870 Color Duplex Document Scanner',
                'price' => 71000,
                'desc'  => 'Heavy-duty sheet-fed duplex scanner delivering up to 65 ppm/130 ipm with a 100-sheet ADF.',
                'short' => "Type: Sheet-Fed Duplex\nResolution: 600 dpi\nScan Speed: 65 ppm / 130 ipm\nADF Capacity: 100 Sheets",
                'long'  => "Brand: Epson\nModel: DS-870\nScanner Type: Sheet-fed color duplex scanner\nOptical Resolution: 600 dpi\nScan Speed: 65 ppm / 130 ipm\nADF Capacity: 100 sheets\nDaily Duty Cycle: 7,000 pages per day\nConnectivity: SuperSpeed USB 3.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson DS-970 Color Duplex Document Scanner',
                'price' => 98000,
                'desc'  => 'Ultra-fast workgroup document scanner with 85 ppm/170 ipm scan speeds and 100-sheet capacity.',
                'short' => "Type: Sheet-Fed Duplex\nResolution: 600 dpi\nScan Speed: 85 ppm / 170 ipm\nADF Capacity: 100 Sheets",
                'long'  => "Brand: Epson\nModel: DS-970\nScanner Type: Sheet-fed 1-pass duplex scanner\nOptical Resolution: 600 dpi\nScan Speed: 85 ppm / 170 ipm\nADF Capacity: 100 sheets\nDaily Duty Cycle: 9,000 pages per day\nConnectivity: SuperSpeed USB 3.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson WorkForce DS-50000 Color Document Scanner',
                'price' => 180000,
                'desc'  => 'Large-format A3 flatbed document scanner engineered for high-resolution graphics and documents.',
                'short' => "Type: A3 Flatbed Scanner\nResolution: 600 dpi\nScan Speed: 4 sec (A3 300dpi)\nMax Paper Size: A3",
                'long'  => "Brand: Epson\nModel: WorkForce DS-50000\nScanner Type: Flatbed color image scanner\nOptical Resolution: 600 dpi\nMax Document Size: A3 (11.7 inch x 17 inch)\nLight Source: ReadyScan LED technology\nInterface: High-Speed USB 2.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson WorkForce DS-60000 Color Document Scanner',
                'price' => 195000,
                'desc'  => 'High-performance A3 flatbed scanner with 200-sheet ADF capacity and 40 ppm scanning speed.',
                'short' => "Type: A3 Flatbed & ADF\nResolution: 600 dpi\nScan Speed: 40 ppm / 80 ipm\nADF Capacity: 200 Sheets",
                'long'  => "Brand: Epson\nModel: WorkForce DS-60000\nScanner Type: A3 Flatbed with ADF\nOptical Resolution: 600 dpi\nScan Speed: 40 ppm / 80 ipm\nADF Capacity: 200 sheets\nMax Paper Size: A3\nInterface: High-Speed USB 2.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson WorkForce ES-580W Wireless Document Scanner',
                'price' => 52000,
                'desc'  => 'Wireless sheet-fed document scanner with 10.9 cm touch screen and PC-free scanning.',
                'short' => "Type: Wireless Sheet-Fed\nResolution: 600 dpi\nScan Speed: 35 ppm / 70 ipm\nConnectivity: USB, Wi-Fi",
                'long'  => "Brand: Epson\nModel: WorkForce ES-580W\nScanner Type: Sheet-fed duplex scanner\nOptical Resolution: 600 dpi\nScan Speed: 35 ppm / 70 ipm\nADF Capacity: 100 sheets\nDisplay: 4.3-inch Touchscreen\nConnectivity: Wireless 802.11 b/g/n, SuperSpeed USB 3.0"
            ],
            [
                'brand' => 'HP',
                'name'  => 'HP ScanJet Enterprise 2000 s2 Sheet-feed Scanner',
                'price' => 45000,
                'desc'  => 'Compact enterprise sheet-fed scanner delivering fast 35 ppm/70 ipm two-sided scanning.',
                'short' => "Type: Sheet-Fed Scanner\nResolution: 600 dpi\nScan Speed: 35 ppm / 70 ipm\nADF Capacity: 50 Sheets",
                'long'  => "Brand: HP\nModel: ScanJet Enterprise 2000 s2\nScanner Type: Sheet-fed\nScan Resolution: Up to 600 dpi\nScan Speed: Up to 35 ppm / 70 ipm\nADF Capacity: 50 sheets\nDaily Duty Cycle: Recommended 3,500 pages per day\nConnectivity: USB 2.0"
            ],
            [
                'brand' => 'Canon',
                'name'  => 'Canon imageFORMULA DR-M160II Document Scanner',
                'price' => 88000,
                'desc'  => 'Robust desktop scanner with high-capacity 60-sheet feeder and 60 ppm/120 ipm scan speed.',
                'short' => "Type: Sheet-Fed Duplex\nResolution: 600 dpi\nScan Speed: 60 ppm / 120 ipm\nADF Capacity: 60 Sheets",
                'long'  => "Brand: Canon\nModel: imageFORMULA DR-M160II\nScanner Type: Desktop Sheet-fed Scanner\nOptical Resolution: 600 dpi\nScan Speed: 60 ppm / 120 ipm\nADF Capacity: 60 sheets\nDaily Duty Cycle: Approx. 7,000 scans\nConnectivity: High-Speed USB 2.0"
            ],
            [
                'brand' => 'Epson',
                'name'  => 'Epson Perfection V39 II Color Flatbed Scanner',
                'price' => 12500,
                'desc'  => 'Slim and lightweight A4 flatbed photo and document scanner with USB power and 4800 dpi resolution.',
                'short' => "Type: Flatbed Scanner\nResolution: 4800 dpi\nScan Speed: 10 sec (A4 300dpi)\nPower: USB Powered",
                'long'  => "Brand: Epson\nModel: Perfection V39 II\nScanner Type: Flatbed Color Scanner\nOptical Resolution: 4800 dpi\nScan Speed: 10 sec (A4 300dpi monochrome/color)\nMaximum Scan Area: 216 x 297 mm (A4/Letter)\nButtons: 4 Easy-to-use buttons\nConnectivity: USB 2.0 Micro-B"
            ],
        ];

        foreach ($products as $pData) {
            // Find or create the brand dynamically
            $brand = Brand::firstOrCreate(
                ['name_en' => $pData['brand']],
                ['name_bn' => $pData['brand'], 'slug' => Str::slug($pData['brand'])]
            );

            Product::create([
                'category_id'       => $categoryId,
                'brand_id'          => $brand->id,
                'name_en'           => $pData['name'],
                'name_bn'           => $pData['name'],
                'slug'              => Str::slug($pData['name']) . '-' . uniqid(),
                'sku'               => 'SCN-' . rand(10000, 99999),
                'description_en'    => $pData['desc'],
                'description_bn'    => $pData['desc'],
                'short_description' => $pData['short'],
                'long_description'  => $pData['long'],
                'price'             => $pData['price'],
                'buying_price'      => $pData['price'] * 0.82,
                'stock'             => rand(5, 30),
                'is_active'         => true,
                'image'             => 'https://placehold.co/600x600/002013/ffffff?text=' . urlencode($pData['name']),
                'discount_type'     => rand(0, 5) > 3 ? 'percentage' : null,
                'discount_value'    => rand(5, 10),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categoryId = 8; // Printer category ID

        $products = [
            // --- Page 1 ---
            ['brand' => 'HP', 'name' => 'HP DeskJet 2320 All in One Printer', 'price' => 8500, 'desc' => 'Compact All-in-One InkJet printer with print, copy, and scan functions.'],
            ['brand' => 'Canon', 'name' => 'Canon Pixma TS207 Single Function Inkjet Printer', 'price' => 6900, 'desc' => 'Affordable single-function color inkjet printer for everyday home printing.'],
            ['brand' => 'HP', 'name' => 'HP Laser 108a Single Function Mono Laser Printer', 'price' => 13000, 'desc' => 'Compact single-function monochrome laser printer for crisp black-and-white documents.'],
            ['brand' => 'HP', 'name' => 'HP Laser MFP 137fnw Multifunction Mono Laser Printer', 'price' => 40000, 'desc' => 'Multifunction mono laser printer with print, scan, copy, fax, ADF, and wireless connectivity.'],
            ['brand' => 'HP', 'name' => 'HP LaserJet Pro M501dn Single Function Mono Laser Printer', 'price' => 45000, 'desc' => 'High-speed mono laser printer featuring auto-duplex and Gigabit Ethernet networking.'],
            ['brand' => 'Canon', 'name' => 'Canon Pixma G570 Wireless Single Function MegaTank Photo Printer', 'price' => 30000, 'desc' => '6-color wireless MegaTank photo printer designed for high-volume photo printing.'],
            ['brand' => 'Canon', 'name' => 'Canon Pixma G4010 All in One Wireless Ink Tank Printer', 'price' => 27500, 'desc' => 'Wireless Ink Tank All-In-One printer with ADF for high-volume printing, scanning, and copying.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L11050 A3 Wi-Fi Ink Tank Printer', 'price' => 58000, 'desc' => 'High-productivity A3+ Wi-Fi single-function ink tank printer.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L18050 A3 Ink Tank Photo Printer', 'price' => 80000, 'desc' => '6-color A3+ Wi-Fi ink tank photo printer for professional image printing.'],
            ['brand' => 'HP', 'name' => 'HP Laser Jet Pro 4002dn Single Function Printer', 'price' => 35000, 'desc' => 'Blazing-fast single-function mono laser printer with auto duplex and Ethernet support.'],
            ['brand' => 'HP', 'name' => 'HP Color LaserJet Pro 4202dw Single Function Printer', 'price' => 78000, 'desc' => 'High-speed wireless single-function color laser printer with auto duplexing.'],
            ['brand' => 'HP', 'name' => 'HP Laser MFP 136a Multifunction Laser Printer', 'price' => 20500, 'desc' => 'Reliable 3-in-1 multifunction mono laser printer for small offices.'],
            ['brand' => 'Canon', 'name' => 'Canon i-SENSYS MF752cdw Multifunction Color Laser Printer', 'price' => 85000, 'desc' => 'Premium wireless color laser printer with print, scan, and copy functionality.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L14150 A3 Wi-Fi Duplex All-in-One Ink Tank Printer', 'price' => 65000, 'desc' => 'Wide-format A3+ Wi-Fi duplex all-in-one printer with high-efficiency printing.'],
            ['brand' => 'Canon', 'name' => 'Canon i-SENSYS LBP647Cdw Single Function Color Laser Printer', 'price' => 44000, 'desc' => 'Fast and secure single-function color laser printer with auto duplex and wireless.'],
            ['brand' => 'Epson', 'name' => 'Epson LQ-690 II Dot Matrix Printer', 'price' => 65000, 'desc' => 'Heavy-duty 24-pin dot matrix printer engineered for fast multi-part form printing.'],
            ['brand' => 'Brother', 'name' => 'Brother MFC-T4500DW A3 Inktank All-in-One Printer', 'price' => 85000, 'desc' => 'A3 wireless refill tank system color all-in-one printer with auto duplex.'],
            ['brand' => 'HP', 'name' => 'HP Laser Jet Pro 4003n Single Function Mono Laser Printer', 'price' => 33000, 'desc' => 'High-speed networkable mono laser printer designed for corporate environments.'],
            ['brand' => 'HP', 'name' => 'HP Laser MFP 136w Multifunction Laser Printer', 'price' => 22800, 'desc' => 'Multifunction mono laser printer with integrated Wi-Fi and mobile printing.'],
            ['brand' => 'HP', 'name' => 'HP Laser 108w Single Function Mono Laser Printer', 'price' => 15300, 'desc' => 'Compact monochrome laser printer featuring Wi-Fi connectivity for wireless printing.'],

            // --- Page 2 ---
            ['brand' => 'Epson', 'name' => 'Epson M1058 Single Function Eco-Tank Printer', 'price' => 24000, 'desc' => 'Heavy-duty monochrome EcoTank printer for low-cost, high-speed single-function printing.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L5290 Wi-Fi All-in-One Ink Tank Printer', 'price' => 40000, 'desc' => 'Full-featured Wi-Fi ink tank printer with ADF, print, scan, copy, and fax capabilities.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L3256 Wi-Fi Multifunction Ink Tank Printer', 'price' => 21500, 'desc' => 'Stylish white Wi-Fi all-in-one ink tank printer with mobile printing support.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L3210 All-in-One Ink Tank Printer', 'price' => 18500, 'desc' => 'Ultra-low-cost print, scan, and copy all-in-one EcoTank printer.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L3260 Wi-Fi All-in-One Ink Tank Printer', 'price' => 22500, 'desc' => 'Smart Wi-Fi ink tank printer featuring a 1.44-inch color LCD display panel.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L6270 Wi-Fi Duplex All-in-One Ink Tank Printer', 'price' => 38500, 'desc' => 'Fast duplex ink tank printer with ADF and PrecisionCore printhead technology.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L6291 Wi-Fi Duplex All-in-One Ink Tank Printer', 'price' => 42000, 'desc' => 'High-performance duplex ink tank printer with ADF, Ethernet, and touch panel.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L1110 Ink Tank Printer', 'price' => 15500, 'desc' => 'Compact single-function ink tank printer with spill-free refill bottles.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L3110 All-in-One Ink Tank Printer', 'price' => 17500, 'desc' => 'Reliable 3-in-1 color ink tank printer for high-volume document printing.'],
            ['brand' => 'Epson', 'name' => 'Epson EcoTank M100 Single Function Mono Ink Tank Printer', 'price' => 18000, 'desc' => 'Monochrome single-function ink tank printer with Ethernet connectivity.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G1020 Single Function Ink Tank Printer', 'price' => 13500, 'desc' => 'High-volume single-function refillable ink tank printer for crisp text and photos.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G2020 All-in-One Ink Tank Printer', 'price' => 16500, 'desc' => 'Refillable ink tank 3-in-1 printer for high-volume color printing.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G3020 Wi-Fi All-in-One Ink Tank Printer', 'price' => 19500, 'desc' => 'Wireless refillable ink tank printer with print, scan, copy, and cloud print support.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G3010 Wi-Fi Multifunction Ink Tank Printer', 'price' => 18000, 'desc' => 'Popular wireless ink tank all-in-one printer for seamless mobile printing.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G2010 All-in-One Ink Tank Printer', 'price' => 15500, 'desc' => 'Efficient 3-in-1 color ink tank printer with built-in integrated ink tanks.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G1010 Single Function Ink Tank Printer', 'price' => 12500, 'desc' => 'Entry-level single-function ink tank printer built for economical printing.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G5070 Single Function Ink Tank Printer', 'price' => 21000, 'desc' => 'High-volume single function wireless ink tank printer with auto duplex capability.'],
            ['brand' => 'Canon', 'name' => 'Canon PIXMA G6070 All-in-One Ink Tank Printer', 'price' => 26500, 'desc' => 'Feature-packed wireless auto duplex 3-in-1 ink tank printer.'],

            // --- Page 3 ---
            ['brand' => 'Epson', 'name' => 'Epson EcoTank L3250 Wi-Fi Multifunction Ink Tank Printer', 'price' => 20500, 'desc' => 'Multifunction Wi-Fi ink tank printer supporting wireless smart panel app.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 531 All-in-One Wireless Printer', 'price' => 22000, 'desc' => 'Wireless ink tank printer delivering high-volume print quality at budget cost.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 515 All-in-One Wireless Printer', 'price' => 19500, 'desc' => 'Wireless color ink tank printer with fast connectivity and high-yield inks.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 500 All-in-One Ink Tank Printer', 'price' => 17000, 'desc' => 'Non-wireless color ink tank printer for fast everyday document printing.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 615 Wireless All-in-One Printer', 'price' => 25500, 'desc' => 'Wireless ink tank printer equipped with automatic document feeder and fax.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 520 All-in-One Ink Tank Printer', 'price' => 16500, 'desc' => 'Reliable print, copy, and scan printer with smart guided buttons.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 580 Wireless All-in-One Printer', 'price' => 18500, 'desc' => 'Smart wireless color ink tank printer with self-healing Wi-Fi connection.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 670 Duplex Wireless All-in-One Printer', 'price' => 27000, 'desc' => 'Auto-duplex wireless color ink tank printer for fast double-sided printing.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 720 Duplex Wireless All-in-One Printer', 'price' => 31000, 'desc' => 'Premium double-sided wireless ink tank printer with smart touch interface.'],
            ['brand' => 'HP', 'name' => 'HP Smart Tank 750 Duplex Wireless All-in-One Printer with ADF', 'price' => 35000, 'desc' => 'Top-tier duplex ink tank printer with automatic document feeder and Ethernet.'],
            ['brand' => 'HP', 'name' => 'HP Laser 107a Single Function Mono Laser Printer', 'price' => 20000, 'desc' => 'Compact entry-level monochrome laser printer for fast output.'],
            ['brand' => 'HP', 'name' => 'HP Laser 107w Single Function Mono Laser Printer with Wi-Fi', 'price' => 23500, 'desc' => 'Single-function mono laser printer with built-in Wi-Fi and mobile app support.'],
            ['brand' => 'HP', 'name' => 'HP Laser MFP 135a Multifunction Mono Laser Printer', 'price' => 18500, 'desc' => 'Versatile 3-in-1 mono laser printer offering high performance at an entry price.'],
            ['brand' => 'HP', 'name' => 'HP Laser MFP 135w Wireless Multifunction Mono Laser Printer', 'price' => 21500, 'desc' => 'Wireless 3-in-1 monochrome laser printer for easy mobile scanning and printing.'],
            ['brand' => 'HP', 'name' => 'HP LaserJet Pro 4003dn Single Function Laser Printer', 'price' => 42000, 'desc' => 'Enterprise-ready single-function mono laser printer with advanced security.'],

            // --- Page 4 ---
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP6030 Single Function Mono Laser Printer', 'price' => 15000, 'desc' => 'Space-saving monochrome laser printer with fast first print-out time.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP6030w Single Function Mono Laser Printer', 'price' => 17500, 'desc' => 'Compact mono laser printer with built-in Wi-Fi and mobile printing capability.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP6230dn Duplex Mono Laser Printer', 'price' => 22000, 'desc' => 'Networked mono laser printer with automatic double-sided document printing.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS MF3010 Multifunction Mono Laser Printer', 'price' => 21500, 'desc' => 'Durable 3-in-1 mono laser printer designed for personal and small-office environments.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP233dw Monochrome Laser Printer', 'price' => 35000, 'desc' => 'High-yield wireless mono laser printer featuring auto duplex and secure print.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP621Cw Color Laser Printer', 'price' => 32000, 'desc' => 'Compact wireless color laser printer producing sharp text and vivid graphics.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS LBP623Cdw Duplex Color Laser Printer', 'price' => 38000, 'desc' => 'Wireless auto-duplex color laser printer engineered for fast business printing.'],
            ['brand' => 'Canon', 'name' => 'Canon imageCLASS MF244dw Duplex Wireless All-in-One Laser Printer', 'price' => 34500, 'desc' => 'Feature-rich 3-in-1 mono laser printer with auto duplex, ADF, and Wi-Fi.'],
            ['brand' => 'Brother', 'name' => 'Brother DCP-T420W Wireless All-in-One Ink Tank Printer', 'price' => 18000, 'desc' => 'Wireless refill tank system printer with high print speeds and mobile app support.'],
            ['brand' => 'Brother', 'name' => 'Brother DCP-T520W Wireless All-in-One Ink Tank Printer', 'price' => 21000, 'desc' => 'Wireless ink tank printer with LCD display screen and transparent tank cover.'],
            ['brand' => 'Brother', 'name' => 'Brother DCP-T720DW Auto Duplex Wireless All-in-One Ink Tank Printer', 'price' => 27500, 'desc' => 'Automatic double-sided printing wireless ink tank printer with ADF.'],
            ['brand' => 'Brother', 'name' => 'Brother DCP-T820DW Auto Duplex Wireless Ink Tank Printer with ADF', 'price' => 33000, 'desc' => 'Robust duplex wireless printer with Ethernet networking and high-yield refill tank.'],
            ['brand' => 'Brother', 'name' => 'Brother HL-L2320D Single Function Duplex Mono Laser Printer', 'price' => 14500, 'desc' => 'Reliable mono laser printer featuring built-in automatic two-sided printing.'],
            ['brand' => 'Brother', 'name' => 'Brother HL-L2365DW Wireless Duplex Mono Laser Printer', 'price' => 19500, 'desc' => 'Compact wireless mono laser printer with automatic duplex and networking.'],
            ['brand' => 'Brother', 'name' => 'Brother LX-310 9-Pin Dot Matrix Printer', 'price' => 22500, 'desc' => 'High-speed 9-pin dot matrix impact printer for continuous form invoice printing.'],
            ['brand' => 'Epson', 'name' => 'Epson LQ-310 24-Pin Dot Matrix Printer', 'price' => 24500, 'desc' => '24-pin narrow carriage dot matrix printer offering extreme reliability and speed.'],
            ['brand' => 'Epson', 'name' => 'Epson LQ-590II 24-Pin Dot Matrix Printer', 'price' => 48000, 'desc' => 'High-volume 24-pin dot matrix printer for heavy commercial document processing.'],
            ['brand' => 'Epson', 'name' => 'Epson PLQ-30 Passbook Printer', 'price' => 58000, 'desc' => 'Specialized dual 24-pin impact passbook printer for banking and financial services.'],
            ['brand' => 'Epson', 'name' => 'Epson TM-T82III Thermal Receipt Printer', 'price' => 13500, 'desc' => 'Fast and reliable POS thermal receipt printer designed for retail and hospitality.'],
        ];

        foreach ($products as $pData) {
            // Find or create the brand dynamically
            $brand = Brand::firstOrCreate(
                ['name_en' => $pData['brand']],
                ['name_bn' => $pData['brand'], 'slug' => Str::slug($pData['brand'])]
            );

            Product::create([
                'category_id'     => $categoryId,
                'brand_id'        => $brand->id,
                'name_en'         => $pData['name'],
                'name_bn'         => $pData['name'],
                'slug'            => Str::slug($pData['name']) . '-' . uniqid(),
                'sku'             => 'PRN-' . rand(10000, 99999),
                'description_en'  => $pData['desc'],
                'description_bn'  => $pData['desc'],
                'short_description' => "Print Speed (A4): Up to 20ppm\nPrint Resolution: 1200 x 1200dpi\nMonthly Duty Cycle: 10000 Pages\nConnectivity: USB 2.0\nDuplex Printing: Manual",
                'long_description'  => "Brand: " . $pData['brand'] . "\nModel: " . $pData['name'] . "\nFunctions: Print, Scan, Copy\nPrint Speed Black (ISO, letter): Up to 21 ppm\nPrint Speed Black (ISO, A4): Up to 20 ppm\nPrint Resolution Technologies: ReCP\nPrint languages: SPL\nDisplay: 2-Line LCD (16 characters per line)\nProcessor speed: 600 MHz\nConnectivity: High-Speed USB 2.0\nMemory: 128 MB\nPaper handling input, standard: 150 sheet input tray\nPaper handling output, standard: 100 sheet output bin\nPower consumption: 300 watts (Active Printing), 38 watts (Ready), 1.9 watts (Sleep)",
                'price'           => $pData['price'],
                'buying_price'    => $pData['price'] * 0.82,
                'stock'           => rand(5, 30),
                'is_active'       => true,
                'image'           => 'https://placehold.co/600x600/002013/ffffff?text=' . urlencode($pData['name']),
                'discount_type'   => rand(0, 5) > 3 ? 'percentage' : null,
                'discount_value'  => rand(5, 10),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Store;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin FlexSport',
            'email' => 'admin@flexsport.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $seller = User::create([
            'name' => 'Seller Pro',
            'email' => 'seller@flexsport.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $buyer = User::create([
            'name' => 'John Buyer',
            'email' => 'buyer@flexsport.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        // 2. Create Single Store for Seller
        $store = Store::create([
            'user_id' => $seller->id,
            'name' => 'FlexSport Official',
            'slug' => 'flexsport-official',
            'description' => 'The premier destination for professional sports equipment.',
            'address' => 'Jakarta Sports Complex, Building A',
            'city' => 'Jakarta',
            'phone' => '08123456789',
            'is_verified' => true,
        ]);

        // 3. Create Categories
        $categories = [
            ['name' => 'Footwear', 'icon' => '👟', 'slug' => 'footwear', 'bg' => '1a0500', 'color' => 'FF4500'],
            ['name' => 'Apparel', 'icon' => '👕', 'slug' => 'apparel', 'bg' => '1e293b', 'color' => 'f43f5e'],
            ['name' => 'Rackets', 'icon' => '🎾', 'slug' => 'rackets', 'bg' => '0f172a', 'color' => 'FF8C00'],
            ['name' => 'Gym Gear', 'icon' => '💪', 'slug' => 'gym-gear', 'bg' => '000000', 'color' => 'ffffff'],
            ['name' => 'Accessories', 'icon' => '🎒', 'slug' => 'accessories', 'bg' => '475569', 'color' => 'ffd700'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = ProductCategory::create([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'image' => "https://placehold.co/100x100/{$cat['bg']}/{$cat['color']}?text={$cat['icon']}",
                'description' => "Best {$cat['name']} for professionals.",
            ]);
        }

        // 4. Create Multiple Products (All in Single Store)
        $productsData = [
            // Footwear
            [
                'cat_idx' => 0, 'name' => 'Neon Striker Elite', 'price' => 1250000, 
                'desc' => 'Top-tier soccer cleats with neon glow technology and high ankle support for maximum protection.',
                'image' => '/images/products/neon_striker_elite_1765270076095.png',
                'features' => 'ankle-support,high-top,soccer,cleats'
            ],
            [
                'cat_idx' => 0, 'name' => 'AirWalker Pro Run', 'price' => 890000, 
                'desc' => 'Lightweight running shoes for marathon training with breathable mesh.',
                'image' => '/images/products/airwalker_pro_run_1765270092786.png',
                'features' => 'running,lightweight,breathable,marathon'
            ],
            [
                'cat_idx' => 0, 'name' => 'CourtMaster Basketball', 'price' => 1500000, 
                'desc' => 'High-top basketball shoes with superior ankle support and high-grip soles for indoor courts.',
                'image' => '/images/products/courtmaster_basketball_1765270110264.png',
                'features' => 'ankle-support,high-top,basketball,grip,indoor'
            ],
            
            // Apparel
            [
                'cat_idx' => 1, 'name' => 'CyberRun Smart Jersey', 'price' => 450000, 
                'desc' => 'Moisture-wicking fabric with breathable mesh panels for intense training.',
                'image' => '/images/products/cyberrun_smart_jersey_1765270136995.png',
                'features' => 'breathable,moisture-wicking,training,jersey'
            ],
            [
                'cat_idx' => 1, 'name' => 'FlexFit Compression Shorts', 'price' => 300000, 
                'desc' => 'Muscle support compression shorts for intense workouts and injury prevention.',
                'image' => '/images/products/flexfit_compression_shorts_1765270154236.png',
                'features' => 'compression,muscle-support,workout,shorts'
            ],
            [
                'cat_idx' => 1, 'name' => 'ProTech Training Jacket', 'price' => 750000, 
                'desc' => 'Windproof and water-resistant training jacket for all-weather workouts.',
                'image' => '/images/products/protech_training_jacket_1765270173451.png',
                'features' => 'windproof,water-resistant,training,jacket'
            ],

            // Rackets
            [
                'cat_idx' => 2, 'name' => 'Vortex Pro Tennis Racket', 'price' => 2800000, 
                'desc' => 'Professional carbon fiber tennis racket for maximum power and control.',
                'image' => '/images/products/vortex_pro_tennis_1765270190221.png',
                'features' => 'tennis,racket,carbon-fiber,professional'
            ],
            [
                'cat_idx' => 2, 'name' => 'SmashLite Badminton Set', 'price' => 1200000, 
                'desc' => 'Complete badminton set with 2 rackets and shuttlecocks.',
                'image' => '/images/products/smashlite_badminton_set_1765270207273.png',
                'features' => 'badminton,racket,set,shuttlecock'
            ],

            // Gym Gear
            [
                'cat_idx' => 3, 'name' => 'Titanium Grip Dumbbells', 'price' => 850000, 
                'desc' => 'Anti-slip grip dumbbells, available in 5kg-20kg for strength training.',
                'image' => '/images/products/titanium_grip_dumbbells_1765270231565.png',
                'features' => 'dumbbells,weights,strength,gym,anti-slip'
            ],
            [
                'cat_idx' => 3, 'name' => 'PowerBench Press 3000', 'price' => 3500000, 
                'desc' => 'Adjustable weight bench for home gyms and professional training.',
                'image' => '/images/products/powerbench_press_3000_1765270252180.png',
                'features' => 'bench,weights,gym,adjustable,home-gym'
            ],
            [
                'cat_idx' => 3, 'name' => 'Elite Resistance Bands', 'price' => 250000, 
                'desc' => 'Set of 5 resistance bands with varying tension for flexibility training.',
                'image' => '/images/products/elite_resistance_bands_1765270268843.png',
                'features' => 'resistance-bands,flexibility,training,workout'
            ],

             // Accessories
             [
                'cat_idx' => 4, 'name' => 'HydraMax Water Bottle', 'price' => 150000, 
                'desc' => 'Premium insulated water bottle that keeps drinks cold for 24 hours.',
                'image' => '/images/products/hydramax_water_bottle_1765270288532.png',
                'features' => 'water-bottle,insulated,hydration,accessory'
            ],
        ];

        $productModels = [];
        foreach ($productsData as $p) {
            $product = Product::create([
                'store_id' => $store->id,
                'product_category_id' => $catModels[$p['cat_idx']]->id,
                'name' => $p['name'],
                'slug' => \Illuminate\Support\Str::slug($p['name']),
                'description' => $p['desc'],
                'condition' => 'new',
                'price' => $p['price'],
                'weight' => rand(200, 2000),
                'stock' => rand(5, 50),
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $p['image'],
            ]);
            
            // Random Reviews
            if (rand(0, 1)) {
                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $buyer->id,
                    'rating' => rand(3, 5),
                    'review' => 'Great product! Highly recommended.',
                ]);
            }

            $productModels[] = $product;
        }

        // 5. Create Sample Transactions with Different Statuses
        // Transaction 1: Delivered
        $t1 = Transaction::create([
            'user_id' => $buyer->id,
            'store_id' => $store->id,
            'code' => 'TRX-' . strtoupper(uniqid()),
            'address' => 'Jl. Sudirman No. 1',
            'city' => 'Jakarta',
            'postal_code' => '10110',
            'shipping' => 'JNE',
            'shipping_type' => 'Regular',
            'shipping_cost' => 15000,
            'tax' => 0,
            'grand_total' => 1265000,
            'payment_status' => 'paid',
            'order_status' => 'delivered',
        ]);

        TransactionDetail::create([
            'transaction_id' => $t1->id,
            'product_id' => $productModels[0]->id, // Neon Striker
            'qty' => 1,
            'price' => 1250000,
            'subtotal' => 1250000,
        ]);

        // Transaction 2: Shipped
        $t2 = Transaction::create([
            'user_id' => $buyer->id,
            'store_id' => $store->id,
            'code' => 'TRX-' . strtoupper(uniqid()),
            'address' => 'Jl. Sudirman No. 1',
            'city' => 'Jakarta',
            'postal_code' => '10110',
            'shipping' => 'JNE',
            'shipping_type' => 'Regular',
            'shipping_cost' => 20000,
            'tax' => 0,
            'grand_total' => 470000,
            'payment_status' => 'paid',
            'order_status' => 'shipped',
        ]);

        TransactionDetail::create([
            'transaction_id' => $t2->id,
            'product_id' => $productModels[3]->id, // CyberRun Jersey
            'qty' => 1,
            'price' => 450000,
            'subtotal' => 450000,
        ]);

        // Transaction 3: Processing
        $t3 = Transaction::create([
            'user_id' => $buyer->id,
            'store_id' => $store->id,
            'code' => 'TRX-' . strtoupper(uniqid()),
            'address' => 'Jl. Thamrin No. 5',
            'city' => 'Jakarta',
            'postal_code' => '10230',
            'shipping' => 'JNE',
            'shipping_type' => 'Regular',
            'shipping_cost' => 15000,
            'tax' => 0,
            'grand_total' => 865000,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);

        TransactionDetail::create([
            'transaction_id' => $t3->id,
            'product_id' => $productModels[8]->id, // Titanium Grip
            'qty' => 1,
            'price' => 850000,
            'subtotal' => 850000,
        ]);
    }
}

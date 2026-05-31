<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@tuquparcelmlg'],
            [
                'name' => 'Admin TuquParcel',
                'password' => Hash::make('tuquparcelmlg'),
                'email_verified_at' => now(),
            ]
        );

        // Settings
        $settings = [
            'site_name'      => 'TuquParcel',
            'site_tagline'   => 'Temukan Hadiah yang Sempurna',
            'whatsapp'       => '628123456789',
            'instagram'      => '@tuquparcel',
            'instagram_url'  => 'https://instagram.com/tuquparcel',
            'email_contact'  => 'hello@tuquparcel.id',
            'address'        => 'Jakarta, Indonesia',
            'low_stock_threshold' => '10',
        ];
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Categories
        $categories = [
            ['name' => 'Bunga Segar', 'slug' => 'bunga-segar', 'icon' => '💐'],
            ['name' => 'Parcel', 'slug' => 'parcel', 'icon' => '🎁'],
            ['name' => 'Bunga Kering', 'slug' => 'bunga-kering', 'icon' => '🌾'],
            ['name' => 'Tanaman Hias', 'slug' => 'tanaman-hias', 'icon' => '🌿'],
            ['name' => 'Wisuda', 'slug' => 'wisuda', 'icon' => '🎓'],
            ['name' => 'Anniversary', 'slug' => 'anniversary', 'icon' => '💝'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Sample Products
        $products = [
            [
                'sku' => 'TP-0001',
                'name' => 'Eternal Grace Box',
                'description' => 'Paket parcel bunga premium dengan sentuhan elegan. Cocok untuk ulang tahun, pernikahan, atau ucapan terima kasih.',
                'price' => 650000,
                'discount_percent' => 0,
                'stock' => 25,
                'is_published' => true,
                'is_featured' => true,
                'badge' => 'Best Seller',
                'category' => 'parcel',
                'contents' => ['12x Mawar Merah Premium', "Baby's Breath", 'Ribbon Satın', 'Kartu Ucapan'],
                'image' => 'https://images.unsplash.com/photo-1490750967868-88df5691166a?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0002',
                'name' => 'Rose Garden Set',
                'description' => 'Bouquet mawar dengan warna-warna yang memukau. Disusun dengan tangan oleh florist berpengalaman.',
                'price' => 450000,
                'discount_percent' => 10,
                'stock' => 15,
                'is_published' => true,
                'is_featured' => true,
                'badge' => 'New',
                'category' => 'bunga-segar',
                'contents' => ['20x Mawar Merah', 'Greenery', 'Wrap Kraft Premium'],
                'image' => 'https://images.unsplash.com/photo-1455659817273-f96807779a8a?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0003',
                'name' => 'Lily Dream Bouquet',
                'description' => 'Rangkaian lili putih yang anggun dan harum. Sempurna untuk momen-momen istimewa.',
                'price' => 380000,
                'discount_percent' => 0,
                'stock' => 8,
                'is_published' => true,
                'is_featured' => false,
                'badge' => null,
                'category' => 'bunga-segar',
                'contents' => ['5 Tangkai Lili Putih', "Eucalyptus", 'Tissue Paper'],
                'image' => 'https://images.unsplash.com/photo-1462275646964-a0e3386b89fa?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0004',
                'name' => 'Purple Orchid Delight',
                'description' => 'Anggrek ungu eksotis dalam pot keramik. Hadiah tahan lama yang indah.',
                'price' => 550000,
                'discount_percent' => 0,
                'stock' => 0,
                'is_published' => true,
                'is_featured' => false,
                'badge' => 'Limited',
                'category' => 'bunga-segar',
                'contents' => ['2 Tangkai Anggrek Ungu', 'Pot Keramik', 'Pupuk Mini'],
                'image' => 'https://images.unsplash.com/photo-1508610048659-a06b669e3321?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0005',
                'name' => 'Spring Tulip Bouquet',
                'description' => 'Bouquet tulip warna-warni yang ceria. Menghadirkan semangat musim semi.',
                'price' => 320000,
                'discount_percent' => 15,
                'stock' => 20,
                'is_published' => true,
                'is_featured' => true,
                'badge' => 'Sale',
                'category' => 'bunga-segar',
                'contents' => ['10 Tangkai Tulip Mix', 'Baby\'s Breath', 'Wrap Warna'],
                'image' => 'https://images.unsplash.com/photo-1490750967868-88df5691166a?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0006',
                'name' => 'Rustic Dried Bouquet',
                'description' => 'Rangkaian bunga kering estetik dengan nuansa natural. Tidak layu, kenangan selamanya.',
                'price' => 280000,
                'discount_percent' => 0,
                'stock' => 30,
                'is_published' => true,
                'is_featured' => false,
                'badge' => null,
                'category' => 'bunga-kering',
                'contents' => ['Pampas Grass', 'Lavender Kering', 'Wheat Stalks', 'Wrap Craft'],
                'image' => 'https://images.unsplash.com/photo-1487530811015-780fdfdca3aa?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0007',
                'name' => 'Mini Succulent Terrarium',
                'description' => 'Terrarium sukulent dalam wadah kaca cantik. Dekorasi ruangan yang hidup dan minimalis.',
                'price' => 150000,
                'discount_percent' => 0,
                'stock' => 45,
                'is_published' => true,
                'is_featured' => false,
                'badge' => null,
                'category' => 'tanaman-hias',
                'contents' => ['3 Tanaman Sukulent', 'Wadah Kaca', 'Pasir Hias', 'Batu Hias'],
                'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=600&h=750&fit=crop',
            ],
            [
                'sku' => 'TP-0008',
                'name' => 'Luxury Wedding Set',
                'description' => 'Paket bunga mewah khusus pernikahan. Mencakup bouquet pengantin dan dekorasi meja.',
                'price' => 1200000,
                'discount_percent' => 0,
                'stock' => 5,
                'is_published' => true,
                'is_featured' => true,
                'badge' => 'Limited',
                'category' => 'parcel',
                'contents' => ['Bouquet Pengantin', 'Dekorasi Meja', 'Boutonniere', 'Karangan Bunga'],
                'image' => 'https://images.unsplash.com/photo-1481487196290-c152efe083f5?w=600&h=750&fit=crop',
            ],
        ];

        foreach ($products as $data) {
            $cat = Category::where('slug', $data['category'])->first();
            if (!$cat) continue;

            $product = Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'discount_percent' => $data['discount_percent'],
                    'stock' => $data['stock'],
                    'is_published' => $data['is_published'],
                    'is_featured' => $data['is_featured'],
                    'badge' => $data['badge'],
                    'category_id' => $cat->id,
                ]
            );

            // Image
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_main' => true],
                ['image_path' => $data['image'], 'sort_order' => 0]
            );

            // Contents
            ProductContent::where('product_id', $product->id)->delete();
            foreach ($data['contents'] as $item) {
                ProductContent::create(['product_id' => $product->id, 'item' => $item]);
            }
        }

        // Sample Reviews
        $sampleReviews = [
            ['Siti Rahmawati', 5, 'Bunganya sangat cantik dan segar! Pengiriman tepat waktu dan pengemasan sangat rapi.'],
            ['Budi Santoso', 5, 'Kualitas terbaik! Istri saya sangat senang dengan bouquet mawarnya. Akan order lagi.'],
            ['Dewi Lestari', 4, 'Bunga yang indah dan harum. Hanya sedikit delay pengiriman tapi secara keseluruhan memuaskan.'],
        ];

        $firstProduct = Product::first();
        if ($firstProduct) {
            foreach ($sampleReviews as [$name, $rating, $comment]) {
                Review::updateOrCreate(
                    ['product_id' => $firstProduct->id, 'reviewer_name' => $name],
                    ['rating' => $rating, 'comment' => $comment, 'is_approved' => true]
                );
            }
        }
    }
}
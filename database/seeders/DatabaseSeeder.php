<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin & Kasir
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kasir.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        

        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@kasir.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        // 2. Buat Kategori Utama
        $cat1 = Category::create(['name' => 'Makanan & Snack']);
        $cat2 = Category::create(['name' => 'Minuman']);
        $cat3 = Category::create(['name' => 'Kebutuhan Harian & Rumah Tangga']);
        $cat4 = Category::create(['name' => 'Obat-obatan & Kesehatan']);

        // 3. Masukkan Produk Lengkap
        // --- KATEGORI 1: Makanan & Snack ---
        Product::create(['category_id' => $cat1->id, 'name' => 'Chitato Sapi Berbumbu 68g', 'price' => 11500, 'stock' => 50]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Chitato Sapi Panggang 68g', 'price' => 11500, 'stock' => 45]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Lays / JetZ / Taro Net', 'price' => 8500, 'stock' => 40]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Qtela Keripik Singkong Balado', 'price' => 10000, 'stock' => 35]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Chiki Twist / Balls', 'price' => 6000, 'stock' => 50]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Piattos Sapi Panggang', 'price' => 9500, 'stock' => 40]);
        Product::create(['category_id' => $cat1->id, 'name' => 'SilverQueen Chocolate Bar', 'price' => 18000, 'stock' => 30]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Beng-Beng / Better Wafer', 'price' => 3000, 'stock' => 100]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Indomie Goreng Original', 'price' => 3500, 'stock' => 150]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Indomie Goreng Rendang', 'price' => 3600, 'stock' => 120]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Mie Sedaap Goreng', 'price' => 3500, 'stock' => 130]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Pop Mie Ayam / Baso', 'price' => 5500, 'stock' => 60]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Minyak Goreng Sania 2L', 'price' => 38000, 'stock' => 25]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Beras Raja Platinum 5kg', 'price' => 69000, 'stock' => 15]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Gula Pasir Gulaku 1kg', 'price' => 17500, 'stock' => 30]);
        Product::create(['category_id' => $cat1->id, 'name' => 'Kecap Manis Bango 520ml', 'price' => 24000, 'stock' => 25]);

        // --- KATEGORI 2: Minuman ---
        Product::create(['category_id' => $cat2->id, 'name' => 'Coca-Cola Kaleng 390ml', 'price' => 7000, 'stock' => 40]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Fanta / Sprite 390ml', 'price' => 7000, 'stock' => 40]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Teh Botol Sosro 450ml', 'price' => 5000, 'stock' => 50]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Teh Pucuk Harum 350ml', 'price' => 4000, 'stock' => 70]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Ultra Milk Chocolate 250ml', 'price' => 6500, 'stock' => 45]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Bear Brand Susu Beruang 189ml', 'price' => 10500, 'stock' => 30]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Good Day Botol / Kopiko 78C', 'price' => 8000, 'stock' => 35]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Buavita Orange / Guava 250ml', 'price' => 7500, 'stock' => 25]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Aqua Botol 600ml', 'price' => 3500, 'stock' => 100]);
        Product::create(['category_id' => $cat2->id, 'name' => 'Le Minerale 600ml', 'price' => 3500, 'stock' => 100]);

        // --- KATEGORI 3: Kebutuhan Harian & Rumah Tangga ---
        Product::create(['category_id' => $cat3->id, 'name' => 'Rinso Molto Deterjen Bubuk 770g', 'price' => 21000, 'stock' => 20]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Sunlight Cair Pencuci Piring 755ml', 'price' => 18500, 'stock' => 30]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Pepsodent Pasta Gigi 225g', 'price' => 14000, 'stock' => 25]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Sunsilk Shampoo Botol', 'price' => 23000, 'stock' => 20]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Lux / Biore Sabun Batang', 'price' => 5000, 'stock' => 40]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Rexona Men Roll-on', 'price' => 17000, 'stock' => 20]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Baygon Obat Nyamuk Spray', 'price' => 36000, 'stock' => 15]);
        Product::create(['category_id' => $cat3->id, 'name' => 'Laurier Pembalut Wanita', 'price' => 16000, 'stock' => 30]);

        // --- KATEGORI 4: Obat-obatan & Kesehatan ---
        Product::create(['category_id' => $cat4->id, 'name' => 'Panadol Extra Strip', 'price' => 12000, 'stock' => 40]);
        Product::create(['category_id' => $cat4->id, 'name' => 'Promag Tablet', 'price' => 9000, 'stock' => 50]);
        Product::create(['category_id' => $cat4->id, 'name' => 'Entrostop Strip', 'price' => 8500, 'stock' => 30]);
        Product::create(['category_id' => $cat4->id, 'name' => 'Betadine Antiseptik 15ml', 'price' => 22000, 'stock' => 20]);
        Product::create(['category_id' => $cat4->id, 'name' => 'Hansaplast Plester Luka (isi 10)', 'price' => 5000, 'stock' => 60]);
        Product::create(['category_id' => $cat4->id, 'name' => 'Vitacimin Box / Strip', 'price' => 9000, 'stock' => 35]);
    }
}
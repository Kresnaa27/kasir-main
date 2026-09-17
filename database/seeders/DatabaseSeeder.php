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

        // 2. Buat Kategori Produk
        $catMakanan = Category::create(['name' => 'Makanan & Snack', 'slug' => 'makanan-snack']);
        $catMinuman = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $catKebutuhan = Category::create(['name' => 'Kebutuhan Harian', 'slug' => 'kebutuhan-harian']);

        // 3. Buat Produk Contoh
        Product::create([
            'category_id' => $catMakanan->id,
            'barcode' => '899100123401',
            'name' => 'Chitato Sapi Berbumbu 68g',
            'price' => 11500,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'barcode' => '899100123402',
            'name' => 'Aqua Botol 600ml',
            'price' => 3500,
            'stock' => 100,
        ]);

        Product::create([
            'category_id' => $catKebutuhan->id,
            'barcode' => '899100123403',
            'name' => 'Rinso Anti Noda 700g',
            'price' => 21000,
            'stock' => 30,
        ]);
    }
}
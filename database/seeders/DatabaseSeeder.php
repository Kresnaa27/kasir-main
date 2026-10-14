<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {   
        // 1. Buat User Kasir dengan QR Code Unik[cite: 10]
        User::create([
            'name' => 'Alex (Barista)',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password'),
            'qr_code' => 'QRIS-MERCHANT-BARISTA-ALEX-001',
            'role' => 'cashier',
        ]);

        // 2. Buat Kategori Sesuai Permintaan[cite: 10]
        $c1 = Category::create(['name' => 'Coffee', 'slug' => 'coffee']);
        $c2 = Category::create(['name' => 'Drink', 'slug' => 'drink']);
        $c3 = Category::create(['name' => 'Food', 'slug' => 'food']);
        $c4 = Category::create(['name' => 'Desserts', 'slug' => 'desserts']);

        // 3. Masukkan Produk Sampel[cite: 10]
        Product::create(['category_id' => $c1->id, 'name' => 'Caffe Latte', 'price' => 28000, 'stock' => 50, 'is_best_seller' => true, 'description' => 'Espresso + Steamed Milk']);
        Product::create(['category_id' => $c2->id, 'name' => 'Lychee Tea', 'price' => 20000, 'stock' => 40, 'description' => 'Teh segar dengan buah leci']);
        Product::create(['category_id' => $c3->id, 'name' => 'Butter Croissant', 'price' => 25000, 'stock' => 25, 'description' => 'Freshly baked pastry']);
        Product::create(['category_id' => $c4->id, 'name' => 'Tiramisu Cake', 'price' => 35000, 'stock' => 15, 'is_best_seller' => true, 'description' => 'Kue lembut rasa kopi']);
    }
}
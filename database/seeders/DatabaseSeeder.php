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
        // 1. Buat User Kasir dengan QR Code Unik
        User::create([
            'name' => 'Alex (Barista)',
            'username' => 'alexbarista',
            'password' => Hash::make('password'),
            'qr_code' => 'QRIS-MERCHANT-BARISTA-ALEX-001',
            'role' => 'cashier',
        ]);

        // 2. Buat Kategori Sesuai Permintaan
        $c1 = Category::create(['name' => 'Coffee', 'slug' => 'coffee']);
        $c2 = Category::create(['name' => 'Non-Coffee', 'slug' => 'non-coffee']);
        $c3 = Category::create(['name' => 'Food', 'slug' => 'food']);
        $c4 = Category::create(['name' => 'Pastry & Desserts', 'slug' => 'pastry-desserts']);

        // 3. Masukkan Produk Baru SENJA Coffee
        
        // --- KATEGORI COFFEE ---
        Product::create(['category_id' => $c1->id, 'name' => 'Caramel Macchiato', 'price' => 28000, 'stock' => 50, 'is_best_seller' => true, 'description' => 'Espresso dengan fresh milk dan saus karamel']);
        Product::create(['category_id' => $c1->id, 'name' => 'Cappucino', 'price' => 26000, 'stock' => 50, 'description' => 'Espresso klasik dengan foamed milk lembut']);
        Product::create(['category_id' => $c1->id, 'name' => 'Biscoff Latte', 'price' => 32000, 'stock' => 40, 'is_best_seller' => true, 'description' => 'Kopi susu dengan sensasi biskuit lotus biscoff']);
        Product::create(['category_id' => $c1->id, 'name' => 'Americano', 'price' => 22000, 'stock' => 50, 'description' => 'Espresso murni dengan air segar']);
        Product::create(['category_id' => $c1->id, 'name' => 'Flat White', 'price' => 27000, 'stock' => 40, 'description' => 'Espresso dengan microfoam susu yang velvety']);
        Product::create(['category_id' => $c1->id, 'name' => 'Caffe Mocha', 'price' => 30000, 'stock' => 40, 'description' => 'Perpaduan espresso, cokelat, dan susu']);
        Product::create(['category_id' => $c1->id, 'name' => 'Asian Dolce Latte', 'price' => 29000, 'stock' => 45, 'description' => 'Kopi susu manis khas dolce yang creamy']);

        // --- KATEGORI NON-COFFEE ---
        Product::create(['category_id' => $c2->id, 'name' => 'Biscoff Milkcheese', 'price' => 33000, 'stock' => 35, 'is_best_seller' => true, 'description' => 'Susu segar dengan keju dan lotus biscoff']);
        Product::create(['category_id' => $c2->id, 'name' => 'Matcha Latte', 'price' => 28000, 'stock' => 40, 'is_best_seller' => true, 'description' => 'Green tea premium Jepang dengan susu segar']);
        Product::create(['category_id' => $c2->id, 'name' => 'Cookies & Cream', 'price' => 29000, 'stock' => 30, 'description' => 'Blended susu dengan remahan biskuit cokelat']);
        Product::create(['category_id' => $c2->id, 'name' => 'Crunchy Caramel', 'price' => 30000, 'stock' => 30, 'description' => 'Minuman manis bertekstur karamel renyah']);
        Product::create(['category_id' => $c2->id, 'name' => 'Vanilla Creme', 'price' => 27000, 'stock' => 35, 'description' => 'Minuman susu rasa vanila yang lembut']);
        Product::create(['category_id' => $c2->id, 'name' => 'Orange Juice', 'price' => 24000, 'stock' => 40, 'description' => 'Jus jeruk murni yang segar dan kaya vitamin']);

        // --- KATEGORI FOOD ---
        Product::create(['category_id' => $c3->id, 'name' => 'French Fries / Potato Wedges', 'price' => 20000, 'stock' => 50, 'description' => 'Kentang goreng renyah saus pilihan']);
        Product::create(['category_id' => $c3->id, 'name' => 'Mix Platter', 'price' => 35000, 'stock' => 30, 'is_best_seller' => true, 'description' => 'Sosis, nugget, dan kentang goreng porsi komplit']);
        Product::create(['category_id' => $c3->id, 'name' => 'Garlic Bread / Toast', 'price' => 22000, 'stock' => 25, 'description' => 'Roti panggang dengan olesan mentega bawang']);
        Product::create(['category_id' => $c3->id, 'name' => 'Beef/Chicken Rice Bowl', 'price' => 38000, 'stock' => 30, 'is_best_seller' => true, 'description' => 'Nasi hangat dengan lauk ayam/daging sapi manis gurih']);
        Product::create(['category_id' => $c3->id, 'name' => 'Classic Beef Burger / Cheese Burger', 'price' => 42000, 'stock' => 25, 'is_best_seller' => true, 'description' => 'Burger daging sapi juicy dengan lelehan keju']);
        Product::create(['category_id' => $c3->id, 'name' => 'Pasta (Carbonara & Aglio Olio)', 'price' => 38000, 'stock' => 25, 'description' => 'Pasta lezat creamy Carbonara atau pedas Aglio Olio']);
        Product::create(['category_id' => $c3->id, 'name' => 'Club Sandwich / Toastie', 'price' => 35000, 'stock' => 20, 'description' => 'Roti lapis isi daging, keju, dan sayuran segar']);

        // --- KATEGORI PASTRY & DESSERTS ---
        Product::create(['category_id' => $c4->id, 'name' => 'Butter Croissant', 'price' => 25000, 'stock' => 25, 'is_best_seller' => true, 'description' => 'Pastry mentega klasik yang renyah dan berlapis']);
        Product::create(['category_id' => $c4->id, 'name' => 'Pain au Chocolat (Chocolate Croissant)', 'price' => 28000, 'stock' => 20, 'description' => 'Croissant renyah isi lelehan cokelat manis']);
        Product::create(['category_id' => $c4->id, 'name' => 'Cinnamon Roll', 'price' => 26000, 'stock' => 20, 'description' => 'Roti gulung aroma kayu manis dengan glasir manis']);
        Product::create(['category_id' => $c4->id, 'name' => 'Smoked Beef & Cheese Croissant', 'price' => 32000, 'stock' => 20, 'is_best_seller' => true, 'description' => 'Croissant gurih isi daging asap dan lelehan keju']);
        Product::create(['category_id' => $c4->id, 'name' => 'Tiramisu Cake', 'price' => 35000, 'stock' => 15, 'is_best_seller' => true, 'description' => 'Kue lembut rasa kopi khas Italia']);
        Product::create(['category_id' => $c4->id, 'name' => 'Cheesecake', 'price' => 38000, 'stock' => 15, 'description' => 'Kue keju lembut yang lumer di mulut']);
        Product::create(['category_id' => $c4->id, 'name' => 'Brownies / Fudgy Brownies', 'price' => 25000, 'stock' => 25, 'description' => 'Kue cokelat padat yang manis dan legit']);
        Product::create(['category_id' => $c4->id, 'name' => 'Waffle / Croffle (Croissant Waffle)', 'price' => 30000, 'stock' => 25, 'description' => 'Croffle/Waffle renyah manis menggugah selera']);
    }
}
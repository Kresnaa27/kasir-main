<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'price', 'description', 'is_best_seller', 'image'];
    public function category() { return $this->belongsTo(Category::class); }

    // Produk bisa ada di banyak transaksi detail
}
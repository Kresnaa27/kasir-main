<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'barcode', 'name', 'price', 'stock', 'image'];

    // Produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Produk bisa ada di banyak transaksi detail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
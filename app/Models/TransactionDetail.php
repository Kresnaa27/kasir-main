<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'product_id', 'quantity', 'price', 'subtotal'
    ];

    // Detail milik satu transaksi utama
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Detail merujuk ke satu produk tertentu
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
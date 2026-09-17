<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id', 'user_id', 'invoice_number', 
        'total_amount', 'paid_amount', 'change_amount', 'payment_method'
    ];

    // Transaksi dicatat dalam satu shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Transaksi dilakukan oleh user (kasir) tertentu
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu transaksi punya banyak detail barang yang dibeli
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
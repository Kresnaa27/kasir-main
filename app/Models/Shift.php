<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'start_time', 'end_time', 
        'initial_cash', 'final_cash', 'status'
    ];

    // Shift dimiliki oleh satu user (kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu shift bisa mencatat banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
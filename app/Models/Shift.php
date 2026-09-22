<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shift',
        'customers',
        'total_sales',
        'items_sold',
        'date',
        'status',
    ];

    // Satu shift bisa mencatat banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
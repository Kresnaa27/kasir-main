<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 
        'order_type', 
        'subtotal', 
        'tax', 
        'grand_total', // <-- Diperbaiki dari 'total' menjadi 'grand_total'
        'payment_method', 
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id'); // <-- Diperbaiki ke OrderItems (sesuai nama modelmu)
    }
}
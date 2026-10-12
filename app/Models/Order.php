<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'order_type', 'subtotal', 'tax', 'total', 'payment_method', 'status'];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
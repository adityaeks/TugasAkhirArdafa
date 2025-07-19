<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function order()
    {
        // Relasi ke order_uuid (UUID) di tabel orders
        return $this->belongsTo(Order::class, 'order_id', 'order_uuid');
    }
    public function orderProduct()
    {
        return $this->hasOneThrough(OrderProduct::class, Order::class, 'id', 'order_id', 'order_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function orderProduct()
    {
        return $this->hasOneThrough(OrderProduct::class, Order::class, 'id', 'order_id', 'order_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'order_uuid');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }
}

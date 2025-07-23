<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index(UserOrderDataTable $dataTable)
    {
        return $dataTable->render('frontend.dashboard.order.index');
    }

    public function show(string $id)
    {
        $order = Order::with(['transaction', 'orderProducts'])->findOrFail($id);
        $transactions = $order->transaction;
        $address = json_decode($order->order_address);

        $provinceName = $address->province_id ? \App\Models\Province::find($address->province_id)?->name : '-';
        $regencyName = $address->regency_id ? \App\Models\Regency::find($address->regency_id)?->name : '-';
        $districtName = $address->district_id ? \App\Models\District::find($address->district_id)?->name : '-';
        $villageName = $address->village_id ? \App\Models\Village::find($address->village_id)?->name : '-';

        return view('frontend.dashboard.order.show', compact('order','transactions','address','provinceName','regencyName','districtName','villageName'));
    }
}

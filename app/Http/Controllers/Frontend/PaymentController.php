<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function index()
    {
        if(!Session::has('address')){
            return redirect()->route('user.checkout');
        }
        return view('frontend.pages.payment');
    }

    public function paymentSuccess()
    {
        return view('frontend.pages.payment-success');
    }

    public function storeOrder($paymentMethod, $paymentStatus, $transactionId, $paidAmount)
{
    $cartItems = \Cart::content();
    $totalWeight = 0;

    foreach($cartItems as $item) {
        // Fetch the product from the database
        $product = Product::find($item->id);

        // Log product weight
        \Log::info('Product ID: ' . $item->id . ' - Weight: ' . $product->weight);

        // Calculate total weight
        $totalWeight += $item->qty * $product->weight;
    }

    $order = new Order();
    $order->invoice_id = rand(1, 999999);
    $order->user_id = Auth::user()->id;
    $order->sub_total = getCartTotal();
    $order->amount = getFinalPayableAmount();
    $order->product_qty = $cartItems->sum('qty');
    $order->product_weight = $totalWeight;
    $order->payment_method = $paymentMethod;
    $order->payment_status = $paymentStatus;
    $order->order_address = json_encode(Session::get('address'));
    $order->shipping_method = json_encode(Session::get('shipping_method'));
    $order->coupon = json_encode(Session::get('coupon'));
    $order->order_status = 'pending';
    $order->save();

    // Store order products
    foreach ($cartItems as $item) {
        $product = Product::find($item->id);
        $orderProduct = new OrderProduct();
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $product->id;
        $orderProduct->vendor_id = $product->vendor_id;
        $orderProduct->product_name = $product->name;
        $orderProduct->unit_price = $item->price;
        $orderProduct->qty = $item->qty;
        $orderProduct->weight = $product->weight * $item->qty; // Store weight per product
        $orderProduct->save();

        // Update product quantity
        $updatedQty = ($product->qty - $item->qty);
        $product->qty = $updatedQty;
        $product->save();
    }

    // Store transaction details
    $transaction = new Transaction();
    $transaction->order_id = $order->id;
    $transaction->transaction_id = $transactionId;
    $transaction->payment_method = $paymentMethod;
    $transaction->amount = getFinalPayableAmount();
    $transaction->save();
}


    public function clearSession()
    {
        \Cart::destroy();
        Session::forget('address');
        Session::forget('shipping_method');
        Session::forget('coupon');
    }

    /** pay with cod */
    public function payWithCod(Request $request)
    {
        $codPaySetting = CodSetting::first();
        if($codPaySetting->status == 0){
            return redirect()->back();
        }

        // amount calculation
       $total = getFinalPayableAmount();
       $payableAmount = round($total, 2);


        $this->storeOrder('COD', 0, \Str::random(10), $payableAmount);
        // clear session
        $this->clearSession();

        return redirect()->route('user.payment.success');


    }

}

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
        $shippingFee = Session::get('shipping_fee', 0); // Ambil biaya pengiriman dari sesi
        $courier = Session::get('courier'); // Ambil kurir dari sesi
        $service = Session::get('service'); // Ambil layanan dari sesi

        foreach($cartItems as $item) {
            $product = Product::find($item->id);
            $totalWeight += $item->qty * $product->weight;
        }

        $order = new Order();
        $order->invoice_id = rand(1, 999999);
        $order->user_id = Auth::user()->id;
        $order->sub_total = getCartTotal();
        $order->amount = getFinalPayableAmount() + $shippingFee; // Tambahkan biaya pengiriman ke jumlah total
        $order->product_qty = $cartItems->sum('qty');
        $order->product_weight = $totalWeight;
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->order_address = json_encode(Session::get('address'));
        $order->shipping_method = json_encode(Session::get('shipping_method'));
        $order->courier = $courier; // Simpan kurir
        $order->service = $service; // Simpan layanan
        $order->coupon = json_encode(Session::get('coupon'));
        $order->order_status = 'pending';
        $order->save();

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->weight = $product->weight * $item->qty;
            $orderProduct->courier = $courier; // Simpan kurir
            $orderProduct->service = $service; // Simpan layanan
            $orderProduct->save();

            $product->qty -= $item->qty;
            $product->save();
        }

        $transaction = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $transactionId;
        $transaction->payment_method = $paymentMethod;
        $transaction->amount = getFinalPayableAmount();
        $transaction->shipping_fee = $shippingFee; // Simpan biaya pengiriman
        $transaction->courier = $courier; // Simpan kurir
        $transaction->service = $service; // Simpan layanan
        $transaction->save();
    }

    public function clearSession()
    {
        \Cart::destroy();
        Session::forget('address');
        Session::forget('shipping_method');
        Session::forget('shipping_fee'); // Hapus biaya pengiriman dari sesi
        Session::forget('courier'); // Hapus kurir dari sesi
        Session::forget('service'); // Hapus layanan dari sesi
        Session::forget('coupon');
    }

    public function payWithCod(Request $request)
    {
        $codPaySetting = CodSetting::first();
        if($codPaySetting->status == 0){
            return redirect()->back();
        }

        $total = getFinalPayableAmount();
        $payableAmount = round($total, 2);

        $this->storeOrder('COD', 0, \Str::random(10), $payableAmount);
        $this->clearSession();

        return redirect()->route('user.payment.success');
    }

    private function generatePaymentUrl($order)
    {
        $this->initPaymentGateway();

        $customerDetails = [
            'first_name' => $order->user_id,
            // 'email' => $order->customer_email,
        ];

        $params = [
            'enable_payments' => \App\Helper\Payment::PAYMENT_CHANNELS,
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => ceil($order->amount),
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => \App\Helper\Payment::EXPIRY_UNIT,
                'duration' => \App\Helper\Payment::EXPIRY_DURATION,
            ]
        ];

        try {
            $snap = \Midtrans\Snap::createTransaction($params);
        } catch (\Exception $e) {
            throw $e;
        }

        return $snap->redirect_url;
    }
    private function initPaymentGateway()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = (bool)env('MIDTRANS_PRODUCTION', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

}

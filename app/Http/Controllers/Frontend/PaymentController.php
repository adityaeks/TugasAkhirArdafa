<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
    public function paymentFail()
    {
        return view('frontend.pages.payment-fail');
    }

    public function storeOrder(Request $request)
    {
        // dd('tes');
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
        // Generate UUID untuk order_id yang akan dipakai di Midtrans dan transaction
        $orderUuid = (string) \Str::uuid();
        $order->order_uuid = $orderUuid;
        $order->save();


        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
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

        $params = [
            'payment_type'=> $request->channel,
            'transaction_details' => [
                'order_id' => $orderUuid,
                'gross_amount' => $request->amount,
            ],
            'item_details' => [
                [
                    'price' => $request->amount,
                    'quantity' => 1,
                    'name' => $request->product_name,
                ]
            ],
            'customer_details' => [
                'first_name' => $request->user_name,
                // 'email' => $request->customer_email,
            ],
            'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va']
        ];
        $auth = base64_encode(config('midtrans.serverKey'));


        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->withOptions([
                'verify' => false, // Abaikan SSL certificate
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            $responseBody = $response->json();

            \Log::info('Midtrans Response', ['response' => $responseBody]);

            if (!$response->successful()) {
                \Log::error('Midtrans API call failed', ['response' => $responseBody]);
                return response()->json(['error' => 'Failed to communicate with Midtrans API'], 500);
            }

            if (!isset($responseBody['redirect_url'])) {
                \Log::error('Redirect URL not found in Midtrans response', ['response' => $responseBody]);
                return response()->json(['error' => 'Failed to retrieve redirect URL from Midtrans'], 500);
            }

            $transaction = new Transaction();
            $transaction->order_id = $orderUuid;
            $transaction->status = 'pending';
            // Simpan transaction_id dari response Midtrans jika ada
            if (isset($responseBody['transaction_id'])) {
                $transaction->transaction_id = $responseBody['transaction_id'];
            }
            $transaction->user_name = $request->name;
            $transaction->payment_method = 'midtrans';
            $transaction->product_name = $request->name;
            $transaction->amount = $request->amount;
            $transaction->checkout_link = $responseBody['redirect_url'];
            $transaction->courier = $courier; // Simpan kurir
            $transaction->service = $service; // Simpan layanan
            $transaction->save();

            return response()->json($responseBody);
        } catch (\Exception $e) {
            \Log::error('Error communicating with Midtrans API', ['exception' => $e]);
            return response()->json(['error' => 'Failed to communicate with Midtrans API', 'message' => $e->getMessage()], 500);
        }


    }
    public function webhook(Request $request)
    {
        $auth = base64_encode(config('midtrans.serverKey'));

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->withOptions([
                'verify' => false, // Menonaktifkan verifikasi SSL
            ])->get("https://api.sandbox.midtrans.com/v2/{$request->order_id}/status");

            $responseData = json_decode($response->body());

            // Pastikan respons berhasil didapatkan dan dapat di-decode
            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch transaction status from Midtrans API'], 500);
            }

            // Verifikasi bahwa ada transaction_status dalam respons
            if (!isset($responseData->transaction_status)) {
                return response()->json(['error' => 'Invalid response format from Midtrans API'], 500);
            }


            // Coba cari berdasarkan order_id, jika tidak ketemu coba pakai transaction_id
            $payment = Transaction::where('order_id', $request->order_id)->first();
            if (!$payment && isset($request->transaction_id)) {
                $payment = Transaction::where('transaction_id', $request->transaction_id)->first();
            }
            if (!$payment) {
                return response()->json(['error' => 'Transaction not found for given order_id or transaction_id'], 404);
            }

            // Update transaction_id jika sebelumnya null dan ada di request
            if (empty($payment->transaction_id) && isset($request->transaction_id)) {
                $payment->transaction_id = $request->transaction_id;
            }

            // Update status transaksi
            $payment->status = $responseData->transaction_status;
            $payment->save();

            // Update status order jika ada
            $order = \App\Models\Order::where('order_uuid', $payment->order_id)->first();
            if ($order) {
                // Kolom pembayaran ambil dari status
                $order->status = $responseData->transaction_status;
                // Tidak perlu update order_status, biarkan default (pending)
                $order->save();
            }

            // Pastikan status pembayaran hanya diubah jika belum di-settlement atau capture
            if ($payment->status === 'settlement' || $payment->status === 'capture') {
                return response()->json('Payment has been already processed');
            }

            // Update status pembayaran sesuai dengan response dari Midtrans
            switch ($responseData->transaction_status) {
                case 'capture':
                    $payment->status  = 'capture';
                    break;
                case 'settlement':
                    $payment->status = 'settlement';
                    break;
                case 'pending':
                    $payment->status = 'pending';
                    break;
                case 'deny':
                    $payment->status = 'deny';
                    break;
                case 'expire':
                    $payment->status = 'expire';
                    break;
                case 'cancel':
                    $payment->status = 'cancel';
                    break;
                default:
                    return response()->json(['error' => 'Unsupported transaction status from Midtrans API'], 500);
            }

            // Simpan perubahan status pembayaran
            $payment->save();

            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to communicate with Midtrans API', 'message' => $e->getMessage()], 500);
        }
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

<?php

namespace App\Http\Controllers\Frontend;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ShippingRule;
use App\Models\Transaction;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Gloudemans\Shoppingcart\Facades\Cart;
use LDAP\Result;

class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);
            $response = Http::setCLient($client)->withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->get('https://api.rajaongkir.com/starter/province');

            $provinces = json_decode($response->getBody(), true);
            return view('frontend.pages.checkout', compact('addresses', 'provinces'));
        } catch (\Exception $e) {
            Log::error('Error calculating shipping fee: ' . $e->getMessage());
            return [];
        }
    }


    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'province' => ['required'],
            'city' => ['required'],
            'zip' => ['required', 'max: 200'],
            'address' => ['required', 'max: 200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Address created successfully!', 'success', 'Success');

        return redirect()->back();
    }

    public function checkOutFormSubmit(Request $request)
    {
        $request->validate([
            // 'delivery_package' => ['required', 'integer'],
            'shipping_address_id' => ['required', 'integer'],
           ]);
        $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
       if($address){
           Session::put('address', $address);
       }
        $cartItems = Cart::content();
        $itemDetails = [];
        $totalAmount = 0;
        $totalWeight = 0;

        // Hitung detail barang
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            $itemDetails[] = [
                'id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->qty,
                'name' => $item->name,
            ];
            $totalAmount += $item->price * $item->qty;
            $totalWeight += $product->weight * $item->qty;
        }

        // Tambahkan biaya pengiriman ke itemDetails
        $shippingFee = Session::get('shipping_fee', 0); // Ambil biaya pengiriman dari sesi
        $itemDetails[] = [
            'id' => 'shipping_fee',
            'price' => $shippingFee,
            'quantity' => 1,
            'name' => 'Shipping Fee',
        ];

        // Ambil biaya pengiriman dari sesi
        $shippingFee = Session::get('shipping_fee', 0);
        // Tambahkan biaya pengiriman ke total amount
        $totalAmount += $shippingFee;

        // Ambil nilai kupon dari sesi
        $couponDiscount = Session::get('coupon_discount', 0);
        // Kurangi nilai kupon dari total amount
        $totalAmount -= $couponDiscount;

        $params = [
            'transaction_details' => [
                'order_id' => \Str::uuid(),
                'gross_amount' => $totalAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->user_name,
            ],
            'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va', 'gopay', 'shopeepay', 'dana']
        ];

        $auth = base64_encode(config('midtrans.serverKey'));

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->withOptions([
                'verify' => false,
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            $responseBody = $response->json();

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to communicate with Midtrans API'], 500);
            }

            if (!isset($responseBody['redirect_url'])) {
                return response()->json(['error' => 'Failed to retrieve redirect URL from Midtrans'], 500);
            }

            // Save to orders table
            $order = new Order();
            $order->invoice_id = rand(1, 999999);
            $order->user_id = Auth::user()->id;
            $order->sub_total = getCartTotal();
            $order->amount = $totalAmount;
            $order->product_qty = $cartItems->sum('qty');
            $order->product_name = $product->name;
            $order->product_weight = $totalWeight;
            $order->payment_method = 'midtrans';
            $order->status = 'pending';
            $order->order_address = json_encode(Session::get('address'));
            $order->shipping_method = json_encode(Session::get('shipping_method'));
            $order->courier = $request->courier;
            $order->service = $request->service;
            $order->coupon = json_encode(Session::get('coupon'));
            $order->order_status = 'pending';
            $order->save();

            // Save to order_products table
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
                $orderProduct->courier = $request->courier;
                $orderProduct->service = $request->service;
                $orderProduct->save();

                $product->qty -= $item->qty;
                $product->save();
            }
            // Save transaction with redirect URL
            $transaction = new Transaction();
            $transaction->order_id = $params['transaction_details']['order_id'];
            // $transaction->transaction_id = $params['transaction_id'];
            $transaction->status = 'pending';
            $transaction->user_name = Auth::user()->name;
            $transaction->payment_method = 'midtrans';
            $transaction->product_name = implode(', ', $cartItems->pluck('name')->toArray());
            $transaction->amount = $totalAmount;
            $transaction->checkout_link = $responseBody['redirect_url'];
            $transaction->save();

              // Save transaction_id from Midtrans
            // if (isset($responseBody['transaction_id'])) {
            //     $transaction->transaction_id = $responseBody['transaction_id'];
            //     $transaction->save();
            // }

             // Clear session after checkout
             $this->clearSession();


            return response()->json($responseBody);
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

    public function setTotalProductWeight(Request $request)
    {
        $totalProductWeight = $request->total_weight;
        Session::put('total_product_weight', $totalProductWeight);

        // dd($totalProductWeight);
        return response()->json(['status' => 'success']);
    }


    public function shippingFee(Request $request)
{
    // Ambil address_id dari request
    $addressId = $request->get('address_id');

    // Cari alamat berdasarkan address_id
    $address = UserAddress::find($addressId);

    // Ambil berat produk dari sesi
    $productWeight = Session::get('total_product_weight', 0); // Default value jika tidak ada berat produk

    // Hitung biaya pengiriman
    $availableServices = $this->calculateShippingFee($addressId, $address, $request->get('courier'), $productWeight);

    // Mengembalikan view dengan data yang sudah diproses
    return $this->loadTheme('available_services', ['services' => $availableServices]);
    }

    public function choosePackage(Request $request)
{
    Log::info('Request data:', $request->all());

    $addressId = $request->get('address_id');
    $address = UserAddress::find($addressId);
    $orders = auth()->user()->orders;

    $productWeight = Session::get('total_product_weight', 0);

    $availableServices = $this->calculateShippingFee($orders, $address, $request->get('courier'), $productWeight);

    Log::info('Available services:', $availableServices);

    $selectedPackage = null;
    if (!empty($availableServices)) {
        foreach ($availableServices as $service) {
            Log::info('Checking service:', $service);
            if ($service['service'] === $request->get('delivery_package')) {
                $selectedPackage = $service;
                break;
            }
        }
    }
    // dd($productWeight);

    if ($selectedPackage == null) {
        return response()->json(['error' => 'No selected package found'], 400);
    }

    Session::put('shipping_fee', $selectedPackage['cost']);
    Session::put('courier', $selectedPackage['courier']);
    Session::put('service', $selectedPackage['service']);

    Log::info('Selected package:', $selectedPackage);

    $subtotal = getCartTotal();
    $shippingFee = $selectedPackage['cost'];
    $total = $subtotal + $shippingFee - getCartDiscount();

    return response()->json([
        'shipping_fee' => number_format($shippingFee, 0, ',', '.'),
        'total_amount' => number_format($total, 0, ',', '.'),
    ]);
}



    private function calculateShippingFee($orders, $address, $courier, $productWeight)
    {
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);

            $response = $client->post(env('API_ONGKIR_BASE_URL') . 'cost', [
                'headers' => [
                    'key' => env('API_ONGKIR_KEY'),
                ],
                'form_params' => [
                    'origin' => env('API_ONGKIR_ORIGIN'),
                    'destination' => $address->city,
                    'weight' => $productWeight, // Menggunakan berat produk yang telah disimpan sebelumnya
                    'courier' => $courier,
                ],
            ]);

            $shippingFees = json_decode($response->getBody(), true);
            Log::info('Shipping fees:', $shippingFees);

            $availableServices = [];
            if (!empty($shippingFees['rajaongkir']['results'])) {
                foreach ($shippingFees['rajaongkir']['results'] as $cost) {
                    if (!empty($cost['costs'])) {
                        foreach ($cost['costs'] as $costDetail) {
                            $availableServices[] = [
                                'service' => $costDetail['service'],
                                'description' => $costDetail['description'],
                                'etd' => $costDetail['cost'][0]['etd'],
                                'cost' => $costDetail['cost'][0]['value'],
                                'courier' => $courier,
                                'address_id' => $address->id,
                            ];
                        }
                    }
                }
            }
            Log::info('Available services after processing:', $availableServices);

            return $availableServices;
        } catch (\Exception $e) {
            Log::error('Error calculating shipping fee: ' . $e->getMessage());
            return [];
        }
    }



public function getShippingCost(Request $request)
    {
        $addressId = $request->input('address_id');
        $courier = $request->input('courier');
        $totalWeight = $request->input('total_weight');

        // Ambil detail alamat berdasarkan ID
        $address = UserAddress::find($addressId);
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        // Ambil data kota tujuan dari alamat
        $destinationCityId = $address->city_id; // Pastikan `city_id` ada di tabel user_address

        // ID kota asal (misalnya, Surabaya)
        $originCityId = 39;

        // Panggil API RajaOngkir untuk mendapatkan biaya pengiriman
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => $originCityId,
            'destination' => $destinationCityId,
            'weight' => $totalWeight,
            'courier' => $courier,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch shipping cost'], 500);
        }

        $shippingCost = $response->json();

        return response()->json([
            'data' => $shippingCost['rajaongkir']['results'][0]['costs']
        ]);
    }
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => env('API_ONGKIR_KEY')
        ])->get('https://api.rajaongkir.com/starter/province');

        $provinces = json_decode($response->getBody(), true);

        return view('frontend.pages.checkout', compact('provinces'));
    }

    public function getCities($provinceId)
    {
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);
            $response = Http::setCLient($client)->withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->get('https://api.rajaongkir.com/starter/city', [
                'province' => $provinceId
            ]);

            $cities = json_decode($response->getBody(), true);
            // dd($cities['rajaongkir']['result']);
            return response()->json($cities['rajaongkir']['results']);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    protected function loadTheme($view, $data = [])
    {
        // Memuat view dari folder frontend.page
        return view("frontend.pages.$view", $data);
    }
}

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
use Illuminate\Support\Str;
use LDAP\Result;

class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $cartItems = Cart::content();
        try {
            $response = Http::withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            $provinces = $response->json();
            // Normalisasi agar setiap item punya key 'province_name' dari 'name', 'province', atau '-'
            if (isset($provinces['data']) && is_array($provinces['data'])) {
                foreach ($provinces['data'] as $k => $province) {
                    $provinces['data'][$k]['province_name'] = $province['name'] ?? $province['province'] ?? '-';
                }
            }
            if (!isset($provinces['data'])) {
                $provinces['data'] = [];
            }
            return view('frontend.pages.checkout', compact('addresses', 'provinces', 'cartItems'));
        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            $provinces = ['data' => []];
            return view('frontend.pages.checkout', compact('addresses', 'provinces', 'cartItems'));
        }
    }


    public function createAddress(Request $request)
    {
        \Log::info('Masuk ke createAddress');
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'province' => ['required'],
            'city' => ['required'],
            'district_name' => ['required', 'max:200'], // tambahkan validasi district_name
            'zip' => ['required', 'max:200'],
            'address' => ['required', 'max:200']
        ]);
        dd($request->all());

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->district_name = $request->district_name; // simpan district_name
        $address->district_id = $request->district_id;     // simpan district_id (meski kosong/manual)
        $address->zip = $request->zip;
        $address->address = $request->address;

        \Log::info('Mencoba menyimpan alamat baru:', $address->toArray());
        try {
            $result = $address->save();
            \Log::info('Hasil save alamat:', ['result' => $result, 'id' => $address->id]);
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan alamat: ' . $e->getMessage());
        }

        toastr('Address created successfully!', 'success', 'Success');

        return redirect()->back();
    }

    public function checkOutFormSubmit(Request $request)
    {
        \Log::info('--- MASUK CHECKOUT FORM SUBMIT ---');
        try {
            $shippingType = $request->input('shipping_type', 'courier');
            $rules = [];
            $messages = [];
            if ($shippingType === 'courier') {
                $rules['shipping_address_id'] = ['required', 'integer'];
                $messages['shipping_address_id.required'] = 'Alamat pengiriman wajib diisi';
            }
            $request->validate($rules, $messages);

            $address = null;
            if ($shippingType === 'courier') {
                $address = \App\Models\UserAddress::findOrFail($request->shipping_address_id)->toArray();
                if($address){
                    \Session::put('address', $address);
                }
            }

            if ($shippingType === 'pickup') {
                $shippingFee = 0;
                $courier = null;
                $service = null;
            } else {
                // Pastikan shipping_fee, courier, dan service sudah ada di session
                $shippingFee = \Session::get('shipping_fee');
                $courier = \Session::get('courier');
                $service = \Session::get('service');
                if ($shippingFee === null || $courier === null || $service === null) {
                    \Log::error('Shipping data missing: shipping_fee=' . var_export($shippingFee, true) . ', courier=' . var_export($courier, true) . ', service=' . var_export($service, true));
                    return response()->json(['error' => 'Silakan pilih layanan pengiriman terlebih dahulu.'], 422);
                }
            }

            $cartItems = \Cart::content();
            $itemDetails = [];
            $totalAmount = 0;
            $totalWeight = 0;

            // Hitung detail barang
            foreach ($cartItems as $item) {
                $product = \App\Models\Product::find($item->id);
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
            $itemDetails[] = [
                'id' => 'shipping_fee',
                'price' => $shippingFee,
                'quantity' => 1,
                'name' => 'Shipping Fee',
            ];

            // Tambahkan biaya pengiriman ke total amount
            $totalAmount += $shippingFee;

            // Ambil nilai kupon dari sesi
            $couponDiscount = Session::get('coupon_discount', 0);
            // Kurangi nilai kupon dari total amount
            $totalAmount -= $couponDiscount;

            // Generate UUID ONCE and use for all references
            $order_uuid = (string) \Str::uuid();

            $params = [
                'transaction_details' => [
                    'order_id' => $order_uuid,
                    'gross_amount' => $totalAmount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->user_name ?? Auth::user()->name,
                ],
                'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va', 'gopay', 'shopeepay', 'dana']
            ];

            $auth = base64_encode(config('midtrans.serverKey'));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Basic $auth",
            ])->withOptions([
                'verify' => false,
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            $responseBody = $response->json();

            if (!$response->successful()) {
                Log::error('Midtrans API failed: ' . json_encode($responseBody));
                return response()->json(['error' => 'Failed to communicate with Midtrans API'], 500);
            }

            if (!isset($responseBody['redirect_url'])) {
                Log::error('Midtrans response missing redirect_url: ' . json_encode($responseBody));
                return response()->json(['error' => 'Failed to retrieve redirect URL from Midtrans'], 500);
            }

            // Save to orders table
            $order = new \App\Models\Order();
            $order->invoice_id = rand(1, 999999);
            $order->user_id = \Auth::user()->id;
            $order->sub_total = getCartTotal();
            $order->shipping_fee = $shippingFee;
            $order->amount = $totalAmount;
            $order->product_qty = $cartItems->sum('qty');
            $order->product_name = isset($product) ? $product->name : '';
            $order->product_weight = $totalWeight;
            $order->payment_method = 'midtrans';
            $order->status = 'pending';
            $order->order_address = json_encode(\Session::get('address'));
            $order->shipping_method = json_encode(\Session::get('shipping_method'));
            $order->courier = $courier;
            $order->service = $service;
            $order->coupon = json_encode(\Session::get('coupon'));
            $order->order_status = 'pending';
            $order->order_uuid = $order_uuid;
            $order->save();

            // Save to order_products table
            foreach ($cartItems as $item) {
                $product = \App\Models\Product::find($item->id);
                $orderProduct = new \App\Models\OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $product->id;
                $orderProduct->product_name = $product->name;
                $orderProduct->unit_price = $item->price;
                $orderProduct->qty = $item->qty;
                $orderProduct->weight = $product->weight * $item->qty;
                $orderProduct->courier = $courier;
                $orderProduct->service = $service;
                $orderProduct->save();

                $product->qty -= $item->qty;
                $product->save();
            }
            // Save transaction with redirect URL
            $transaction = new \App\Models\Transaction();
            $transaction->order_id = $order_uuid;
            $transaction->status = 'pending';
            $transaction->user_name = \Auth::user()->name;
            $transaction->payment_method = 'midtrans';
            $transaction->product_name = implode(', ', $cartItems->pluck('name')->toArray());
            $transaction->amount = $totalAmount;
            $transaction->checkout_link = $responseBody['redirect_url'];
            $transaction->save();

            // Clear session after checkout
            $this->clearSession();

            \Log::info('--- SELESAI CHECKOUT FORM SUBMIT ---');
            return response()->json($responseBody);
        } catch (\Throwable $e) {
            \Log::error('Checkout error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
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
        $addressId = $request->get('address_id');
        $productWeight = $request->get('total_weight');
        $courier = $request->get('courier');
        $address = UserAddress::find($addressId);
        // Diasumsikan address->district_id sudah ada, jika tidak, tambahkan field ini
        $districtId = $address->district_id ?? null;
        if (!$districtId) {
            return response()->json(['error' => 'District ID not found in address'], 400);
        }
        try {
            $response = Http::withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->post('https://rajaongkir.komerce.id/api/v1/cost/district', [
                'origin_district_id' => env('API_ONGKIR_ORIGIN_DISTRICT_ID'), // set di .env
                'destination_district_id' => $districtId,
                'weight' => $productWeight,
                'courier' => $courier,
            ]);
            $costs = $response->json();
            // Struktur response: ['data' => [...]]
            return response()->json($costs['data'] ?? []);
        } catch (\Exception $e) {
            Log::error('Error fetching shipping fee: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function choosePackage(Request $request)
    {
        Log::info('Request data:', $request->all());

        $addressId = $request->get('address_id');
        $address = UserAddress::find($addressId);
        $orders = auth()->user()->orders;

        $productWeight = $request->get('total_weight'); // Ambil total_weight dari request

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
            'shipping_fee' => $shippingFee,
            'total_amount' => $total,
        ]);
    }



    private function calculateShippingFee($orders, $address, $courier, $productWeight)
    {
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);

            Log::info('RajaOngkir API Request:', [
                'origin' => env('API_ONGKIR_ORIGIN'),
                'destination' => $address->city,
                'weight' => $productWeight,
                'courier' => $courier,
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
            Log::info('RajaOngkir API Response (Raw):', ['body' => $response->getBody()->getContents()]);
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
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        // dd($response->json());
        $provinces = $response->json();
        if (!isset($provinces['data'])) {
            $provinces['data'] = [];
        }
        return view('frontend.pages.checkout', compact('provinces'));
    }

    public function getCities($provinceId)
    {
        try {
            $response = Http::withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/city?province_id=' . $provinceId);

            $cities = $response->json();
            dd($cities);
            // Normalisasi agar setiap item punya key 'city_id' dan 'city_name'
            $result = [];
            if (isset($cities['data']) && is_array($cities['data'])) {
                foreach ($cities['data'] as $city) {
                    $result[] = [
                        'city_id' => $city['id'] ?? $city['city_id'] ?? '-',
                        'city_name' => $city['name'] ?? $city['city_name'] ?? '-',
                    ];
                }
            }
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    // Hapus duplikasi method searchCity, sisakan satu versi yang benar
    public function searchCity(Request $request)
    {
        $search = $request->get('search');
        $response = Http::withHeaders([
            'key' => env('API_ONGKIR_KEY')
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . $search);

        $cities = $response->json();
        // dd($cities);
        Log::info('searchCity response:', $cities);
        $result = [];
        if (isset($cities['data']) && is_array($cities['data'])) {
            foreach ($cities['data'] as $city) {
                $result[] = [
                    'city_id' => $city['id'] ?? '-',
                    'city_name' => $city['label'] ?? $city['name'] ?? $city['city'] ?? $city['city_name'] ?? '-',
                ];
            }
        }
        return response()->json($result);
    }

    public function searchDistrict(Request $request)
    {
        $search = $request->get('search');
        $cityId = $request->get('city_id');
        $url = 'https://rajaongkir.komerce.id/api/v1/destination/district?search=' . urlencode($search);
        if ($cityId) {
            $url .= '&city=' . $cityId;
        }
        $response = Http::withHeaders([
            'key' => env('API_ONGKIR_KEY')
        ])->get($url);

        $districts = $response->json();
        $result = [];
        if (isset($districts['data']) && is_array($districts['data'])) {
            foreach ($districts['data'] as $district) {
                $result[] = [
                    'district_id' => $district['id'] ?? '-',
                    'district_name' => $district['label'] ?? $district['name'] ?? $district['district'] ?? $district['district_name'] ?? '-',
                ];
            }
        }
        // Jika gagal, return array kosong, jangan error
        return response()->json($result);
    }

    protected function loadTheme($view, $data = [])
    {
        // Memuat view dari folder frontend.page
        return view("frontend.pages.$view", $data);
    }
}

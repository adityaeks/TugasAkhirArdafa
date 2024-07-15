<?php

namespace App\Http\Controllers\Frontend;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckOutController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();
        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods'));
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'country' => ['required', 'max: 200'],
            'state' => ['required', 'max: 200'],
            'city' => ['required', 'max: 200'],
            'zip' => ['required', 'max: 200'],
            'address' => ['required', 'max: 200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Address created successfully!', 'success', 'Success');

        return redirect()->back();
    }

    public function checkOutFormSubmit(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'shipping_method_id' => ['nullable', 'integer'],
            'shipping_address_id' => ['required', 'integer'],
            'delivery_service' => ['required'],
            'total_qty' => ['required', 'integer'],
            'total_price' => ['required', 'numeric'],
        ]);

        if ($request->filled('shipping_method_id')) {
            $shippingMethod = ShippingRule::find($request->shipping_method_id);
            if($shippingMethod){
                Session::put('shipping_method', [
                    'id' => $shippingMethod->id,
                    'name' => $shippingMethod->name,
                    'type' => $shippingMethod->type,
                    'cost' => $shippingMethod->cost
                ]);
            }
        }

        $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
        if($address){
            Session::put('address', $address);
        }

        return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
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
        $addressId = $request->get('address_id');
        $address = UserAddress::find($addressId);
        $orders = auth()->user()->orders;

        // Mengambil berat produk dari sesi sebelumnya
        $productWeight = Session::get('product_weight', 0); // Default value jika tidak ada berat produk

        $availableServices = $this->calculateShippingFee($orders, $address, $request->get('courier'), $productWeight);

        $selectedPackage = null;
        if (!empty($availableServices)) {
            foreach ($availableServices as $service) {
                if ($service['service'] === $request->get('delivery_package')) {
                    $selectedPackage = $service;
                    continue;
                }
            }
        }

        if ($selectedPackage == null) {
            return [];
        }

        // Simpan biaya pengiriman, kurir, dan layanan di sesi
        Session::put('shipping_fee', $selectedPackage['cost']);
        Session::put('courier', $selectedPackage['courier']);
        Session::put('service', $selectedPackage['service']);

        return [
            'shipping_fee' => number_format($selectedPackage['cost']),
            'grand_total' => number_format($orders->sum('amount') + $selectedPackage['cost']),
        ];
    }
    private function calculateShippingFee($orders, $address, $courier, $productWeight)
{
    $shippingFees = [];

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
        // dd($shippingFees);

        return $availableServices;
    } catch (\Exception $e) {
        Log::error('Error calculating shipping fee: ' . $e->getMessage());
        return [];
    }
}



    protected function loadTheme($view, $data = [])
    {
        // Memuat view dari folder frontend.page
        return view("frontend.pages.$view", $data);
    }
}

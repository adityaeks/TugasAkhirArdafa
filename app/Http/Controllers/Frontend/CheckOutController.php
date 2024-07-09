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
       $request->validate([
        'shipping_method_id' => ['required', 'integer'],
        'shipping_address_id' => ['required', 'integer'],
       ]);

       $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
       if($shippingMethod){
           Session::put('shipping_method', [
                'id' => $shippingMethod->id,
                'name' => $shippingMethod->name,
                'type' => $shippingMethod->type,
                'cost' => $shippingMethod->cost
           ]);
       }
       $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
       if($address){
           Session::put('address', $address);
       }

       return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }



    public function shippingFee(Request $request)
    {
        // Ambil address_id dari request
        $addressId = $request->get('address_id');

        // Cari alamat berdasarkan address_id
        $address = UserAddress::find($addressId);

        // Dapatkan semua order terkait dengan pengguna yang sedang login
        $orders = auth()->user()->orders;

        // Debugging untuk memastikan metode dipanggil
        // dd("Memanggil calculateShippingFee"); // Tambahkan ini untuk memastikan metode dipanggil

        // Hitung biaya pengiriman
        $availableServices = $this->calculateShippingFee($orders, $address, $request->get('courier'));

        // Mengembalikan view dengan data yang sudah diproses
        return $this->loadTheme('available_services', ['services' => $availableServices]);
    }




    private function calculateShippingFee($orders, $address, $courier)
{
    $shippingFees = [];

    try {
        $client = new Client([
            'verify' => false, // Nonaktifkan verifikasi SSL
        ]);

        // Debugging sebelum request HTTP
        // dd("Sebelum mengirimkan request HTTP ke API ongkir");

        $response = $client->post(env('API_ONGKIR_BASE_URL') . 'cost', [
            'headers' => [
                'key' => env('API_ONGKIR_KEY'),
            ],
            'form_params' => [
                'origin' => env('API_ONGKIR_ORIGIN'),
                'destination' => $address->city,
                'weight' => $orders->sum('product_weight'), // Pastikan ini menjumlahkan berat dari semua order
                'courier' => $courier,
            ],
        ]);

        // Debugging setelah menerima response
        // dd("Response diterima: " . $response->getBody());

        $shippingFees = json_decode($response->getBody(), true);

        // Debugging sebelum dd
        // dd("Shipping fees: " . json_encode($shippingFees));
    } catch (\Exception $e) {
        // Debugging untuk eksepsi
        // dd("Terjadi kesalahan: " . $e->getMessage());

        return [];
    }
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
    return $availableServices;
}


    protected function loadTheme($view, $data = [])
    {
        // Memuat view dari folder frontend.page
        return view("frontend.pages.$view", $data);
    }
}

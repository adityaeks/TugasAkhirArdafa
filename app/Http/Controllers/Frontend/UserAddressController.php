<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();

        return view('frontend.dashboard.address.index', compact('addresses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);
            $response = Http::setCLient($client)->withHeaders([
                'key' => env('API_ONGKIR_KEY')
            ])->get('https://api.rajaongkir.com/starter/province');

            $provinces = json_decode($response->getBody(), true);
            return view('frontend.dashboard.address.create', compact('provinces'));
        } catch (\Exception $e) {
            Log::error('Error calculating shipping fee: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'max:200', 'email'],
            'phone' => ['required', 'max:200'],
            'province' => ['required'],
            'city' => ['required'],
            'zip' => ['required', 'max:200'],
            'address' => ['required'],
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Created Successfully!', 'success', 'Success');

        return redirect()->route('user.address.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $address = UserAddress::findOrFail($id);
        return view('frontend.dashboard.address.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'max:200', 'email'],
            'phone' => ['required', 'max:200'],
            'province' => ['required'],
            'city' => ['required'],
            'zip' => ['required', 'max:200'],
            'address' => ['required'],
        ]);

        $address = UserAddress::findOrFail($id);
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->province = $request->province;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Updated Successfully!', 'success', 'Success');

        return redirect()->route('user.address.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = UserAddress::findOrFail($id);
        $address->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    private function getProvinces()
{
    try {
        $client = new Client(['verify' => false]);
        $response = Http::setClient($client)->withHeaders([
            'key' => env('API_ONGKIR_KEY')
        ])->get('https://api.rajaongkir.com/starter/province');

        $provinces = json_decode($response->getBody(), true)['rajaongkir']['results'];
        return collect($provinces)->pluck('province', 'province_id')->all();
    } catch (\Exception $e) {
        Log::error('Error fetching provinces: ' . $e->getMessage());
        return [];
    }
}
    public function getCities($provinceId)
    {
        try {
            $client = new Client([
                'verify' => false, // Nonaktifkan verifikasi SSL
            ]);
            $response = Http::setClient($client)->withHeaders([
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
}

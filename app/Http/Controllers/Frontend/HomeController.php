<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Category;
use App\Models\HomePageSetting;
use App\Models\HomeSetting;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Cache::rememberForever('sliders', function(){
            return Slider::where('status', 1)->orderBy('serial', 'asc')->get();
        });

        $popularCategory = HomeSetting::where('key', 'popular_category_section')->first();

        $categoryProductSliderSectionOne = HomeSetting::where('key', 'product_slider_section_one')->first();
        $categoryProductSliderSectionTwo = HomeSetting::where('key', 'product_slider_section_two')->first();
        $categoryProductSliderSectionThree = HomeSetting::where('key', 'product_slider_section_three')->first();

        // banners

        $homepage_secion_banner_one = Adverisement::where('key', 'homepage_secion_banner_one')->first();
        $homepage_secion_banner_one = json_decode($homepage_secion_banner_one?->value);

        $homepage_secion_banner_two = Adverisement::where('key', 'homepage_secion_banner_two')->first();
        $homepage_secion_banner_two = json_decode($homepage_secion_banner_two?->value);

        $homepage_secion_banner_three = Adverisement::where('key', 'homepage_secion_banner_three')->first();
        $homepage_secion_banner_three = json_decode($homepage_secion_banner_three?->value);

        $homepage_secion_banner_four = Adverisement::where('key', 'homepage_secion_banner_four')->first();
        $homepage_secion_banner_four = json_decode($homepage_secion_banner_four?->value);


        return view('frontend.home.home');
    }

    function ShowProductModal(string $id) {
       $product = Product::findOrFail($id);

       $content = view('frontend.layouts.modal', compact('product'))->render();

       return Response::make($content, 200, ['Content-Type' => 'text/html']);
    }
}

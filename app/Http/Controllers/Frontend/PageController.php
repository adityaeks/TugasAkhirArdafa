<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\About;
use App\Models\Category;
use App\Models\EmailConfiguration;
use App\Models\TermsAndCondition;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function termsAndCondition()
    {
        $terms = TermsAndCondition::first();
        return view('frontend.pages.terms-and-condition', compact('terms'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function handleContactForm(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'max:200'],
            'message' => ['required', 'max:1000']
        ]);

        $setting = EmailConfiguration::first();

        Mail::to($setting->email)->send(new Contact($request->subject, $request->message, $request->email));

        return response(['status' => 'success', 'message' => 'Mail sent successfully!']);

    }

    public function prasmananBuffet()
    {
        $category = Category::where('slug', 'prasmanan-buffet')->first();
        $products = collect();

        if ($category) {
            $products = Product::where('category_id', $category->id)
                               ->where('status', 1)
                               ->orderBy('id', 'DESC')
                               ->get();
        }

        return view('frontend.pages.prasmanan-buffet', compact('products'));
    }

    public function mealBox()
    {
        $category = Category::where('slug', 'meal-box')->first();
        $products = collect();

        if ($category) {
            $products = Product::where('category_id', $category->id)
                               ->where('status', 1)
                               ->orderBy('id', 'DESC')
                               ->get();
        }

        return view('frontend.pages.meal-box', compact('products'));
    }

    public function tumpengNasiLiwet()
    {
        $category = Category::where('slug', 'tumpeng-nasi-liwet')->first();
        $products = collect();

        if ($category) {
            $products = Product::where('category_id', $category->id)
                               ->where('status', 1)
                               ->orderBy('id', 'DESC')
                               ->get();
        }

        return view('frontend.pages.tumpeng-nasi-liwet', compact('products'));
    }

    public function dailyHomeCatering()
    {
        $category = Category::where('slug', 'daily-home-catering')->first();
        $products = collect();

        if ($category) {
            $products = Product::where('category_id', $category->id)
                               ->where('status', 1)
                               ->orderBy('id', 'DESC')
                               ->get();
        }

        return view('frontend.pages.daily-home-catering', compact('products'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendProductController extends Controller
{
    public function productsIndex(Request $request)
{
    if ($request->has('category')) {
        $category = Category::where('slug', $request->category)->firstOrFail();
        $products = Product::with(['category'])
            ->where([
                'category_id' => $category->id,
                'status' => 1,
                'is_approved' => 1
            ])
            ->when($request->has('range'), function($query) use ($request) {
                $price = explode(';', $request->range);
                $from = $price[0];
                $to = $price[1];

                return $query->where('price', '>=', $from)->where('price', '<=', $to);
            })
            ->paginate(12);
    } elseif ($request->has('search')) {
        $products = Product::with(['category'])
            ->where(['status' => 1, 'is_approved' => 1])
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('long_description', 'like', '%'.$request->search.'%')
                    ->orWhereHas('category', function($query) use ($request) {
                        $query->where('name', 'like', '%'.$request->search.'%')
                            ->orWhere('long_description', 'like', '%'.$request->search.'%');
                    });
            })
            ->paginate(12);
    } else {
        // Tambahkan definisi default untuk variabel $products
        $products = Product::with(['category'])
            ->where(['status' => 1, 'is_approved' => 1])
            ->paginate(12);
    }

    $categories = Category::all();
    // atur benner
    $productpage_banner_section = Adverisement::where('key', 'productpage_banner_section')->first();
    $productpage_banner_section = json_decode($productpage_banner_section?->value);

    return view('frontend.pages.product', compact('products', 'categories', 'productpage_banner_section'));
}


    public function showProduct(string $slug)
    {
        $product = Product::with(['vendor', 'category'])->where('slug', $slug)->where('status', 1)->first();
        return view('frontend.pages.product-detail', compact('product'));
    }

    public function chageListView(Request $request)
    {
       Session::put('product_list_style', $request->style);
    }
}

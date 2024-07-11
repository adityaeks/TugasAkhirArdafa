<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    public function cartDetails()
    {
        $cartItems = Cart::content();

        if(count($cartItems) === 0){
            Session::forget('coupon');
            toastr('Silakan tambahkan produk ke keranjang untuk melihat halaman keranjang anda  ', 'warning', 'Keranjang kosong!');
            return redirect()->route('home');
        }

        $cartpage_banner_section = Adverisement::where('key', 'cartpage_banner_section')->first();
        $cartpage_banner_section = json_decode($cartpage_banner_section?->value);

        return view('frontend.pages.cart-detail', compact('cartItems', 'cartpage_banner_section'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // cek stock produk
        if($product->qty === 0){
            return response(['status' => 'error', 'message' => 'Stok produk habis']);
        } elseif($product->qty < $request->qty){
            return response(['status' => 'error', 'message' => 'Jumlah stok tidak tersedia']);
        }

        // cek diskon
        $productPrice = checkDiscount($product) ? $product->offer_price : $product->price;

        $cartData = [
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty,
            'price' => $productPrice,
            'weight' => $product->weight * $request->qty, // Menggunakan berat produk dari database dan mengalikannya dengan jumlah
            'options' => [
                'image' => $product->thumb_image,
                'slug' => $product->slug,
            ]
        ];

        // \Log::info('Adding to cart: ', $cartData);


        Cart::add($cartData);

        return response(['status' => 'success', 'message' => 'Berhasil ditambahkan ke keranjang!']);
    }


    /** Update product quantity */
    public function updateProductQty(Request $request)
    {
        $cartItem = Cart::get($request->rowId);
        $productId = $cartItem->id;
        $product = Product::findOrFail($productId);

        // cek stok produk
        if($product->qty === 0){
            return response(['status' => 'error', 'message' => 'Stok produk habis']);
        } elseif($product->qty < $request->qty){
            return response(['status' => 'error', 'message' => 'Jumlah stok tidak tersedia']);
        }

        $weight = $product->weight * $request->qty; // Menggunakan berat produk dari database dan mengalikannya dengan jumlah baru

        Cart::update($request->rowId, ['qty' => $request->qty, 'weight' => $weight]);
        $productTotal = $this->getProductTotal($request->rowId);

        return response(['status' => 'success', 'message' => 'Stok Produk di update!', 'product_total' => $productTotal]);
    }

    public function getTotalWeight()
    {
        $totalWeight = 0;
        foreach (Cart::content() as $item) {
            $totalWeight += $item->weight;
        }
        return $totalWeight;
    }

    /** get product total */
    public function getProductTotal($rowId)
    {
       $product = Cart::get($rowId);
       $total = ($product->price + $product->options->variants_total) * $product->qty;
       return $total;
    }

    /** get cart total amount */
    public function cartTotal()
    {
        $total = 0;
        foreach(Cart::content() as $product){
            $total += $this->getProductTotal($product->rowId);
        }

        return $total;
    }

    /** clear all cart products */
    public function clearCart()
    {
        Cart::destroy();

        return response(['status' => 'success', 'message' => 'Keranjang di bersihkan']);
    }

    /** Remove product form cart */
    public function removeProduct($rowId)
    {
        Cart::remove($rowId);
        toastr('Produk berhasil dihapus!', 'success', 'Success');
        return redirect()->back();
    }

    /** Get cart count */
    public function getCartCount()
    {
        return Cart::content()->count();
    }

    /** Get all cart products */
    public function getCartProducts()
    {
        return Cart::content();
    }

    /** Romve product form sidebar cart */
    public function removeSidebarProduct(Request $request)
    {
        Cart::remove($request->rowId);

        return response(['status' => 'success', 'message' => 'Produk berhasil dihapus!']);
    }

    /** Apply coupon */
    public function applyCoupon(Request $request)
    {
        if($request->coupon_code === null){
            return response(['status' => 'error', 'message' => 'Kupon diperlukan']);
        }

        $coupon = Coupon::where(['code' => $request->coupon_code, 'status' => 1])->first();

        if($coupon === null){
            return response(['status' => 'error', 'message' => 'Kupon tidak ada!']);
        }elseif($coupon->start_date > date('Y-m-d')){
            return response(['status' => 'error', 'message' => 'Kupon tidak ada!']);
        }elseif($coupon->end_date < date('Y-m-d')){
            return response(['status' => 'error', 'message' => 'Kupon kadaluarsa']);
        }elseif($coupon->total_used >= $coupon->quantity){
            return response(['status' => 'error', 'message' => 'Kupon tidak bisa dipakai']);
        }

        if($coupon->discount_type === 'amount'){
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'amount',
                'discount' => $coupon->discount
            ]);
        }elseif($coupon->discount_type === 'percent'){
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'percent',
                'discount' => $coupon->discount
            ]);
        }

        return response(['status' => 'success', 'message' => 'Kupon berhasil digunakan!']);
    }

    /** Calculate coupon discount */
    public function couponCalculation()
    {
        if(Session::has('coupon')){
            $coupon = Session::get('coupon');
            $subTotal = getCartTotal();
            if($coupon['discount_type'] === 'amount'){
                $total = $subTotal - $coupon['discount'];
                return response(['status' => 'success', 'cart_total' => $total, 'discount' => $coupon['discount']]);
            }elseif($coupon['discount_type'] === 'percent'){
                $discount = $subTotal - ($subTotal * $coupon['discount'] / 100);
                $total = $subTotal - $discount;
                return response(['status' => 'success', 'cart_total' => $total, 'discount' => $discount]);
            }
        }else {
            $total = getCartTotal();
            return response(['status' => 'success', 'cart_total' => $total, 'discount' => 0]);
        }
    }

}

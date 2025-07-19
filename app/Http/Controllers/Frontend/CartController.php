<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Adverisement;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function cartDetails()
    {
        // dd(Session::all());
        $cartItems = Cart::content();

        // dd($cartItems);

        // if(count($cartItems) === 0){
        //     Session::forget('coupon');
        //     toastr('Silakan tambahkan produk ke keranjang untuk melihat halaman keranjang anda  ', 'warning', 'Keranjang kosong!');
        //     return redirect()->route('home');
        // }

        $cartpage_banner_section = Adverisement::where('key', 'cartpage_banner_section')->first();
        $cartpage_banner_section = json_decode($cartpage_banner_section?->value);

        return view('frontend.pages.cart-detail', compact('cartItems', 'cartpage_banner_section'));
    }

    public function addToCart(Request $request)
    {
        try {
            // Debug incoming request data
            // \Log::info('Request Data:', $request->all());

            $product = Product::findOrFail($request->product_id);

            // Debug product weight when adding to cart
            Log::info('Product Weight when adding to cart:', ['product_id' => $product->id, 'weight' => $product->weight]);

            // Cek stock produk
            if ($product->qty === 0) {
                // \Log::warning('Stok produk habis untuk ID: ' . $request->product_id);
                return response()->json(['status' => 'error', 'message' => 'Stok produk habis']);
            } elseif ($product->qty < $request->quantity) {
                // \Log::warning('Jumlah stok tidak tersedia untuk ID: ' . $request->product_id . ', diminta: ' . $request->quantity . ', tersedia: ' . $product->qty);
                return response()->json(['status' => 'error', 'message' => 'Jumlah stok tidak tersedia']);
            }

            // Cek diskon
            $productPrice = checkDiscount($product) ? $product->offer_price : $product->price;

            $cartData = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'price' => $productPrice,
                'weight' => $product->weight,
                'options' => [
                    'image' => $product->thumb_image,
                    'slug' => $product->slug
                ]
            ];

            // Debug cart data before adding
            // Log::info('Cart Data to be added:', $cartData);

            // Add to cart
            $cart = Cart::add($cartData);

            // Debug cart after adding
            // \Log::info('Cart Content:', Cart::content()->toArray());
            // \Log::info('Cart Total:', Cart::total());
            // \Log::info('Cart Count:', Cart::count());

            // Return response with cart data
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart' => [
                    'content' => Cart::content()->toArray(),
                    'total' => Cart::total(),
                    'count' => Cart::count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in addToCart: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }




    /** Update product quantity */
    public function updateProductQty(Request $request)
    {
        // Perbaikan: gunakan 'id' bukan 'rowId' pada request body JS
        $rowId = $request->id ?? $request->rowId;
        if (!$rowId) {
            return response()->json(['status' => 'error', 'message' => 'ID keranjang tidak ditemukan'], 400);
        }

        $cartItem = \Gloudemans\Shoppingcart\Facades\Cart::get($rowId);
        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan di keranjang'], 404);
        }

        $productId = $cartItem->id;
        $product = \App\Models\Product::find($productId);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
        }

        // Check product quantity
        if ($product->qty === 0) {
            return response()->json(['status' => 'error', 'message' => 'Stok produk habis']);
        } elseif ($product->qty < $request->quantity) {
            return response()->json(['status' => 'error', 'message' => 'Jumlah stok tidak tersedia']);
        }

        \Gloudemans\Shoppingcart\Facades\Cart::update($rowId, $request->quantity);
        $productTotal = $this->getProductTotal($rowId);
        $productWeight = $cartItem->weight * $request->quantity;

        return response()->json([
            'status' => 'success',
            'message' => 'Product Quantity Updated!',
            'product_total' => $productTotal,
            'product_weight' => $productWeight
        ]);
    }


    public function getTotalWeightAjax()
    {
        try {
            $totalWeight = 0;

            foreach (Cart::content() as $item) {
                $totalWeight += $item->weight * $item->qty;
            }

            return response()->json($totalWeight);
        } catch (\Exception $e) {
            Log::error('Error in getTotalWeightAjax: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghitung total berat.'], 500);
        }
    }

    /** get product total */
    public function getProductTotal($rowId)
    {
       $product = Cart::get($rowId);
       $variantsTotal = $product->options->variants_total ?? 0;
       $total = ($product->price + $variantsTotal) * $product->qty;
       return $total;
    }

    /** get cart total amount */
    public function cartTotal()
    {
        try {
            $total = 0;
            foreach(Cart::content() as $product){
                $total += $this->getProductTotal($product->rowId);
            }
            Log::info('Calculated Cart Total: ' . $total);
            return response()->json($total);
        } catch (\Exception $e) {
            Log::error('Error in cartTotal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Terjadi kesalahan saat menghitung total keranjang.'], 500);
        }
    }

    /** clear all cart products */
    public function clearCart()
    {
        try {
            Cart::destroy();

            return response(['status' => 'success', 'message' => 'Keranjang di bersihkan']);
        } catch (\Exception $e) {
            Log::error('Error in clearCart: ' . $e->getMessage());
            return response(['status' => 'error', 'message' => 'Terjadi kesalahan saat membersihkan keranjang.'], 500);
        }
    }

    /** Remove product form cart */
    public function removeProduct($rowId)
    {
        try {
            Cart::remove($rowId);
            return response()->json(['status' => 'success', 'message' => 'Produk berhasil dihapus!']);
        } catch (\Exception $e) {
            Log::error('Error in removeProduct: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus produk!'], 500);
        }
    }

    /** Get cart count */
    public function getCartCount()
    {
        try {
            return response()->json(Cart::content()->count());
        } catch (\Exception $e) {
            Log::error('Error in getCartCount: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mendapatkan jumlah keranjang.'], 500);
        }
    }

    /** Get all cart products */
    public function getCartProducts()
    {
        try {
            return response()->json(Cart::content());
        } catch (\Exception $e) {
            Log::error('Error in getCartProducts: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mendapatkan produk keranjang.'], 500);
        }
    }
    public function getCartWeight()
    {
        try {
            return response()->json(Cart::content());
        } catch (\Exception $e) {
            Log::error('Error in getCartWeight: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mendapatkan berat keranjang.'], 500);
        }
    }

    /** Romve product form sidebar cart */
    public function removeSidebarProduct(Request $request)
    {
        try {
            Cart::remove($request->rowId);

            return response(['status' => 'success', 'message' => 'Produk berhasil dihapus!']);
        } catch (\Exception $e) {
            Log::error('Error in removeSidebarProduct: ' . $e->getMessage());
            return response(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus produk dari sidebar.'], 500);
        }
    }

}

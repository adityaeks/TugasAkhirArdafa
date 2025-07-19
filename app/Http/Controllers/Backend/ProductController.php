<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\Product;
// use App\Models\ProductImageGallery;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ProductController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('admin.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.produk.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:3000'],
            'name' => ['required', 'max:200'],
            'category' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            'weight' => ['required'],
            'short_description' => ['required', 'max: 600'],
            'long_description' => ['required'],
            'status' => ['required']
        ]);

        /** Handle the image upload */
        $imagePath = $this->uploadImage($request, 'image', 'uploads');

        $product = new Product();
        $product->thumb_image = $imagePath;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category;
        $product->qty = $request->qty;
        $product->weight = $request->weight;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->save();

        toastr('Created Successfully!', 'success');

        return redirect()->route('admin.produk.index');

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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.produk.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'name' => ['required', 'max:200'],
            'category' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            'weight' => ['required'],
            'short_description' => ['required', 'max: 600'],
            'long_description' => ['required'],
            'status' => ['required']
        ]);

        $product = Product::findOrFail($id);

        /** Handle the image upload */
        $imagePath = $this->updateImage($request, 'image', 'uploads', $product->thumb_image);

        $product->thumb_image = empty(!$imagePath) ? $imagePath : $product->thumb_image;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category;
        $product->qty = $request->qty;
        $product->weight = $request->weight;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->route('admin.produk.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \Log::info('Proses hapus produk dimulai', ['id' => $id]);
        try {
            $product = Product::findOrFail($id);
            \Log::info('Produk ditemukan', ['product' => $product]);

            // Cek apakah produk memiliki pesanan
            if(OrderProduct::where('product_id', $product->id)->count() > 0){
                \Log::warning('Produk memiliki pesanan, tidak bisa dihapus', ['product_id' => $product->id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk ini memiliki pesanan, tidak bisa dihapus.'
                ]);
            }

            // Hapus gambar utama produk
            $this->deleteImage($product->thumb_image);
            \Log::info('Gambar utama dihapus', ['thumb_image' => $product->thumb_image]);

            // Hapus gambar galeri produk (Dihilangkan karena tabel tidak ada)
            // $galleryImages = ProductImageGallery::where('product_id', $product->id)->get();
            // foreach($galleryImages as $image){
            //     $this->deleteImage($image->image);
            //     $image->delete();
            // }
            // \Log::info('Gambar galeri dihapus', ['gallery_count' => count($galleryImages)]);

            // Hapus produk itu sendiri
            $product->delete();
            \Log::info('Produk berhasil dihapus', ['id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saat hapus produk', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }


    public function changeStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $request->status == 'true' ? 1 : 0;
        $product->save();

        return response(['message' => 'Status has been updated!']);
    }


}

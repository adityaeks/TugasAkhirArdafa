@extends('frontend.layouts.app')

@section('title', 'Semua Produk - OurKitchen')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary/10 to-primary/5 py-16">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Semua Produk</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Temukan berbagai pilihan menu catering berkualitas dari Our Kitchen untuk memenuhi kebutuhan kuliner Anda.
                </p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-16">
        <div class="container mx-auto px-6 lg:px-16">
            <!-- Filter and Search -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                    <!-- Search -->
                    <div class="w-full lg:w-96">
                        <form action="{{ route('products.index') }}" method="GET" class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
                            <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    {{-- <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}"
                            class="px-6 py-3 rounded-full text-sm font-medium transition-all duration-300 {{ !request('category') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:shadow-md' }}">
                            <i class="fas fa-th-large mr-2"></i>
                            Semua
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                class="px-6 py-3 rounded-full text-sm font-medium transition-all duration-300 {{ request('category') == $category->slug ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:shadow-md' }}">
                                <i class="fas fa-utensils mr-2"></i>
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div> --}}
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-500 group overflow-hidden border border-gray-100">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden">
                                <a href="{{ route('product-detail', $product->slug) }}" class="block">
                                    <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}"
                                        class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @if($product->offer_price && $product->offer_price < $product->price)
                                        <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                            {{ round((($product->price - $product->offer_price) / $product->price) * 100) }}% OFF
                                        </div>
                                    @endif
                                    <!-- Quick View Overlay -->
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="bg-white text-primary px-4 py-2 rounded-full font-medium text-sm">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <!-- Category -->
                                <div class="mb-3">
                                    <span class="text-xs text-primary bg-primary/10 px-3 py-1 rounded-full font-medium">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $product->category->name }}
                                    </span>
                                </div>

                                <!-- Product Name -->
                                <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('product-detail', $product->slug) }}" class="block">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $product->short_description }}
                                </p>

                                <!-- Price and Weight -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2">
                                        @if($product->offer_price && $product->offer_price < $product->price)
                                            <span class="text-xl font-bold text-primary">
                                                Rp {{ number_format($product->offer_price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-sm text-gray-500 line-through">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-xl font-bold text-primary">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-4xl text-gray-400"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Maaf, tidak ada produk yang sesuai dengan pencarian Anda. Coba ubah filter atau kata kunci pencarian.
                    </p>
                    <a href="{{ route('products.index') }}"
                        class="bg-primary text-white px-8 py-4 rounded-xl hover:bg-primary/90 transition-all duration-300 font-medium shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-1 inline-flex items-center gap-2">
                        <i class="fas fa-refresh mr-2"></i>
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        fetch('{{ route('add-to-cart') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update cart count
                updateCartCount();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Produk berhasil ditambahkan ke keranjang',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang',
                    toast: true,
                    position: 'top-end'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menambahkan produk ke keranjang',
                toast: true,
                position: 'top-end'
            });
        });
    }

    function updateCartCount() {
        fetch('{{ route('cart-count') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').innerText = data;
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }
</script>
@endpush

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom scrollbar untuk filter kategori */
    .category-filter {
        scrollbar-width: thin;
        scrollbar-color: #e5e7eb #f3f4f6;
    }

    .category-filter::-webkit-scrollbar {
        height: 6px;
    }

    .category-filter::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 3px;
    }

    .category-filter::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 3px;
    }

    .category-filter::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
</style>
@endpush

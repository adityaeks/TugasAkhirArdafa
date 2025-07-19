{{-- filepath: c:\laragon\www\ourktichenv2\resources\views\pages\product-detail.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Our Kitchen - ' . $product->name)

@section('content')
<main class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Product Images -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-4">
                <img src="{{ asset($product->thumb_image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-80 md:h-96 object-cover product-image">
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:w-1/2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $product->name }}</h2>
                        <p class="text-gray-500">{{ $product->category->name }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="text-3xl font-bold text-blue-500">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    {{-- <span class="ml-2 text-gray-400 line-through">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span> --}}
                    {{-- <span class="ml-2 bg-blue-100 text-blue-600 text-sm font-semibold px-2 py-1 rounded">
                        @if($product->discount_percentage > 0)
                            {{ $product->discount_percentage }}% OFF
                        @endif
                    </span> --}}
                </div>

                <p class="text-gray-700 mb-6">
                    {{ $product->short_description }}
                </p>

                <div class="flex items-center mb-6">
                    <div class="flex items-center border border-gray-300 rounded-md mr-4">
                        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" onclick="updateQuantity(-1)">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->qty }}" class="w-16 text-center border-0 focus:ring-0">
                        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" onclick="updateQuantity(1)">+</button>
                    </div>
                    <form action="{{ route('add-to-cart') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="cart_quantity" value="1">
                        <button type="submit" class="w-full add-to-cart-btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md font-medium flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center text-gray-600 mt-2">
                        <i class="fas fa-weight mr-2 text-blue-500"></i>
                        <span>Berat: {{ $product->weight }} gram</span>
                    </div>
                    <div class="flex items-center text-gray-600 mt-2">
                        <i class="fas fa-box mr-2 text-blue-500"></i>
                        <span>Stok: {{ $product->qty }} unit</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="mt-12 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="openTab(event, 'description')" class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                    Description
                </button>
            </nav>
        </div>
        <div class="p-6">
            <div id="description" class="tab-content active">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">About This Dish</h3>
                <p class="text-gray-700 mb-4">
                    {!! $product->long_description !!}
                </p>
            </div>

            <div id="nutrition" class="tab-content">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Nutritional Information</h3>
                <div class="mb-6">
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-700">Calories</span>
                        <span class="font-medium">{{ $product->calories }} kcal</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $product->calories_percentage }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-800 mb-3">Macronutrients</h4>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">Protein</span>
                                    <span class="font-medium">{{ $product->protein }}g</span>
                                </div>
                                <div class="nutrition-bar bg-blue-400" style="width: {{ $product->protein_percentage }}%"></div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">Carbohydrates</span>
                                    <span class="font-medium">{{ $product->carbohydrates }}g</span>
                                </div>
                                <div class="nutrition-bar bg-green-400" style="width: {{ $product->carbohydrates_percentage }}%"></div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700">Fats</span>
                                    <span class="font-medium">{{ $product->fats }}g</span>
                                </div>
                                <div class="nutrition-bar bg-yellow-400" style="width: {{ $product->fats_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 mb-3">Micronutrients</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-blue-500 mb-1">
                                    <i class="fas fa-carrot"></i>
                                </div>
                                <div class="text-sm text-gray-700">Vitamin A {{ $product->vitamin_a_percentage }}%</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-blue-500 mb-1">
                                    <i class="fas fa-lemon"></i>
                                </div>
                                <div class="text-sm text-gray-700">Vitamin C {{ $product->vitamin_c_percentage }}%</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-blue-500 mb-1">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="text-sm text-gray-700">Fiber {{ $product->fiber }}g</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="text-blue-500 mb-1">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="text-sm text-gray-700">Iron {{ $product->iron_percentage }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
    <div class="mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-6">You Might Also Like</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <a href="{{ route('product-detail', $relatedProduct->slug) }}" class="block">
                    <div class="relative">
                        <img src="{{ asset($relatedProduct->thumb_image) }}"
                             alt="{{ $relatedProduct->name }}"
                             class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $relatedProduct->name }}</h4>
                        <p class="text-gray-600 text-sm mb-2">{{ $relatedProduct->short_description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-blue-500">Rp{{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                            <button class="text-blue-500 hover:text-blue-600">
                                <i class="fas fa-plus-circle text-xl"></i>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
    function updateQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        const cartQuantityInput = document.getElementById('cart_quantity');
        let quantity = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max);

        quantity = Math.max(1, Math.min(maxStock, quantity + change));
        quantityInput.value = quantity;
        cartQuantityInput.value = quantity;
    }

    // Update cart quantity when input changes
    document.getElementById('quantity').addEventListener('change', function() {
        const cartQuantityInput = document.getElementById('cart_quantity');
        cartQuantityInput.value = this.value;
    });

    // Fungsi untuk memperbarui jumlah keranjang di navbar
    function updateCartCount() {
        fetch('{{ route('cart-count') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').innerText = data;
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    // Fungsi untuk memperbarui mini-cart di navbar
    function updateMiniCart() {
        fetch('{{ route('cart-products') }}')
            .then(response => response.json())
            .then(products => {
                const miniCartWrapper = document.getElementById('mini_cart_wrapper');
                let html = '';
                if (products.length === 0) {
                    html = '<p class="text-center text-gray-500">Keranjang Kosong!</p>';
                } else {
                    products.forEach(product => {
                        html += `<div id="mini_cart_${product.rowId}" class="flex items-center space-x-4">
                            <img src="${product.options.image}" alt="${product.name}" class="w-16 h-16 object-cover rounded">
                            <div class="flex-1">
                                <a href="/product-detail/${product.options.slug}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                                    ${product.name}
                                </a>
                                <p class="text-sm text-gray-600">Rp ${product.price.toLocaleString('id-ID')}</p>
                                <p class="text-xs text-gray-500">Qty: ${product.qty}</p>
                            </div>
                            <button class="remove_sidebar_product text-red-500 hover:text-red-600" data-id="${product.rowId}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`;
                    });
                }
                miniCartWrapper.innerHTML = html;
                updateMiniCartSubtotal();
            })
            .catch(error => console.error('Error fetching mini cart products:', error));
    }

    // Fungsi untuk memperbarui subtotal mini-cart
    function updateMiniCartSubtotal() {
        fetch('{{ route('cart.sidebar-product-total') }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('mini_cart_subtotal').innerText = 'Rp' + data.toLocaleString('id-ID');
                if (data === 0) {
                    document.querySelector('.mini_cart_wrapper').nextElementSibling.style.display = 'none';
                } else {
                    document.querySelector('.mini_cart_wrapper').nextElementSibling.style.display = 'block';
                }
            })
            .catch(error => console.error('Error fetching mini cart subtotal:', error));
    }

    // Handle form submission with AJAX
    document.querySelector('.add-to-cart-btn').addEventListener('click', function(e) {
        e.preventDefault();

        const form = this.closest('form');
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    toastr.success(data.message);
                    updateCartCount(); // Perbarui jumlah keranjang
                    updateMiniCart(); // Perbarui mini-cart
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Terjadi kesalahan saat menambahkan produk ke keranjang.');
            });
    });

    // Initial load for cart count and mini-cart (if on page load)
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        updateMiniCart();
    });

    function openTab(evt, tabName) {
        const tabContents = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove("active");
        }
        const tabButtons = document.getElementsByClassName("tab-button");
        for (let i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove("border-blue-500", "text-blue-600");
            tabButtons[i].classList.add("border-transparent", "text-gray-500");
        }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("border-blue-500", "text-blue-600");
        evt.currentTarget.classList.remove("border-transparent", "text-gray-500");
    }
</script>
@endpush

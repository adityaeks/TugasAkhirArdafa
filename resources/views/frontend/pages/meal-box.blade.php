@extends('frontend.layouts.app')

@section('title', 'Our Kitchen - Meal Box')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-cover bg-center py-12" style="background-image: url('{{ asset('frontend/images/menu-meal.jpg') }}');">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-400/50 to-blue-600/50"></div>
        <div class="container mx-auto px-6 lg:px-16 text-center relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 text-white">Meal Box</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto mb-6 text-white">
                Makanan Sehat dan Lezat untuk Kebutuhan Harian Anda</p>
            <a href="#menuSection" class="inline-block bg-white text-blue-600 hover:bg-blue-50 font-bold py-3 px-8 rounded-full transition duration-300">
                Pesan Sekarang
            </a>
        </div>
    </div>

    <!-- About Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <!-- Deskripsi -->
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Layanan Meal Box Kami</h2>
                    <p class="text-gray-600 mb-4">
                        Kami menyediakan layanan meal box untuk kebutuhan makan harian Anda. Setiap menu kami dibuat dengan bahan-bahan berkualitas tinggi, dipersiapkan oleh chef profesional, dan dikemas dengan rapi untuk memastikan kualitas dan kebersihan makanan.
                    </p>
                    <p class="text-gray-600 mb-4">
                        Layanan kami mencakup pengiriman tepat waktu, kemasan yang aman dan higienis, serta berbagai pilihan menu yang dapat disesuaikan dengan kebutuhan nutrisi Anda.
                    </p>
                    <p class="text-gray-600">
                        Dengan pengalaman lebih dari 5 tahun dalam industri makanan, kami berkomitmen memberikan pengalaman kuliner terbaik untuk kebutuhan makan harian Anda.
                    </p>
                </div>
                <!-- Foto -->
                <div class="md:w-1/2">
                    <img src="{{ asset('frontend/images/menu-meal.jpg') }}"
                         alt="Meal Box"
                         class="rounded-lg shadow-xl w-full h-[400px] object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Divider -->
    <div class="container mx-auto px-6 lg:px-16">
        <div class="border-t border-gray-200 my-8"></div>
    </div>

    <!-- Menu Content -->
    <main id="menuSection" class="container mx-auto px-6 lg:px-16 py-8">
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800">Paket Meal Box</h2>
            <div class="w-24 h-1 bg-blue-500 mx-auto mt-4"></div>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="menuGrid">
            @if(isset($products) && !$products->isEmpty())
                @foreach($products as $product)
                <div class="menu-item bg-white rounded-xl shadow-md overflow-hidden animate-fadeIn" data-category="{{ $product->category->name }}"
                    data-popular="5" data-price="{{ $product->price }}" data-calories="350" data-new="true">
                    <a href="{{ route('product-detail', $product->slug) }}" class="block">
                        <div class="relative">
                            <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover">
                            <span class="absolute top-2 right-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">NEW</span>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-800">{{ $product->name }}</h3>
                                {{-- <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $product->category->name }}</span> --}}
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ $product->short_description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-orange-500">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-12">
                    <div class="bg-gray-50 rounded-lg p-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Produk Tidak Tersedia</h3>
                        <p class="mt-2 text-sm text-gray-500">Mohon maaf, saat ini belum ada produk yang tersedia dalam kategori ini.</p>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Promo Section -->
    {{-- <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Promo Spesial</h2>
                <div class="w-24 h-1 bg-blue-500 mx-auto mt-4"></div>
            </div>

            <!-- Promo Banner Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Promo Banner 1 -->
                <div class="relative overflow-hidden rounded-xl shadow-lg group">
                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80"
                         alt="Promo Weekly Package"
                         class="w-full h-[300px] object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full mb-2 inline-block">PROMO</span>
                        <h3 class="text-2xl font-bold mb-2">Paket Mingguan</h3>
                        <p class="text-sm mb-4">Dapatkan diskon 15% untuk pemesanan minimal 5 hari</p>
                        <a href="#" class="inline-block bg-white text-blue-600 hover:bg-blue-50 font-bold py-2 px-6 rounded-full transition duration-300">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

                <!-- Promo Banner 2 -->
                <div class="relative overflow-hidden rounded-xl shadow-lg group">
                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80"
                         alt="Promo Corporate Package"
                         class="w-full h-[300px] object-cover transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 text-white">
                        <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full mb-2 inline-block">SPESIAL</span>
                        <h3 class="text-2xl font-bold mb-2">Paket Corporate</h3>
                        <p class="text-sm mb-4">Gratis pengiriman untuk pemesanan minimal 10 box per hari</p>
                        <a href="#" class="inline-block bg-white text-blue-600 hover:bg-blue-50 font-bold py-2 px-6 rounded-full transition duration-300">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Promo Info -->
            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">* Promo berlaku sampai 31 Desember 2024</p>
                <p class="text-gray-600">* Syarat dan ketentuan berlaku</p>
            </div>
        </div>
    </section> --}}

    <!-- FAQ Section -->
    @include('frontend.layouts.faq')

    <script>
        // Filter menu by category
        function filterMenu(category) {
            const menuItems = document.querySelectorAll('.menu-item');
            const tabs = document.querySelectorAll('.category-tab');

            // Update active tab
            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.textContent.toLowerCase().includes(category) ||
                    (category === 'all' && tab.textContent === 'All Items')) {
                    tab.classList.add('active');
                }
            });

            // Show/hide menu items
            menuItems.forEach(item => {
                item.style.display = 'none';

                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                    item.classList.add('animate-fadeIn');
                }
            });
        }

        // Search menu items
        function searchMenu() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const description = item.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Sort menu items
        function sortMenu(criteria) {
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = Array.from(document.querySelectorAll('.menu-item'));

            menuItems.sort((a, b) => {
                switch (criteria) {
                    case 'price-low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price-high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'calories':
                        return parseFloat(a.dataset.calories) - parseFloat(b.dataset.calories);
                    case 'newest':
                        return b.dataset.new === 'true' ? -1 : 1;
                    case 'popular':
                    default:
                        return parseFloat(b.dataset.popular) - parseFloat(a.dataset.popular);
                }
            });

            // Re-append sorted items
            menuItems.forEach(item => {
                menuGrid.appendChild(item);
                item.classList.add('animate-fadeIn');
            });
        }

        // Initialize with all items showing
        window.onload = function() {
            filterMenu('all');
        };

        function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('svg');

            // Toggle content
            content.classList.toggle('hidden');

            // Rotate icon
            icon.classList.toggle('rotate-180');
        }
    </script>
@endsection

@push('scripts')
    <script>
        // Filter menu by category
        function filterMenu(category) {
            const menuItems = document.querySelectorAll('.menu-item');
            const tabs = document.querySelectorAll('.category-tab');

            // Update active tab
            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.textContent.toLowerCase().includes(category) ||
                    (category === 'all' && tab.textContent === 'All Items')) {
                    tab.classList.add('active');
                }
            });

            // Show/hide menu items
            menuItems.forEach(item => {
                item.style.display = 'none';

                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                    item.classList.add('animate-fadeIn');
                }
            });
        }

        // Search menu items
        function searchMenu() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const description = item.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Sort menu items
        function sortMenu(criteria) {
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = Array.from(document.querySelectorAll('.menu-item'));

            menuItems.sort((a, b) => {
                switch (criteria) {
                    case 'price-low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price-high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'calories':
                        return parseFloat(a.dataset.calories) - parseFloat(b.dataset.calories);
                    case 'newest':
                        return b.dataset.new === 'true' ? -1 : 1;
                    case 'popular':
                    default:
                        return parseFloat(b.dataset.popular) - parseFloat(a.dataset.popular);
                }
            });

            // Re-append sorted items
            menuItems.forEach(item => {
                menuGrid.appendChild(item);
                item.classList.add('animate-fadeIn');
            });
        }

        // Initialize with all items showing
        window.onload = function() {
            filterMenu('all');
        };
    </script>
@endpush

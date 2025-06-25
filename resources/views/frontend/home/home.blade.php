@extends('frontend.layouts.app')

@section('title', 'Home - OurKitchen')

@section('content')

    <!-- Hero Section -->
    <section id="home" class="hero-gradient py-16 md:py-24 relative">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/home-cover.jpg') }}'); opacity: 0.3;"></div>
        <div class="container mx-auto px-6 lg:px-16 flex flex-col md:flex-row items-center relative z-10">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Rasakan Makanan Olahan Khas Kami</h1>
                <p class="text-lg text-gray-600 mb-8">Jelajahi cita rasa autentik dari masakan khas kami yang dibuat dengan
                    bahan-bahan segar dan berkualitas tinggi.</p>
                <div class="mt-8 flex items-center space-x-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-gray-700">Free Shipping</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-gray-700">30-Day Returns</span>
                    </div>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="{{ asset('images/home-cover.jpg') }}" alt="Premium Kitchenware"
                    class="rounded-lg shadow-xl w-full max-w-md">
            </div>
        </div>
    </section>


    <!-- Featured Products -->
    <section id="products" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Layanan Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan berbagai layanan catering berkualitas dari Our Kitchen untuk memenuhi kebutuhan kuliner Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Layanan 1: Prasmanan Buffet -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden product-card transition duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" alt="Prasmanan Buffet" class="w-full h-64 object-cover">

                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">Prasmanan Buffet</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Layanan prasmanan untuk pernikahan, ulang tahun, rapat perusahaan, dan acara keluarga.</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-blue-600 font-bold">Mulai Rp45.000/pax</span>
                            </div>
                            <a href="{{ url('/prasmanan-buffet') }}" class="bg-blue-100 text-blue-600 p-2 rounded-full hover:bg-blue-200 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Layanan 2: Meal Box -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden product-card transition duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" alt="Meal Box" class="w-full h-64 object-cover">

                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">Meal Box</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Makanan sehat dan lezat untuk kebutuhan harian Anda dengan berbagai pilihan paket.</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-blue-600 font-bold">Mulai Rp35.000/box</span>
                            </div>
                            <a href="{{ url('/meal-box') }}" class="bg-blue-100 text-blue-600 p-2 rounded-full hover:bg-blue-200 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Layanan 3: Tumpeng & Nasi Liwet -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden product-card transition duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=800&q=80" alt="Tumpeng & Nasi Liwet" class="w-full h-64 object-cover">

                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">Tumpeng & Nasi Liwet</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Tumpeng dan nasi liwet tradisional dengan berbagai lauk pilihan untuk acara spesial Anda.</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-blue-600 font-bold">Mulai Rp250.000</span>
                            </div>
                            <a href="{{ url('/tumpeng-nasi-liwet') }}" class="bg-blue-100 text-blue-600 p-2 rounded-full hover:bg-blue-200 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Layanan 4: Daily Home Catering -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden product-card transition duration-300">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" alt="Daily Home Catering" class="w-full h-64 object-cover">

                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">Daily Home Catering</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Menu baru setiap hari untuk kebutuhan makan harian keluarga Anda.</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-blue-600 font-bold">Mulai Rp25.000/porsi</span>
                            </div>
                            <a href="{{ url('/daily-home-catering') }}" class="bg-blue-100 text-blue-600 p-2 rounded-full hover:bg-blue-200 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="about" class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Pelayanan Usaha Catering Our Kitchen
                </h2>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Our Kitchen berdiri sejak 2015 dengan visi menghadirkan kuliner berkualitas tinggi, cita rasa autentik,
                    dan layanan prima. Kami didukung tim koki profesional dan staf berpengalaman yang memastikan setiap
                    sajian tiba tepat waktu dan sesuai standar kebersihan.
                </p>
                </br>
                <p class="text-gray-600 max-w-2xl mx-auto mb-4">
                    Usaha Catering Our Kitchen memiliki pelayanan bisnis sebagai berikut:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- 1. Catering Wedding/Acara Pernikahan -->
                <div class="text-center p-6 rounded-lg hover:bg-gray-50 transition">
                    <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        Catering Wedding / Pernikahan
                    </h3>
                    <p class="text-gray-600">
                        Layanan prasmanan untuk pernikahan dan acara resmi, dengan menu
                        istimewa yang dapat disesuaikan dengan tema acara Anda.
                    </p>
                </div>

                <!-- 2. Catering Kantor / Instansi -->
                <div class="text-center p-6 rounded-lg hover:bg-gray-50 transition">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        Catering Kantor / Instansi
                    </h3>
                    <p class="text-gray-600">
                        Menu prasmanan atau nasi kotak untuk rapat, seminar, hingga
                        gathering kantor atau instansi lainnya.
                    </p>
                </div>

                <!-- 3. Catering Perorangan B2C -->
                <div class="text-center p-6 rounded-lg hover:bg-gray-50 transition">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        Catering Perorangan B2C (Menu Hari Ini)
                    </h3>
                    <p class="text-gray-600">
                        Pesan menu harian siap santap, dikemas rapi dan diantar langsung
                        ke rumah atau kantor Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Apa Kata Pelanggan Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Berikut ulasan dari beberapa pelanggan yang telah menikmati layanan catering Our Kitchen.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1: Pernikahan -->
                <div class="bg-white p-6 rounded-xl shadow-sm testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "Layanan prasmanan untuk pernikahan kami benar‑benar luar biasa. Menu disajikan cantik, rasanya
                        lezat, dan semua tamu merasa puas."
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Rina S."
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">Rina S.</h4>
                            <p class="text-gray-500 text-sm">Pengantin</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2: Kantor/Instansi -->
                <div class="bg-white p-6 rounded-xl shadow-sm testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "Catering nasi kotak untuk rapat kantor sangat memuaskan. Pengiriman tepat waktu, porsi pas, dan
                        kualitas makanannya terjaga."
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Andi P."
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">Andi P.</h4>
                            <p class="text-gray-500 text-sm">Manajer HR</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3: Perorangan B2C -->
                <div class="bg-white p-6 rounded-xl shadow-sm testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-6">
                        "Menu Hari Ini dari Our Kitchen praktis dan lezat. Pesanan tiba cepat, kemasan rapi, dan rasanya
                        konsisten enak."
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/52.jpg" alt="Dewi K."
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">Dewi K.</h4>
                            <p class="text-gray-500 text-sm">Pelanggan B2C</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

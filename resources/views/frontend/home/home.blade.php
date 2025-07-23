@extends('frontend.layouts.app')

@section('title', 'Home - OurKitchen')

@section('content')

    <!-- Hero Section -->
    <section id="home" class="hero-gradient py-16 md:py-24 relative">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('frontend/images/home.jpg') }}'); opacity: 0.2;"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 via-white/60 to-white/40"></div>
        <div class="container mx-auto px-6 lg:px-16 flex flex-col md:flex-row items-center relative z-10">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Rasakan Makanan Olahan Khas Kami</h1>
                <p class="text-lg text-gray-600 mb-8">Jelajahi cita rasa autentik dari masakan khas kami yang dibuat dengan
                    bahan-bahan segar dan berkualitas tinggi.</p>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="{{ asset('frontend/images/home.jpg') }}" alt="Premium Kitchenware"
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

            <!-- Carousel Section -->
            <div class="relative" x-data="carousel()">
                <div id="layanan-carousel" class="overflow-hidden">
                    <div class="flex transition-transform duration-500" :style="carouselTransform">
                        <template x-for="(card, idx) in cards.concat(cards.slice(0, 4))" :key="card.title + '-' + idx">
                            <div :class="cardClass">
                                <a :href="card.href" class="block bg-white rounded-xl shadow-md overflow-hidden product-card transition duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <div class="relative">
                                        <img :src="card.img" :alt="card.title" class="w-full h-64 object-cover">
                                    </div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-800" x-text="card.title"></h3>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4" x-text="card.desc"></p>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Carousel Controls -->
                <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <!-- Alpine.js Carousel Data & Logic -->
            <script>
                function carousel() {
                    return {
                        active: 0,
                        cards: [
                            {
                                href: "{{ route('prasmanan-buffet') }}",
                                img: "{{ asset('frontend/images/buffet.jpg') }}",
                                title: "Prasmanan Buffet",
                                desc: "Layanan prasmanan untuk pernikahan, ulang tahun, rapat perusahaan, dan acara keluarga."
                            },
                            {
                                href: "{{ route('meal-box') }}",
                                img: "{{ asset('frontend/images/3.png') }}",
                                title: "Meal Box",
                                desc: "Makanan sehat dan lezat untuk kebutuhan harian Anda dengan berbagai pilihan paket meal box."
                            },
                            {
                                href: "{{ route('snack-box') }}",
                                img: "{{ asset('frontend/images/4.png') }}",
                                title: "Snack Box",
                                desc: "Aneka snack box untuk berbagai acara, cocok untuk rapat, arisan, dan event lainnya."
                            },
                            {
                                href: "{{ route('tumpeng-nasi-liwet') }}",
                                img: "{{ asset('frontend/images/Nasi-Liwet.jpg') }}",
                                title: "Tumpeng & Nasi Liwet",
                                desc: "Tumpeng dan nasi liwet tradisional dengan berbagai lauk pilihan untuk acara spesial Anda."
                            },
                            {
                                href: "{{ route('daily-home-catering') }}",
                                img: "{{ asset('frontend/images/daily.jpg') }}",
                                title: "Daily Home Catering",
                                desc: "Menu baru setiap hari untuk kebutuhan makan harian keluarga Anda."
                            }
                        ],
                        get isMobile() {
                            return window.innerWidth < 640;
                        },
                        get visibleCards() {
                            if (window.innerWidth < 640) return 1;
                            if (window.innerWidth < 1024) return 2;
                            return 4;
                        },
                        get cardClass() {
                            if (window.innerWidth < 640) return 'w-full px-2 align-top flex-shrink-0';
                            if (window.innerWidth < 1024) return 'w-1/2 px-2 align-top flex-shrink-0';
                            return 'w-1/4 px-2 align-top flex-shrink-0';
                        },
                        get carouselTransform() {
                            let percent = this.active * (100 / this.visibleCards);
                            return `transform: translateX(-${percent}%);`;
                        },
                        next() {
                            if (this.active < this.cards.length) {
                                this.active++;
                            } else {
                                this.active = 1;
                            }
                        },
                        prev() {
                            if (this.active > 0) {
                                this.active--;
                            } else {
                                this.active = this.cards.length - 1;
                            }
                        },
                        handleResize() {
                            this.active = 0;
                        },
                        init() {
                            window.addEventListener('resize', () => this.handleResize());
                        }
                    }
                }
            </script>
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

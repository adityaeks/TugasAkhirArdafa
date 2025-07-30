<!-- Footer -->
<footer id="contact" class="bg-gray-900 text-white pt-16 pb-8">
    <div class="container mx-auto px-6 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <img src="{{ asset('frontend/images/logo12.jpg') }}" alt="OurKitchen Logo" class="w-8 h-8 mr-2 rounded">
                    <h3 class="text-xl font-bold text-gray-400">OurKitchen</h3>
                </div>
                <p class="text-gray-400 text-sm">Makanan segar dan sehat yang disiapkan dengan cinta dan bahan-bahan terbaik.
                </p>
                {{-- <div class="flex space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                </div> --}}
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-400 mb-4">Menu Utama</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white text-sm">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white text-sm">Menu</a></li>
                    <li><a href="#profile" class="text-gray-400 hover:text-white text-sm">Profile</a></li>
                    <li><a href="#testimonials" class="text-gray-400 hover:text-white text-sm">Testimoni</a></li>
                    <li><a href="#contact" class="text-gray-400 hover:text-white text-sm">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-400 mb-4">Layanan</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('products.index') }}?category=prasmanan-buffet" class="text-gray-400 hover:text-white text-sm">Prasmanan Buffet</a></li>
                    <li><a href="{{ route('products.index') }}?category=meal-box" class="text-gray-400 hover:text-white text-sm">Meal Box</a></li>
                    <li><a href="{{ route('products.index') }}?category=snack-box" class="text-gray-400 hover:text-white text-sm">Snack Box</a></li>
                    <li><a href="{{ route('products.index') }}?category=tumpeng-nasi-liwet" class="text-gray-400 hover:text-white text-sm">Tumpeng & Nasi Liwet</a></li>
                    <li><a href="{{ route('products.index') }}?category=daily-home-catering" class="text-gray-400 hover:text-white text-sm">Daily Home Catering</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-400 mb-4">Hubungi Kami</h3>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-600"></i>
                        <span class="text-sm">Perum Villa Jasmine 3, A2 No. 8-2, Suko, Sidoarjo</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-2 text-blue-600"></i>
                        <span class="text-sm">+62 878-8797-3004</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        <span class="text-sm">hello@ourkitchen.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-200 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">© 2025 Our Kitchen</p>
        </div>
    </div>
</footer>

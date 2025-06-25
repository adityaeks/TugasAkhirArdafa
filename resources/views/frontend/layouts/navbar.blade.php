<!-- Mobile Menu -->
<div class="mobile-menu hidden">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">OurKitchen</h2>
        <button id="close-mobile-menu" class="text-gray-600">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    <nav class="flex flex-col space-y-4">
        <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 text-lg">Home</a>
        <a href="{{ route('tumpeng-nasi-liwet') }}" class="text-gray-700 hover:text-blue-600">Tumpeng & Nasi Liwet</a>
        <a href="{{ route('daily-home-catering') }}" class="text-gray-700 hover:text-blue-600">Daily Home Catering</a>
        <a href="{{ route('prasmanan-buffet') }}" class="text-gray-700 hover:text-blue-600">Prasmanan Buffet</a>
        <a href="{{ route('meal-box') }}" class="text-gray-700 hover:text-blue-600">Meal Box</a>
    </nav>
    <div class="mt-8 space-y-4">
        @auth
            <div class="flex flex-col space-y-2">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashbaord') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-tachometer-alt mr-2"></i>User Dashboard
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-user mr-2"></i>Profil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-blue-600">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="block text-center bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                Login
            </a>
            <a href="{{ route('register') }}" class="block text-center border border-blue-600 text-blue-600 px-6 py-2 rounded-full hover:bg-blue-50 transition">
                Register
            </a>
        @endauth
    </div>
</div>

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="container mx-auto px-6 lg:px-16 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <button id="mobile-menu-button" class="mr-4 text-gray-600 md:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-utensils text-blue-600 mr-2"></i>
                OurKitchen
            </a>
        </div>

        <nav class="hidden md:flex space-x-8">
            <a href="{{ route('tumpeng-nasi-liwet') }}" class="text-gray-700 hover:text-blue-600">Tumpeng & Nasi Liwet</a>
            <a href="{{ route('daily-home-catering') }}" class="text-gray-700 hover:text-blue-600">Daily Home Catering</a>
            <a href="{{ route('prasmanan-buffet') }}" class="text-gray-700 hover:text-blue-600">Prasmanan Buffet</a>
            <a href="{{ route('meal-box') }}" class="text-gray-700 hover:text-blue-600">Meal Box</a>
        </nav>

        <div class="flex items-center space-x-4">
            @auth

            @endauth

            <div>
                <a href="{{ route('cart-details') }}" id="cart-button" class="text-gray-600 hover:text-blue-600 relative">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        0
                    </span>
                </a>
            </div>

            @auth
                <div class="relative group">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashbaord') }}" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                    @endif
                </div>
            @else
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper functions (could be moved to a global script or passed from app.blade.php if needed elsewhere)
        function updateCartCount() {
            fetch('{{ route('cart-count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').innerText = data;
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        // Panggil saat halaman dimuat untuk memastikan total keranjang dan mini-cart terbaru
        updateCartCount();
    });
</script>
@endpush

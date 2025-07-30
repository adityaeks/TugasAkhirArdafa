<!-- Mobile Menu -->
<div class="mobile-menu hidden">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <img src="{{ asset('frontend/images/logo12.jpg') }}" alt="OurKitchen Logo" class="w-8 h-8 mr-2 rounded">
            OurKitchen
        </h2>
        <button id="close-mobile-menu" class="text-gray-600">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    <nav class="flex flex-col space-y-4">
        <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Home</a>

        <!-- Mobile Menu Dropdown -->
        <div class="mobile-menu-dropdown">
            <button class="mobile-menu-dropdown-btn text-gray-700 hover:text-blue-600 text-sm font-medium flex items-center justify-between w-full">
                Menu
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="mobile-menu-dropdown-content hidden mt-2 ml-4 space-y-2">
                <a href="{{ route('prasmanan-buffet') }}" class="text-gray-600 hover:text-blue-600 text-sm block py-1">Prasmanan Buffet</a>
                <a href="{{ route('meal-box') }}" class="text-gray-600 hover:text-blue-600 text-sm block py-1">Meal Box</a>
                <a href="{{ route('snack-box') }}" class="text-gray-600 hover:text-blue-600 text-sm block py-1">Snack Box</a>
                <a href="{{ route('tumpeng-nasi-liwet') }}" class="text-gray-600 hover:text-blue-600 text-sm block py-1">Tumpeng & Nasi Liwet</a>
                <a href="{{ route('daily-home-catering') }}" class="text-gray-600 hover:text-blue-600 text-sm block py-1">Daily Home Catering</a>
            </div>
        </div>

        <a href="{{ request()->is('/') ? '#profile' : route('profile.edit') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Profile</a>
        <a href="{{ request()->is('/') ? '#testimonials' : route('testimoni') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Testimoni</a>
        <a href="{{ request()->is('/') ? '#contact' : route('contact') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Contact</a>
    </nav>
    <div class="mt-8 space-y-4">
        @auth
            <div class="flex flex-col space-y-2">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashbaord') }}" class="text-gray-700 hover:text-blue-600 text-sm">
                        <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600 text-sm">
                        <i class="fas fa-tachometer-alt mr-2"></i>User Dashboard
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-blue-600 text-sm">
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
                <img src="{{ asset('frontend/images/logo12.jpg') }}" alt="OurKitchen Logo" class="w-8 h-8 mr-2 rounded">
                OurKitchen
            </a>
        </div>

        <nav class="hidden md:flex space-x-8">
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors">Home</a>

            <!-- Menu Dropdown -->
            <div class="relative group">
                <button class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors flex items-center">
                    Menu
                    <i class="fas fa-chevron-down text-xs ml-1 group-hover:rotate-180 transition-transform"></i>
                </button>
                <div class="absolute top-full left-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top scale-95 group-hover:scale-100">
                    <div class="py-2">
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="{{ route('tumpeng-nasi-liwet') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-drumstick-bite mr-2"></i>
                            Tumpeng & Nasi Liwet
                        </a>
                        <a href="{{ route('daily-home-catering') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Daily Home Catering
                        </a>
                        <a href="{{ route('prasmanan-buffet') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-utensils mr-2"></i>
                            Prasmanan Buffet
                        </a>
                        <a href="{{ route('meal-box') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-box mr-2"></i>
                            Meal Box
                        </a>
                        <a href="{{ route('snack-box') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-cookie-bite mr-2"></i>
                            Snack Box
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ request()->is('/') ? '#profile' : route('profile.edit') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors">Profile</a>
            <a href="{{ request()->is('/') ? '#testimonials' : route('testimoni') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors">Testimoni</a>
            <a href="{{ request()->is('/') ? '#contact' : route('contact') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
            <div>
                <a href="{{ route('cart-details') }}" id="cart-button" class="text-gray-600 hover:text-blue-600 relative transition-colors">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        0
                    </span>
                </a>
            </div>

            @auth
                <div class="relative group">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashbaord') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-user text-xl"></i>
                        </a>
                    @endif
                </div>
            @else
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 text-sm font-medium transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition-colors text-sm font-medium">
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
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenu = document.querySelector('.mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        });

        // Mobile menu dropdown functionality
        const mobileMenuDropdownBtns = document.querySelectorAll('.mobile-menu-dropdown-btn');
        mobileMenuDropdownBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const dropdownContent = this.nextElementSibling;
                const icon = this.querySelector('i');

                dropdownContent.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });

        // Smooth scroll functionality for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Helper functions
        function updateCartCount() {
            fetch('{{ route('cart-count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').innerText = data;
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        // Panggil saat halaman dimuat untuk memastikan total keranjang terbaru
        updateCartCount();
    });
</script>
@endpush

@push('styles')
<style>
    .mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: white;
        z-index: 50;
        padding: 2rem;
        overflow-y: auto;
    }

    .mobile-menu-dropdown-content {
        transition: all 0.3s ease;
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

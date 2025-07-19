<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - Our Kitchen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="bg-gray-50">
    @include('frontend.layouts.navbar')
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Keranjang Belanja Anda</h1>

        @if(count($cartItems) > 0)
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Hapus</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-20 w-20">
                                    <img class="h-20 w-20 rounded-md object-cover" src="{{ asset($item->options->image) }}" alt="{{ $item->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <button class="quantity-button p-2 border rounded-md text-gray-600 hover:bg-gray-100" data-id="{{ $item->rowId }}" data-action="decrement">-</button>
                                <input type="text" class="quantity-input w-16 text-center mx-2 border rounded-md" value="{{ $item->qty }}" data-id="{{ $item->rowId }}">
                                <button class="quantity-button p-2 border rounded-md text-gray-600 hover:bg-gray-100" data-id="{{ $item->rowId }}" data-action="increment">+</button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 product-subtotal" data-id="{{ $item->rowId }}">
                            Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-red-600 hover:text-red-900 remove-from-cart" data-id="{{ $item->rowId }}">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Keranjang</h2>
                    <div class="flex justify-between text-gray-700 mb-2">
                        <span>Subtotal Produk:</span>
                        <span id="cart-total-display">Rp{{ number_format(getCartTotal(), 0, ',', '.') }}</span>
                    </div>
                    {{-- <div class="flex justify-between text-gray-700 mb-4">
                        <span>Biaya Pengiriman:</span>
                        <span id="shipping-fee-display">Rp0</span>
                    </div> --}}
                    <div class="flex justify-between text-xl font-bold text-gray-800 pt-4 border-t">
                        <span>Total:</span>
                        <span id="final-total-display">Rp{{ number_format(getCartTotal(), 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('user.checkout') }}" class="mt-6 w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:text-lg">Lanjutkan ke Checkout</a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center bg-white rounded-xl shadow-md p-10">
            <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
            <p class="text-xl text-gray-600 font-semibold mb-2">Keranjang Anda kosong.</p>
            <p class="text-gray-500 mb-6">Tambahkan beberapa item dari produk kami untuk memulai!</p>
        </div>
        @endif
    </main>
    @include('frontend.layouts.footer12')
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Konfigurasi Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Mengambil elemen-elemen yang diperlukan
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenu = document.querySelector('.mobile-menu');

        // Tambahkan pengecekan agar tidak error jika elemen tidak ada
        if (mobileMenuButton && closeMobileMenu && mobileMenu) {
            // Menambahkan event listener untuk tombol hamburger
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.add('active');
                }, 10);
            });

            // Menambahkan event listener untuk tombol close
            closeMobileMenu.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        }

        // Fungsi untuk memperbarui total keranjang di halaman keranjang
        function updateCartPageTotals() {
            fetch('{{ route('cart.sidebar-product-total') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-total-display').innerText = 'Rp' + data.toLocaleString('id-ID');
                    document.getElementById('final-total-display').innerText = 'Rp' + data.toLocaleString('id-ID');
                })
                .catch(error => console.error('Error fetching cart totals:', error));
        }

        // Fungsi untuk memperbarui subtotal item individu
        function updateItemSubtotal(rowId, newQuantity) {
            const productPrice = parseFloat(document.querySelector(`.quantity-input[data-id="${rowId}"]`).closest('tr').querySelector('td:nth-child(2)').innerText.replace(/[^\d,]/g, '').replace(',', '.'));
            const newSubtotal = productPrice * newQuantity;
            document.querySelector(`.product-subtotal[data-id="${rowId}"]`).innerText = 'Rp' + newSubtotal.toLocaleString('id-ID');
        }

        // Event listener untuk tombol kuantitas
        document.querySelectorAll('.quantity-button').forEach(button => {
            button.addEventListener('click', function() {
                const rowId = this.dataset.id;
                const action = this.dataset.action;
                const input = document.querySelector(`.quantity-input[data-id="${rowId}"]`);
                let quantity = parseInt(input.value);
                const oldQuantity = quantity;

                if (action === 'increment') {
                    quantity++;
                } else if (action === 'decrement' && quantity > 1) {
                    quantity--;
                }

                // Kirim permintaan AJAX untuk memperbarui kuantitas
                fetch('{{ route('cart.update-quantity') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: rowId, quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        input.value = quantity;
                        updateItemSubtotal(rowId, quantity);
                        updateCartPageTotals();
                        if (typeof updateCartCount === 'function') updateCartCount();
                        if (typeof updateMiniCart === 'function') updateMiniCart();
                        toastr.success(data.message);
                    } else {
                        input.value = oldQuantity; // Kembalikan ke qty lama jika gagal
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    input.value = oldQuantity; // Kembalikan ke qty lama jika error
                    console.error('Error updating quantity:', error);
                });
            });
        });

        // Event listener untuk tombol hapus
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const rowId = this.dataset.id;

                fetch(`{{ route('cart.remove-product', '') }}/${rowId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.closest('tr').remove();
                        updateCartPageTotals();
                        if (typeof updateCartCount === 'function') updateCartCount();
                        if (typeof updateMiniCart === 'function') updateMiniCart();
                        toastr.success(data.message);
                        if (document.querySelectorAll('.quantity-input').length === 0) {
                            document.querySelector('main').innerHTML = `
                                <div class="text-center bg-white rounded-xl shadow-md p-10">
                                    <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                                    <p class="text-xl text-gray-600 font-semibold mb-2">Keranjang Anda kosong.</p>
                                    <p class="text-gray-500 mb-6">Tambahkan beberapa item dari produk kami untuk memulai!</p>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Lihat Produk</a>
                                </div>
                            `;
                        }
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => console.error('Error removing item:', error));
            });
        });

        // Panggil saat halaman dimuat untuk memastikan total terbaru
        document.addEventListener('DOMContentLoaded', function() {
            updateCartPageTotals();
        });
    </script>
</body>

</html>

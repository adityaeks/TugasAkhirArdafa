<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OurKitchen || Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery harus sebelum jQuery UI -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="bg-gray-50">
    @include('frontend.layouts.navbar')
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Kolom kiri: Form/alamat -->
            <div class="w-full lg:w-3/4 order-2 lg:order-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div id="shipping-details-section">
                        <div class="flex justify-between items-center mb-6">
                            <h5 class="text-xl font-semibold text-gray-800">Detail Pengiriman</h5>
                            <a href="javascript:;" class="common_btn text-blue-600 hover:text-blue-800" id="add-address-button">Tambah alamat baru</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($addresses as $address)
                                <div class="bg-gray-50 rounded-lg p-4 shadow-sm border border-gray-200">
                                    <div class="flex items-center mb-2">
                                        <input class="form-radio h-4 w-4 text-blue-600 shipping_address delivery_address"
                                            value="{{ $address->id }}" data-id="{{ $address->id }}" data-regency-id="{{ $address->regency_id }}" type="radio"
                                            name="flexRadioDefault" id="flexRadioDefault{{ $address->id }}">
                                        <label class="ml-2 text-gray-700 font-medium" for="flexRadioDefault{{ $address->id }}">
                                            Pilih Alamat
                                        </label>
                                    </div>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li><span>Nama :</span> {{ $address->name }}</li>
                                        <li><span>Phone :</span> {{ $address->phone }}</li>
                                        <li><span>Email :</span> {{ $address->email }}</li>
                                        <li><span>Provinsi  :</span> {{ $address->province->name }}</li>
                                        <li><span>Kota  :</span> {{ $address->regency->name }}</li>
                                        <li><span>Kecamatan  :</span> {{ $address->district->name }}</li>
                                        <li><span>Desa/Kelurahan  :</span> {{ $address->village->name }}</li>
                                        <li><span>Kode Pos :</span> {{ $address->zip }}</li>
                                        <li><span>Alamat :</span> {{ $address->address }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kolom kanan: Card Delivery Service -->
            <div class="w-full lg:w-1/4 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20" id="sticky_sidebar">
                    <h5 class="mb-4 text-xl font-semibold text-gray-800"><i class='fa fa-truck mr-2'></i> Pengiriman</h5>
                    <div class="mb-4 flex flex-col gap-y-2">
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600" type="radio" name="jenis_pengiriman" id="pengirimanSidoarjoSurabaya" value="sidoarjo_surabaya" disabled>
                            <label class="ml-2 text-gray-800 font-semibold" for="pengirimanSidoarjoSurabaya">Sidoarjo & Surabaya</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600" type="radio" name="jenis_pengiriman" id="pengirimanLuarSidoarjoSurabaya" value="luar_sidoarjo_surabaya" disabled>
                            <label class="ml-2 text-gray-800 font-semibold" for="pengirimanLuarSidoarjoSurabaya">Luar Sidoarjo & Surabaya</label>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="mb-4">
                            <h6 class="font-semibold text-gray-700 mb-2">Detail Produk</h6>
                            <ul class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <li class="py-2 flex justify-between items-center">
                                        <div>
                                            <span class="font-medium">{{ $item->name }}</span>
                                            <span class="text-xs text-gray-500">x{{ $item->qty }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-gray-700">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr class="my-4 border-gray-200">
                        <div class="flex justify-between text-gray-700 mb-2">
                            <span>Subtotal:</span>
                            <span>Rp{{ number_format(getCartTotal(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700 mb-2">
                            <span>Biaya Pengiriman(+):</span>
                            <span id="cost">Rp0</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-800 pt-4 border-t">
                            <span>Total:</span>
                            <span><b id="total_amount" data-id="0">Rp0</b></span>
                        </div>
                    </div>
                    <form action="{{ route('user.checkout.submit') }}" id="checkOutForm" class="mt-6">
                        <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                        <input type="hidden" name="shipping_address_id" value="" id="shipping_address_id">
                        <input type="hidden" name="delivery_service" value="" id="delivery_service">
                        <input type="hidden" name="delivery_package" value="" id="delivery_package">
                        <input type="hidden" name="total_qty" id="total_qty">
                        <input type="hidden" name="total_price" id="total_price">
                        <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
                    </form>
                    <button id="submitCheckoutForm" class="mt-6 w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition">Place Order</button>
                </div>
            </div>
        </div>
    </section>

    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="exampleModal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h5 class="text-xl font-semibold text-gray-800" id="exampleModalLabel">Add New Address</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600" id="close-address-modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 text-gray-600">
                <form action="{{ route('user.checkout.address.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" placeholder="Nama *" name="name"
                            value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" placeholder="No. Hp *" name="phone"
                                value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <input type="email" placeholder="Email *" name="email"
                                value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <select id="province" name="province_id" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="regency" name="regency_id" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <select id="district" name="district_id" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div>
                            <select id="village" name="village_id" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <input type="text" placeholder="Kode Pos *" name="zip"
                            value="{{ old('zip') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <input type="text" placeholder="Alamat lengkap *" name="address"
                            value="{{ old('address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mt-4">
                        <button class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition" type="submit">Save Address</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
    @include('frontend.layouts.footer12')
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

        // Logika untuk modal Tambah Alamat Baru
        const addAddressButton = document.getElementById('add-address-button');
        const exampleModal = document.getElementById('exampleModal');
        const closeAddressModal = document.getElementById('close-address-modal');

        addAddressButton.addEventListener('click', () => {
            exampleModal.classList.remove('hidden');
        });

        closeAddressModal.addEventListener('click', () => {
            exampleModal.classList.add('hidden');
        });

        // Menutup modal jika mengklik di luar area modal
        exampleModal.addEventListener('click', (e) => {
            if (e.target === exampleModal) {
                exampleModal.classList.add('hidden');
            }
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var products = [{
                    "id": 1,
                    "weight": 1.5,
                    "price": 10000,
                    "qty": 2
                },
                {
                    "id": 2,
                    "weight": 2.0,
                    "price": 15000,
                    "qty": 1
                }
                // Tambahkan data produk lainnya di sini
            ];

            function calculateTotalWeight() {
                let totalWeight = 0;
                products.forEach(function(product) {
                    totalWeight += product.weight * product.qty;
                });
                return totalWeight;
            }

            function calculateTotalQty() {
                let totalQty = 0;
                products.forEach(function(product) {
                    totalQty += product.qty;
                });
                return totalQty;
            }

            function calculateTotalPrice() {
                let totalPrice = 0;
                products.forEach(function(product) {
                    totalPrice += product.price * product.qty;
                });
                return totalPrice;
            }

            function updateFormValues() {
                let totalQty = calculateTotalQty();
                let totalPrice = calculateTotalPrice();
                $('#total_qty').val(totalQty);
                $('#total_price').val(totalPrice);
            }

            $('input[type="radio"]').prop('checked', false);
            $('#shipping_method_id').val("");
            $('#shipping_address_id').val("");
            $('#delivery_package').val("");

            $('.shipping_method').on('click', function() {
                let shippingFee = $(this).data('id');
                let currentTotalAmount = parseInt($('#total_amount').data('id'));
                let totalAmount = currentTotalAmount + shippingFee;

                $('#shipping_method_id').val($(this).val());
                $('#shipping_fee').text("Rp" + shippingFee.toLocaleString());
                $('#total_amount').text("Rp" + totalAmount.toLocaleString());
            });

            $('.shipping_address').on('click', function() {
                // Pastikan ID alamat selalu diisi ke input hidden
                $('#shipping_address_id').val($(this).data('id'));
                var regencyId = $(this).data('regency-id');
                var sidoarjoRegencyId = 3515;
                var surabayaRegencyId = 3578;
                var ongkirTetap = 10000;
                var shippingFee = ongkirTetap;
                var isAreaGratis = (regencyId == sidoarjoRegencyId || regencyId == surabayaRegencyId);

                var cartItems = window.cartItems || [];
                var slugTumpeng = 'tumpeng-nasi-liwet';
                var slugPrasmanan = 'prasmanan-buffet';
                var slugDaily = 'daily-home-catering';
                var slugMeal = 'meal-box';
                var slugSnack = 'snack-box';

                // Jika ada produk tumpeng atau prasmanan buffet, gratis ongkir
                var adaTumpengAtauPrasmanan = cartItems.some(function(item) {
                    return [slugTumpeng, slugPrasmanan].includes(item.options.category_slug);
                });

                // Jika semua produk daily home/meal box/snack box dan qty >= 3 serta sidoarjo, gratis ongkir
                var semuaDailyMealSnack = cartItems.length > 0 && cartItems.every(function(item) {
                    return [slugDaily, slugMeal, slugSnack].includes(item.options.category_slug);
                });
                var totalQtyDailyMealSnack = 0;
                cartItems.forEach(function(item) {
                    if ([slugDaily, slugMeal, slugSnack].includes(item.options.category_slug)) {
                        totalQtyDailyMealSnack += parseInt(item.qty);
                    }
                });

                if (adaTumpengAtauPrasmanan) {
                    shippingFee = 0;
                } else if (isAreaGratis && semuaDailyMealSnack && totalQtyDailyMealSnack >= 3) {
                    shippingFee = 0;
                } else if (isAreaGratis && semuaDailyMealSnack && totalQtyDailyMealSnack < 3) {
                    shippingFee = ongkirTetap;
                } else if (!isAreaGratis && adaTumpengAtauPrasmanan) {
                    shippingFee = 0;
                } else {
                    shippingFee = ongkirTetap;
                }

                if(isAreaGratis) {
                    $('#pengirimanSidoarjoSurabaya').prop('checked', true);
                    $('#pengirimanLuarSidoarjoSurabaya').prop('checked', false);
                } else {
                    $('#pengirimanSidoarjoSurabaya').prop('checked', false);
                    $('#pengirimanLuarSidoarjoSurabaya').prop('checked', true);
                }
                // Update biaya pengiriman dan total
                $('#cost').text('Rp' + shippingFee.toLocaleString());
                $('#shipping_fee').val(shippingFee); // update hidden input
                var subtotal = parseInt({{ getCartTotal() }});
                var total = subtotal + shippingFee;
                $('#total_amount').text('Rp' + total.toLocaleString());
                $('#total_amount').data('id', total);
            });

            $('.delivery-package').on('click', function() {
                $('#delivery_package').val($(this).val());
            });

            // Sembunyikan detail pengiriman jika pilih pickup
            $('input[name="shipping_type"]').change(function() {
                if ($(this).val() === 'pickup') {
                    $('#shipping-details-section').hide();
                    $('.courier-code').closest('.mt-3').hide();
                    $('.available-services').hide();
                    $('#delivery_package').val('');
                    $('#cost').text('Rp0');
                    $('#total_amount').text('Rp' + $('#total_price').val());
                } else {
                    $('#shipping-details-section').show();
                    $('.courier-code').closest('.mt-3').show();
                }
            });
            // Trigger default
            $('input[name="shipping_type"]:checked').trigger('change');

            $('#submitCheckoutForm').off('click').on('click', function(e) {
                e.preventDefault(); // Prevent default action
                console.log('Submit button clicked'); // Log for debugging

                var shippingType = $('input[name="shipping_type"]:checked').val();

                if (shippingType === 'courier' && $('#shipping_address_id').val() == "") {
                    toastr.error('Shipping address is required');
                } else if ($('#delivery_package').val() == "" && shippingType === 'courier') {
                    toastr.error('Delivery service is required');
                } else {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    var totalQty = $('#total_qty').val();
                    var totalPrice = $('#total_price').val();
                    var shippingFee = $('#shipping_fee').val(); // Get shipping fee from hidden input

                    console.log('Sending AJAX request'); // Log for debugging

                    // Disable the button to prevent multiple submits
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin fa-1x"></i>');

                    $.ajax({
                        url: "{{ route('user.checkout.form-submit') }}",
                        method: 'POST',
                        data: {
                            _token: token,
                            shipping_address_id: $('#shipping_address_id').val(),
                            delivery_package: $('#delivery_package').val(),
                            total_qty: totalQty,
                            total_price: totalPrice,
                            shipping_type: shippingType,
                            shipping_fee: shippingFee // Include shipping fee in AJAX data
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.redirect_url) {
                                console.log('AJAX request successful');
                                window.location.href = data
                                    .redirect_url; // Redirect to Snap Midtrans
                            } else {
                                toastr.error('Failed to get redirect URL from server');
                            }
                        },
                        error: function(data) {
                            console.log('AJAX request failed:', data); // Log for debugging
                            toastr.error('Failed to process the checkout. Please try again.');
                        },
                        complete: function() {
                            // Re-enable the button after request completes
                            $('#submitCheckoutForm').prop('disabled', false).html(
                                'Place Order');
                        }
                    });
                }
            });

            $('.delivery_address').change(function() {
                $('.courier-code').removeAttr('checked');
                $('.available-services').hide();
            });

            $('.courier-code').click(function() {
                let courier = $(this).val();
                $('#delivery_service').val($(this).val());
                let addressID = $('.delivery_address:checked').val();
                let totalWeight = calculateTotalWeight();

                console.log('Fetching shipping fee'); // Log for debugging

                $.ajax({
                    url: "checkout/shipping-fee",
                    method: "POST",
                    data: {
                        address_id: addressID,
                        courier: courier,
                        total_weight: totalWeight,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        $('.available-services').show();
                        $('.available-services').html(result);
                    },
                    error: function(e) {
                        console.log("Error fetching shipping fee:", e);
                    }
                });
            });


            function displayTotalWeight() {
                let totalWeight = calculateTotalWeight();
                $('#cart-total-weight').text(totalWeight + ' grams');
                console.log('Total Weight:', totalWeight);
            }

            $(document).ready(function() {
                // Autocomplete untuk kota
                $('#city').autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: '/user/checkout/search-city',
                            dataType: 'json',
                            data: { search: request.term },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.city_name,
                                        value: item.city_name,
                                        city_id: item.city_id
                                    };
                                }));
                            }
                        });
                    },
                    minLength: 3,
                    select: function(event, ui) {
                        $('#city_id').val(ui.item.city_id);
                    }
                });
                // Hapus autocomplete untuk #district
            });

            displayTotalWeight();
            updateFormValues();
        });

        $(document).ready(function() {
            // Cascade wilayah Indonesia
            $('#province').change(function() {
                var id = $(this).val();
                $('#regency').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                if(id) {
                    $.get('/get-regencies/' + id, function(data) {
                        $.each(data, function(i, item) {
                            $('#regency').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });
            $('#regency').change(function() {
                var id = $(this).val();
                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                if(id) {
                    $.get('/get-districts/' + id, function(data) {
                        $.each(data, function(i, item) {
                            $('#district').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });
            $('#district').change(function() {
                var id = $(this).val();
                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                if(id) {
                    $.get('/get-villages/' + id, function(data) {
                        $.each(data, function(i, item) {
                            $('#village').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });
        });
    </script>
    <script>
window.cartItems = [
@foreach($cartItems as $item)
    {
        id: {{ $item->id }},
        name: @json($item->name),
        qty: {{ $item->qty }},
        price: {{ $item->price }},
        options: {
            category_slug: @json($item->options['category_slug'] ?? null)
        }
    },
@endforeach
];
</script>
</body>

</html>

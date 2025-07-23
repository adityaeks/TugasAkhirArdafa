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
            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20" id="sticky_sidebar">
                    <h5 class="mb-4 text-xl font-semibold text-gray-800"><i class='fa fa-truck mr-2'></i> Delivery Service</h5>
                    <div class="mb-4 flex flex-col gap-y-2">
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600 shipping_type" type="radio" name="shipping_type" id="shippingTypeCourier" value="courier" checked>
                            <label class="ml-2 text-gray-800 font-semibold" for="shippingTypeCourier">Pakai Kurir</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600 shipping_type" type="radio" name="shipping_type" id="shippingTypePickup" value="pickup">
                            <label class="ml-2 text-gray-800 font-semibold" for="shippingTypePickup">Ambil Sendiri</label>
                        </div>
                    </div>
                    <div class="mt-3 flex space-x-4">
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600 courier-code" type="radio" name="courier" id="inlineRadio1"
                                value="jne">
                            <label class="ml-2 text-gray-700" for="inlineRadio1">JNE</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600 courier-code" type="radio" name="courier" id="inlineRadio2"
                                value="pos">
                            <label class="ml-2 text-gray-700" for="inlineRadio2">POS</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-blue-600 courier-code" type="radio" name="courier" id="inlineRadio3"
                                value="tiki">
                            <label class="ml-2 text-gray-700" for="inlineRadio3">TIKI</label>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
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
                            <span><b id="total_amount"
                                        data-id="0">Rp0</b></span>
                        </div>
                    </div>
                    <form action="{{ route('user.checkout.submit') }}" id="checkOutForm" class="mt-6">
                        <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                        <input type="hidden" name="shipping_address_id" value="" id="shipping_address_id">
                        <input type="hidden" name="delivery_service" value="" id="delivery_service">
                        <input type="hidden" name="delivery_package" value="" id="delivery_package">
                        <input type="hidden" name="total_qty" id="total_qty">
                        <input type="hidden" name="total_price" id="total_price">
                    </form>
                    <button id="submitCheckoutForm" class="mt-6 w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition">Place Order</button>
                </div>
            </div>
            <div class="w-full lg:w-3/4">
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
                                            value="{{ $address->id }}" data-id="{{ $address->id }}" type="radio"
                                            name="flexRadioDefault" id="flexRadioDefault{{ $address->id }}">
                                        <label class="ml-2 text-gray-700 font-medium" for="flexRadioDefault{{ $address->id }}">
                                            Pilih Alamat
                                        </label>
                                    </div>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li><span>Nama :</span> {{ $address->name }}</li>
                                        <li><span>Phone :</span> {{ $address->phone }}</li>
                                        <li><span>Email :</span> {{ $address->email }}</li>
                                        <li><span>Kode Pos :</span> {{ $address->zip }}</li>
                                        <li><span>Alamat :</span> {{ $address->address }}</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-3">Available services:</h5>
                            <ul class="list-none available-services hidden"></ul>
                        </div>
                    </div>
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
                            <select id="province" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="province">
                                <option value="">Select Province</option>
                                @if(isset($provinces['data']) && is_array($provinces['data']))
                                    @foreach ($provinces['data'] as $province)
                                        <option value="{{ $province['id'] }}">{{ $province['province_name'] }}</option>
                                    @endforeach
                                @else
                                    <option value="">Data provinsi tidak tersedia</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <input type="text" id="city" name="city_name" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type city name..." autocomplete="off">
                            <input type="hidden" name="city_id" id="city_id">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <input type="text" id="district" name="district_name" class="select_2 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kecamatan *" required>
                            <input type="hidden" name="district_id" id="district_id" value="">
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
                $('#shipping_address_id').val($(this).data('id'));
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
                            shipping_type: shippingType
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
    </script>
</body>

</html>

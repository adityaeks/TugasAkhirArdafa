@extends('frontend.layouts.master')

@section('title')
    UMKM Lowayu || Checkout
@endsection

@section('content')
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="wsus__check_form">
                        <div class="d-flex">
                            <h5>Detail Pengiriman </h5>
                            <a href="javascript:;" style="margin-left:auto;" class="common_btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Tambah alamat baru</a>
                        </div>

                        <div class="row">
                            @foreach ($addresses as $address)
                                <div class="col-xl-6">
                                    <div class="wsus__checkout_single_address">
                                        <div class="form-check">
                                            <input class="form-check-input shipping_address delivery_address"
                                                value="{{ $address->id }}" data-id="{{ $address->id }}" type="radio"
                                                name="flexRadioDefault" id="flexRadioDefault{{ $address->id }}">
                                            <label class="form-check-label" for="flexRadioDefault{{ $address->id }}">
                                                Pilih Alamat
                                            </label>
                                        </div>
                                        <ul>
                                            <li><span>Name :</span> {{ $address->name }}</li>
                                            <li><span>Phone :</span> {{ $address->phone }}</li>
                                            <li><span>Email :</span> {{ $address->email }}</li>
                                            <li><span>Country :</span> {{ $address->country }}</li>
                                            <li><span>City :</span> {{ $address->city }}</li>
                                            <li><span>Zip Code :</span> {{ $address->zip }}</li>
                                            <li><span>Address :</span> {{ $address->address }}</li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <h5>Available services:</h5>
                            <ul class="list-group list-group-flush available-services" style="display: none;"></ul>
                        </div>

                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="wsus__order_details" id="sticky_sidebar">
                        <h5 class="mb-0"><i class='fa fa-truck'></i> Delivery Service</h5>
                        <div class="mt-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input courier-code" type="radio" name="courier" id="inlineRadio1"
                                    value="jne">
                                <label class="form-check-label" for="inlineRadio1">JNE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input courier-code" type="radio" name="courier" id="inlineRadio2"
                                    value="pos">
                                <label class="form-check-label" for="inlineRadio2">POS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input courier-code" type="radio" name="courier" id="inlineRadio3"
                                    value="tiki">
                                <label class="form-check-label" for="inlineRadio3">TIKI</label>
                            </div>
                        </div>

                        <div class="wsus__order_details_summery">
                            <p>subtotal: <span>Rp{{ number_format(getCartTotal(), 0, ',', '.') }}</span></p>
                            <p>biaya pengiriman(+): <span id="shipping_fee">Rp{{ number_format(0, 0, ',', '.') }}</span>
                            </p>
                            <p>kupon(-): <span>Rp{{ number_format(getCartDiscount(), 0, ',', '.') }}</span></p>
                            <p><b>total:</b> <span><b id="total_amount"
                                        data-id="{{ getMainCartTotal() }}">Rp{{ number_format(getMainCartTotal(), 0, ',', '.') }}</b></span>
                            </p>
                        </div>

                        <div class="terms_area">
                            <div class="form-check">
                                <input class="form-check-input agree_term" type="checkbox" value=""
                                    id="flexCheckChecked3" checked>
                                <label class="form-check-label" for="flexCheckChecked3">
                                    I have read and agree to the website <a
                                        href="{{ route('terms-and-conditions') }}">terms and conditions *</a>
                                </label>
                            </div>
                        </div>
                        <form action="{{ route('user.checkout.submit') }}" id="checkOutForm">
                            <input type="hidden" name="shipping_method_id" value="" id="shipping_method_id">
                            <input type="hidden" name="shipping_address_id" value="" id="shipping_address_id">
                            <input type="hidden" name="delivery_service" value="" id="delivery_service">
                            <input type="hidden" name="delivery_package" value="" id="delivery_package">
                            <input type="hidden" name="total_qty" id="total_qty">
                            <input type="hidden" name="total_price" id="total_price">
                        </form>
                        <a href="javascript:;" id="submitCheckoutForm" class="common_btn">Place Order</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wsus__popup_address">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">add new address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wsus__check_form p-3">
                            <form action="{{ route('user.checkout.address.create') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Name *" name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Phone *" name="phone"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="email" placeholder="Email *" name="email"
                                                value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="country">
                                                <option value="">Country / Region *</option>
                                                @foreach (config('settings.country_list') as $key => $county)
                                                    <option {{ $county === old('country') ? 'selected' : '' }}
                                                        value="{{ $county }}">{{ $county }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="State *" name="state"
                                                value="{{ old('state') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="City *" name="city"
                                                value="{{ old('city') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Post code *" name="zip"
                                                value="{{ old('zip') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Address *" name="address"
                                                value="{{ old('address') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <button class="common_btn" type="submit">save address</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // JSON data for products
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
                },
                // Tambahkan data produk lainnya di sini
            ];

            // Menghitung total berat produk dari JSON
            function calculateTotalWeight() {
                let totalWeight = 0;
                products.forEach(function(product) {
                    totalWeight += product.weight * product.qty;
                });
                return totalWeight;
            }

            // Menghitung total qty dari JSON
            function calculateTotalQty() {
                let totalQty = 0;
                products.forEach(function(product) {
                    totalQty += product.qty;
                });
                return totalQty;
            }

            // Menghitung total harga dari JSON
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

            $('.courier-code').on('click', function() {
                $('#delivery_package').val($(this).val());
            });

            // submit checkout form
            $('#submitCheckoutForm').on('click', function(e) {
                e.preventDefault();
                if ($('#shipping_address_id').val() == "") {
                    toastr.error('Shipping address is required');
                } else if ($('#delivery_package').val() == "") {
                    toastr.error('Delivery service is required');
                } else if (!$('.agree_term').prop('checked')) {
                    toastr.error('You have to agree to the website terms and conditions');
                } else {
                    // Mendapatkan token
                    var token = '{{ csrf_token() }}'; // Ini mendapatkan token dari Laravel

                    // Mengambil nilai total_qty dan total_price dari form
                    var totalQty = $('#total_qty').val();
                    var totalPrice = $('#total_price').val();

                    // Mengirim data dengan tambahan token, total_qty, dan total_price
                    $.ajax({
                        url: "{{ route('user.checkout.form-submit') }}",
                        method: 'POST',
                        data: {
                            _token: token,
                            shipping_address_id: $('#shipping_address_id').val(),
                            delivery_service: $('#delivery_service').val(),
                            delivery_package: $('#delivery_package').val(),
                            total_qty: totalQty,
                            total_price: totalPrice
                        },
                        beforeSend: function() {
                            $('#submitCheckoutForm').html(
                                '<i class="fas fa-spinner fa-spin fa-1x"></i>');
                        },
                        success: function(data) {
                            if (data.status === 'success') {
                                $('#submitCheckoutForm').text('Place Order');
                                // redirect user to next page
                                window.location.href = data.redirect_url;
                            }
                        },
                        error: function(data) {
                            console.log(data);
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
                let totalWeight = calculateTotalWeight(); // Mengambil total berat produk dari JSON

                $.ajax({
                    url: "checkout/shipping-fee",
                    method: "POST",
                    data: {
                        address_id: addressID,
                        courier: courier,
                        total_weight: totalWeight, // Kirim total berat produk dari JSON
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        $('.available-services').show();
                        $('.available-services').html(result);

                        // Update shipping fee display
                        if (result.hasOwnProperty('shipping_fee')) {
                            let shippingFee = parseInt(result.shipping_fee);
                            $('#shipping_fee').text("Rp" + shippingFee.toLocaleString());

                            // Update grand total display
                            let currentTotalAmount = parseInt($('#total_amount').data('id'));
                            let totalAmount = currentTotalAmount + shippingFee;
                            $('#total_amount').text("Rp" + totalAmount.toLocaleString());
                        } else {
                            console.error("Shipping fee not found in response:", result);
                        }
                    },
                    error: function(e) {
                        console.log("Error fetching shipping fee:", e);
                    }
                });
            });

            // Memanggil fungsi untuk menghitung dan menampilkan total berat pada halaman checkout
            function displayTotalWeight() {
                let totalWeight = calculateTotalWeight();
                $('#cart-total-weight').text(totalWeight + ' grams'); // Menampilkan total berat produk
                console.log('Total Weight:', totalWeight); // Log total berat produk untuk debugging

                // Di sini Anda dapat memproses total berat produk sesuai kebutuhan, misalnya untuk menghitung biaya pengiriman atau menyimpan dalam formulir checkout
                // Contoh: $('#total_weight_field').val(totalWeight);
            }

            // Panggil fungsi untuk menampilkan total berat saat halaman checkout dimuat
            displayTotalWeight();
            updateFormValues(); // Panggil untuk mengupdate total qty dan total price
        });
    </script>
@endpush

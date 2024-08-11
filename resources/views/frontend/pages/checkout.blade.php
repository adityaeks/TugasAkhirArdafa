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
                                            <li><span>Nama :</span> {{ $address->name }}</li>
                                            <li><span>Phone :</span> {{ $address->phone }}</li>
                                            <li><span>Email :</span> {{ $address->email }}</li>
                                            <li><span>Kode Pos :</span> {{ $address->zip }}</li>
                                            <li><span>Alamat :</span> {{ $address->address }}</li>
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
                            <p>biaya pengiriman(+): <span id="cost">Rp{{ number_format(0, 0, ',', '.') }}</span>
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
                                            <input type="text" placeholder="Nama *" name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="No. Hp *" name="phone"
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
                                            <select id="province" class="select_2" name="province">
                                                <option value="">Select Province</option>
                                                @foreach ($provinces['rajaongkir']['results'] as $province)
                                                    <option value="{{ $province['province_id'] }}">
                                                        {{ $province['province'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select id="city" class="select_2" name="city">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Kode Pos *" name="zip"
                                                value="{{ old('zip') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Alamat lengkap *" name="address"
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

            $('#submitCheckoutForm').off('click').on('click', function(e) {
                e.preventDefault(); // Prevent default action
                console.log('Submit button clicked'); // Log for debugging

                if ($('#shipping_address_id').val() == "") {
                    toastr.error('Shipping address is required');
                } else if ($('#delivery_package').val() == "") {
                    toastr.error('Delivery service is required');
                } else if (!$('.agree_term').prop('checked')) {
                    toastr.error('You have to agree to the website terms and conditions');
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
                            total_price: totalPrice
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

                        if (result.hasOwnProperty('shipping_fee')) {
                            let shippingFee = parseInt(result.shipping_fee);
                            $('#shipping_fee').text("Rp" + shippingFee.toLocaleString());

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


            function displayTotalWeight() {
                let totalWeight = calculateTotalWeight();
                $('#cart-total-weight').text(totalWeight + ' grams');
                console.log('Total Weight:', totalWeight);
            }

            $(document).ready(function() {
                $('#province').change(function() {
                    var provinceId = $(this).val();
                    if (provinceId) {
                        $.ajax({
                            url: 'checkout/cities/' + provinceId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#city').empty();
                                $('#city').append(
                                    '<option value="">Select City</option>');
                                $.each(data, function(key, value) {
                                    $('#city').append('<option value="' + value
                                        .city_id + '">' + value.city_name +
                                        '</option>');
                                });
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error);
                            }
                        });
                    } else {
                        $('#city').empty();
                        $('#city').append('<option value="">Select City</option>');
                    }
                });
            });

            displayTotalWeight();
            updateFormValues();
        });
    </script>
@endpush

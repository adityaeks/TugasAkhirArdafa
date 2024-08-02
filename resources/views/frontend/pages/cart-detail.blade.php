@extends('frontend.layouts.master')

@section('title')
UMKM Lowayu || Cart Details
@endsection

@section('content')

    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="wsus__cart_list">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr class="d-flex">
                                        <th class="wsus__pro_img">
                                            product item
                                        </th>

                                        {{-- <th class="wsus__pro_name">
                                            product name
                                        </th> --}}

                                        <th class="wsus__pro_tk">
                                           unit price
                                        </th>

                                        <th class="wsus__pro_tk">
                                            total
                                        </th>

                                        <th class="wsus__pro_select">
                                            quantity
                                        </th>



                                        <th class="wsus__pro_icon">
                                            <a href="#" class="common_btn clear_cart">clear cart</a>
                                        </th>
                                    </tr>
                                    @foreach ($cartItems as $item)
                                    <tr class="d-flex">
                                        <td class="wsus__pro_img"><img src="{{asset($item->options->image)}}" alt="product"
                                                class="img-fluid w-100">
                                        </td>
                                        {{-- <td class="wsus__pro_name">
                                            <h6>{{$item->name}}</h6>
                                        </td> --}}
                                        <td class="wsus__pro_tk">
                                            <h6>{{('Rp').$item->price}}</h6>
                                        </td>

                                        <td class="wsus__pro_tk">
                                            <h6 id="{{$item->rowId}}">{{('Rp').($item->price + $item->options->variants_total) * $item->qty}}</h6>
                                        </td>

                                        <td class="wsus__pro_select">
                                            <div class="product_qty_wrapper">
                                                <button class="btn btn-danger product-decrement">-</button>
                                                <input class="product-qty" data-rowid="{{$item->rowId}}" type="text" min="1" max="100" value="{{$item->qty}}" readonly />
                                                <button class="btn btn-success product-increment">+</button>
                                            </div>
                                        </td>

                                        <td class="wsus__pro_icon">
                                            <a href="{{route('cart.remove-product', $item->rowId)}}"><i class="far fa-times"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if (count($cartItems) === 0)
                                        <tr class="d-flex" >
                                            <td class="wsus__pro_icon" rowspan="2" style="width:100%">
                                                Cart is empty!
                                            </td>
                                        </tr>

                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="wsus__cart_list_footer_button" id="sticky_sidebar">
                        <h6>total cart</h6>
                        <p>subtotal: <span id="sub_total">Rp {{getCartTotal()}}</span></p>
                        <p>coupon(-): <span id="discount">Rp {{getCartDiscount()}}</span></p>
                        <p class="total"><span>total:</span> <span id="cart_total">Rp {{getMainCartTotal()}}</span></p>

                        <form id="coupon_form">
                            <input type="text" placeholder="Coupon Code" name="coupon_code" value="{{session()->has('coupon') ? session()->get('coupon')['coupon_code'] : ''}}">
                            <button type="submit" class="common_btn">apply</button>
                        </form>
                        <a class="common_btn mt-4 w-100 text-center" href="{{route('user.checkout')}}">checkout</a>
                        <a class="common_btn mt-1 w-100 text-center" href="{{route('home')}}"><i
                                class="fab fa-shopify"></i> Keep Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Increment product quantity
        $('.product-increment').on('click', function(){
            let input = $(this).siblings('.product-qty');
            let quantity = parseInt(input.val()) + 1;
            let rowId = input.data('rowid');
            input.val(quantity);

            $.ajax({
                url: "{{route('cart.update-quantity')}}",
                method: 'POST',
                data: {
                    rowId: rowId,
                    quantity: quantity
                },
                success: function(data){
                    if(data.status === 'success'){
                        let productId = '#'+rowId;
                        let totalAmount = ('Rp') + data.product_total;
                        $(productId).text(totalAmount);

                        renderCartSubTotal();
                        calculateCouponDescount();

                        toastr.success(data.message);

                        console.log('Updated Weight:', data.product_weight); // Menampilkan berat produk yang diperbarui
                    } else if (data.status === 'error'){
                        toastr.error(data.message); // Tampilkan notifikasi kesalahan
                    }
                },
                error: function(data){
                    console.error('Error updating quantity:', data);
                }
            });
        });

        // Decrement product quantity
        $('.product-decrement').on('click', function(){
            let input = $(this).siblings('.product-qty');
            let quantity = parseInt(input.val()) - 1;
            let rowId = input.data('rowid');

            if(quantity < 1){
                quantity = 1;
            }

            input.val(quantity);

            $.ajax({
                url: "{{route('cart.update-quantity')}}",
                method: 'POST',
                data: {
                    rowId: rowId,
                    quantity: quantity
                },
                success: function(data){
                    if(data.status === 'success'){
                        let productId = '#'+rowId;
                        let totalAmount = "Rp" + data.product_total;
                        $(productId).text(totalAmount);

                        renderCartSubTotal();
                        calculateCouponDescount();

                        toastr.success(data.message);

                        console.log('Updated Weight:', data.product_weight); // Menampilkan berat produk yang diperbarui
                    } else if (data.status === 'error'){
                        toastr.error(data.message); // Tampilkan notifikasi kesalahan
                    }
                },
                error: function(data){
                    console.error('Error updating quantity:', data);
                }
            });
        });

        // Clear cart
        $('.clear_cart').on('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will clear your cart!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'GET',
                        url: "{{route('clear.cart')}}",
                        success: function(data){
                            if(data.status === 'success'){
                                window.location.reload();
                            }
                        },
                        error: function(xhr, status, error){
                            console.log(error);
                        }
                    });
                }
            });
        });

        // Get subtotal of cart and put it on DOM
        function renderCartSubTotal(){
            $.ajax({
                method: 'GET',
                url: "{{ route('cart.sidebar-product-total') }}",
                success: function(data) {
                    $('#sub_total').text("Rp" + data);
                },
                error: function(data) {
                    console.error('Error fetching cart subtotal:', data);
                }
            });
        }

        // Apply coupon on cart
        $('#coupon_form').on('submit', function(e){
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                method: 'GET',
                url: "{{ route('apply-coupon') }}",
                data: formData,
                success: function(data) {
                    if(data.status === 'error'){
                        toastr.error(data.message);
                    } else if (data.status === 'success'){
                        calculateCouponDescount();
                        toastr.success(data.message);
                    }
                },
                error: function(data) {
                    console.error('Error applying coupon:', data);
                }
            });
        });

        // Calculate discount amount
        function calculateCouponDescount(){
            $.ajax({
                method: 'GET',
                url: "{{ route('coupon-calculation') }}",
                success: function(data) {
                    if(data.status === 'success'){
                        $('#discount').text('Rp' + data.discount);
                        $('#cart_total').text('Rp' + data.cart_total);
                    }
                },
                error: function(data) {
                    console.error('Error calculating coupon discount:', data);
                }
            });
        }
    });
</script>


@endpush

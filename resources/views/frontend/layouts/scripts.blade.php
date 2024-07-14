<script>
    $(document).ready(function() {
    let productWeights = {};

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getTotalWeight() {
        $.ajax({
            method: 'GET',
            url: "{{ route('cart.total-weight') }}",
            success: function(data) {
                $('#cart-total-weight').text(data + ' grams'); // Menampilkan total berat produk
                console.log('Total Weight:', data); // Log total berat produk untuk debugging

                // Simpan data berat produk dalam JSON
                productWeights.totalWeight = data;
                console.log('Product Weights JSON:', JSON.stringify(productWeights));

                // Kirim data berat produk ke server
                $.ajax({
                    method: 'POST',
                    url: "{{ route('user.set-total-weight') }}", // Gunakan prefix 'user.' jika route berada di dalam grup user
                    data: {
                        total_weight: data
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Total weight saved to session');
                    },
                    error: function(response) {
                        console.error('Error saving total weight to session:', response);
                    }
                });
            },
            error: function(data) {
                console.error('Error fetching total weight:', data);
            }
        });
    }

    // Add product into cart
    $(document).on('submit', '.shopping-cart-form', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        console.log('Form Data:', formData); // Log form data untuk debugging

        $.ajax({
            method: 'POST',
            data: formData,
            url: "{{ route('add-to-cart') }}",
            success: function(data) {
                console.log('Add to Cart Response:', data); // Log response untuk debugging
                if (data.status === 'success') {
                    getCartCount();
                    fetchSidebarCartProducts();
                    $('.mini_cart_actions').removeClass('d-none');
                    toastr.success(data.message);
                } else if (data.status === 'error') {
                    toastr.error(data.message);
                }
            },
            error: function(data) {
                console.error('Error adding product to cart:', data);
            }
        });
    });

    function getCartCount() {
        $.ajax({
            method: 'GET',
            url: "{{ route('cart-count') }}",
            success: function(data) {
                $('#cart-count').text(data);
            },
            error: function(data) {
                console.error('Error fetching cart count:', data);
            }
        });
    }

    function fetchSidebarCartProducts() {
        $.ajax({
            method: 'GET',
            url: "{{ route('cart-products') }}",
            success: function(data) {
                console.log('Sidebar Cart Products:', data); // Log data produk untuk debugging
                $('.mini_cart_wrapper').html("");
                var html = '';
                for (let item in data) {
                    let product = data[item];
                    html += `
                    <li id="mini_cart_${product.rowId}">
                        <div class="wsus__cart_img">
                            <a href="{{ url('product-detail') }}/${product.options.slug}"><img src="{{ asset('/') }}${product.options.image}" alt="product" class="img-fluid w-100"></a>
                            <a class="wsis__del_icon remove_sidebar_product" data-id="${product.rowId}" href=""><i class="fas fa-minus-circle"></i></a>
                        </div>
                        <div class="wsus__cart_text">
                            <a class="wsus__cart_title" href="{{ url('product-detail') }}/${product.options.slug}">${product.name}</a>
                            <p>${product.price}</p>
                            <small>Qty: ${product.qty}</small>
                            <div class="total-weight">
                                Total Berat: <span id="cart-total-weight">0 grams</span>
                            </div>
                        </div>
                    </li>`;
                }

                $('.mini_cart_wrapper').html(html);

                getSidebarCartSubtoal();
                getTotalWeight(); // Panggil fungsi ini untuk memperbarui total berat
            },
            error: function(data) {
                console.error('Error fetching sidebar cart products:', data);
            }
        });
    }

    // Remove product from sidebar cart
    $('body').on('click', '.remove_sidebar_product', function(e) {
        e.preventDefault();
        let rowId = $(this).data('id');
        console.log('Removing Product Row ID:', rowId); // Log rowId untuk debugging

        $.ajax({
            method: 'POST',
            url: "{{ route('cart.remove-sidebar-product') }}",
            data: {
                rowId: rowId
            },
            success: function(data) {
                let productId = '#mini_cart_' + rowId;
                $(productId).remove();

                getSidebarCartSubtoal();
                getTotalWeight(); // Panggil fungsi ini untuk memperbarui total berat

                if ($('.mini_cart_wrapper').find('li').length === 0) {
                    $('.mini_cart_actions').addClass('d-none');
                    $('.mini_cart_wrapper').html(
                        '<li class="text-center">Keranjang Kosong!</li>');
                }
                toastr.success(data.message);
            },
            error: function(data) {
                console.error('Error removing product:', data);
            }
        });
    });

    // Get sidebar cart sub total
    function getSidebarCartSubtoal() {
        $.ajax({
            method: 'GET',
            url: "{{ route('cart.sidebar-product-total') }}",
            success: function(data) {
                $('#mini_cart_subtotal').text(data);
            },
            error: function(data) {
                console.error('Error fetching sidebar cart subtotal:', data);
            }
        });
    }

    // Add product to wishlist
    $('.add_to_wishlist').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        console.log('Adding to Wishlist Product ID:', id); // Log product ID untuk debugging

        $.ajax({
            method: 'GET',
            url: "{{ route('wishlist.store') }}",
            data: {
                id: id
            },
            success: function(data) {
                if (data.status === 'success') {
                    $('#wishlist_count').text(data.count);
                    toastr.success(data.message);
                } else if (data.status === 'error') {
                    toastr.error(data.message);
                }
            },
            error: function(data) {
                console.error('Error adding to wishlist:', data);
            }
        });
    });

    $('.show_product_modal').on('click', function() {
        let id = $(this).data('id');
        console.log('Showing Product Modal for ID:', id); // Log product ID untuk debugging

        $.ajax({
            method: 'GET',
            url: '{{ route('show-product-modal', ':id') }}'.replace(":id", id),
            beforeSend: function() {
                $('.product-modal-content').html('<span class="loader"></span>');
            },
            success: function(response) {
                $('.product-modal-content').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error showing product modal:', error);
            },
            complete: function() {
                // Any additional actions after completion
            }
        });
    });

    // Call the function initially to display total weight on page load
    getTotalWeight();
});

</script>

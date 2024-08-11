@extends('frontend.layouts.master')

@section('title')
    UMKM Lowayu || Product Details
@endsection

@section('content')
    <section id="wsus__product_page">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="wsus__sidebar_filter ">
                        <p>filter</p>
                        <span class="wsus__filter_icon">
                            <i class="far fa-minus" id="minus"></i>
                            <i class="far fa-plus" id="plus"></i>
                        </span>
                    </div>
                    <div class="wsus__product_sidebar" id="sticky_sidebar">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        All Categories
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul>
                                            @foreach ($categories as $category)
                                                <li><a
                                                        href="{{ route('products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
                            <div class="wsus__product_topbar">
                                <div class="wsus__product_topbar_left">
                                    <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <button
                                            class="nav-link {{ session()->has('product_list_style') && session()->get('product_list_style') == 'grid' ? 'active' : '' }} {{ !session()->has('product_list_style') ? 'active' : '' }} list-view"
                                            data-id="grid" id="v-pills-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-home" type="button" role="tab"
                                            aria-controls="v-pills-home" aria-selected="true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button
                                            class="nav-link list-view {{ session()->has('product_list_style') && session()->get('product_list_style') == 'list' ? 'active' : '' }}"
                                            data-id="list" id="v-pills-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-profile" type="button" role="tab"
                                            aria-controls="v-pills-profile" aria-selected="false">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade {{ session()->has('product_list_style') && session()->get('product_list_style') == 'grid' ? 'show active' : '' }} {{ !session()->has('product_list_style') ? 'show active' : '' }}"
                                id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="row">
                                    @foreach ($products as $product)
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="wsus__product_item">
                                                <span class="">{{ productType($product->product_type) }}</span>
                                                {{-- @if (checkDiscount($product))
                                                    <span
                                                        class="wsus__minus">-{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
                                                @endif --}}
                                                <a class="wsus__pro_link"
                                                    href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_1" />
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_2" />
                                                </a>
                                                <ul class="wsus__single_pro_icon">
                                                    <li><a href="#" class="add_to_wishlist"
                                                            data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                    </li>
                                                </ul>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category" href="#">{{ $product->category->name }}
                                                    </a>
                                                    <a class="wsus__pro_name"
                                                        href="{{ route('product-detail', $product->slug) }}">{{ limitText($product->name, 53) }}</a>
                                                    @if (checkDiscount($product))
                                                        <p class="wsus__price">
                                                            Rp{{ number_format($product->offer_price, 0, ',', '.') }}
                                                            <del>Rp{{ number_format($product->price, 0, ',', '.') }}</del>
                                                        </p>
                                                    @else
                                                        <p class="wsus__price">
                                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                                        </p>
                                                    @endif

                                                    <form class="shopping-cart-form">
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <input class="" name="qty" type="hidden" min="1"
                                                            max="100" value="1" />
                                                        <button class="add_cart" type="submit">add to cart</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                            </div>

                            <div class="tab-pane fade {{ session()->has('product_list_style') && session()->get('product_list_style') == 'list' ? 'show active' : '' }}"
                                id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    @foreach ($products as $product)
                                        <div class="col-xl-12">
                                            <div class="wsus__product_item wsus__list_view">
                                                <span class="">{{ productType($product->product_type) }}</span>
                                                @if (checkDiscount($product))
                                                    <span
                                                        class="wsus__minus">-{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
                                                @endif

                                                <a class="wsus__pro_link"
                                                    href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_1" />

                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_2" />
                                                </a>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category"
                                                        href="#">{{ @$product->category->name }} </a>
                                                    <a class="wsus__pro_name"
                                                        href="{{ route('product-detail', $product->slug) }}">{{ $product->name }}</a>

                                                    @if (checkDiscount($product))
                                                        <p class="wsus__price">
                                                            Rp{{ number_format($product->offer_price, 0, ',', '.') }}
                                                            <del>Rp{{ number_format($product->price, 0, ',', '.') }}</del>
                                                        </p>
                                                    @else
                                                        <p class="wsus__price">
                                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                                        </p>
                                                    @endif


                                                    <p class="list_description">{{ $product->short_description }}</p>
                                                    <ul class="wsus__single_pro_icon">

                                                        <form class="shopping-cart-form">
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->id }}">
                                                            {{-- 45 --}}
                                                            <input class="" name="qty" type="hidden"
                                                                min="1" max="100" value="1" />
                                                            <button class="add_cart_two mr-2" type="submit">add to
                                                                cart</button>
                                                        </form>
                                                        <li><a href="#"><i class="far fa-heart"></i></a></li>
                                                        {{-- <li><a href="#"><i class="far fa-random"></i></a> --}}
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($products) === 0)
                        <div class="text-center mt-5">
                            <div class="card">
                                <div class="card-body">
                                    <h2>Product not found!</h2>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-xl-12 text-center">
                    <div class="mt-5" style="display:flex; justify-content:center">
                        @if ($products->hasPages())
                            {{ $products->withQueryString()->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.list-view').on('click', function() {
                let style = $(this).data('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('change-product-list-view') }}",
                    data: {
                        style: style
                    },
                    success: function(data) {

                    }
                })
            })
        })
        @php
            if (request()->has('range') && request()->range != '') {
                $price = explode(';', request()->range);
                $from = $price[0];
                $to = $price[1];
            } else {
                $from = 0;
                $to = 8000;
            }
        @endphp
        jQuery(function() {
            jQuery("#slider_range").flatslider({
                min: 0,
                max: 10000,
                step: 100,
                values: [{{ $from }}, {{ $to }}],
                range: true,
                einheit: 'Rp'
            });
        });
    </script>
@endpush

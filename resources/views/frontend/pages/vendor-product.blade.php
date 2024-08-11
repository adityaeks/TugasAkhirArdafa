@extends('frontend.layouts.master')

@section('title')
    UMKM Lowayu || Vendor Products
@endsection

@section('content')
    <section id="wsus__product_page">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__pro_page_bammer vendor_det_banner">
                        <img src="{{ asset($vendor->banner) }}" alt="banner" class="img-fluid w-100">
                        <div class="wsus__pro_page_bammer_text wsus__vendor_det_banner_text">
                            <div class="wsus__vendor_text_center">
                                <h4>{{ $vendor->shop_name }}</h4>
                                <a href="callto:{{ $vendor->phone }}"><i class="far fa-phone-alt"></i> {{ $vendor->phone }}</a>
                                <a href="mailto:{{ $vendor->email }}"><i class="far fa-envelope"></i> {{ $vendor->email }}</a>
                                <p class="wsus__vendor_location"><i class="fal fa-map-marker-alt"></i> {{ $vendor->address }}</p>
                                <ul class="d-flex">
                                    <li><a class="facebook" href="{{ $vendor->fb_link }}"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a class="instagram" href="{{ $vendor->insta_link }}"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
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
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="wsus__product_item">
                                                <span class="">{{ productType($product->product_type) }}</span>
                                              
                                                <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                                                    <img src=" {{ asset($product->thumb_image) }} "
                                                        alt="product" class="img-fluid w-100 img_2" />
                                                </a>
                                                <ul class="wsus__single_pro_icon">
                                                    <li><a href="#" class="add_to_wishlist" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a></li>
                                                </ul>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category" href="#">{{ $product->category->name }}</a>
                                                    <a class="wsus__pro_name" href="{{ route('product-detail', $product->slug) }}">{{ limitText($product->name, 53) }}</a>
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
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input class="" name="qty" type="hidden" min="1" max="100" value="1" />
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
                                                <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                                                    <img src="{{ asset($product->thumb_image) }} "
                                                        alt="product" class="img-fluid w-100 img_2" />
                                                </a>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category" href="#">{{ @$product->category->name }}</a>
                                                    <a class="wsus__pro_name" href="{{ route('product-detail', $product->slug) }}">{{ $product->name }}</a>
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
                                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            <input class="" name="qty" type="hidden" min="1" max="100" value="1" />
                                                            <button class="add_cart_two mr-2" type="submit">add to cart</button>
                                                        </form>
                                                        <li><a href="#"><i class="far fa-heart"></i></a></li>
                                                        <li><a href="#"><i class="far fa-random"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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
    </script>
@endpush

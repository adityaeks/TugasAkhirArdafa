@extends('frontend.layouts.master')
@section('title')
UMKM Lowayu || Home
@endsection

@section('content')

    <!--============================
        banner 1
    ==============================-->
    @include('frontend.home.sections.banner-slider')

    <!--============================
       Produk 1
    ==============================-->
    @include('frontend.home.sections.top-category-product')



    <!--============================
        banner 2
    ==============================-->
    @include('frontend.home.sections.single-banner')


    <!--============================
        Peoduk 2
    ==============================-->
    @include('frontend.home.sections.category-product-slider-one')


    <!--============================
        produk 3
    ==============================-->
    @include('frontend.home.sections.weekly-best-item')

@endsection

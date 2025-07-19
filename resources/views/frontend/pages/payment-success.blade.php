@extends('frontend.layouts.app')

@section('title')
OurKitchen || Payment
@endsection

@section('content')


    <!--============================
        PAYMENT PAGE START
    ==============================-->
    <section id="wsus__cart_view" class="py-16 bg-gray-50 min-h-[60vh] flex items-center justify-center">
        <div class="container flex justify-center">
            <div class="bg-white rounded-xl shadow-lg p-10 max-w-lg w-full text-center">
                <div class="flex flex-col items-center mb-6">
                    <div class="bg-green-100 rounded-full p-4 mb-4">
                        <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-green-600 mb-2">Payment Success!</h1>
                    <p class="text-gray-700 text-lg mb-4">Thank you for your purchase. Your payment has been processed successfully.</p>
                </div>
                <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold shadow hover:bg-blue-600 transition">Back to Home</a>
            </div>
        </div>
    </section>
    <!--============================
        PAYMENT PAGE END
    ==============================-->
@endsection

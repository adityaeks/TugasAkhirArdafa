@extends('frontend.layouts.master')

@section('title')
OurKitchen || Flash Sale
@endsection

@section('content')
    <section id="wsus__daily_deals">
        <div class="container">
            <div class="wsus__offer_details_area">
                <div class="row">

                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header rounded-0">
                            <h3>flash sell</h3>
                            <div class="wsus__offer_countdown">
                                <span class="end_text">ends time :</span>
                                <div class="simply-countdown simply-countdown-one"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $products = \App\Models\Product::withAvg('reviews', 'rating')->withCount('reviews')
                    ->with(['variants', 'category', 'productImageGalleries'])
                        ->whereIn('id', $flashSaleItems)->get();
                    @endphp
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                {{-- <div class="mt-5">
                    @if ($flashSaleItems->hasPages())
                        {{$flashSaleItems->links()}}
                    @endif
                </div> --}}
            </div>
        </div>
    </section>


@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        simplyCountdown('.simply-countdown-one', {
            year: {{date('Y', strtotime($flashSaleDate->end_date))}},
            month: {{date('m', strtotime($flashSaleDate->end_date))}},
            day: {{date('d', strtotime($flashSaleDate->end_date))}},
        });
    })
</script>
@endpush

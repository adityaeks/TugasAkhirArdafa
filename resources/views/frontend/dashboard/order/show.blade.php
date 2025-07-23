@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shipping_method);
    $coupon = json_decode($order->coupon);
@endphp

@extends('frontend.dashboard.layouts.master')

@section('title')
    OurKitchen Resi Order
@endsection

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Order Details</h3>
                        <div class="wsus__dashboard_profile">

                            {{-- Invoice --}}
                            <section id="" class="invoice-print">
                                <div class="">
                                    <div class="wsus__invoice_area">
                                        <div class="wsus__invoice_header">
                                            <div class="wsus__invoice_content">
                                                <div class="row">
                                                    <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                        <div class="wsus__invoice_single">
                                                            <h5>Alamat Pengiriman</h5>
                                                            <p>{{ $address->address ?? '-' }}, {{ $address->zip ?? '-' }}</p>
                                                            <p>Nama: {{ $address->name ?? '-' }}</p>
                                                            <p>Phone: {{ $address->phone ?? '-' }}</p>
                                                            <p>Email: {{ $address->email ?? '-' }}</p>
                                                            <p>Provinsi: {{ $address->province ?? '-' }}</p>
                                                            <p>Kota: {{ $address->city ?? '-' }}</p>
                                                            <p>Kecamatan: {{ $address->district_name ?? '-' }}</p>
                                                            <p>Kode Pos: {{ $address->zip ?? '-' }}</p>
                                                            <p>Kurir : {{ $order->courier ?? '-' }}, {{ $order->service ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                        <div class="wsus__invoice_single text-md-center">
                                                            <h5>informasi pembayaran</h5>
                                                            <h6>{{ $address->name ?? '-'}}</h6>
                                                            <p>{{ $address->email ?? '-'}}</p>
                                                            <p>{{ $address->phone ?? '-'}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="wsus__invoice_single text-md-end">
                                                            <h5>Invoice: {{ $order->invoice_id ?? '-' }}</h5>
                                                            <h6>Order status:
                                                                {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                                            </h6>
                                                            {{-- <p>Payment Method: {{ $order->payment_method }}</p> --}}
                                                            <p>Payment Status: {{ $transactions->status }}</p>
                                                            {{-- <p>Transaction id: {{ $order->transaction->transaction_id }} --}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wsus__invoice_description">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="name">
                                                                produk
                                                            </th>

                                                            <th class="amount">
                                                                amount
                                                            </th>

                                                            <th class="quentity">
                                                                jumlah
                                                            </th>
                                                            <th class="total">
                                                                total
                                                            </th>
                                                        </tr>
                                                        @foreach ($order->orderProducts as $product)
                                                            <tr>
                                                                <td class="name">
                                                                    {{ $product->product_name }}
                                                                </td>
                                                                <td class="amount">
                                                                    Rp{{ number_format($product->unit_price, 0, ',', '.') }}
                                                                </td>

                                                                <td class="quentity">
                                                                    {{ $product->qty }}
                                                                </td>
                                                                <td class="total">
                                                                    Rp {{ number_format($product->unit_price * $product->qty, 0, ',', '.') }}
                                                                </td>

                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wsus__invoice_footer">

                                            <p><span>Sub Total:</span>
                                                Rp{{ number_format($order->sub_total, 0, ',', '.') }}</p>
                                            <p><span>Pengiriman(+):</span>
                                                Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</p>
                                            <p><span>Total Semua:</span>
                                                Rp{{ number_format($order->amount, 0, ',', '.') }}</p>


                                        </div>
                                    </div>
                                </div>
                            </section>
                            {{-- end --}}
                            <div class="col">
                                <div class="mt-2 float-end">
                                    <button class="btn btn-warning print_invoice">print</button>
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
        $('.print_invoice').on('click', function() {
            let printBody = $('.invoice-print');
            let originalContents = $('body').html();

            $('body').html(printBody.html());

            window.print();

            $('body').html(originalContents);

        })
    </script>
@endpush

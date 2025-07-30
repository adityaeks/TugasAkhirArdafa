@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shpping_method);
    $coupon = json_decode($order->coupon);

@endphp
@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header no-print">
            <h1>Orders</h1>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <div class="header-yellow p-3 mb-3 rounded" style="background-color: #ffd90027">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('frontend/images/logo123.png') }}" alt="OurKitchen Logo" class="logo-small mr-2 rounded" style="width: 80px">
                                            <h2 class="mb-0 text-dark">OurKitchen</h2>
                                        </div>
                                        <div class="invoice-number text-dark">Invoice #{{ $order->invoice_id }}</div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Informasi Pengiriman:</strong><br>
                                        <b>Name:</b> {{ $address->name }}<br>
                                        <b>Email: </b> {{ $address->email }}<br>
                                        <b>Phone:</b> {{ $address->phone }}<br>
                                        <b>Address:</b> {{ $address->address }},<br>
                                        {{ $address->zip }}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Informasi Pembayaran:</strong><br>
                                        <b>Name:</b> {{ $address->name }}<br>
                                        <b>Email: </b> {{ $address->email }}<br>
                                        <b>Phone:</b> {{ $address->phone }}<br>
                                        <b>Status:</b> {{ $order->status }},<br>
                                        <strong>Order Date:</strong><br>
                                        {{ date('d F, Y', strtotime($order->created_at)) }}<br><br>
                                    </address>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Detail Pesanan</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Item</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Totals</th>
                                    </tr>
                                    @foreach ($order->orderProducts as $product)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            @if (isset($product->product->slug))
                                                <td><a target="_blank"
                                                        href="{{ route('product-detail', $product->product->slug) }}">{{ $product->product_name }}</a>
                                                </td>
                                            @else
                                                <td>{{ $product->product_name }}</td>
                                            @endif
                                            <td class="text-center">Rp{{ $product->unit_price }} </td>
                                            <td class="text-center">{{ $product->qty }}</td>
                                            <td class="text-right">
                                                Rp{{ $product->unit_price * $product->qty + $product->variant_total }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="col-md-4">
                                        {{-- <div class="form-group">
                                            <label for="">Payment status</label>

                                            <select name="" id="payment_status" class="form-control"
                                                data-id="{{ $order->id }}">
                                                <option {{ $order->payment_status === 0 ? 'selected' : '' }}
                                                    value="0">
                                                    Pending</option>
                                                <option {{ $order->payment_status === 1 ? 'selected' : '' }}
                                                    value="1">
                                                    Completed</option>
                                            </select>
                                        </div> --}}

                                        <div class="form-group">
                                            <label for="">Status Pengiriman</label>
                                            <select name="order_status" id="order_status" data-id="{{ $order->id }}"
                                                class="form-control">
                                                @foreach (config('order_status.order_status_admin') as $key => $orderStatus)
                                                    <option {{ $order->order_status === $key ? 'selected' : '' }}
                                                        value="{{ $key }}">{{ $orderStatus['status'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Subtotal</div>
                                        <div class="invoice-detail-value">Rp {{ $order->sub_total }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping (+)</div>
                                        <div class="invoice-detail-value">Rp {{ $order->shiping_fee ?? 0}}</div>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">Rp {{ $order->amount }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right no-print">
                    <button class="btn btn-warning btn-icon icon-left print_invoice"><i class="fas fa-print"></i>
                        Print</button>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .logo-small {
        object-fit: contain;
        border-radius: 4px;
        max-width: 100%;
        display: block;
    }

    .header-yellow {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        border: 2px solid #FF8C00;
        box-shadow: 0 4px 8px rgba(255, 215, 0, 0.3);
    }

    .header-yellow h2, .header-yellow h3 {
        color: #333 !important;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
    }

    /* Print Styles */
    @media print {
        body {
            background: white !important;
            color: black !important;
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .header-yellow {
            background: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            margin-bottom: 20px !important;
        }

        .header-yellow h2, .header-yellow h3 {
            color: #333 !important;
            text-shadow: none !important;
            font-size: 18px !important;
        }

        .logo-small {
            width: 40px !important;
            height: 40px !important;
        }

        .invoice-number {
            font-size: 14px !important;
            font-weight: bold !important;
        }

        .table {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-bottom: 20px !important;
        }

        .table th, .table td {
            border: 1px solid #dee2e6 !important;
            padding: 8px !important;
            text-align: left !important;
        }

        .table th {
            background: #f8f9fa !important;
            font-weight: bold !important;
        }

        .invoice-detail-item {
            margin-bottom: 8px !important;
        }

        .invoice-detail-name {
            font-weight: bold !important;
        }

        .invoice-detail-value {
            text-align: right !important;
        }

        .invoice-detail-value-lg {
            font-size: 16px !important;
            font-weight: bold !important;
        }

        /* Hide unnecessary elements for print */
        .btn, .section-header, .navbar-bg, .main-sidebar, .main-navbar, .no-print {
            display: none !important;
        }

        /* Ensure proper page breaks */
        .invoice-print {
            page-break-inside: avoid !important;
        }

        /* Remove shadows and decorative elements */
        * {
            box-shadow: none !important;
            text-shadow: none !important;
        }

        /* Ensure text is readable */
        a {
            color: #333 !important;
            text-decoration: none !important;
        }

        /* Compact spacing for print */
        .section-body {
            padding: 0 !important;
        }

        .invoice {
            margin: 0 !important;
            padding: 0 !important;
        }

        hr {
            border: 1px solid #dee2e6 !important;
            margin: 15px 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#order_status').on('change', function() {
                let status = $(this).val();
                let id = $(this).data('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.order.status') }}",
                    data: {
                        status: status,
                        id: id
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success(data.message)
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })

            $('#payment_status').on('change', function() {
                let status = $(this).val();
                let id = $(this).data('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.payment.status') }}",
                    data: {
                        status: status,
                        id: id
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success(data.message)
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })

            $('.print_invoice').on('click', function() {
                let printBody = $('.invoice-print');
                let originalContents = $('body').html();

                $('body').html(printBody.html());

                window.print();

                $('body').html(originalContents);

            })
        })
    </script>
@endpush

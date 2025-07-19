@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('admin.order.index') }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Todays Orders</h4>
                            </div>
                            <div class="card-body">
                                {{ $todaysOrder }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('admin.order.index') }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Orders</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrders }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Product</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalProducts }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('admin.customer.index') }}">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total User Custommers</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalUsers }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </section>
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Order per Hari (Bulan Ini)</h4>
                </div>
                <div class="card-body">
                    <canvas id="ordersPerDayChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Order per Bulan (Tahun Ini)</h4>
                </div>
                <div class="card-body">
                    <canvas id="ordersPerMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Grafik Order per Hari (Bar Chart Sederhana Tanpa Library)
const ordersPerDayLabels = @json($ordersPerDay->pluck('date'));
const ordersPerDayData = @json($ordersPerDay->pluck('total'));
const dayCanvas = document.getElementById('ordersPerDayChart');
if (dayCanvas) {
    const ctx = dayCanvas.getContext('2d');
    const width = dayCanvas.width = dayCanvas.offsetWidth;
    const height = dayCanvas.height = 250;
    ctx.clearRect(0, 0, width, height);
    // Axis
    ctx.strokeStyle = '#ccc';
    ctx.beginPath();
    ctx.moveTo(40, 10); ctx.lineTo(40, height-30); ctx.lineTo(width-10, height-30); ctx.stroke();
    // Bars
    const max = Math.max(...ordersPerDayData, 1);
    const barW = (width-60) / ordersPerDayLabels.length;
    ordersPerDayData.forEach((val, i) => {
        const barH = (val/max)*(height-60);
        ctx.fillStyle = 'rgba(54,162,235,0.5)';
        ctx.fillRect(45+i*barW, height-30-barH, barW-10, barH);
        ctx.fillStyle = '#333';
        ctx.fillText(ordersPerDayLabels[i], 45+i*barW, height-10);
        ctx.fillText(val, 45+i*barW, height-35-barH);
    });
}

// Grafik Order per Bulan (Line Chart Sederhana Tanpa Library)
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const ordersPerMonthLabels = @json($ordersPerMonth->pluck('month')->map(function($m){ return $m ? $m : 0; })->toArray());
const ordersPerMonthData = @json($ordersPerMonth->pluck('total'));
const monthLabels = ordersPerMonthLabels.map(m => monthNames[m-1]);
const monthCanvas = document.getElementById('ordersPerMonthChart');
if (monthCanvas) {
    const ctx = monthCanvas.getContext('2d');
    const width = monthCanvas.width = monthCanvas.offsetWidth;
    const height = monthCanvas.height = 250;
    ctx.clearRect(0, 0, width, height);
    // Axis
    ctx.strokeStyle = '#ccc';
    ctx.beginPath();
    ctx.moveTo(40, 10); ctx.lineTo(40, height-30); ctx.lineTo(width-10, height-30); ctx.stroke();
    // Line
    const max = Math.max(...ordersPerMonthData, 1);
    const stepX = (width-60) / (monthLabels.length-1 || 1);
    ctx.strokeStyle = 'rgba(255,99,132,1)';
    ctx.beginPath();
    ordersPerMonthData.forEach((val, i) => {
        const x = 40 + i*stepX;
        const y = height-30 - (val/max)*(height-60);
        if(i===0) ctx.moveTo(x, y); else ctx.lineTo(x, y);
    });
    ctx.stroke();
    // Area
    ctx.fillStyle = 'rgba(255,99,132,0.2)';
    ctx.beginPath();
    ordersPerMonthData.forEach((val, i) => {
        const x = 40 + i*stepX;
        const y = height-30 - (val/max)*(height-60);
        if(i===0) ctx.moveTo(x, y); else ctx.lineTo(x, y);
    });
    ctx.lineTo(40+stepX*(monthLabels.length-1), height-30);
    ctx.lineTo(40, height-30);
    ctx.closePath();
    ctx.fill();
    // Points & Labels
    ctx.fillStyle = 'rgba(255,99,132,1)';
    ordersPerMonthData.forEach((val, i) => {
        const x = 40 + i*stepX;
        const y = height-30 - (val/max)*(height-60);
        ctx.beginPath(); ctx.arc(x, y, 4, 0, 2*Math.PI); ctx.fill();
        ctx.fillText(monthLabels[i], x-10, height-10);
        ctx.fillText(val, x-5, y-10);
    });
}
</script>
@endpush

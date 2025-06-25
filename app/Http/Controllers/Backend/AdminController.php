<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Review;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->count();
        $todaysPendingOrder = Order::whereDate('created_at', Carbon::today())
        ->where('order_status', 'pending')->count();
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('order_status', 'pending')->count();
        $totalCanceledOrders = Order::where('order_status', 'canceled')->count();
        $totalCompleteOrders = Order::where('order_status', 'delivered')->count();

        // $todaysEarnings = Order::where('order_status','!=', 'canceled')
        // ->where('payment_status',1)
        // ->whereDate('created_at', Carbon::today())
        // ->sum('sub_total');

        // $monthEarnings = Order::where('order_status','!=', 'canceled')
        // ->where('payment_status',1)
        // ->whereMonth('created_at', Carbon::now()->month)
        // ->sum('sub_total');

        // $yearEarnings = Order::where('order_status','!=', 'canceled')
        // ->where('payment_status',1)
        // ->whereYear('created_at', Carbon::now()->year)
        // ->sum('sub_total');

        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalPendingProducts = Product::where('status', '0')->count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalUsers = User::where('role', 'user')->count();

        // Order per hari di bulan ini
        $ordersPerDay = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Order per bulan di tahun ini
        $ordersPerMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'todaysOrder',
            'todaysPendingOrder',
            'totalOrders',
            'totalPendingOrders',
            'totalCanceledOrders',
            'totalCompleteOrders',
            // 'todaysEarnings',
            // 'monthEarnings',
            // 'yearEarnings',
            'totalCategories',
            'totalProducts',
            'totalPendingProducts',
            'totalVendors',
            'totalUsers',
            'ordersPerDay',
            'ordersPerMonth'
        ));
    }

    public function login()
    {
        return view('admin.auth.login');
    }
}

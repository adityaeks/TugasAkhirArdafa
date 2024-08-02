<?php

use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\AboutController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminListController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CodSettingController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\HomePageSettingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\TermsAndConditionController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\VendorConditionController;
use App\Http\Controllers\Backend\VendorListController;
use App\Http\Controllers\Backend\VendorRequestController;
use Illuminate\Support\Facades\Route;


/** Admin Routes */
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashbaord');

/** Profile Routes */
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');

/** Slider Route */
Route::resource('slider', SliderController::class);

/** Category Route */
Route::put('change-status', [CategoryController::class, 'changeStatus'])->name('kategori.change-status');
Route::resource('kategori', CategoryController::class);
Route::delete('produk/{id}', [ProductController::class, 'destroy'])->name('admin.produk.destroy');

/** Vendor Profile routes */
Route::resource('vendor-profile', AdminVendorProfileController::class);

/** Products routes */
Route::put('product/change-status', [ProductController::class, 'changeStatus'])->name('produk.change-status');
Route::resource('produk', ProductController::class);

// seller produk
Route::get('seller-products', [SellerProductController::class, 'index'])->name('seller-produk.index');
Route::get('seller-pending-products', [SellerProductController::class, 'pendingProducts'])->name('seller-pending-produk.index');
Route::put('change-approve-status', [SellerProductController::class, 'changeApproveStatus'])->name('change-approve-status');

/** Coupon Routes */
Route::put('kupon/change-status', [CouponController::class, 'changeStatus'])->name('kupon.change-status');
Route::resource('kupon', CouponController::class);

/** Order routes */
Route::get('payment-status', [OrderController::class, 'changePaymentStatus'])->name('payment.status');
Route::get('order-status', [OrderController::class, 'changeOrderStatus'])->name('order.status');

Route::get('pending-orders', [OrderController::class, 'pendingOrders'])->name('pending-orders');
Route::get('processed-orders', [OrderController::class, 'processedOrders'])->name('processed-orders');
Route::get('dropped-off-orders', [OrderController::class, 'droppedOfOrders'])->name('dropped-off-orders');

Route::get('shipped-orders', [OrderController::class, 'shippedOrders'])->name('shipped-orders');
Route::get('out-for-delivery-orders', [OrderController::class, 'outForDeliveryOrders'])->name('out-for-delivery-orders');
Route::get('delivered-orders', [OrderController::class, 'deliveredOrders'])->name('delivered-orders');
Route::get('canceled-orders', [OrderController::class, 'canceledOrders'])->name('canceled-orders');
Route::resource('order', OrderController::class);

/** Order Transaction route */
Route::get('transaction', [TransactionController::class, 'index'])->name('transaction');

/** settings routes */
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

/** home page setting route */
Route::get('home-page-setting', [HomePageSettingController::class, 'index'])->name('home-page-setting');
Route::put('popular-category-section', [HomePageSettingController::class, 'updatePopularCategorySection'])->name('popular-category-section');

Route::put('product-slider-section-one', [HomePageSettingController::class, 'updateProductSliderSectionOn'])->name('product-slider-section-one');
Route::put('product-slider-section-two', [HomePageSettingController::class, 'updateProductSliderSectionTwo'])->name('product-slider-section-two');
Route::put('product-slider-section-three', [HomePageSettingController::class, 'updateProductSliderSectionThree'])->name('product-slider-section-three');

/** Advertisement Routes */
Route::get('advertisement', [AdvertisementController::class, 'index'])->name('iklan.index');
Route::put('advertisement/homepage-banner-secion-one', [AdvertisementController::class, 'homepageBannerSecionOne'])->name('homepage-banner-secion-one');
Route::put('advertisement/homepage-banner-secion-two', [AdvertisementController::class, 'homepageBannerSecionTwo'])->name('homepage-banner-secion-two');
Route::put('advertisement/homepage-banner-secion-three', [AdvertisementController::class, 'homepageBannerSecionThree'])->name('homepage-banner-secion-three');
Route::put('advertisement/homepage-banner-secion-four', [AdvertisementController::class, 'homepageBannerSecionFour'])->name('homepage-banner-secion-four');
Route::put('advertisement/productpage-banner', [AdvertisementController::class, 'productPageBanner'])->name('productpage-banner');
Route::put('advertisement/cartpage-banner', [AdvertisementController::class, 'cartPageBanner'])->name('cartpage-banner');

/** Vendor request routes */
Route::get('vendor-requests', [VendorRequestController::class, 'index'])->name('vendor-requests.index');
Route::get('vendor-requests/{id}/show', [VendorRequestController::class, 'show'])->name('vendor-requests.show');
Route::put('vendor-requests/{id}/change-status', [VendorRequestController::class, 'changeStatus'])->name('vendor-requests.change-status');

/** coustomer list routes */
Route::get('customer', [CustomerListController::class, 'index'])->name('customer.index');
Route::put('customer/status-change', [CustomerListController::class, 'changeStatus'])->name('customer.status-change');
/** coustomer list routes */
Route::get('admin-list', [AdminListController::class, 'index'])->name('admin-list.index');
Route::put('admin-list/status-change', [AdminListController::class, 'changeStatus'])->name('admin-list.status-change');
Route::delete('admin-list/{id}', [AdminListController::class, 'destory'])->name('admin-list.destory');


/** manage user routes */
Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user.index');
Route::post('manage-user', [ManageUserController::class, 'create'])->name('manage-user.create');

Route::get('vendor-list', [VendorListController::class, 'index'])->name('vendor-list.index');
Route::put('vendor-list/status-change', [VendorListController::class, 'changeStatus'])->name('vendor-list.status-change');

Route::get('vendor-condition', [VendorConditionController::class, 'index'])->name('vendor-condition.index');
Route::put('vendor-condition/update', [VendorConditionController::class, 'update'])->name('vendor-condition.update');

/** about routes */
Route::get('about', [AboutController::class, 'index'])->name('about.index');
Route::put('about/update', [AboutController::class, 'update'])->name('about.update');
/** terms and conditons routes */
Route::get('terms-and-conditions', [TermsAndConditionController::class, 'index'])->name('terms-and-conditions.index');
Route::put('terms-and-conditions/update', [TermsAndConditionController::class, 'update'])->name('terms-and-conditions.update');







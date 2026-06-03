<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgotPasswordAdminController;
use App\Http\Controllers\Admin\ResetPasswordAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\OrderApprovalController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC ROUTES ───────────────────────────────────────
Route::get('/', [HomeController::class, 'landing'])->name('public.landing');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('public.katalog');
Route::get('/produk/{slug}', [HomeController::class, 'detail'])->name('public.produk');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('public.tentang');
Route::post('/ulasan', [ReviewController::class, 'store'])->name('public.review.store');



// ── AUTH CUSTOMER (USER PUBLIK) ───────────────────────────
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);

    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);

    // Lupa Password
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('customer.auth');

// ── CART & ORDER (wajib login customer) ───────────────────
Route::middleware('customer.auth')->group(function () {

    // Keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout & Pembayaran
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/pembayaran/{order}', [OrderController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/pembayaran/{order}/upload', [OrderController::class, 'uploadProof'])->name('checkout.upload-proof');

    // Akun User
    Route::get('/akun', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/akun', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::put('/akun/password', [AccountController::class, 'updatePassword'])->name('account.update-password');
    Route::get('/akun/pesanan', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/akun/pesanan/{order}', [AccountController::class, 'orderDetail'])->name('account.order-detail');
    Route::post('/akun/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('account.order.cancel');

});



// ─── ADMIN AUTH ────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // ── Lupa Password ──────────────────────────────────────────────────────
    Route::get('/forgot-password',  [ForgotPasswordAdminController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordAdminController::class, 'sendResetLinkEmail'])->name('password.email');
    // ── Reset Password (link dari email) ───────────────────────────────────
    Route::get('/reset-password/{token}', [ResetPasswordAdminController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordAdminController::class, 'reset'])->name('password.update');

    // Protected admin routes
    Route::middleware(['auth', 'admin.session'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        // Inventaris / Products
        Route::get('/inventaris', [ProductController::class, 'index'])->name('inventaris.index');
        Route::get('/inventaris/tambah', [ProductController::class, 'create'])->name('inventaris.create');
        Route::post('/inventaris', [ProductController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/{product}/edit', [ProductController::class, 'edit'])->name('inventaris.edit');
        Route::put('/inventaris/{product}', [ProductController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{product}', [ProductController::class, 'destroy'])->name('inventaris.destroy');
        Route::get('/inventaris/export', [ProductController::class, 'export'])->name('inventaris.export');


        // Reviews
        Route::get('/review', [AdminReviewController::class, 'index'])->name('review.index');
        Route::post('/review/{review}/approve', [AdminReviewController::class, 'approve'])->name('review.approve');
        Route::post('/review/approve-all', [AdminReviewController::class, 'approveAll'])->name('review.approveAll');
        Route::post('/review/{review}/toggle-landing', [AdminReviewController::class, 'toggleLanding'])->name('review.toggleLanding');
        Route::delete('/review/{review}', [AdminReviewController::class, 'destroy'])->name('review.destroy');

        // Kategori
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy']);

        // Settings
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');
        Route::put('/pengaturan/akun', [SettingController::class, 'updateAkun'])->name('pengaturan.updateAkun');
        Route::put('/pengaturan/password', [SettingController::class, 'updatePassword'])->name('pengaturan.updatePassword');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');

        // Pesanan
        Route::get('/pesanan', [OrderApprovalController::class, 'index'])->name('orders.index');
        Route::post('/pesanan/{order}/approve', [OrderApprovalController::class, 'approve'])->name('orders.approve');
        Route::post('/pesanan/{order}/status', [OrderApprovalController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/pesanan/{order}/bukti', [OrderApprovalController::class, 'viewProof'])->name('orders.view-proof');


    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// Import Controllers ฝั่ง User
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;

// Import Controllers ฝั่ง Admin (ใช้ as เพื่อไม่ให้ซ้ำกับข้างบน)
use App\Http\Controllers\Admin\ProductController as AdminProductController;

// =========================================================
// 1. หน้าทั่วไป (ไม่ต้อง Login)
// =========================================================
Route::get('/', function () {
    return view('home');
})->name('home.view');

Route::get('/products', [ProductController::class, 'showAllProduct'])->name('all.product');
Route::get('/category/{id}/products', [ProductController::class, 'showByCategory'])->name('category.products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// =========================================================
// 2. ระบบยืนยันตัวตน (Login/Register)
// =========================================================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ระบบยืนยัน Email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'ส่งลิงก์ยืนยันอีกครั้งเรียบร้อย!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// =========================================================
// 3. สำหรับ USER (ต้อง Login ก่อน)
// =========================================================
Route::middleware(['auth'])->group(function () {
    
    // โปรไฟล์และการจัดการที่อยู่
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::post('/profile/address/set-default/{id}', [ProfileController::class, 'setDefault'])->name('address.set-default');

    // ตะกร้าสินค้า
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    // การชำระเงิน (Checkout)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    
    // สถานะคำสั่งซื้อและการแจ้งโอน
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/upload-slip', [OrderController::class, 'uploadSlip'])->name('orders.uploadSlip');
});

// =========================================================
// 4. สำหรับ ADMIN (ต้องผ่าน Middleware 'admin')
// =========================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // หน้าหลัก Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // การจัดการคำสั่งซื้อ (ดูรายการ/เปลี่ยนสถานะ)
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::put('/orders/{order}/update', [OrderController::class, 'updateStatus'])->name('orders.update');

    // การจัดการสินค้า (ครบทุก Function: ดู, เพิ่ม, แก้ไข, ลบ)
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

// =========================================================
// ทดสอบระบบ
// =========================================================
Route::get('/test-mail', function () {
    Mail::raw('Test email from Laravel', function ($message) {
        $message->to('mackmo.rss@gmail.com')->subject('Test Mail');
    });
    return "✅ ส่งเมลแล้ว!";
});
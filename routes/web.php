<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (เข้าถึงได้ทุกคน ไม่ต้อง Login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home.view');

// แสดงสินค้า
Route::get('/products', [ProductController::class, 'showAllProduct'])->name('all.product');
Route::get('/category/{id}/products', [ProductController::class, 'showByCategory'])->name('category.products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// ระบบ Authentication
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. User Routes (ต้อง Login เท่านั้น)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // โปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // ตะกร้าสินค้าและการสั่งซื้อ
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{productId}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    });

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

    // จัดการคำสั่งซื้อฝั่ง User
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/{id}/complete', [OrderController::class, 'complete'])->name('complete'); // กดยืนยันรับสินค้า
    });
});


/*
|--------------------------------------------------------------------------
| 3. Admin Routes (ต้อง Login และเป็น Admin เท่านั้น)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // หน้า Dashboard หลัก
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // การจัดการคำสั่งซื้อ (จบในกลุ่มเดียว)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'adminIndex'])->name('index'); // หน้าจัดการ Order
        Route::put('/{order}/update', [OrderController::class, 'updateStatus'])->name('update'); // อัปเดตสถานะ/เลขพัสดุ
    });

    // การจัดการสินค้า
    Route::resource('products', AdminProductController::class);
});


/*
|--------------------------------------------------------------------------
| 4. System Test (สำหรับทดสอบ)
|--------------------------------------------------------------------------
*/
Route::get('/test-mail', function () {
    Mail::raw('Test email from MusicStore', function ($message) {
        $message->to('your-email@gmail.com')->subject('Test Connection');
    });
    return "✅ ระบบส่งอีเมลทำงานปกติ";
});

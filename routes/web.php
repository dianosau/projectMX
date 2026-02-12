<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home.view');

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'showAllProduct')->name('all.product');
    Route::get('/category/{id}/products', 'showByCategory')->name('category.products');
    Route::get('/product/{id}', 'show')->name('product.show');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| 2. User Routes (Middleware: auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // โปรไฟล์และการแก้ไขข้อมูลส่วนตัว (Inline Update)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile/update-info', 'updateInfo')->name('profile.update.info'); // สำหรับปุ่มยืนยันแก้ไขโปรไฟล์
    });

    // จัดการที่อยู่
    Route::name('address.')->controller(ProfileController::class)->group(function () {
        Route::post('/address/store', 'storeAddress')->name('store');
        Route::put('/address/{id}/update', 'updateAddress')->name('update');
        Route::delete('/address/{id}/delete', 'deleteAddress')->name('delete');
        Route::post('/address/{id}/set-default', 'setDefaultAddress')->name('set-default');
    });

    // ระบบตะกร้าสินค้า
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add/{productId}', 'add')->name('add');
        Route::delete('/remove/{id}', 'remove')->name('remove');
    });

    // ระบบชำระเงิน (Checkout)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

    // ระบบติดตามคำสั่งซื้อสำหรับลูกค้า
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{id}/complete', 'complete')->name('complete');
    });
});

/*
|--------------------------------------------------------------------------
| 3. Admin Routes (Middleware: auth, admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ส่วนจัดการสินค้า
    Route::controller(AdminProductController::class)->group(function () {
        Route::get('/products/categories', 'categoryIndex')->name('products.categories');
        Route::get('/category/{id}/products', 'index')->name('products.index');
        Route::post('/products', 'store')->name('products.store');
        Route::put('/products/{id}', 'update')->name('products.update');
        Route::delete('/products/{id}', 'destroy')->name('products.destroy');
    });

    // ส่วนจัดการคำสั่งซื้อ (เพิ่ม Bulk Update สำหรับปุ่มบันทึกทั้งหมด)
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::put('/bulk-update', 'bulkUpdate')->name('bulkUpdate'); // ปุ่มบันทึกทั้งหมด
        Route::put('/{order}/update', 'updateStatus')->name('update'); // บันทึกรายแถว (ถ้ายังมีอยู่)
    });

    // ส่วนจัดการสมาชิก
    Route::prefix('users')->name('users.')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::patch('/{user}/toggle-role', 'toggleRole')->name('toggleRole');
        Route::patch('/{user}/toggle-status', 'toggleStatus')->name('toggleStatus');
    });
});

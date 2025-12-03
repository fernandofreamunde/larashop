<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index']);

Route::get('/product/{product}', [ProductController::class, 'show']);

Route::post('/cart', [CartController::class, 'store']);
Route::patch('/cart/{productId}', [CartController::class, 'update']);
Route::delete('/cart/{productId}', [CartController::class, 'destroy']);

Route::get('/checkout', function () {
    $customer = null;
    $email = session('customer_email');

    if ($email) {
        $customer = \App\Models\Customer::where('email', $email)->first();
    }

    return view('checkout', ['customer' => $customer]);
});

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name(
    'orders.show',
);

Route::get('/login', [App\Http\Controllers\SessionController::class, 'create'])->name('login');
Route::post('/login', [App\Http\Controllers\SessionController::class, 'store']);
Route::delete('/logout', [App\Http\Controllers\SessionController::class, 'destroy'])->name('logout');

Route::get('/register', [App\Http\Controllers\UserController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\UserController::class, 'store']);

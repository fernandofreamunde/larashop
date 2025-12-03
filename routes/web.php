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
    return view('checkout');
});

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name(
    'orders.show',
);

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

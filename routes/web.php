<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get("/", [ProductController::class, "index"]);

Route::get("/product/{id}", function () {
    return view("product");
});

Route::get("/checkout", function () {
    return view("checkout");
});

Route::get("/orders/{id}", function () {
    return view("order-history-detail");
});

Route::get("/orders", function () {
    return view("order-history");
});

Route::get("/login", function () {
    return view("login");
});

Route::get("/register", function () {
    return view("register");
});

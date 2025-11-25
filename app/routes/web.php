<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return redirect()->route('clients.index');
});

Route::resource('products', ProductController::class);

Route::resource('clients', ClientController::class);

Route::resource('orders', OrderController::class);

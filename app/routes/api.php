<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

Route::apiResource('clients', ClientController::class)->names('api.clients');
Route::apiResource('products', ProductController::class)->names('api.products');
Route::apiResource('orders', OrderController::class)->names('api.orders');
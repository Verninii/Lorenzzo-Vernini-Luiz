<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Rotas publicas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('clients.index');
});

Route::middleware('auth')->group(function () {
    Route::delete('clients/bulk-destroy', [ClientController::class, 'bulkDestroy'])
    ->name('clients.bulk-destroy');
    Route::resource('clients', ClientController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
});

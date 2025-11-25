<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return redirect()->route('clients.index');
});

Route::resource('clients', ClientController::class);

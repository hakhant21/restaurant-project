<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\ShowController;
use App\Http\Controllers\Orders\IndexController;
// u

Route::middleware(['auth', 'role:admin|vendor'])->group(function () {
    Route::get('/orders', IndexController::class)->name('orders.index');
    Route::get('/orders/details/{order}', ShowController::class)->name('orders.show');
});


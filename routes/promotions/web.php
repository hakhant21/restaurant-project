<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Promotions\EditController;
use App\Http\Controllers\Promotions\IndexController;
use App\Http\Controllers\Promotions\StoreController;
use App\Http\Controllers\Promotions\CreateController;
use App\Http\Controllers\Promotions\DeleteController;
use App\Http\Controllers\Promotions\UpdateController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/promotions', IndexController::class)->name('promotions.index');
    Route::get('/promotions/create', CreateController::class)->name('promotions.create');
    Route::post('promotions/store', StoreController::class)->name('promotions.store');
    Route::get('/promotions/{promotion}/edit', EditController::class)->name('promotions.edit');
    Route::post('promotions/{promotion}/update', UpdateController::class)->name('promotions.update');
    Route::delete('promotions/{promotion}/delete', DeleteController::class)->name('promotions.delete');
});

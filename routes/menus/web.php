<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Menus\IndexController;
use App\Http\Controllers\Menus\StoreController;
use App\Http\Controllers\Menus\CreateController;
use App\Http\Controllers\Menus\DeleteController;
use App\Http\Controllers\Menus\EditController;
use App\Http\Controllers\Menus\UpdateController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/menus', IndexController::class)->name('menus.index');
    Route::get('/menus/create', CreateController::class)->name('menus.create');
    Route::post('menus/store', StoreController::class)->name('menus.store');
    Route::get('/menus/{menu}/edit', EditController::class)->name('menus.edit');
    Route::post('menus/{menu}/update', UpdateController::class)->name('menus.update');
    Route::delete('menus/{menu}/delete', DeleteController::class)->name('menus.delete');
});


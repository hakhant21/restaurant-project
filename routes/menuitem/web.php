<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItems\IndexController;
use App\Http\Controllers\MenuItems\StoreController;
use App\Http\Controllers\MenuItems\CreateController;
use App\Http\Controllers\MenuItems\DeleteController;
use App\Http\Controllers\MenuItems\EditController;
use App\Http\Controllers\MenuItems\UpdateController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/menu_items', IndexController::class)->name('menu_items.index');
    Route::get('/menu_items/create', CreateController::class)->name('menu_items.create');
    Route::post('menu_items/store', StoreController::class)->name('menu_items.store');
    Route::get('/menu_items/{item}/edit', EditController::class)->name('menu_items.edit');
    Route::post('menu_items/{item}/update', UpdateController::class)->name('menu_items.update');
    Route::delete('menu_items/{item}/delete', DeleteController::class)->name('menu_items.delete');
});


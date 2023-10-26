<?php

use App\Http\Controllers\Carts\CartActionController;
use App\Http\Controllers\Orders\CreateController;
use Illuminate\Support\Facades\Route;
// u

Route::middleware(['auth', 'role:admin|vendor'])->group(function () {
     Route::get('/home_menu', CartActionController::class)->name('home_menu');
    Route::get('/item-add-to-cart/{id}', [CartActionController::class, 'item_add_to_cart'])->name('item_add_to_cart');
    Route::delete('/item-remove/{id}', [CartActionController::class, 'item_remove_from_cart'])->name('item_remove_from_cart');
    Route::get('/item-quantity-increment/{id}', [CartActionController::class, 'item_quantity_increment'])->name('item_quantity_increment');
    Route::get('/item-quantity-decrement/{id}', [CartActionController::class, 'item_quantity_decrement'])->name('item_quantity_decrement');
    Route::post('/item/order/{order_number}', [CartActionController::class, 'cart_order_confirm'])->name('cart_order_confirm');
    Route::get('/print', [CartActionController::class, 'printTest'])->name('print');
    Route::post('clear/carts', [CartActionController::class, 'clearCart'])->name('clear_carts');
});

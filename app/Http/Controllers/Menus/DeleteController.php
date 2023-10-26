<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carts\Cart;
use App\Models\MenuItem;

class DeleteController extends Controller
{
    public function __invoke(Request $request, Menu $menu)
    {
        $menu_items = MenuItem::where('menu_id', $menu->id)->get();

        foreach($menu_items as $menu_item) {
            $menu_item->delete();
        }

        $cart = Cart::query()->delete();
        $menu->delete();

        return to_route('menus.index');
    }
}

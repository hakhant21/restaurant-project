<?php

namespace App\Http\Controllers\MenuItems;

use App\Models\Menu;
use Inertia\Inertia;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    public function __invoke(Request $request, MenuItem $item)
    {
        $menus = Menu::select('id', 'name')->get();

        return Inertia::render('MenuItems/Edit', [
            'menus' => $menus,
            'menuItem' => $item
        ]);
    }
}

<?php

namespace App\Http\Controllers\MenuItems;

use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        $menus = Menu::select('id', 'name')->get();

        return Inertia::render('MenuItems/Create', [
            'menus' => $menus
        ]);
    }
}

<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class EditController extends Controller
{
    public function __invoke(Request $request, Menu $menu)
    {
        return Inertia::render('Menus/Edit', [
            'menu' => $menu
        ]);
    }
}

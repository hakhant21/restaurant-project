<?php

namespace App\Http\Controllers\MenuItems;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteController extends Controller
{
    public function __invoke(Request $request, MenuItem $item)
    {
        $item->delete();

        return to_route('menu_items.index');
    }
}

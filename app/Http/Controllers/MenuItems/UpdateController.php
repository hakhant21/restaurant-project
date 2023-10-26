<?php

namespace App\Http\Controllers\MenuItems;

use App\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    public function __invoke(Request $request, MenuItem $item)
    {
        $menu_item_url = null;

        if($request->file('image')) {
            $menu_item_url = ImageService::singleUpload($request->image, 'menus-' . Str::slug($request->name), 'public');
        }

        $item->update([
            'menu_id' => $request['menu_id'],
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image' => $menu_item_url ?? $item->image,
            'discount'=> 0
        ]);

        return to_route('menu_items.index');
    }
}

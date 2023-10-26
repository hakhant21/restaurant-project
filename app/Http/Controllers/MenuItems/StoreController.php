<?php

namespace App\Http\Controllers\MenuItems;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use App\Services\ImageService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $menu_item_url = null;

        if($request->file('image')) {
            $menu_item_url = ImageService::singleUpload($request->image, 'item-' . Str::slug($request->name), 'public');
        }

        MenuItem::create([
            'menu_id' => $request->menu_id,
            'name' => $request->name,
            'price' => $request->price,
            'description'=> $request->description,
            'image' => $menu_item_url,
        ]);

        return to_route('menu_items.index');
    }
}

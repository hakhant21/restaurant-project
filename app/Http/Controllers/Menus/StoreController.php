<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $menu_url = null;

        if($request->file('image')) {
            $menu_url = ImageService::singleUpload($request->image, 'menus-' . Str::slug($request->name), 'public');
        }

        Menu::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $menu_url,
        ]);

        return to_route('menus.index');
    }
}

<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Menu $menu)
    {
        $menu_url = null;

        if($request->file('image')) {
            $menu_url = ImageService::singleUpload($request->image, 'menus-' . Str::random(10), 'public');
        }

        $menu->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $menu_url ?? $menu->image,
        ]);

        return to_route('menus.index');
    }
}

<?php

namespace App\Http\Controllers\Menus;

use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Menus\MenuResource;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Menu::query();
        if( $request->has("filtermenu") ) {   
            $query->where("name", 'like','%'.$request->filtermenu.'%');
        }
        $data = $query->paginate(10)->withQueryString();
        
        $menus = MenuResource::collection($data);
       
       
        return Inertia::render('Menus/Index', [
            'menus' => $menus,
            'filters' => $request->only('filtermenu'),
        ]);
    }
}

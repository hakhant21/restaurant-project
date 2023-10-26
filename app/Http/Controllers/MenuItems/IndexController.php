<?php

namespace App\Http\Controllers\MenuItems;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuItems\MenuItemResource;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {  
        $query = MenuItem::query();
        if( $request->has("filtername") ) {   
            $query->where("name", 'like','%'.$request->filtername.'%');
        }
        
        $data = $query->with('menu')->whereHas('menu',function($query) use($request){
            if($request->has('filtermenu')){
                $query->where('name','like','%'.$request->filtermenu.'%');
            }
        })->paginate(10)->withQueryString();
        $menu_items = MenuItemResource::collection($data);
        return Inertia::render('MenuItems/Index', [
            'menu_items' => $menu_items,
            'filters' => $request->only('filtermenu','filtername'),
        ]);
    }
}

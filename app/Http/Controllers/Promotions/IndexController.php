<?php

namespace App\Http\Controllers\Promotions;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Promotions\PromotionResource;
use App\Models\Promotions\Promotion;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request->all());
        $data = Promotion::when($request->filterPromotion, function($query,$filterPromotion){
            $query->where('name', 'like', '%'.$filterPromotion.'%');
        })->paginate(10)->withQueryString();

        $promotions = PromotionResource::collection($data);

        return Inertia::render('Promotions/Index', [
            'promotions' => $promotions,
            'filters' => $request->only('filterPromotion')
        ]);
    }
}

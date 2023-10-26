<?php

namespace App\Http\Controllers\Promotions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promotions\Promotion;

class DeleteController extends Controller
{
    public function __invoke(Request $request, Promotion $promotion)
    {
        $promotion->delete();

        return to_route('promotions.index');
    }
}

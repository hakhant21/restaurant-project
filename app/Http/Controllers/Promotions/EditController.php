<?php

namespace App\Http\Controllers\Promotions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promotions\Promotion;
use Inertia\Inertia;

class EditController extends Controller
{
    public function __invoke(Request $request, Promotion $promotion)
    {
        return Inertia::render('Promotions/Edit', [
            'promotion' => $promotion
        ]);
    }
}

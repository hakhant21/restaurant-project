<?php

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Promotions/Create');
    }
}

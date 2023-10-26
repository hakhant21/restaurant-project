<?php

namespace App\Http\Controllers\Orders;

use App\Models\Orders\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderDetailResource;
use App\Models\Orders\OrderDetail;
use Inertia\Inertia;

class ShowController extends Controller
{
    public function __invoke(Request $request, Order $order)
    {
        $data = OrderDetail::with('menuItem')
                    ->where('order_id', $order->id)
                    ->paginate(10)
                    ->withQueryString();

        $details = OrderDetailResource::collection($data);

        return Inertia::render('Orders/Show', [
            'details' => $details
        ]);
    }
}

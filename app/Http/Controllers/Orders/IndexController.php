<?php

namespace App\Http\Controllers\Orders;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Orders\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Order::query();
        if ($request->has("orderDate") && $request->orderDate!=null) {
            $query->whereBetween('order_date', DateHelper::getDateRange($request->orderDate));
        }
        if( $request->has("orderNumber") ) {
            $query->where("order_number",'like','%'.$request->orderNumber.'%');
        }

        if($request->has('paymentType')) {
            $query->where('payment_type', 'like', '%'.$request->paymentType.'%');
        }

        $total_amount = $query->sum('total_amount');

        $data = $query->paginate(10)->withQueryString();

        $orders = OrderResource::collection($data);
        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'total_amount' => $total_amount,
            'filters' => $request->only('orderNumber','orderDate', 'paymentType'),
        ]);
    }
}

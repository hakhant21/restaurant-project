<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Carts\Cart;
use App\Models\Orders\Order;
use App\Models\Orders\OrderDetail;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(Request $request,$order_number){
        $carts = Cart::where("order_number", $order_number)->get();
        $amount = $carts->sum("total_amount");
        $order = Order::create([
            "order_number"=> $order_number,
            "total_amount"=> $amount,
            "order_date"=> now(),
        ]);
        if($order){ 
            foreach($carts as $cart){
                $order_detail = OrderDetail::create([
                    "order_id"=> $order->id,
                    "menu_item_id"=> $cart->menu_item_id,
                    "quantity"=> $cart->quantity,
                    "unit_price"=> $cart->price,
                    "total_price" => $cart->total_amount,
                ]);
                $cart->delete();
            }
        }
        return to_route('home_menu');
    }
}

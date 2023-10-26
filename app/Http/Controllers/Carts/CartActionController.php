<?php

namespace App\Http\Controllers\Carts;

use App\Http\Controllers\Controller;
use App\Models\Carts\Cart;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Orders\Order;
use App\Models\Orders\OrderDetail;
use App\Models\Promotions\Promotion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;

class CartActionController extends Controller
{
    public function __invoke(Request $request)
    {
       $menu_item_query = MenuItem::query();

        if( $request->filtermenu != 'All' && $request->has("filtermenu")) {
            $menu_id = Menu::where('slug', $request->filtermenu)->first()->id;
            $menu_item_query->where("menu_id",$menu_id);
        }
        if( $request->filtername){
            $menu_item_query->where("name","like","%". $request->filtername ."%");
        }
        $menu_items = $menu_item_query->with('menu')->orderBy('menu_id')->get();
        $menus = Menu::all();
        $order = new Order();
        $order_number =  $order->generateInvoiceNumber();
        $cart_items = Cart::where("order_number", $order_number)->orderBy('id')->get();
        if( !is_null($cart_items)){
            $remove_carts = Cart::where("order_number",'!=', $order_number)->orderBy('id')->get();
            foreach($remove_carts as $remove_cart){
                $remove_cart->delete();
            }
        }
        $total_amount = $cart_items->sum('total_amount');
        $currentDate = Carbon::now();
        $eventPromotions = Promotion::where('start_date', '<=', $currentDate)->where('status','active')
        ->where('end_date', '>=', $currentDate)->where('type','event')
        ->first();
        $normalPromotions = Promotion::where('type','promotion')->where('status','active')->first();
        $promotion_amount = 0;
        if($eventPromotions){
            $promotion_amount = $total_amount*($eventPromotions->discount_percentage/100);
        }else{
            if($normalPromotions){
            if($total_amount>=$normalPromotions->base_amount){
                    $promotion_amount = $total_amount*($normalPromotions->discount_percentage/100);
                }
            }
        }
        $total_discount_amount = 0;
        if(!$eventPromotions && !$normalPromotions ){
            foreach ($cart_items as $item)
            {
                $total_discount_amount += $item->total_amount*($item->menuItems->discount/100);
            }
        }
        return Inertia::render('HomeMenu', [
            'menu_items' => $menu_items,
            'order_number'=> $order_number,
            'cart_items' => $cart_items,
            'total_amount'=> $total_amount,
            'menus' => $menus,
            'promotion_amount'=>$promotion_amount,
            'total_discount_amount'=>$total_discount_amount,
            'filters' => $request->only('filtermenu','filteritem','filtername'),
        ]);

    }

    public function item_quantity_increment($id)
    {
        $data= Cart::find($id);
        $data->quantity = $data->quantity + 1;
        $data->total_amount = $data->quantity * $data->price;
        $update = $data->update();
        return to_route('home_menu');
    }
    public function item_quantity_decrement($id)
    {
        $data= Cart::find($id);
        $data->quantity = $data->quantity - 1;
        $data->total_amount = $data->quantity * $data->price;
        $update = $data->update();
        if($data->quantity < 1)
        {
            $data->delete();
        }
        return to_route('home_menu');
    }

    public function item_add_to_cart($id)
    {
        TODO://Image add for menu item
        $item = MenuItem::find($id);
        $data= Cart::where('menu_item_id',$id)->first();
        if($data != null)
        {
            $data->quantity = $data->quantity + 1;
            $data->total_amount = $data->quantity * $data->price;
            $update = $data->update();
        }
        else
        {
            $data = new Cart();
            $order = new Order();
            $order_number =  $order->generateInvoiceNumber();
            $data->menu_item_id = $item->id;
            $data->item_name = $item->name;
            $data->order_number = $order_number;
            $data->price = $item->price;
            $data->quantity = 1;
            $data->total_amount = $data->price * $data->quantity;
            $save = $data->save();
        }
        return to_route('home_menu');
    }
    public function show_item_cart()
    {
        return to_route('home_menu');
    }
    public function item_remove_from_cart($id)
    {
        $data= Cart::find($id);
        $delete = $data->delete();
        return to_route('home_menu');
    }

    // public function menu_items_search(Request $request)
	// {
    //     $query = $request->get('query');
	// 	if($request->get('query') == null)
	// 	{
    //         $allData=MenuItem::latest()->get();
	// 	}
	// 	else
	// 	{
    //         $allData=MenuItem::where('name', 'LIKE', "%{$query}%")->latest()->get();
	// 	}
    //     TODO::to_route("cart UI");
    //     // return to_route('menus.index');
	// }
    public function cart_order_confirm(Request $request)
    {
        // $printer = new ReceiptController();

        $order = new Order();
        $order_number = $order->generateInvoiceNumber();
        $mid = '123123456';
        $store_name = 'EM-9';
        $store_address = 'Bo Ta Yar Rd, Pyinmana';
        $store_phone = '09-448538778';
        $store_email = 'Korean Food, Dessert & Coffee';
        $store_website = 'yourmart.com';
        $tax_percentage = 0;
        $promotion = 0;
        $total_discount_amount = 0;
        $service_charges = 0;


        $currency = 'MMK';
        $image_path = 'logo.png';
        $items = Cart::get();
        $total_amount = $items->sum('total_amount');
        $order_date = date('Y-m-d');
        $data = [];

                if($request->includeTax==true){
                    $tax_percentage = $request->taxAmount;
                }
                if($request->includeServiceCharge==true){
                    $service_charges =$request->serviceChargeRef;
                }

                $currentDate = Carbon::now();
                $eventPromotions = Promotion::where('start_date', '<=', $currentDate)
                ->where('end_date', '>=', $currentDate)->where('type','event')->where('status','active')
                ->first();
                $normalPromotions = Promotion::where('type','promotion')->where('status','active')->first();
                if( $eventPromotions){
                    $promotion = $eventPromotions->discount_percentage;
                }else{
                    if($normalPromotions){
                    if($total_amount>=$normalPromotions->base_amount ){
                            $normalPromotions = Promotion::where('type','promotion')->first();
                            $promotion = $normalPromotions->discount_percentage;
                        }
                    }
                }

                if(!$eventPromotions && !$normalPromotions){
                    foreach ($items as $item)
                    {
                        $total_discount_amount += $item->total_amount*($item->menuItems->discount/100);
                    }
                }
                // Init printer
                $printer = new ReceiptController();

                $printer->init(
                    config('receiptprinter.connector_type'),
                    config('receiptprinter.connector_descriptor')
                );

                // Set store info
                $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);
                $printer->setPromotionDiscount($promotion);
                $printer->setTotalDiscount($request->total_discount_amount);
                $printer->setCalculateTotal($total_amount);
                $printer->setOrderNumber($order_number);
                $printer->setCalculateTax($tax_percentage);
                $printer->setServiceCharge($service_charges);
                $printer->setCurrency($currency);
                $printer->setPaidBy($request->paid_by);
                $printer->setChanged($request->changed);
                $printer->setCalculateSubTotal($request->total_amount);
                // Add items
                foreach ($items as $item) {
                    $printer->addItem(
                        $item->item_name,
                        $item->quantity,
                        $item->price
                    );
                }
                $printer->printReceipt();

            $data = [
                'tax' => $request->taxAmount,
                'service_charges' => $request->serviceChargeRef,
                'promotion' => $promotion,
                'discount' => $total_discount_amount,
                'grand_total' => $total_amount,
                'total_amount' => $request->total_amount
            ];
            $this->saveOrder($order_number, $request->payment_type, $data);
    }

    public function saveOrder($order_number, $payment_type, $data){

        $carts = Cart::where("order_number", $order_number)->get();

        $order = Order::create([
            "order_number"=> $order_number,
            "total_amount"=> $data['total_amount'],
            "order_date"=> now(),
            "payment_type" => $payment_type == false ? 'cash' : 'kbzpay',
            'tax' => $data['tax'] ?? 0,
            'service_charges' => $data['service_charges'] ?? 0,
            'promotion' => $data['promotion'] ?? 0,
            'discount' => $data['discount'] ?? 0,
            'grand_total' => $data['grand_total'] ?? 0
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
    }

    public function clearCart(Request $request)
    {
        Cart::query()->delete();

        return to_route('home_menu');
    }

}

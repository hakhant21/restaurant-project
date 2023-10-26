<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Orders\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $total_order = Order::count();
        $daily_order = Order::whereDate('order_date',now())->count();
        $total_amount = Order::sum("total_amount");
        $daily_total_amount = Order::whereDate('order_date',now())->sum("total_amount");
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $monthly_total_order = Order::whereBetween('order_date', [$startDate, $endDate])->count();
        $monthlyTotalAmount = Order::whereBetween('order_date', [$startDate, $endDate])->sum('total_amount');
        $now = Carbon::now();
        $currentMonth = $now->format('Y-m');
        $previousMonth = $now->subMonth()->format('Y-m');
        $top_menu_items = MenuItem::select('menu_items.id', 'menu_items.name', 'menu_items.price', DB::raw('SUM(order_details.quantity) as total_quantity'))
        ->join('order_details', 'menu_items.id', '=', 'order_details.menu_item_id')
        ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.price')
        ->orderBy('total_quantity', 'desc')
        ->take(5) 
        ->get();
       
        $currentMonthOrders = Order::whereBetween('created_at', [
            "{$currentMonth}-01 00:00:00",
            "{$currentMonth}-31 23:59:59"
        ])->count();
        
        $previousMonthOrders = Order::whereBetween('created_at', [
            "{$previousMonth}-01 00:00:00",
            "{$previousMonth}-31 23:59:59"
        ])->count();
        
        if ($previousMonthOrders == 0) {
            $percentageChange = $currentMonthOrders > 0 ? 100 : 0;
        } else {
            $percentageChange = (($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100;
        }
        $change = '';
        if($previousMonthOrders > $currentMonthOrders){
            $change = '-';
        }else{
            $change = '+';
        }
        $percentage = round($percentageChange, 2) . "%";
        $data = [
            'total_order'=> $total_order,
            'percentage'=> $percentage,
            'change'=> $change,
            'total_amount'=>$total_amount,
            'daily_total_amount'=> $daily_total_amount,
            'monthlyTotalAmount'=> $monthlyTotalAmount,
            'monthly_total_order'=>$monthly_total_order,
            'daily_order'=> $daily_order,
            'top_menu_items'=> $top_menu_items
        ];


        return Inertia::render('Dashboard',[
           'data' => $data
        ]);

    } 
}

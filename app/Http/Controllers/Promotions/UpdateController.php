<?php

namespace App\Http\Controllers\Promotions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promotions\Promotion;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Promotion $promotion)
    {
        $start_date = Carbon::parse($request->promotionDate[0])->toDateTimeString();
        $end_date = Carbon::parse($request->promotionDate[1])->toDateTimeString();

        $allPromotions = Promotion::where('type', $request->type)->where('status', 'active')->get();

        foreach($allPromotions as $allPromotion) {
             $allPromotion->update(['status' => 'inactive']);
        }

        $promotion->update([
            'name' => $request->name,
            'discount_percentage' => $request->discountPercentage,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'type' => $request->type,
            'status' => $request->status,
            'base_amount' => $request->base_amount ?? 0
        ]);


        return to_route('promotions.index');
    }
}

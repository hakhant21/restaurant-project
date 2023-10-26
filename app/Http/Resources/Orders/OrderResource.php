<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total_amount' => $this->total_amount,
            'order_date' => $this->order_date,
            'payment_type' => ucfirst($this->payment_type),
            'tax' => $this->tax,
            'service_charges' => $this->service_charges,
            'promotion' => $this->promotion,
            'grand_total' => $this->grand_total,
            'discount' => $this->discount
        ];
    }
}

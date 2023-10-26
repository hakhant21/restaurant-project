<?php

namespace App\Http\Resources\Promotions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount_percentage' => $this->discount_percentage,
            'start_date' => date('m/d/Y', strtotime($this->start_date)),
            'end_date' => date('m/d/Y', strtotime($this->end_date)),
            'type'=>$this->type,
            'status'=>$this->status
        ];
    }
}

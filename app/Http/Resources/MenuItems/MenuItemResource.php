<?php

namespace App\Http\Resources\MenuItems;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
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
            'menu_name' => $this->menu->name,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image,
            'discount' => $this->discount
        ];
    }
}

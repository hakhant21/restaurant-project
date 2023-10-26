<?php

namespace App\Models\Carts;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public function menuItems()
    {
        return $this->belongsTo(MenuItem::class,'menu_item_id');
    }
}

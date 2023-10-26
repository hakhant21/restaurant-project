<?php

namespace App\Models;

use App\Models\Carts\Cart;
use App\Models\Orders\OrderDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['menu_id','name', 'price', 'description', 'image', 'discount'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'menu_item_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class,'menu_item_id');
    }
}

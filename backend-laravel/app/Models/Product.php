<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;


class Product extends Model
{
    protected $fillable = [
        'title',
        'price',
        'description',
        'category',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(){
    return $this->hasMany(OrderItem::class);
    }
}
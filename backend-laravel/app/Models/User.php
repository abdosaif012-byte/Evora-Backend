<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;


class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dob',
        'address',
        'profile_image',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel auto-hashes on set
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    //  the order is only for one user
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
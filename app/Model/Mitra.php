<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mitra extends Authenticatable
{
    use Notifiable;

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function wallet()
    {
        return $this->hasMany(SellerWallet::class, 'seller_id');
    }
}

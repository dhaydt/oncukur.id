<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

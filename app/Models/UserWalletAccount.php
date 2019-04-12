<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletAccount extends Model
{
    protected $table = 'user_wallet_account';
    public function user(){
        return $this->belongsTo(User::class,'uid');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletAccount extends Model
{
    protected $table = 'user_wallet_account';
    protected $guarded = ['user_id'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

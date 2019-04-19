<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRec extends Model
{
   protected  $table = 'running_account';

   public function user(){
       return $this->belongsTo(User::class,'user_id');
   }

    public function toUser(){
        return $this->belongsTo(User::class,'to_uid');
    }
}

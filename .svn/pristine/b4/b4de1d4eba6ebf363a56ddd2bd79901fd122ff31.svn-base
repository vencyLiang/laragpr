<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $table = 'system_config';
    protected $guarded = [];
    public function  setCurrencyAttribute($value){
        if($value === 'eth'){
            return 1;
        }elseif($value === 'usdt'){
            return 0;
        }
    }

    public function  getCurrencyAttribute($value){
        if($value === 1){
            return 'eth';
        }elseif($value === 0){
            return 'usdt';
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function hasBeenReadByUsers(){
        return $this->belongsToMany(User::class,'have_read_relation');
    }
}

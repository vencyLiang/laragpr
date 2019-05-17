<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function hasBeenReadByUsers(){
        return $this->belongsToMany(User::class,'have_read_relation');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
}

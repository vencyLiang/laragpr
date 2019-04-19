<?php
use Illuminate\Support\Facades\DB;
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
 */
function play_config(){
    return DB::find(1);
}
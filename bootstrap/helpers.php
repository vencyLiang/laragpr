<?php
use Illuminate\Support\Facades\DB;
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
 */
function system_config(){
    return DB::table('system_config')->where('id',1)->first();
}
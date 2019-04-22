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
    return DB::table('system_config')->find(1);
}

/**生成邀请码；
 * @param $user_id
 * @return string
 */
function generate_invite_code($user_id) {
    static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';
    $num = $user_id;
    $code = '';
    while ( $num > 0) {
        $mod = $num % 35;
        $num = ($num - $mod) / 35;
        $code = $source_string[$mod].$code;
    }
    if(empty($code[3]))
        $code = str_pad($code,4,'0',STR_PAD_LEFT);
    return $code;
}
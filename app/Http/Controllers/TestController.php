<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;
use DB;

class TestController extends Controller
{
    function  test(){
        dd(DB::table('running_account')->where([['is_confirmed', '=', 0 ],['coin_type','=', 1], ['transfer_type', '<>', 3],['fail_type','=',NULL]])->get()->toArray());
    }
}

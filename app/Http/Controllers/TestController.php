<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;
use DB;
use App\Models\UserWalletAccount;
use App\Models\User;
use App\Extras\Task\Bonus;
use App\Extras\Task\Activate;

class TestController extends Controller
{
    function test()
    {
        $check = DB::table('user_wallet_account')->where([['eth_address','<>',NULL]])->toSql();

        dd( $check);
        //dd(get_object_vars(0));
    }

    function testBonus(User $user, $userPath = ""){
        dd(Bonus::generate_bonus($user,$userPath));
    }

    function testChildren(User $user){
        dd($user->get_all_children());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;
use DB;
use App\Models\UserWalletAccount;
use App\Models\User;
use App\Extras\Task\Activate;

class TestController extends Controller
{
    function test()
    {
      //dd( User::where('id',1)->first()->toArray());
        dd(get_object_vars(0));
    }
}

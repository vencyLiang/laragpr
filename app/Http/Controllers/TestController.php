<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;

class TestController extends Controller
{
    function  test(){
        dd(UserRelation::all());
    }
}

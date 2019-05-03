<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29
 * Time: 19:43
 */

namespace App\Http\Validator;
use Illuminate\Contracts\Validation\ImplicitRule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithDrawPasswordValidator implements ImplicitRule
{
    public function passes($attribute,$value)
    {
        return Hash::make($value) === User::find(Auth::id())->with_draw_password;
    }

    public function message()
    {
        return '提现密码不正确';
    }
}
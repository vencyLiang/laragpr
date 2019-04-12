<?php
/**
 * Created by PhpStorm.
 * Author: vency
 * Date: 2018/10/27
 * Time: 22:21
 */
namespace App\Http\Validator;
use Illuminate\Contracts\Validation\ImplicitRule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class BonusValidationRule implements ImplicitRule
{
    public function passes($attribute,$value)
    {
        return (float)$value <= User::find(Auth::id())->bonus;
    }

    public function message()
    {
        return ':attribute 余额不足';
    }
}
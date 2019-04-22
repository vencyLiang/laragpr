<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        return collect(['name', 'email', 'phone_num'])->contains(function ($value) use ($request) {
            $account = $request->get($this->username());
            $password = $request->get('password');
            return $this->guard()->attempt([$value => $account, 'password' => $password], $request->filled('remember'));
        });
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            //'captcha' => 'required|captcha',
        ],[
            //'captcha.required' => ':attribute 不能为空',
           // 'captcha.captcha' => '请输入正确的 :attribute',
        ],[
            $this->username() => '账号',
            //'captcha' => '验证码',
        ]);
    }

    public function username()
    {
        return 'account';
    }
}

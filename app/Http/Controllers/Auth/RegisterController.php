<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required_without_all:email,phone_num|nullable|max:255|unique:users',
            'email' => 'required_without_all:name,phone_num|nullable|email|max:255|unique:users',
            'phone_num'=>['required_without_all:name,email','nullable','regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/','unique:users'],
            'password' => 'required|string|min:6|confirmed',
            'up_invite_code' =>  'required|string'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data){
        $data['password'] = bcrypt($data['password']);
        unset($data['password_confirmation']);
        $user =  User::create($data);
        $user->unique = generate_invite_code($user->id);
        $user->account()->create([]);
        $user->save();
        return $user;
    }

    protected function showRegistrationForm(){
        $up_invite_code = NULL;
        if (isset($_GET['up_invite_code'])){
            $up_invite_code = $_GET['up_invite_code'];
        }
        return view('auth.register',compact(['up_invite_code']));
    }

}

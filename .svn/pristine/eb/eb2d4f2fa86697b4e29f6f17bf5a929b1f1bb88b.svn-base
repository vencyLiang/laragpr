<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Extras\Task\SystemConfig;

class UserController extends Controller{
    use SystemConfig;
    private $user;
    public function __construct()
    {
        $this->middleware('auth',['except'=>'ajax_check_invite_code']);
        //获取当前登录用户
        $this->user = User::find(Auth::id());
        self::getConfig();
    }

    protected function ajax_check_invite_code(Request $request){
        $upInviteCode = $request->input('up_invite_code');
        if($upInviteCode == ""){
            return 2;
        }
        $res = User::Where('invite_code',$upInviteCode)->first();
        if($res || ($upInviteCode === PLATFORM_WALLET_ADDRESS) ){
            return 1;
        }else{
            return 0;
        }
    }

    public function bind_user_wallet_address(UserRequest $request){
        //方法1：$this->user->update($request->all());
        $validateData = $request->validated();
        $this->user->user_wallet_address = $validateData['user_wallet_address'];
        $this->user->withdraw_password = Hash::make($validateData['set_withdraw_password']);
        $res = $this->user->save();
        if($res){
            return ['message' => '绑定提现钱包成功','status' => 200];
        }else{
            //系统出错
            return ['message' => '绑定提现钱包失败','status' => 500];
        }
    }

    public function  modify_withdraw_password(UserRequest $request){
        $validateData = $request->validated();
        $withdraw_password = $validateData['update_withdraw_password'];
        if($withdraw_password){
            $this->user->withdraw_password = $withdraw_password;
            $res = $this->user->save();
            if($res){
                //修改提现密码成功
                return ['message' => '修改提现密码成功','status' => 200];
            }else{
                //系统出错
                return ['message' => '修改提现密码成功','status' => 500];
            }
        }else{
            //提现密码并未改变；
            return ['message' => '没有修改提现密码','status' => 200];
        }
    }


    public function show(User $user){
            return 1;

    }


    public function edit(User $user){



    }

    public function  update(UserRequest $request, User $user){



    }




}

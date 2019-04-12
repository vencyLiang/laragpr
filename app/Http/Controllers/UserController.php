<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Ethwallet\EthRpcMethod;

class UserController extends Controller{
    private $user;
    public function __construct()
    {
        $this->middleware('auth',['except'=> ['show']]);
        //获取当前登录用户
        $this->user = User::find(Auth::id());
    }

    /**功能：实现购买激活码后的逻辑。
     * @return string
     */
    public function buy_invite_code()
    {
        $activeStatus = session('activation_status');
        if(!$activeStatus){
            $eth = new EthRpcMethod();
            $userBalance =$eth->eth_getBalance($this->user->platform_wallet_address);
            if($userBalance >= ACTIVATE_COST){
                $this->user->activation_status = '1';
                $this->user->save();
                $activeStatus = '1';
                session(['activation_status',$activeStatus]);
                $activation = new ActivateController();
                $activateRes = $activation->activate($this->user);
                $bonus = new BonusController();
                $bonus->generate_bonus($this->user);
                if($activateRes){
                    return json_encode(['status'=>200, 'message'=>"已经成功购买，无需再次购买"]);
                }else{
                    return json_encode(['status'=>302, 'message'=>"已满足购买条件，但由于系统故障，未能购买成功，请及时联系客服！"]);
                }
            }else{
                return json_encode(['status'=>302, 'message'=>"请转入足够的以太坊！"]);
            }
        }else{
            return json_encode(['status'=>200, 'message'=>"已经成功购买，无需再次购买"]);
        }
    }

    public function bind_user_wallet_address(UserRequest $request){
        //方法1：$this->user->update($request->all());
        $validateData = $request->validated();
        $this->user->user_wallet_address = $validateData['user_wallet_address'];
        $this->user->withdraw_password = Hash::make($validateData['set_withdraw_password']);
        $res = $this->user->save();
        if($res){
            return '1';
        }else{
            //系统出错
            return '2';
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
                return '1';
            }else{
                //系统出错
                return '2';
            }
        }else{
            //提现密码并未改变；
            return '0';
        }
    }


    public function show(User $user){


    }


    public function edit(User $user){



    }

    public function  update(UserRequest $request, User $user){



    }




}

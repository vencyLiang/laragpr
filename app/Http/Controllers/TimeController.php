<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Ethwallet\EthRpcMethod;

class TimeController extends Controller
{
    public function check_activation_status()
    {
        $unactivatedUserCollection = User::where('activate_status', '0')->get();
        foreach ($unactivatedUserCollection as $user) {
            $activeStatus = $user->platform_wallet_address;
            if (!$activeStatus) {
                $eth = new EthRpcMethod();
                $userBalance = $eth->eth_getBalance($this->user->platform_wallet_address);
                if ($userBalance >= ACTIVATE_COST) {
                    $user->activation_status = '1';
                    $user->save();
                    $activeStatus = '1';
                    session(['activation_status', $activeStatus]);
                    $activation = new ActivateController();
                    $activation->activate($this->user);
                    $bonus = new BonusController();
                    $bonus->generate_bonus($this->user);
                }
            }
        }
    }


    public function is_bonus_arrived(){

    }

}

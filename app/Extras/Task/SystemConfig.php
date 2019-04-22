<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/22
 * Time: 12:23
 */

namespace App\Extras\Task;


trait SystemConfig
{
    protected static function  getConfig(){
        $config = play_config();
        define("PLATFORM_WALLET_ADDRESS",$config->platform_invite_code);
        define("DIRECT_BONUS_RATIO",$config->direct_bonus_ratio);
        define("LEVEL_BONUS_RATIO" ,$config->level_bonus_ratio);
        define("ACTIVATE_COST",$config->activate_cost);
        define("GENERATIONS",$config->generations);
        define("CURRENCY",$config->currency);
        define("TRANSFER_FEE",$config->transfer_fee);
        define("CENTER_USDT_ADDRESS",$config->center_usdt_address);
        define("CENTER_ETH_ADDRESS",$config->center_eth_address);
        define("CENTER_ETH_PASSWORD",$config->center_eth_passowrd);
    }
}
<?php
/**
 * Created by vency.
 * Date: 2019/3/20
 * Time: 16:40
 */
namespace App\Http\Controllers;
use App\Models\UserWalletAccount;
use App\Models\User;
use App\Extras\Task\Activate;
use DB;
use App\Extras\Common\Coins;
class EthController
{

    /**充值业务逻辑定时任务。
     * @return bool|string
     * @throws \Exception
     */
    public function  ethRecharge(){
        ini_set('memory_limit','10G');
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        //当前的共识区块；
        $params =['action' => 'eth_eth_blockNumber'];
        $currentBlock = Coins::getResult($params);
        if(isset($currentBlock['error'])){
            return false;
        }
        //最近扫描的区块；
        $latestScannedBlock = DB::table('eth_scan_records')->orderByRaw('block_num desc')->limit(1)->first();
        if(! $latestScannedBlock){
            self::scanEthBlock($currentBlock['result']);
            return "start!";
        }else{
            //扫描所有未完成的区块
            $continue = DB::table('eth_scan_records')->where('is_end',0)->get()->toArray();
            foreach($continue as $item){
                self::scanEthBlock($item['block_num']);
            }
        }
        $latestScannedBlock = get_object_vars($latestScannedBlock);
        $latestScannedBlockNum = $latestScannedBlock['block_num'];
        //将最近区块外的所有已完成扫描的区块记录全部删除;
        DB::table('eth_scan_records')->where([['block_num','<>',$latestScannedBlockNum],['is_end','=',1]])->delete();
        //接着扫描新块；
        if ($currentBlock['block'] !== $latestScannedBlockNum){
            for($i = $latestScannedBlockNum +1; $i<=$currentBlock['block'];$i++ ){
                self::scanEthBlock($i);
            }
        }
        return "finish!";
    }

    /**单个区块扫描记录的逻辑：遍历交易数组将合乎要求的充值交易入库，并更新用户账户表。
     * @param $blockNum
     * @throws \Exception
     */
    private static function scanEthBlock($blockNum){
        $method = 'eth_eth_getBlockByNumber';
        $result = Coins::$method(...[$blockNum,true]);
        if(!isset($result['error'])){
            $result = $result['result'];
            $existRec =  DB::table('eth_scan_records')->where('block_hash',$result['hash'])->update(['is_end'=>1]);
            if(!$existRec){
                $data = ['block_num' => $blockNum,
                        'block_hash' => $result['hash'],
                        'trans_num' => count($result['transactions']),
                        'generate_time' => date('Y-m-d H:i:s', $result['timestamp'])
                ];
                DB::table('eth_scan_records')->insert($data);
            }
            foreach($result['transactions'] as $transaction){
                self::checkTxid($transaction);
            }
        }
        DB::table('eth_scan_records')->where('block_num',$blockNum)->update(['is_end'=>1]);
    }

    /**
     * @param $blockNum
     * @return bool
     */
    static function ethConfirmations($blockNum){
        $result = Coins::getResult(['action'=> 'eth_eth_blockNumber']);
        if($result['error']){
            return NULL;
        }
        $currentBlockNum = $result['result'];
        return decodeHex($currentBlockNum)- decodeHex($blockNum) ;
    }


    /**判断某笔交易是否为本平台充值交易的逻辑
     * @param $scanResult
     * @return bool
     * @throws \Exception
     */
    private static function checkTxid($scanResult)
    {
        $check = DB::table('user_wallet_account')->where('eth_address',$scanResult['to'])->first();
        if ($check) {
            $exist = DB::table('running_account')->where(['txid_hash'=> $scanResult['hash'],'coin_type'=> 2])->first();
            $data['user_id'] = $check['user_id'];
            $data['to_address'] = $check['eth_address'];
            $data['from_address'] = $scanResult['from'];
            $data['num'] = toCommonValue($scanResult['value']);
            $data['txid_hash'] = $scanResult['hash'];
            $data['block_hash'] = $scanResult['blockHash'];
            $data['coin_type'] = 2;
            $data['transfer_type'] = 3;
            if($exist){
                if(!$exist['is_confirmed'] && isset($scanResult['blockNumber']) && self::ethConfirmations($scanResult['blockNumber']) >= 16){
                    self::confirmRecharge($check['user_id'],$scanResult['hash'],$check['eth_balance'] + $data['num']);
                    CoinController::synCenter($data);
                }
            }else{
                if ( self::ethConfirmations($scanResult['blockNumber']) >= 16) {
                    $data['is_confirmed'] = 1;
                    DB::table("user_wallet_account")->where('user_id',$data['user_id'])->increment('eth_balance',$data['num']);
                    CoinController::synCenter($data);
                }
                DB::table('running_account')->insert($data);
            }
            return true;
        }
        return false;
    }


    /**区块确认后，数据库的充值上分.
     * @param $userId
     * @param $txid
     * @param $amount
     * @return bool|string
     * @throws \Exception
     */
    private static function  confirmRecharge($userId,$txid,$amount){
        DB::beginTransaction();
        try{
            $userAccountInfo = UserWalletAccount::where('user_id',$userId)->first();
            $userAccountInfo->eth_balance += $amount;
            $userModel = User::find($userId);
            if($userAccountInfo->eth_balance >= ($cost = play_config()->activate_cost) && !$userModel->activation_status){
                Activate::activate($userModel);
                $userAccountInfo->eth_balance -= $cost;
            }
            $userAccountInfo->save();
            //DB::table('user_wallet_account')->where('user_id',$userId)->increment('eth_balance',$amount);
            DB::table('running_account')->where('txid_hash',$txid)->update(['is_confirmed' => 1]);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            DB::commit();
            return $e->getMessage();
        }
    }

    /**检查已有充值记录在区块链上的确认数。
     * @throws \Exception
     */
    public static function  checkExistRechargeConfirm(){
        ini_set('memory_limit','10G');    // 设置临时最大内存占用
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $forConfirming = DB::table('running_account')->where(['is_confirmed'=> 0,'coin_type' => 2,'transfer_type' => 3,'fail_type'=> NULL])->get()->toArray();
        foreach($forConfirming as $confirm){
            $params =['action'=>"eth_eth_getTransactionByHash","txid"=>$confirm['txid_hash']];
            $checkResult = Coins::getResult($params);
            if(!isset($checkResult['error']) && isset($checkResult['result']['blockNumber'])){
                $ethConfirmations =  self::ethConfirmations([$checkResult['result']['blockNumber']]);
                if($ethConfirmations >= 16){
                    self::confirmRecharge($confirm['user_id'],$confirm['txid_hash'],$confirm['num']);
                    CoinController::synCenter($confirm);
                }else{
                    DB::table('running_account')->where('txid_hash',$confirm['txid_hash'])->update(['confirmations',$checkResult['confirmations']]);
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function  confirmWithdraw(){
        ini_set('memory_limit','2G');    // 设置临时最大内存占用
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $forConfirming = DB::table('running_account')->where([['is_confirmed', '=', 0 ],['coin_type','=', 2], ['transfer_type', '<>', 3],['fail_type','=',NULL]])->get()->toArray();
        foreach($forConfirming as $confirm){
            $params =['action'=>"eth_eth_getTransactionByHash","txid"=>$confirm['txid_hash']];
            $checkResult = Coins::getResult($params);
            if(!isset($checkResult['error']) && isset($checkResult['result']['blockNumber'])){
                $checkResult = $checkResult['result'];
                $ethConfirmations =  self::ethConfirmations($checkResult['blockNumber']);
                if($ethConfirmations >= 32) {
                    $data = [
                        'hash_time' => date("Y-m-d H:i:s", $checkResult['timestamp']),
                        'block_hash' => $checkResult['blockHash'],
                        'fee' => decodeHex($checkResult['gas']) * toCommonValue($checkResult['gasPrice']),
                        'is_confirmed' => 1
                    ];
                    DB::beginTransaction();
                    try {
                        DB::table('running_account')->where('txid_hash', $confirm['txid_hash'])->update($data);
                        DB::table('center_address')->where('txid_hash', $confirm['txid_hash'])->update($data);
                        DB::table('user_wallet_account')->where('user_id', $confirm['user_id'])->increment('eth_frozen', $confirm['num']);
                        DB::commit();
                    }catch (\Throwable $e){
                        DB::rollback();
                        DB::commit();
                        continue;
                    }
                }
            }elseif(isset($checkResult['error'])){
                $data = [
                        'fail_type' => 1,
                        'err_msg' =>$checkResult['error']['message'],
                        ];
                DB::beginTransaction();
                try {
                DB::table('running_account')->where('txid_hash',$confirm['txid_hash'])->update($data);
                DB::table('center_address')->where('txid_hash',$confirm['txid_hash'])->update($data);
                DB::table('user_wallet_account')->where('user_id',$confirm['user_id'])->update([
                    "eth_balance" => DB::raw("eth_balance+{$confirm['num']}"),
                    "eth_frozen" => DB::raw("eth_frozen-{$confirm['num']}")
                ]);
                DB::commit();
                }catch (\Throwable $e){
                    DB::rollback();
                    DB::commit();
                    continue;
                }
            }
        }
    }
}
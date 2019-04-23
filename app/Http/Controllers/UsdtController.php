<?php
/**
 * Created by vency.
 * Date: 2019/3/20
 * Time: 16:40
 */
namespace App\Http\Controllers;
use App\Extras\Common\Coins;
use DB;
class UsdtController extends Controller
{
    /**
     * 充值业务逻辑定时任务。
     */
    public function  usdtRecharge(){
        ini_set('memory_limit','10G');
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        //当前的共识区块；
        $params =['action'=>"usdt_omni_getcurrentconsensushash"];
        $currentBlock = Coins::getResult($params);
        //最近扫描的区块；
        $latestScannedBlock = DB::table('usdt_scan_records')->orderByRaw('block_num desc')->limit(1)->first();
        if(! $latestScannedBlock){
            $currentBlockData = self::getBlockData($currentBlock['block']);
            $cbData['block_num'] = $currentBlock['block'];
            $cbData['trans_num'] = count($currentBlockData);
            self::saveNewScanRec($cbData);
            self::scanUstBlock($cbData['block_num'],$currentBlockData);
            return "start!";
        }else{
            //扫描所有未完成的区块
            $continue = Db::table('usdt_scan_records')->where('is_end',0)->select();
            foreach($continue as $item){
                self::scanUstBlock($item['block_num'],self::getBlockData($item['block_num']));
            }
        }
        $latestScannedBlockNum = $latestScannedBlock['block_num'];
        //将最近区块外的所有已完成扫描的区块记录全部删除;
        Db::table('usdt_scan_records')->where([['block_num','<>',$latestScannedBlockNum],['is_end','=',1]])->delete();
        //接着扫描新块；
        if ($currentBlock['block'] !== $latestScannedBlockNum){
            for($i = $latestScannedBlockNum +1; $i<=$currentBlock['block'];$i++ ){
                $blockData = self::getBlockData($i);
                $data['block_num'] = $i;
                $data['trans_num'] = count($blockData);
                self::saveNewScanRec($data);
                self::scanUstBlock($i,$blockData);
            }
        }
        return "finish!";
    }

    /**单个区块扫描记录的逻辑：遍历txid数组将合乎要求的充值交易入库，并更新用户账户表。
     * @param $blockNum
     * @param $blockData :区块的所有的交易txid数组
     */
    private static function scanUstBlock($blockNum,$blockData){
        foreach($blockData as $txid){
            self::checkTxid($txid);
        }
        Db::table('usdt_scan_records')->where('block_num',$blockNum)->update(['is_end'=>1]);
    }

    /**判断某笔交易是否为本平台充值交易的逻辑
     * @param $txid
     * @return boolean
     */
    private static function checkTxid($txid)
    {
        $params = ['action' => "usdt_omni_gettransaction", 'block_num' => $txid];
        $scanResult = Coins::getResult($params);
        if( !isset($scanResult['ismine']) || !$scanResult['ismine'] || !isset($scanResult['propertyid']) || $scanResult['propertyid'] != 31 || !isset($scanResult['referenceaddress']) || empty($scanResult['referenceaddress'])){
            return false;
        }
        $check = Db::table('user_wallet_account')->where('usdt_address',$scanResult['referenceaddress'])->find();
        if ($check && $scanResult['valid']) {
            $exist = Db::table('running_account')->where('txid_hash',$txid)->find();
            $data['user_id'] = $check['user_id'];
            $data['to_address'] = $check['usdt_address'];
            $data['from_address'] = $scanResult['sendingaddress'];
            $data['num'] = $scanResult['amount'];
            $data['hash_time'] = date('Y-m-d H:i:s', $scanResult['blocktime']);
            $data['txid_hash'] = $txid;
            $data['block_hash'] = $scanResult['blockhash'];
            $data['confirmations'] = $scanResult['confirmations'];
            $data['coin_type'] = 1;
            $data['transfer_type'] = 3;
            if($exist){
                if(!$exist['is_confirmed'] && $scanResult['confirmations'] >= 6){
                    self::confirmRecharge($check['user_id'],$txid,$check['usdt_balance'] + $scanResult['amount']);
                    CoinApi::synCenter($data);
                }
            }else{
                //$data['position_in_block'] = $scanResult['positioninblock'];
                if ($scanResult['confirmations'] >= 6) {
                    $data['is_confirmed'] = 1;
                    Db::table("user_wallet_account")->where('user_id',$data['user_id'])->setInc('usdt_balance',$data['num']);
                    CoinApi::synCenter($data);
                }
                Db::table('running_account')->insert($data);
            }
            return true;
        }
        return false;
    }

    /**获取区块的所有交易的txid 数组
     * @param $blockNum
     * @return array|Null|string
     */
    private static function  getBlockData($blockNum){
        $params =['action'=>"usdt_omni_listblocktransactions",'block_num'=> $blockNum];
        $result = Coins::getResult($params);
        return $result;
    }

    /**单条区块扫描记录入库；
     * @param $data:区块数据；
     */
    private static function  saveNewScanRec($data){
        $exist = Db::table('usdt_scan_records')->where('block_num',$data['block_num'])->count();
        if(!$exist) {
            Db::table('usdt_scan_records')->insert($data);
        }
    }

    /**区块确认后，数据库的充值上分。
     * @param $userId
     * @param $txid
     * @param $amount
     * @return bool|string
     */
    private static function  confirmRecharge($userId,$txid,$amount){
        Db::startTrans();
        try{
            Db::table('user_wallet_account')->where('user_id',$userId)->setInc('usdt_balance',$amount);
            Db::table('running_account')->where('txid_hash',$txid)->setField('is_confirmed',1);
            Db::commit();
            return true;
        }catch (\Exception $e){
            Db::rollback();
            Db::commit();
            return $e->getMessage();
        }
    }

    /**
     * 检查已有充值记录在区块链上的确认数。
     */
    public static function  checkExistRechargeConfirm(){
        ini_set('memory_limit','10G');    // 设置临时最大内存占用
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $forConfirming = Db::table('running_account')->where(['is_confirmed'=> 0,'coin_type' => 1,'transfer_type' => 3])->select();
        foreach($forConfirming as $confirm){
            $params =['action'=>"usdt_omni_gettransaction","txid"=>$confirm['txid_hash']];
            $checkResult = Coins::getResult($params);
            if(is_array($checkResult) && $checkResult['confirmations']>=6){
                self::confirmRecharge($confirm['user_id'],$confirm['txid_hash'],$confirm['num']);
                CoinApi::synCenter($confirm);
            }if(is_array($checkResult) && $checkResult['confirmations']<6){
                Db::table('running_account')->where('txid_hash',$confirm['txid_hash'])->setField('confirmations',$checkResult['confirmations']);
            }
        }
    }

    public function  confirmWithdraw(){
        ini_set('memory_limit','2G');    // 设置临时最大内存占用
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $forConfirming = Db::table('running_account')->where([['is_confirmed', '=', 0 ],['coin_type','=', 1], ['transfer_type', '<>', 3]])->select();
        foreach($forConfirming as $confirm){
            $params =['action'=>"usdt_omni_gettransaction","txid"=>$confirm['txid_hash']];
            $checkResult = Coins::getResult($params);
            if(is_array($checkResult) && isset($checkResult['confirmations']) && $checkResult['confirmations'] >= 6){
                Db::table('running_account')->where('txid_hash',$confirm['txid_hash'])->update([
                    'hash_time' => date("Y-m-d H:i:s",$checkResult['blocktime']),
                    'block_hash' => $checkResult['blockhash'],
                    'fee' => $checkResult['fee'],
                    'is_confirmed' => 1
                ]);
                Db::table('center_address')->where('txid_hash',$confirm['txid_hash'])->update([
                    'hash_time' => date("Y-m-d H:i:s",$checkResult['blocktime']),
                    'block_hash' => $checkResult['blockhash'],
                    'fee' => $checkResult['fee'],
                    'is_confirmed' => 1
                ]);
                Db::table('user_wallet_account')->where('user_id',$confirm['user_id'])->setDec('usdt_frozen',$checkResult['amount']);
            }elseif($checkResult === "No information available about transaction"){
                Db::table('running_account')->where('txid_hash',$confirm['txid_hash'])->update([
                    'fail_type' => 1,
                    'err_msg' =>json_encode($checkResult),
                ]);
                Db::table('center_address')->where('txid_hash',$confirm['txid_hash'])->update([
                    'fail_type' => 1,
                    'err_msg' =>json_encode($checkResult),
                ]);
                Db::table('user_wallet_account')->where('user_id',$confirm['user_id'])->dec('usdt_frozen',$confirm['num'])->inc('usdt_balance',$confirm['num'])->update();
            }
        }
    }

}
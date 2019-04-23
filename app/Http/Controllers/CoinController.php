<?php
/**
 * Created by Vency
 * Date: 2019/3/21
 * Time: 17:16
 * USDT 业务逻辑API
 */

namespace App\Http\Controllers;
use App\Extras\Common\Coins;
use App\Extras\Task\SystemConfig;
use Illuminate\Support\Facades\DB;
class CoinController extends Controller{
    use SystemConfig;
    public function __construct()
    {
        self::getConfig();
    }

    /**生成地址；
     * @param null $unique
     * @param $type
     * @return array|Null|string
     */
     public function generateAddress($unique = null,$type = 'usdt'){
        $unique =  $unique ?? request('unique');
        $type =  $type ?? request('type');
        if(!$unique){
            return ['status'=> 400 ,'msg'=> 'Must Provide An Account Name!','data'=>""];
        }
        if($type === 'eth'){
            $params['action'] = 'eth_personal_newAccount';
        }elseif($type === 'usdt'){
            $params['action'] = 'usdt_getnewaddress';
        }
        $params['account'] =  $unique;
        if($type === 'eth'){
            $method = $params['action'];
            $result = Coins::$method($params['account']);
            if(substr($result,0,2) === '0x'){
                return ['status'=> 200 ,'msg'=>'success', 'data' => $result];
            }else{
                return ['status'=>301 ,'msg'=> $result,'data'=>""];
            }
        }elseif($type === 'usdt'){
            $result = Coins::getResult($params);
            if(is_string($result) && strlen($result) == 34 && !strpos($result," ")){
                return ['status'=> 200 ,'msg'=>'success', 'data' => $result];
            }else{
                return ['status'=>301 ,'msg'=> $result,'data'=>""];
            }
        }
        return ['status' => 404,'msg' => "Can not find the coin!", 'data'=>""];
     }

    /**币转出平台（上链交易）
     * @param null $data:['user_id'=> string , 'from_address’=>string(34位)，'to_address'=>string(34位),'num'=>float,
     * 'transfer_type'=>enum(转账类型（1.通过提现转出平台 2.通过C2C交易转出平台）),
     * 'coin_type'=> 1,2,3(1.usdt,2.eth,3.btc)]
     * @return array
     */
    public function coinTransfer($data = NULL){
        if(!request()->isMethod('post')){
            return ['status'=>400 , 'msg'=>"HTTP_BAD_REQUEST"];
        }
        $data  = $data ?? request();
        if(empty($data)){
            return ['status'=>404 , 'msg'=>"HTTP_NOT_FOUND"];
        }
        $result = self::synCenter($data,0);
        $status = $result['status'];
        $center =  $result ['data'];
        $center['transfer_type'] = $data['transfer_type'];
        $coinArr = [1 => 'usdt',2 => 'eth',3 => 'btc'];
        if($status){
            DB::table('running_account')->insert($center);
            DB::table('user_wallet_account')->where('user_id',$data['user_id'])->update([
                "{$coinArr[$data['coin_type']]}_balance" => DB::raw("{$coinArr[$data['coin_type']]}_balance-{$data['num']}-".ACTIVATE_COST),
                "{$coinArr[$data['coin_type']]}_frozen" => DB::raw("{$coinArr[$data['coin_type']]}_frozen+{$data['num']}")
            ]);
            return ['status' => 200, 'message' => '操作成功！请等待区块确认！'];
        }else{
            DB::table('running_account')->insert($center);
            return ['status' => 301, 'message' => '操作失败，请联系客服！'];
        }
    }

    /**资金的统一分配(归集与转出)
     * @param $data: 必须字段array_keys($data) = ['user_id','from_address','to_address','num','coin_type'];
     * @param $direction: 方向，向中央账号里in为1，out为0；
     * @return array;
     */
    public static function synCenter($data,$direction = 1){
        $configArr = [
            1 => ['coin'=>'usdt','method'=>'usdt_omni_funded_send'],
            2 => ['coin'=>'eth','method'=>'eth_eth_sendTransaction'],
            3 => ['coin'=>'btc','method'=>''],
        ];
        $coin = $configArr[$data['coin_type']];
        $coinCenterAddress = constant(strtoupper("center_{$coin['coin']}_address"));
        $transferData = [ ];
        if($direction) {
            if ($coin['coin'] === 'usdt') {
                $transferData = [
                    'from_address' => $data['to_address'],
                    'to_address' => $coinCenterAddress,
                    'propertyid' => 31,
                    'amount' => (string)$data['num'],
                    'feeaddress' => $coinCenterAddress,
                ];
            } elseif ($coin['coin'] === 'eth') {
                $transferData = [
                    'from' => $data['to_address'],
                    'to' => $coinCenterAddress,
                    'value' => $data['num'],
                    'password' => DB::table('users')->where('id', $data['user_id'])->value('unique'),
                ];
            }
        }else{
            if ($coin['coin'] === 'usdt') {
                $transferData = [
                    'from_address' => $coinCenterAddress,
                    'to_address' => $data['to_address'],
                    'propertyid' => 31,
                    'amount' => (string)$data['num'],
                    'feeaddress' => $coinCenterAddress,
                ];
            } elseif ($coin['coin'] === 'eth') {
                $transferData = [
                    'from' => $coinCenterAddress,
                    'to' => $data['to_address'],
                    'value' => $data['num'],
                    'password' => CENTER_ETH_PASSWORD,
                ];
            }
        }
        $method = $coin['method'];
        $transferRes = Coins::$method(...array_values($transferData));
        $needle = ['user_id','from_address','to_address','num','coin_type'];
        foreach ($needle as $field){
            $center[$field] = $data[$field];
        }
        $center['in_or_out'] = $direction;
        if (($coin['coin'] === 'usdt' && $transferRes && !strpos($transferRes," ") && strlen($transferRes) === 64) || ($coin['coin'] === 'eth' && substr($transferRes,0,2) === '0x')) {
            $center['txid_hash'] = $transferRes;
            Db::table('center_address')->insert($center);
            $status = true;
        } else {
            $center['fail_type'] = 0;
            $center['err_msg'] = $transferRes;
            Db::table('center_address')->insert($center);
            $status = false;
        }
        return ['status' => $status,'data' => $center];
    }


}
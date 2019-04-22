<?php
/**
 * Created by PhpStorm.
 * User: dt
 * Date: 2017-7-21
 * Time: 15:30
 */

namespace App\Extras\Coins\Eth;


class Ethereum extends EthereumBaseMethod{
    public $base = "1000000000000000000";

    function toWei($ethNumber)
    {
        $tenNumber=bcmul($ethNumber,$this->base);//高精度浮点数相乘
        $weiNumber = base_convert($tenNumber, 10, 16);
        return  '0x'.$weiNumber;
    }

    /**
     * @param $weiNumber 16进制wei单位
     * @return float|int 10进制eth单位【正常单位】
     */
    function fromWei($weiNumber){
        $tenNumber = base_convert($weiNumber, 16, 10);
        $ethNumber = bcdiv($tenNumber,$this->base);
        return $ethNumber;
    }

    function eth_getBlockByNumber($block='latest', $full_tx=TRUE){
        if(is_numeric($block)){
            $block = encodeToHexString($block);
        }
        return $this->ether_request(__FUNCTION__, array($block, $full_tx));
    }


    /**转账
     * @param $from
     * @param $to
     * @param $value
     * @param $password
     * @return string
     * @throws RPCException
     */
    function eth_sendTransaction($from,$to,$value,$password)
    {
        //$from = "0x85e155e9c980a71a719e4b6399f8e3f4f616d337";
        //$to = "0xf669bb9a039a8e816f67a824eed88feaf4826726";
        //$value = "0x9184e72a";
        //如果不是16进制 转化为16进制
        if (!ctype_xdigit($value)) {
            $value = $this->toWei($value);
        }
        $gas = $this->eth_estimateGas($from, $to, $value);//16进制 消耗的gas 0x5209
        $gasPrice = $this->eth_gasPrice();//价格 0x430e23400
        //$password = "123";//解锁密码
        $status = $this->personal_unlockAccount($from, $password);//解锁
        if (!$status) {
            return '解锁失败';
        }
        $params = array(
            "from" => $from,
            "to" => $to,
            "gas" => $gas,//2100
            "gasPrice " => $gasPrice,//18000000000
            "value" => $value,//2441406250
            "data" => "",
        );

        $data = $this->request(__FUNCTION__, [$params]);
        if (empty($data['error']) && !empty($data['result'])) {
            return $data['result'];//转账之后，生成HASH
        } else {
            return $data['error']['message'];
        }
        //0x536135ef85aa8015b086e77ab8c47b8d40a3d00a975a5b0cc93b2a6345f538cd
    }

    /**根据hash值查看转账记录。
     * @param $transactionHash
     * @return string
     */
    function eth_getTransactionReceipt($transactionHash)
    {
        //$transactionHash = "0x536135ef85aa8015b086e77ab8c47b8d40a3d00a975a5b0cc93b2a6345f538cd";
        $params = array(
            $transactionHash,
        );
        $data = $this->request(__FUNCTION__, $params);
        if (empty($data['error'])) {
            if (count($data['result']) == 0) {
                return  '等待确认';
            } else {
                return $data['result']['blockHash'];
            }
        } else {
            return $data['error']['message'];
        }
    }

    /**创建个人钱包。
     * @param $password
     * @return mixed
     */
    function personal_newAccount($password)
    {
        $params = array(
            $password,
        );
        $data = $this->request(__FUNCTION__, $params);
        if (empty($data['error']) && !empty($data['result'])) {
            return $data['result'];//新生成的账号公钥
        } else {
            return $data['error']['message'];
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $value
     * @return mixed
     */
    function eth_estimateGas($from, $to, $value)
    {
        $params = array(
            "from" => $from,
            "to" => $to,
            "value" => $value
        );
        $data = $this->request(__FUNCTION__, [$params]);
        return $data['result'];
    }

    /**获得当前gas price
     * @return mixed
     * @throws RPCException
     */
    function eth_gasPrice()
    {
        $params = array();
        $data = $this->request(__FUNCTION__, $params);
        return $data['result'];
    }


    /**解锁账号
     * @param $account
     * @param $password
     * @return mixed
     * @throws RPCException
     */

    function personal_unlockAccount($account, $password)
    {
        $params = array(
            $account,
            $password,
            100,
        );
        $data = $this->request(__FUNCTION__, $params);
        if (!empty($data['error'])) {
            return $data['error']['message'];//解锁失败
        } else {
            return $data['result'];//成功返回true
        }
    }


}


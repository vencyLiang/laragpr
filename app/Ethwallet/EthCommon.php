<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2017-9-21
 * Time: 15:33
 */
namespace App\Ethwallet;

use App\Http\Controllers\Controller;
use \Exception;

class EthCommon extends Controller
{

    protected $host, $port, $version;
    protected $id = 0;
    public $base = "1000000000000000000";//1e18 wei  基本单位
	//高精度函数参考http://www.jb51.net/article/80726.htm

    /**
     * 构造函数
     * Common constructor.
     * @param $host
     * @param string $port
     * @param string $version
     */
    function __construct($host, $port = "80", $version = "2.0")
    {

        $this->host = $host;
        $this->port = $port;
        $this->version = $version;
    }


    /**
     * 发送请求
     * @param $method
     * @param array $params
     * @return mixed
     */
    function request($method, $params = array())
    {
        $data = array();
        $data['jsonrpc'] = $this->version;
        $data['id'] = $this->id + 1;
        $data['method'] = $method;
        $data['params'] = $params;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_PORT, $this->port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $ret = curl_exec($ch);
        //返回结果
        try{
            if(!$ret){
                $error = curl_errno($ch);
                curl_close($ch);
                throw new Exception("curl出错，错误码:$error");
            }
            curl_close($ch);
            return json_decode($ret, true);
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return false;
    }


    /**
     * @param $weiNumber 16进制wei单位
     * @return float|int 10进制eth单位【正常单位】
     */
    function fromWei($weiNumber)
    {
		$tenNumber = base_convert($weiNumber, 16, 10);
        echo $tenNumber."<br/>";
        $ethNumber = bcdiv($tenNumber,$this->base);
        return $ethNumber;
    }

    function fromWei2($weiNumber)
    {
        $ethNumber = bcdiv($weiNumber,$this->base,8);//高精度浮点数相除
        return $ethNumber;
    }

    /**
     * @param $ethNumber 10进制eth单位
     * @return string    16进制wei单位
     */
    function toWei($ethNumber)
    {

		$tenNumber=bcmul($ethNumber,$this->base);//高精度浮点数相乘
        $weiNumber = base_convert($tenNumber, 10, 16);
        return  '0x'.$weiNumber;
    }

    function toWei2($ethNumber)
    {
        $weiNumber = bcmul($ethNumber,$this->base);
        return $weiNumber;
    }

    /**大的数据转换为16进制.
     * @param $str: 10进制数的字符串。
     * @return string
     */
    function dec2hex($str)
    {
        $base = 1000000000000000000;
        $str = number_format($str*$base, 0, '', '');
        $hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
        // Result value
        $hexValue = '';
        // The quotient of each division operation
        $quotient = $str;
        $divisor = $str;
        // The ending condition
        $flag = true;
        while($flag)
        {
            $len = strlen($divisor);
            $pos = 1;
            $quotient = 0;
            // Take the first two digits as temp divisor and advance by 1 each iteration
            $div = substr($divisor, 0, 2);
            $remainder = $div[0];
            while($pos < $len)
            {
                // Calculate the next div
                $div = $remainder == 0 ? $divisor[$pos] : $remainder.$divisor[$pos];
                $remainder = $div % 16;
                $quotient = $quotient.floor($div/16);
                $pos++;
            }
            // Recast the divisor as string to make the $divisor[$pos] work
            $quotient = $this->trim_left_zeros($quotient);
            $divisor = "$quotient";
            $hexValue = $hex[$remainder].$hexValue;
            // If the divisor is smaller than 15 then end the iteration
            if (strlen($divisor)<=2)
            {
                if ($divisor<15)
                {
                    $flag = false;
                }
            }
        }
        $hexValue = $hex[$quotient].$hexValue;
        $hexValue = $this->trim_left_zeros($hexValue);
        return $hexValue;
    }


    /**功能：从一个数的左边截取掉所有的0.
     * @param $str
     * @return string
     */
    function trim_left_zeros($str)
    {
        $str = ltrim($str, '0');
        if (empty($str))
        {
            $str = '0';
        }
        return $str;
    }

    /**
     * 判断是否是16进制
     * @param $a
     * @return int
     */
    function assertIsHex($a)
    {
        if (ctype_xdigit($a)) {
            return true;
        } else {
            return false;
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 12:14
 */
namespace App\Extras\Coins\Eth;
class JSON_RPC
{
    protected $host, $port, $version;
    protected $id = 0;

    function __construct($host, $port, $version="2.0")
    {
        $this->host = $host;
        $this->port = $port;
        $this->version = $version;
    }

    function request($method, $params=array())
    {
        $data = array();
        $data['jsonrpc'] = $this->version;
        $data['id'] = $this->id++;
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

        if($ret !== FALSE)
        {
            $formatted = $this->format_response($ret);
            return $formatted;
        }
        else {
            throw new RPCException('Curl error: ' . curl_error($ch));
        }
    }

    function format_response($response,$assoc = true)
    {
        return @json_decode($response,$assoc);
    }
}
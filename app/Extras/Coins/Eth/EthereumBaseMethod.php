<?php
namespace App\Extras\Coins\Eth;
class EthereumBaseMethod extends JSON_RPC
{
	protected function ether_request($method, $params=array())
	{
		try
		{
			$ret = $this->request($method, $params);
			return $ret;
		}
		catch(RPCException $e)
		{
			throw $e;
		}
	}
	function __call($method,$params){
	    return $this->request($method,$params);
    }
	protected function decode_hex($input)
	{
		if(substr($input, 0, 2) == '0x')
			$input = substr($input, 2);

		if(preg_match('/[a-f0-9]+/', $input))
			return hexdec($input);

		return $input;
	}

	function web3_clientVersion()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function web3_sha3($input)
	{
		return $this->ether_request(__FUNCTION__, array($input));
	}

	function net_version()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function net_listening()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function net_peerCount()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_protocolVersion()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_coinbase()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_mining()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_hashrate()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_gasPrice()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_accounts()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_blockNumber($decode_hex=FALSE)
	{
		$block = $this->ether_request(__FUNCTION__);

		if($decode_hex)
			$block = $this->decode_hex($block);

		return $block;
	}

	function eth_getBalance($address, $block='latest', $decode_hex=FALSE)
	{
		$balance = $this->ether_request(__FUNCTION__, array($address, $block));

		if($decode_hex)
			$balance = $this->decode_hex($balance);

		return $balance;
	}

	function eth_getStorageAt($address, $at, $block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($address, $at, $block));
	}

	function eth_getTransactionCount($address, $block='latest', $decode_hex=FALSE)
	{
		$count = $this->ether_request(__FUNCTION__, array($address, $block));

        if($decode_hex)
            $count = $this->decode_hex($count);

        return $count;
	}

	function eth_getBlockTransactionCountByHash($tx_hash)
	{
		return $this->ether_request(__FUNCTION__, array($tx_hash));
	}

	function eth_getBlockTransactionCountByNumber($tx='latest')
	{
		return $this->ether_request(__FUNCTION__, array($tx));
	}

	function eth_getUncleCountByBlockHash($block_hash)
	{
		return $this->ether_request(__FUNCTION__, array($block_hash));
	}

	function eth_getUncleCountByBlockNumber($block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($block));
	}

	function eth_getCode($address, $block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($address, $block));
	}

	function eth_sign($address, $input)
	{
		return $this->ether_request(__FUNCTION__, array($address, $input));
	}

	function eth_getBlockByHash($hash, $full_tx=TRUE)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $full_tx));
	}

	function eth_getBlockByNumber($block='latest', $full_tx=TRUE)
	{
		return $this->ether_request(__FUNCTION__, array($block, $full_tx));
	}

	function eth_getTransactionByHash($hash)
	{
		return $this->ether_request(__FUNCTION__, array($hash));
	}

	function eth_getTransactionByBlockHashAndIndex($hash, $index)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $index));
	}

	function eth_getTransactionByBlockNumberAndIndex($block, $index)
	{
		return $this->ether_request(__FUNCTION__, array($block, $index));
	}

	function eth_getTransactionReceipt($tx_hash)
	{
		return $this->ether_request(__FUNCTION__, array($tx_hash));
	}

	function eth_getUncleByBlockHashAndIndex($hash, $index)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $index));
	}

	function eth_getUncleByBlockNumberAndIndex($block, $index)
	{
		return $this->ether_request(__FUNCTION__, array($block, $index));
	}

	function eth_getCompilers()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_compileSolidity($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}

	function eth_compileLLL($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}

	function eth_compileSerpent($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}


	function eth_newBlockFilter($decode_hex=FALSE)
	{
		$id = $this->ether_request(__FUNCTION__);

		if($decode_hex)
			$id = $this->decode_hex($id);

		return $id;
	}

	function eth_newPendingTransactionFilter($decode_hex=FALSE)
	{
		$id = $this->ether_request(__FUNCTION__);

		if($decode_hex)
			$id = $this->decode_hex($id);

		return $id;
	}

	function eth_uninstallFilter($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	function eth_getFilterChanges($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	function eth_getFilterLogs($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	function eth_getWork()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function eth_submitWork($nonce, $pow_hash, $mix_digest)
	{
		return $this->ether_request(__FUNCTION__, array($nonce, $pow_hash, $mix_digest));
	}

	function db_putString($db, $key, $value)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key, $value));
	}

	function db_getString($db, $key)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key));
	}

	function db_putHex($db, $key, $value)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key, $value));
	}

	function db_getHex($db, $key)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key));
	}

	function shh_version()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function shh_newIdentinty()
	{
		return $this->ether_request(__FUNCTION__);
	}

	function shh_hasIdentity($id)
	{
		return $this->ether_request(__FUNCTION__);
	}

	function shh_newFilter($to=NULL, $topics=array())
	{
		return $this->ether_request(__FUNCTION__, array(array('to'=>$to, 'topics'=>$topics)));
	}

	function shh_uninstallFilter($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	function shh_getFilterChanges($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	function shh_getMessages($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
}










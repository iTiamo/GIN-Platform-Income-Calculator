<?php
require_once("../gincoin/RPC/Client.php");
require_once("../gincoin/RPC/HttpClient.php");
require_once("../gincoin/RPC/RequestBuilder.php");
require_once("../gincoin/RPC/ResponseParser.php");
require_once("../gincoin/RPC/JsonFormatValidator.php");
use JsonRPC as RPC;
//credit to fguillot for JsonRPC, find it here: https://github.com/fguillot/JsonRPC

/*
 * you need to have the gincoind/gincoin wallet running and properly configured for it to return calls properly.
 * find more info here: 
 * https://en.bitcoin.it/wiki/PHP_developer_intro
 * https://en.bitcoin.it/wiki/Running_Bitcoin
 * https://en.bitcoin.it/wiki/Bitcoind
*/

class coin //class to communicate with a coin's daemon 
{  
    private $client;
    public $coinid;
    public $blocktime;
    public $powReward;
    public $mnReward;
    
    function __construct($coinid, $rpcuser, $rpcpassword, $ip, $port) { //url or ip address
        $this->coinid = $coinid;
        $this->client = new RPC\Client("http://".$rpcuser.":".$rpcpassword."@".$ip.":".$port);
        $this->blocktime = $this->getBlockTime();
        $this->mnReward = $this->getMNReward();
        $this->powReward = $this->getPoWReward();
    }
    
    function getMasternodeCount() {
        return $this->client->masternode("count", "enabled");
    }
    
    function getNetworkHashPs($blocks) { //returns the network's hashrate over the past n blocks
        return $this->client->getnetworkhashps($blocks);
    }
    
    function getDifficulty() {
        return $this->client->getdifficulty();
    }
    
    function getBlock($hash) {
        return $this->client->getblock($hash);
    }
    
    function getBestBlock() {
        return $this->client->getblock($this->client->getbestblockhash());
    }
    
    function getBlockCount() { //returns the latest block index
        return $this->client->getblockcount();
    }
    
    function getBlockHash($index) {
        return $this->client->getblockhash($index);
    }
    
    function getRawTransaction($hash) {
        return $this->client->getrawtransaction($hash, 1);
    }
    
    private function getBlockTime() {
        $bestblock = $this->getBestBlock();
        $block2 = $this->getblock($this->getBlockHash($this->getBlockCount() - 100));
        $timedelta = $bestblock["mediantime"] - $block2["mediantime"]; //the time in seconds that has passed between the best block and the block 100 blocks before the best block
        return $timedelta / 101; //returns the average blocktime over the past 101 blocks
    }
    
    private function getMNReward() {
        $bestblock = $this->getBestBlock();
        $coinbase = $this->getRawTransaction($bestblock["tx"][0]); //coinbase transaction of the best block
        return $coinbase["vout"][0]["value"]; //the 1st output in a coinbase transaction is always the MN reward, here we return the value of that output
    }
    
    private function getPoWReward() {
        $bestblock = $this->getBestBlock();
        $coinbase = $this->getRawTransaction($bestblock["tx"][0]); //coinbase transaction of the best block
        return $coinbase["vout"][1]["value"]; //the 2nd output in a coinbase transaction is always the PoW reward, here we return the value of that output
    }
}
?>

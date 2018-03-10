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

class rpcclient
{  
    function __construct($rpcuser, $rpcpassword, $url, $port) {
        $this->gincoin_client = new RPC\Client("http://".$rpcuser.":".$rpcpassword."@".$url.":".$port);
    }
    
    function getMasternodeCount() {
        return $this->gincoin_client->masternode("count");
    }
}
?>
<?php
class ticker //class to interact with exchange API
{
    private $cryptobridge = "https://api.crypto-bridge.org/api/v1/ticker";
    private $graviex = "https://graviex.net/api/v2/tickers/";
    private $coins;
    
    function __construct() {
        $this->coins = json_decode(file_get_contents($this->cryptobridge));
    }
    
    function getCryptoBridgeCoin($id) {
        for($i = 0; $i < count($this->coins); $i++) {
            if($this->coins[$i]->id == $id . "_BTC") {
                return $this->coins[$i];
            }
        }
    }
    
    function getGraviexCoin($id) {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        
        return json_decode(file_get_contents(($this->graviex . strtolower($id) . "btc.json"), false, stream_context_create($arrContextOptions)));
    }
}
?>
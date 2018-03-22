<?php
class ticker //class to interact with CryptoBridge ticker API
{
    private $tickerurl = "https://api.crypto-bridge.org/api/v1/ticker";
    private $coins;
    
    function __construct() {
        $this->coins = json_decode(file_get_contents($this->tickerurl));
    }

    function getGIN() {
        for($i = 0; $i <= count($this->coins); $i++) {
            if($this->coins[$i]->id == "GIN_BTC") {
                return $this->coins[$i];
            }
        }
    }
}
?>

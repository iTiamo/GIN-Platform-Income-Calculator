<?php
class ticker 
{
    private $ticker = "https://api.crypto-bridge.org/api/v1/ticker";
    
    function getGIN() {
        $arr = json_decode(file_get_contents($this->ticker));
        
        for($i = 0; $i < count($arr); $i++) {
            if($arr[$i]->id == "GIN_BTC") {
                return $arr[$i];
            }
        }
        return null;
    }
}
?>
<?php 
//This class is no longer used...
class explorer 
{
    private $explorer = "https://explorer.gincoin.io/api/"; //you can use any block explorer API URL. this one connects to the official GINcoin block explorer.
    
    function getBlockHeight() {
    return file_get_contents($this->explorer . "getblockcount");
    }

    function getBlockHash($blockHeight) {
    return file_get_contents($this->explorer . "getblockhash?index=" . $blockHeight);
    }

    function getBlock($blockHash) {
    return json_decode(file_get_contents($this->explorer . "getblock?hash=" . $blockHash));
    }

    function getDifficulty($block) {
    return $block->difficulty;
    }
}
?>
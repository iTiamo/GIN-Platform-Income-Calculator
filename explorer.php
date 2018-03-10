<?php 
class explorer 
{
    public $explorer_url = "https://explorer.gincoin.io/api/"; //you can use any block explorer API URL. this one connects to the official GINcoin block explorer.

    function getBlockHeight() {
    return file_get_contents($this->explorer_url . "getblockcount");
    }

    function getBlockHash($blockHeight) {
    return file_get_contents($this->explorer_url . "getblockhash?index=" . $blockHeight);
    }

    function getBlock($blockHash) {
    return json_decode(file_get_contents($this->explorer_url . "getblock?hash=" . $blockHash));
    }

    function getDifficulty($block) {
    return $block->difficulty;
    }
}
?>
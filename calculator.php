<?php
class calculator
{
    function averageArray($array) {
        $sum=0;
        foreach ($array as $value) {
            $sum += $value;
        }
        return $sum/count($array);
    }
    
    function calculatePoWCoins($hashrate, $difficulty) {
        if ($hashrate == 0) {
            return 0;
        } else {
        return (86400/($difficulty*2**32/$hashrate))*10; //based off my generic formula coins_day=(seconds_day/(d*2^32/hashrate))*block_reward, where 2^32 is the average number of shares needed to find a block at a difficulty of 1
        }
    }
    
    function calculateMasternodeCoins($multiplier, $masternode_count) {
        return $multiplier * (((86400/120)*10)/$masternode_count); //based off my generic formula for one masternode coins_day = (((seconds_day/block_time)*block_rewards)/masternode_count)
    }
}
?>
<?php
class calculator
{   
    function calculatePoWCoinsByDifficulty($hashrate, $difficulty, $blockreward, $timeperiod=86400) {
        if ($hashrate == 0) {
            return 0;
        } else {
        return ($timeperiod/($difficulty*2**32/$hashrate))*$blockreward; //based off my generic formula coins_day=(seconds_day/(d*2^32/hashrate))*block_reward, where 2^32 is the average number of shares needed to find a block at a difficulty of 1
        }
    }
    
    function calculatePoWCoinsByNetworkHashPs($hashrate, $networkHashPs, $blockreward, $blocktime, $timeperiod=86400) {
        if ($hashrate == 0) {
            return 0;
        } else {
        return ($hashrate/$networkHashPs)*(($timeperiod/$blocktime)*$blockreward); //based off my generic formula coins_day=(hashrate/nethash)*((seconds_day/block_time)*block_reward
        }
    }
    
    function calculateMasternodeCoins($multiplier, $masternode_count, $blockreward, $blocktime, $timeperiod=86400) {
        return $multiplier * ((($timeperiod/$blocktime)*$blockreward)/$masternode_count); //based off my generic formula for one masternode coins_day = (((seconds_day/block_time)*block_rewards)/masternode_count)
    }
}
?>

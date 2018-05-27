<?php
require_once "calculator.php";
require_once "coin.php";
require_once "ticker.php";

function calculateIncome($coin, $params) {
    if ($params["hashrate"] === "") {
        $params["hashrate"] = 0;
    } else {
        $params["hashrate"] *= 1000000;
    }
    
    if ($params["masternodes"] === "") {
        $params["masternodes"] = 0;
    }

    $netHashrate = $coin->getNetworkHashPs(101);
    $netMasternodes = $coin->getMasternodeCount(); 
    $difficulty = $coin->getDifficulty();

    $calculator = new calculator();
    $pow_coins = number_format(($calculator->calculatePoWCoinsByNetworkHashPs($params["hashrate"], $netHashrate, $coin->powReward, $coin->blocktime)), 8);
    $masternode_coins = number_format(($calculator->calculateMasternodeCoins($params["masternodes"], $netMasternodes, $coin->mnReward, $coin->blocktime)), 8);
    $totalCoins = number_format(($pow_coins + $masternode_coins), 8);

    $ticker = new ticker();
    if($ticker->getCryptoBridgeCoin($coin->coinid)) {
        $price = $ticker->getCryptoBridgeCoin($coin->coinid)->last;
    } else {
        $price = $ticker->getGraviexCoin($coin->coinid)->ticker->last;
    }

    $pow_coins_worth = number_format(($pow_coins * $price), 8);
    $masternode_coins_worth = number_format(($masternode_coins * $price), 8);
    $total_coins_worth = number_format(($pow_coins_worth + $masternode_coins_worth), 8);

    return array(
        "proof-of-work" => array(
            $coin->coinid => $pow_coins,
            "BTC" => $pow_coins_worth
        ),
        "masternode-rewards" => array(
            $coin->coinid => $masternode_coins,
            "BTC" => $masternode_coins_worth
        )
    );
}
?>
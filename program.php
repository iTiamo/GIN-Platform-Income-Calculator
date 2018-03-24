<?php

require_once("calculator.php");
require_once("coin.php");
require_once("ticker.php");

function program() {
    if ($_GET["hashrate"]) {
        $hashrate = $_GET["hashrate"] * 1000000;
    } else {
        $hashrate = 0;
    }
    
    if ($_GET["masternodes"]) {
        $masternodes = $_GET["masternodes"];
    } else {
        $masternodes = 0;
    }

    switch($_GET["coin"]) { //gets the coin and instantiates a class to interact with the coin's daemon
        case "GIN":
            $coin = new coin("GIN", "gincoin", "gincoin", "127.0.0.1", "10112"); 
            break;

        case "LUCKY":
            $coin = new coin("LUCKY", "luckybit", "luckybit", "127.0.0.1", "10113");
            break;

        case "PROTON":
            $coin = new coin("PROTON", "protoncoin", "protoncoin", "127.0.0.1", "10114");
            break;
    }

    $netHashrate = $coin->getNetworkHashPs(101);
    $netMasternodes = $coin->getMasternodeCount(); 
    $difficulty = $coin->getDifficulty();

    $calculator = new calculator();
    $pow_coins = round(($calculator->calculatePoWCoinsByNetworkHashPs($hashrate, $netHashrate, $coin->powReward, $coin->blocktime)), 2);
    $masternode_coins = round(($calculator->calculateMasternodeCoins($masternodes, $netMasternodes, $coin->mnReward, $coin->blocktime)), 2);
    $totalCoins = round(($pow_coins + $masternode_coins), 2);

    $ticker = new ticker();
    if($ticker->getCryptoBridgeCoin($coin->coinid)) {
        $price = $ticker->getCryptoBridgeCoin($coin->coinid)->last;
    } else {
        $price = $ticker->getGraviexCoin($coin->coinid)->ticker->last;
    }

    $pow_coins_worth = round(($pow_coins * $price), 8);
    $masternode_coins_worth = round(($masternode_coins * $price), 8);
    $total_coins_worth = round(($pow_coins_worth + $masternode_coins_worth), 8);

    echo "<p>You will make an average of <b>$pow_coins $coin->coinid</b> per day by Proof of Work, equal to <b>$pow_coins_worth BTC</b>.<br>";
    echo "You will make an average of <b>$masternode_coins $coin->coinid</b> per day by Masternodes, equal to <b>$masternode_coins_worth BTC</b>.<br>";
    echo "You will make a total of <b>$totalCoins $coin->coinid</b> per day, equal to <b>$total_coins_worth BTC</b>.</p>";

    echo "<p>The average network hashrate is <b>" . round($netHashrate/1000000000, 3) . " GHs</b>.<br>";
    echo "The average blocktime is <b>" . round($coin->blocktime) . " seconds</b>.<br>";
    echo "The current amount of Masternodes is <b>$netMasternodes</b>.</p>";
}

?>

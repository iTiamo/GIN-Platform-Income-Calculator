<!DOCTYPE html>

<?php 
require_once("calculator.php");
require_once("rpcclient.php");
require_once("ticker.php");
?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Simple GINcoin Income Calculator</title>
</head>
<body>
    <h1>A Simple <a href="https://gincoin.io/">GINcoin</a> Income Calculator</h1>
    <h3>A <a href="https://gincoin.io/">GINcoin</a> income calculator made by @Tiamo#1675 on GINcoin Discord, written in PHP.</h3>
    
    <p>Insert your hashrate, and amount of masternodes owned and click "submit"; the program will output an estimated amount of daily coins earned.
    Decimal values are supported, for example if you own 25% of a shared Masternode, input 0.25 under "Amount of Masternodes". Both fields are optional.
    This calculator assumes the network hash over the past 1 hour, and dynamically calculates blocktime based on the last 101 blocks.</p>
    
    <p>The <a href="https://github.com/iTiamo/GINcoin-Income-Calculator">source code is available on Github</a>.</p>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>Hashrate (MH/s):
    <input type="text" name="hashrate" value="<?php if ($_GET) { echo $_GET["hashrate"]; }?>"></p>
    <p>Amount of Masternodes:
    <input type="text" name="masternode_multiplier" value="<?php if ($_GET) { echo $_GET["masternode_multiplier"]; }?>"></p>
    <input type="submit">
    </form>
    
    <?php
        if ($_GET) 
        {
            if ($_GET["hashrate"]) {
                $hashrate = $_GET["hashrate"] * 1000000;
            } else {
                $hashrate = 0;
            }
            if ($_GET["masternode_multiplier"]) {
                $masternode_multiplier = $_GET["masternode_multiplier"];
            } else {
                $masternode_multiplier = 0;
            }

            $gincoin = new coin("gincoin", "gincoin", "127.0.0.1", "10112"); //instantiates a class to interact with gincoind, assumes you have set up rpc with username gincoin and password gincoin on port 10112. 
            $networkHashPs = $gincoin->getNetworkHashPs(30);
            $masternode_count = $gincoin->getMasternodeCount(); 
            $difficulty = $gincoin->getDifficulty();
            
            $calculator = new calculator();
            $pow_coins = round(($calculator->calculatePoWCoinsByNetworkHashPs($hashrate, $networkHashPs, $gincoin->powReward, $gincoin->blocktime)), 2);
            $masternode_coins = round(($calculator->calculateMasternodeCoins($masternode_multiplier, $masternode_count, $gincoin->mnReward, $gincoin->blocktime)), 2);
            $totalCoins = round(($pow_coins + $masternode_coins), 2);
            
            $ticker = new ticker();
            $GINprice = $ticker->getGIN()->last;
            $pow_coins_worth = round(($pow_coins * $GINprice), 8);
            $masternode_coins_worth = round(($masternode_coins * $GINprice), 8);
            $total_coins_worth = round(($pow_coins_worth + $masternode_coins_worth), 8);

            echo "<p>You will make an average of <b>$pow_coins GIN</b> per day by Proof of Work, equal to <b>$pow_coins_worth BTC</b>.<br>";
            echo "You will make an average of <b>$masternode_coins GIN</b> per day by Masternodes, equal to <b>$masternode_coins_worth BTC</b>.<br>";
            echo "You will make a total of <b>$totalCoins GIN</b> per day, equal to <b>$total_coins_worth BTC</b>.</p>";
            
            echo "<p>The calculator assumed a nethash of <b>" . round($networkHashPs/1000000000, 3) . " GHs</b> over the past hour.<br>";
            echo "The average blocktime over the past 101 blocks was <b>" . round($gincoin->blocktime) . " seconds</b>.<br>";
            echo "The current amount of Masternodes is <b>$masternode_count</b>.</p>";
        }
    ?>
    
    <p>Tipjar (GIN): GXUQQXBr5i2gKcPaa5SJHqQ9M9G9SgL1X1</p>
</body>
</html>

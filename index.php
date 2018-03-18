<!DOCTYPE html>
<?php 
require_once("explorer.php");
require_once("calculator.php");
require_once("rpcclient.php");
require_once("ticker.php");
?>
<!DOCTYPE html>
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
    Decimal values are supported, for example if you own 25% of a shared Masternode, input 0.25 under "Amount of Masternodes".
    Both fields are optional.</p>
    
    <p>The <a href="https://github.com/iTiamo/GINcoin-Income-Calculator">source code is available on Github</a>.</p>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <p>Hashrate (MH/s):
    <input type="text" name="hashrate" value="<?php if ($_GET) { echo $_GET["hashrate"]; }?>"></p>
    <p>Amount of Masternodes:
    <input type="text" name="masternode_multiplier" value="<?php if ($_GET) { echo $_GET["masternode_multiplier"]; }?>"></p>
    <input type="submit">
    </form>
    
    <?php
        if ($_GET) {
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

            //$explorer = new explorer();
            $rpcclient = new rpcclient(user, password, ip, port);
            $calculator = new calculator();
            $ticker = new ticker();
            
/*          OLD WAY OF CALCULATING COINS/DAY, SLOW AND LOTS OF CALLS TO GINCOIN BLOCK EXPLORER
 *          $blockHeight = $explorer->getBlockHeight(); //get current blockheight
 *          for ($i = $blockHeight; $i > $blockHeight - 30; $i--) { //get the difficulties of the current block, and the previous 29 blocks
 *              $block = $explorer->getBlock($explorer->getBlockHash($i));
 *              $difficulty = $explorer->getDifficulty($block);
 *              $difficulties[$i] = $difficulty;
 *          }
 *          $avg_difficulty = $calculator->averageArray($difficulties); //take the average of the current block, and the previous 29 blocks */
            
            $difficulty = round($rpcclient->getDifficulty(), 0);
            //NEW WAY TO CALCULATE COINS/DAY WITH GINCOIND getnetworkhashps
            $networkHashPs = $rpcclient->getNetworkHashPs(30);            
            $masternode_count = $rpcclient->getMasternodeCount();
            
            $pow_coins = round(($calculator->calculatePoWCoinsByNetworkHashPs($hashrate, $networkHashPs)), 8);
            $masternode_coins = round(($calculator->calculateMasternodeCoins($masternode_multiplier, $masternode_count)), 8);
            $totalCoins = round(($pow_coins + $masternode_coins), 8);
            
            $GINprice = $ticker->getGIN()->last;
            $pow_coins_worth = round(($pow_coins * $GINprice), 8);
            $masternode_coins_worth = round(($masternode_coins * $GINprice), 8);
            $total_coins_worth = round(($pow_coins_worth + $masternode_coins_worth), 8);

            echo "<p>You will make an average of <b>$pow_coins</b> GIN per day by Proof of Work, equal to <b>$pow_coins_worth BTC</b>.<br>";
            echo "You will make an average of <b>$masternode_coins</b> GIN per day by Masternodes, equal to <b>$masternode_coins_worth BTC</b>.<br>";
            echo "You will make a total of <b>$totalCoins</b> GIN per day, equal to <b>$total_coins_worth BTC</b>.</p>";
            
            echo "<p>The current difficulty is $difficulty.<br>";
            echo "The current amount of Masternodes is $masternode_count.</p>";
        }
    ?>
    
    <p>Generic formula for calculating Proof-of-Work coins by difficulty: coins_day=(seconds_day/(d*2^32/hashrate))*block_reward, where 2^32 is the average number of shares needed to find a block at a difficulty of 1<br>
    Generic formula for calculating Proof-of-Work coins by nethash: coins_day=(hashrate/nethash)*((seconds_day/block_time)*block_reward)<br>
    Generic formula for calculating Masternode coins: coins_day=(((seconds_day/block_time)*block_rewards)/masternode_count)</p>
    
    <p>Tipjar (GIN): GXUQQXBr5i2gKcPaa5SJHqQ9M9G9SgL1X1</p>
</body>
</html>
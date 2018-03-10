<!DOCTYPE html>
<?php 
require_once("explorer.php");
require_once("calculator.php");
require_once("rpcclient.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Simple GINcoin Income Calculator</title>
</head>
<body>
    <h1>A Simple GINcoin Income Calculator</h1>
    <h3>A GINcoin income calculator made by @Tiamo#1675 on GINcoin Discord, written in PHP.</h3>
    
    <p>Insert your hashrate, and amount of masternodes owned and click "submit"; the program will output an estimated amount of daily coins earned.
    Decimal values are supported, for example if you own 25% of a shared Masternode, input 0.25 under "Amount of Masternodes".
    Both fields are optional.</p>
    
    <p>Difficulty is estimated from the last 30 blocks (1 hour) and is gotten from the official GINcoin explorer.</p>
    
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

            $explorer = new explorer();
            $rpcclient = new rpcclient("user", "password", "ipaddress", "port");
            $calculator = new calculator();
            
            $blockHeight = $explorer->getBlockHeight(); //get current blockheight
            for ($i = $blockHeight; $i > $blockHeight - 30; $i--) { //get the difficulties of the current block, and the previous 29 blocks
                $block = $explorer->getBlock($explorer->getBlockHash($i));
                $difficulty = $explorer->getDifficulty($block);
                $difficulties[$i] = $difficulty;
            }
            $avg_difficulty = $calculator->averageArray($difficulties); //take the average of the current block, and the previous 29 blocks
            
            $masternode_count = $rpcclient->getMasternodeCount();
            
            $pow_coins = $calculator->calculatePoWCoins($hashrate, $avg_difficulty);
            $masternode_coins = $calculator->calculateMasternodeCoins($masternode_multiplier, $masternode_count);
            $totalCoins = $pow_coins + $masternode_coins;

            echo "<p>You will make an average of $pow_coins GIN per day by Proof of Work. The current difficulty is $difficulty, the average difficulty over the past hour was $avg_difficulty.</p>";
            echo "<p>You will make an average of $masternode_coins GIN per day by Masternodes. The current amount of Masternodes is $masternode_count.</p>";
            echo "<p>You will make a total of $totalCoins GIN per day.</p>";
        }
    ?>
</body>
</html>
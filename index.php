<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Simple GINcoin Income Calculator</title>
</head>

<body>
    <h1>A Simple <a href="https://gincoin.io/">GIN Platform</a> Income Calculator</h1>
    <h3>An income calculator to calculate all of your income of coins on the <a href="https://p.gincoin.io/">GIN Platform</a> made by @Tiamo#1675 on GINcoin Discord, written in PHP.</h3>
    
    <p>Insert your hashrate, and amount of masternodes owned and click "submit"; the program will output an estimated amount of daily coins earned.
    Decimal values are supported, for example if you own 25% of a shared Masternode, input 0.25 under "Amount of Masternodes". Both fields are optional.
    This calculator assumes the network hash over the past 1 hour, and dynamically calculates blocktime based on the last 101 blocks.
    If the calculator is slow, it most likely means the CryptoBridge API is having hiccups.</p>
    
    <p>The <a href="https://github.com/iTiamo/GINcoin-Income-Calculator">source code is available on Github</a>.</p>

    <form>
        <p>Select a coin:
            <select name="coin">
                <option selected disabled hidden>Select a coin</option>
                <option <?php if(!empty($_GET["coin"]) && $_GET["coin"] == "GIN") { echo "selected"; }?>>GIN</option>
                <option <?php if(!empty($_GET["coin"]) && $_GET["coin"] == "LUCKY") { echo "selected"; }?>>LUCKY</option>
                <option <?php if(!empty($_GET["coin"]) && $_GET["coin"] == "PROTON") { echo "selected"; }?>>PROTON</option>
            </select>
        </p>
        <p>Hashrate (MH/s):
            <input type="text" name="hashrate" value="<?php if ($_GET) { echo $_GET["hashrate"]; }?>"></p>
        <p>Amount of Masternodes:
            <input type="text" name="masternodes" value="<?php if ($_GET) { echo $_GET["masternodes"]; }?>"></p>
        <input type="submit" value="Calculate" method="get" action="">
    </form>
    
    <?php   
        if (!empty($_GET) && !empty($_GET["coin"])) { 
            require_once("program.php");
            program(); 
        } 
    ?>
    
    <p>Tipjar (GIN): GXUQQXBr5i2gKcPaa5SJHqQ9M9G9SgL1X1</p>
</body>
</html>

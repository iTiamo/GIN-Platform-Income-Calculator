<?php
require_once("../src/coin.php");
require_once("../src/networkStats.php");

$coin = getCoin($_GET["coin"]);
$networkStats = getNetworkStats($coin);

echo json_encode($networkStats);
?>
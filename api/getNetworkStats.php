<?php
require_once("../src/coin.php");
require_once("../src/networkStats.php");

$coin = getCoin($_GET["coin"]);
$networkStats = getNetworkStats($coin);

header('Content-Type: application/json');
echo json_encode($networkStats);
?>
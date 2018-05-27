<?php
require_once("../src/coin.php");
require_once("../src/income.php");

$coin = getCoin($_GET["coin"]);
$income = calculateIncome($coin, array(
    "hashrate" => $_GET["hashrate"],
    "masternodes" => $_GET["masternodes"]
));

echo json_encode($income);
?>
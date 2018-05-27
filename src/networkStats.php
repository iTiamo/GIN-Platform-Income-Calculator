<?php
function getNetworkStats($coin) {
    $netHashrate = $coin->getNetworkHashPs(101);
    $masternodes = $coin->getMasternodeCount(); 
    $difficulty = $coin->getDifficulty();

    return array(
        "network-hashrate" => $netHashrate,
        "masternodes" => $masternodes,
        "difficulty" => $difficulty
    );
}
?>
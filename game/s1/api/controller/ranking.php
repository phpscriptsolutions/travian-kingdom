<?php
if ($data['action'] == "getWorldStats") {
    echo json_encode(array(
        "serialNo" => $engine->session->serialNo(),
        "time" => round(microtime(true) * 1000),
        "response" => $engine->ranking->getWorldStats(),
    ));
}elseif ($data['action'] == "getKingdomStats") {
    echo json_encode(array(
        "serialNo" => $engine->session->serialNo(),
        "time" => round(microtime(true) * 1000),
        "response" => $engine->ranking->getKingdomStats($data['params']['kingdomId']),
    ));
}elseif($data['action'] == "getKingdomInternalRanking"){
    echo json_encode(array(
        "serialNo" => $engine->session->serialNo(),
        "time" => round(microtime(true) * 1000),
        "response" => [
            'top10Attacker' => [],
            'top10Defender' => [],
            'top10Climber' => [],
        ],
    ));
}
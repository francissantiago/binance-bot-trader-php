<?php
header('Content-Type: application/json'); // Envia a resposta como JSON
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/MarketsController.php';

$msg = [];
if( isset($_POST['binance_api_key']) && isset($_POST['binance_api_secret']) && isset($_POST['trade_pair']) ) {
    $key = $_POST['binance_api_key'];
    $secret = $_POST['binance_api_secret'];
    $market_pair = $_POST['trade_pair'];

    $call_market_class = new MarketsController($key, $secret);
    $callMehod = $call_market_class->rollingWindowTicker($market_pair);

    $msg = [
        'code' => 200,
        'message' => $callMehod
    ];
} else {
    $msg = [
        'code' => 400,
        'message' => 'Invalid or missing submitted fields!'
    ];
}

echo json_encode($msg);
?>
<?php
$app_title = "Binance Trade Bot";
$app_version = "1.0.0";
$app_testnet = 1; // 1 = testnet, 2 = mainnet

// Base endpoints
$app_endpoint_api = "https://api.binance.com/api";
$app_endpoint_ws_api = "wss://ws-api.binance.com/ws-api/v3";
$app_endpoint_ws = "wss://stream.binance.com:9443/ws";
$app_endpoint_stream = "wss://stream.binance.com:9443/stream";

if($app_testnet === 1){
    $app_endpoint_api = "https://testnet.binance.vision/api";
    $app_endpoint_ws_api = "wss://testnet.binance.vision/ws-api/v3";
    $app_endpoint_ws = "wss://testnet.binance.vision/ws";
    $app_endpoint_stream = "wss://testnet.binance.vision/stream";
}

?>
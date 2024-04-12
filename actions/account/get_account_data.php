<?php
header('Content-Type: application/json'); // Envia a resposta como JSON
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/AccountsController.php';

$msg = [];
if( isset($_POST['binance_api_key']) && isset($_POST['binance_api_secret']) ) {
    $key = $_POST['binance_api_key'];
    $secret = $_POST['binance_api_secret'];

    $call_account_class = new AccountsController($key, $secret);
    $callMehod = $call_account_class->spot_accountStatus();

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
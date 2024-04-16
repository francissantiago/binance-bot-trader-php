<?php
class AccountsController {
    public $key;
    public $secret;
    public $client;

    public function __construct($key, $secret) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        $this->key = $key;
        $this->secret = $secret;
        $this->client = new \Binance\Spot(['key' => $key, 'secret' => $secret]);
    }

    public function spot_time() {
        $response = $this->client->time();
        return $response;
    }

    public function spot_account() {
        $response = $this->client->account();
        return $response;
    }

    public function spot_accountStatus() {
        $response = $this->client->accountStatus(['recvWindow' => 5000]);
        return $response;
    }

    public function userAssets() {
        $response = $this->client->userAsset();
        return $response;
    }

    public function tradeFee($market_pair) {
        $response = $this->client->tradeFee([
            'symbol' => $market_pair,
            'recvWindow' => 5000
        ]);
        return $response;
    }
}


?>
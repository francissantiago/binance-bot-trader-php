<?php
class AccountsController {
    public $key;
    public $secret;

    public function __construct($key, $secret) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        $this->key = $key;
        $this->secret = $secret;
    }

    public function spot_time(){
        $client = new \Binance\Spot();
        $response = $client->time();
        return $response;
    }

    public function spot_account() {
        $client = new \Binance\Spot( [ 'key' => $this->key, 'secret' => $this->secret ] );
        $response = $client->account();
        return $response;
    }

    public function spot_accountStatus() {
        $client = new \Binance\Spot( [ 'key' => $this->key, 'secret' => $this->secret ] );
        $response = $client->accountStatus( [ 'recvWindow' => 5000 ] );
        return $response;
    }

    public function userAssets() {
        $client = new \Binance\Spot( [ 'key' => $this->key, 'secret' => $this->secret ] );
        $response = $client->userAsset();

        return $response;
    }

    public function tradeFee($market_pair) {
        $client = new \Binance\Spot( [ 'key' => $this->key, 'secret' => $this->secret ] );
        $response = $client->tradeFee(
            [
                'symbol' => $market_pair,
                'recvWindow' => 5000
            ]
        );

        return $response;
    }
}

?>
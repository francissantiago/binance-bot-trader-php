<?php
class AccountUser {
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
}

?>
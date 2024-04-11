<?php
class MarketsController {
    public $key;
    public $secret;

    public function __construct($key, $secret) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        $this->key = $key;
        $this->secret = $secret;
    }

    public function get_all_markets(){
        $client = new \Binance\Spot([
            'key'  => $this->key,
            'secret'  => $this->secret
        ]);

        $data = $client->exchangeInfo();
        $symbols = $data['symbols'];

        return $symbols;
    }
}

?>
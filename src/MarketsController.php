<?php
class MarketsController {
    public $key;
    public $secret;
    public $client;

    public function __construct($key, $secret) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
        $this->key = $key;
        $this->secret = $secret;
        $this->client = new \Binance\Spot(['key' => $key, 'secret' => $secret]);
    }

    public function getAllMarkets() {
        $data = $this->client->exchangeInfo();
        $symbols = $data['symbols'];

        return $symbols;
    }

    public function getTicker24hr($market) {
        $response = $this->client->ticker24hr(['symbol' => $market]);
        return $response;
    }

    public function rollingWindowTicker($market) {
        $response = $this->client->rollingWindowTicker([
            'symbol' => $market,
            'windowSize' => '5m'
        ]);
        return $response;
    }
}
?>

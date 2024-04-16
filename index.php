<?php
// Inclui os arquivos necessários
require_once 'src/database.php'; // Importa o script de conexão com o banco de dados
require_once 'config/vars.php'; // Importa o arquivo de configuração de variáveis

// Estabelece a conexão com o banco de dados
$conn = connectDatabase();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <title>Binance Trader Bot</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JQuery 3.7.1 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-black text-white">
    <div class="container-fluid">
        <div class="row bg-dark">
            <div class="col-md-12 text-center p-3">
                <h3 class="display-5 p-3 text-uppercase fw-bold">
                    <?php echo $app_title; ?> <!-- Exibe o título do aplicativo -->
                    <a class="btn btn-success btn-lg float-end fw-bold" id="btn_start_bot"><span class="p-3">START</span></a>
                    <a class="btn btn-danger btn-lg float-end fw-bold" id="btn_stop_bot" style="display:none"><span class="p-3">STOP</span></a>
                </h3>
            </div>
        </div>
        <div class="row bg-dark">
            <div class="col-md-12 text-center p-3">
                <span class="float-start fw-bold">Developer: Francis Santiago</span>
                <span class="float-end fw-bold">Version: <?php echo $app_version; ?></span> <!-- Exibe a versão do aplicativo -->
            </div>
            <hr class="bg-warning mt-2 mb-2" style="height: 2px">
        </div>

        <div class="row bg-dark text-dark d-flex align-items-center p-2">
            <div class="row d-flex align-items-center">
                <div class="col-md-6">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-success form-control" role="alert">
                            <span class="fw-bold">Balance</span> : <span id="balance_pair_one" class="float-end fw-bold">0.00000000</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-success form-control" role="alert">
                            <span class="fw-bold">Balance</span> : <span id="balance_pair_two" class="float-end fw-bold">0.00000000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row bg-dark text-dark d-flex align-items-center p-2">
            <div class="row d-flex align-items-center">
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Trade Market</span> : <span id="trade_market_selected" class="float-end">-</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Fee (Taker %)</span> : <span id="fee_level_taker" class="float-end">0.000</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Fee (Maker %)</span> : <span id="fee_level_maker" class="float-end">0.000</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Last Price</span> : <span id="market_last_price" class="float-end">0.00000000</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Last 5m (%)</span> : <span id="last_5_minutes" class="float-end fw-bold">0.0000</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Last 24h (%)</span> : <span id="last_24_hours" class="float-end fw-bold">0.0000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-dark text-dark d-flex align-items-top p-2">
            <div class="col-md-8">
                <div class="accordion accordion-flush mb-2" id="accordionTradeBot">
                    <div class="accordion-item bg-dark text-light">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                <span class="display-6 fw-bold">Authentication</span>
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionTradeBot">
                            <div class="accordion-body">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-5">
                                        <div class="input-group mb-3 mt-3">
                                            <span class="input-group-text bg-dark">🔑</span>
                                            <div class="form-floating">
                                                <input type="password" class="form-control" id="input_binance_api_key" placeholder="Binanc=e API Key">
                                                <label for="input_binance_api_key">Binance API Key</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group mb-3 mt-3">
                                            <span class="input-group-text bg-dark">🔐</span>
                                            <div class="form-floating">
                                                <input type="password" class="form-control" id="input_binance_api_secret" placeholder="Binance API Secret">
                                                <label for="input_binance_api_secret">Binance API Secret</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group mb-3 mt-3" id="div_connect_binance">
                                            <a class="btn btn-success form-control btn-md fw-bold" id="btn_connect_binance">CONNECT</a>
                                        </div>
                                        <div class="input-group mb-3 mt-3" style="display: none" id="div_disconnect_binance">
                                            <a class="btn btn-danger form-control btn-md fw-bold"  id="btn_disconnect_binance">DISCONNECT</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel bg-secondary" id="div_panel" style="display:none">
                        <div class="accordion-item bg-dark text-light">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    <span class="display-6 fw-bold">Balances</span>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionTradeBot">
                                <div class="accordion-body">
                                    <div class="row" id="div_balance">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status" style="width: 4rem; height: 4rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-dark text-light">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <span class="display-6 fw-bold">Bot Settings</span>
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionTradeBot">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Market Pairs</span>
                                                <select class="form-control" id="select_trade_pair_coin">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Analysis Interval(seconds)</span>
                                                <input type="text" class="form-control" id="input_analysis_interval" placeholder="2">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Analysis Candles Depth</span>
                                                <input type="text" class="form-control" id="input_analysis_candles_depth" placeholder="100">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Target Profit(%)</span>
                                                <input type="text" class="form-control" id="input_target_profit" placeholder="2.53">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Max Lose(%)</span>
                                                <input type="text" class="form-control" id="input_max_lose" placeholder="2.53">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-1 mt-1">
                                                <span class="input-group-text bg-dark text-light">Max Profit(%)</span>
                                                <input type="text" class="form-control" id="input_max_profit" placeholder="2.53">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group mb-1 mt-2" id="div_save_bot_settings">
                                                <a class="btn btn-success btn-lg fw-bold form-control text-uppercase" id="btn_save_settings">Save Settings</a>
                                            </div>
                                            <div class="input-group mb-1 mt-2" id="div_clear_bot_settings" style="display: none">
                                                <a class="btn btn-danger btn-lg fw-bold form-control text-uppercase" id="btn_clear_settings">Clear Settings</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-dark text-light">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                    <span class="display-6 fw-bold">Transactions</span>
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionTradeBot">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="accordion-item bg-dark text-light">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseBuy" aria-expanded="false" aria-controls="flush-collapseBuy">
                                                        <span class="fw-bold">BUY</span>
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseBuy" class="accordion-collapse collapse" data-bs-parent="#accordionBuy">
                                                    <div class="accordion-body">
                                                        <div class="row" id="div_buy">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border" role="status" style="width: 4rem; height: 4rem;">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="accordion-item bg-dark text-light">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSell" aria-expanded="false" aria-controls="flush-collapseSell">
                                                        <span class="fw-bold">SELL</span>
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseSell" class="accordion-collapse collapse" data-bs-parent="#accordionSell">
                                                    <div class="accordion-body">
                                                        <div class="row" id="div_sell">
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border" role="status" style="width: 4rem; height: 4rem;">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <textarea class="form-control" rows="20">Console:</textarea>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script src="dist/js/main.js"></script>
</body>
</html>

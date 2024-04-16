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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="input-group mb-1 mt-1">
                        <div class="alert alert-light form-control" role="alert">
                            <span class="fw-bold">Last Price</span> : <span id="market_last_price" class="float-end">0.00000000</span>
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
        <div class="row bg-dark text-dark d-flex align-items-center p-2">
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
                                    <div class="col-md-3">
                                        <div class="input-group mb-1 mt-1">
                                            <span class="input-group-text bg-dark text-light">Market Pairs</span>
                                            <select class="form-control" id="select_trade_pair_coin">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-1 mt-1">
                                            <span class="input-group-text bg-dark text-light">Target Profit(%)</span>
                                            <input type="text" class="form-control" id="input_target_profit" placeholder="2.53">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-1 mt-1">
                                            <span class="input-group-text bg-dark text-light">Max Lose(%)</span>
                                            <input type="text" class="form-control" id="input_max_lose" placeholder="2.53">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
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

        <div class="row bg-dark">
            <div class="col-md-12 mt-2 mb-4">
                <span class="fw-bold" style="font-size: 20px">Console:</span>
                <textarea class="form-control" rows="20"></textarea>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script type="text/javascript">
        $(document).ready(() => {
            /* =================================================================
            * VARIÁVEIS
            * ==================================================================
            */
           // Authentication
            let input_binance_api_key = $('#input_binance_api_key');
            let input_binance_api_secret = $('#input_binance_api_secret');

            let btn_connect_binance = $('#btn_connect_binance');
            let btn_disconnect_binance = $('#btn_disconnect_binance');

            let div_connect_binance = $('#div_connect_binance');
            let div_disconnect_binance = $('#div_disconnect_binance');

            // Settings
            let select_trade_pair_coin = $('#select_trade_pair_coin');
            let input_target_profit = $('#input_target_profit');
            let input_max_lose = $('#input_max_lose');
            let input_max_profit = $('#input_max_profit');
            let btn_save_settings = $('#btn_save_settings');
            let btn_clear_settings = $('#btn_clear_settings');

            // Bot buttonns actions
            let btn_start_bot = $('#btn_start_bot');
            let btn_stop_bot = $('#btn_stop_bot');
            
            // Genenral
            let div_panel = $('#div_panel');
            let div_balance = $('#div_balance');
            let div_buy = $('#div_buy');
            let div_sell = $('#div_sell');
            let div_save_bot_settings = $('#div_save_bot_settings');
            let div_clear_bot_settings = $('#div_clear_bot_settings');

            let fee_level_taker = $('#fee_level_taker');
            let fee_level_maker = $('#fee_level_maker');
            let market_last_price = $('#market_last_price');
            let last_24_hours = $('#last_24_hours');
            let trade_market_selected = $('#trade_market_selected');

            /* =================================================================
            * BOTÃO DE CONEXÃO E TESTE DE AUTENTICAÇÃO BINANCE
            * ==================================================================
            */
            // Evento de clique no botão "CONNECT"
            btn_connect_binance.click((e) => {
                e.preventDefault();

                let binance_api_key = input_binance_api_key.val();
                let binance_api_secret = input_binance_api_secret.val();

                // Verifica se as chaves da API foram inseridas
                if(!binance_api_key) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Binance API Key not entered!"
                    }).then(() => { input_binance_api_key.focus(); });
                } else if(!binance_api_secret) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Binance Secret Key not entered!"
                    }).then(() => { input_binance_api_secret.focus(); });
                } else {
                    // Realiza uma requisição AJAX para obter os dados da conta
                    $.ajax({
                        url: "actions/account/get_account_data.php",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            binance_api_key:binance_api_key,
                            binance_api_secret:binance_api_secret
                        }, success: function(data) {
                            if(data.code === 200){
                                // Exibe um alerta de sucesso
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: "Connected successfully!"
                                }).then(() => {
                                    // Armazena as credenciais no LocalStorage e recarrega a página
                                    let user_credentials = ({
                                        binance_api_key: binance_api_key,
                                        binance_api_secret: binance_api_secret
                                    });

                                    credentials.push(user_credentials);
                                    localStorage.setItem("local_credentials", JSON.stringify(credentials));

                                    window.location.reload();
                                });
                            } else {
                                // Exibe um alerta de erro
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: data.message
                                });
                            }
                        }, error: function (e) {
                            // Exibe um alerta de erro e loga os detalhes no console
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "An error occurred when trying to connect, more details in the console"
                            });
                            console.log(`Error: ${JSON.stringify(e)}`);
                        }
                    })
                }
            });

            // Evento de clique no botão "DISCONNECT"
            btn_disconnect_binance.click((e) => {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure",
                    text: "you would like to disconnect your account?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, disconnect!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.clear();
                        Swal.fire({
                            title: "Disconnected!",
                            text: "Your account has been successfully disconnected!",
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            });

            /* =================================================================
            * VERIFICAÇÕES DE ACESSO DO USUÁRIO
            * ==================================================================
            */

            // Cria um objeto "local_credentials" no LocalStorage se não existir
            if (!localStorage.getItem("local_credentials")) {
                localStorage.setItem("local_credentials", JSON.stringify([]));
            }

            if (!localStorage.getItem("local_settings")) {
                localStorage.setItem("local_settings", JSON.stringify([]));
            }

            // Obtém os dados do LocalStorage
            var credentials = JSON.parse(localStorage.getItem("local_credentials"));
            var settings = JSON.parse(localStorage.getItem("local_settings"));

            // Se a conversão for bem-sucedida, a chave possui dados
            if (credentials.length > 0) {
                input_binance_api_key.attr('disabled', true).val('*****************************');
                input_binance_api_secret.attr('disabled', true).val('*****************************');
                div_connect_binance.hide();
                div_disconnect_binance.show();
                div_panel.show();

                 // Recupera as credenciais do primeiro objeto
                var firstCredentials = credentials[0];

                // Atribui as credenciais a variáveis separadas
                var saved_binance_api_key = firstCredentials.binance_api_key;
                var saved_binance_api_key_secret = firstCredentials.binance_api_secret;

                /* =================================================================
                * AÇÕES PARA USUÁRIO AUTENTICADO
                * ==================================================================
                */
               // Lista todos os ativos com saldo do usuário
                $.ajax({
                    url: "actions/account/get_all_active_balances.php",
                    type: "POST",
                    data: {
                        binance_api_key:saved_binance_api_key,
                        binance_api_secret:saved_binance_api_key_secret
                    },
                    dataType: "json",
                    success: function (resultado) {
                        var resultFilter = resultado['message'];
                        options = [];
                        $.each(resultFilter, function (index, value){
                            options += `<div class="col-md-3">
                                            <div class="alert alert-info" role="alert">
                                                <span class="fw-bold" id="balance_coin">${value.asset}</span>
                                                <span class="float-end" id="balance_amount">${parseFloat(value.free).toFixed(8)}</span>
                                            </div>
                                        </div>
                            `;
                        });

                        div_balance.html(options);
                    }
                });

                // Exibe as taxas de acordo com o mercado selecionado
                select_trade_pair_coin.change(() => {
                    let select_trade_pair_coin_value = select_trade_pair_coin.val();

                    if(select_trade_pair_coin_value){
                        getMarketData(saved_binance_api_key, saved_binance_api_key_secret, select_trade_pair_coin_value);
                    } else {
                        fee_level_taker.html('0.000');
                        fee_level_maker.html('0.000');
                    }
                });

                // Salva configurações de trade
                btn_save_settings.click((e) => {
                    e.preventDefault();

                    Swal.fire({
                        title: "Are you sure",
                        text: "you want to save your current trade settings?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Saved!",
                                text: "Your settings have been saved! Launch the bot and good profits!",
                                icon: "success"
                            }).then(() => {
                                // Armazena as connfigurações de trade no LocalStorage e recarrega a página
                                let trade_settings = ({
                                    select_trade_pair_coin:select_trade_pair_coin.val(),
                                    input_target_profit:parseFloat(input_target_profit.val()),
                                    input_max_lose:parseFloat(input_max_lose.val()),
                                    input_max_profit:parseFloat(input_max_profit.val())
                                });
                                settings.push(trade_settings);
                                localStorage.setItem("local_settings", JSON.stringify(settings));
                                window.location.reload();
                            });
                        }
                    });
                });

                // Limpar configurações de trade
                btn_clear_settings.click((e) => {
                    e.preventDefault();

                    Swal.fire({
                        title: "Do you want",
                        text: "to clear your current trade settings?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, clean!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Clean!",
                                text: "Your trade settings have been cleared!",
                                icon: "success"
                            }).then(() => {
                                // Limpa as configurações de trade no LocalStorage e recarrega a página
                                localStorage.removeItem("local_settings");
                                window.location.reload();
                            });
                        }
                    });
                });

                if (settings.length > 0) {
                    // Recupera as credenciais do primeiro objeto
                    var settingsData = settings[0];

                    // Atribui as credenciais a variáveis separadas
                    let saved_trade_pair_coin = settingsData.select_trade_pair_coin;
                    let saved_target_profit = settingsData.input_target_profit;
                    let saved_max_lose = settingsData.input_max_lose;
                    let saved_max_profit = settingsData.input_max_profit;

                    let options = `<option class="text-capitalize" value=""> ${saved_trade_pair_coin}</option>`;
                    select_trade_pair_coin.html(options)
                                        .addClass('bg-secondary text-white')
                                        .attr('disabled', true);
                    input_target_profit.addClass('bg-secondary text-white').attr('readonly', true).val(saved_target_profit);
                    input_max_lose.addClass('bg-secondary text-white').attr('readonly', true).val(saved_max_lose);
                    input_max_profit.addClass('bg-secondary text-white').attr('readonly', true).val(saved_max_profit);

                    getMarketData(saved_binance_api_key, saved_binance_api_key_secret, saved_trade_pair_coin)

                    div_save_bot_settings.hide();
                    div_clear_bot_settings.show();
                } else {
                    // Lista todos as moedas disponíveis na Binance
                    $.ajax({
                        url: "actions/market/get_all_markets.php",
                        type: "POST",
                        data: {
                            binance_api_key:saved_binance_api_key,
                            binance_api_secret:saved_binance_api_key_secret
                        },
                        dataType: "json",
                        success: function (resultado) {
                            var resultFilter = resultado['message'];

                            // Sort the resultFilter based on value.symbol in ASC order
                            resultFilter.sort(function(a, b) {
                                return (a.symbol > b.symbol) ? 1 : ((a.symbol < b.symbol) ? -1 : 0);
                            });

                            var options = '<option value=""> - </option>';
                            $.each(resultFilter, function (index, value){
                                if(value.status === 'TRADING'){
                                    options = options + `<option class="text-capitalize" value="${value.symbol}"> ${value.baseAsset} <-> ${value.quoteAsset}</option>`;
                                }
                            });
                            
                            select_trade_pair_coin.html(options);
                        }
                    });
                }
            }

            function getMarketData(binance_api_key, binance_api_secret, trade_pair){
                // Retorna as taxas do mercado selecionado
                $.ajax({
                    url: "actions/account/get_trade_fee.php",
                    type: "POST",
                    data: {
                        binance_api_key:saved_binance_api_key,
                        binance_api_secret:saved_binance_api_key_secret,
                        trade_pair:trade_pair
                    },
                    dataType: "json",
                    success: function (resultado) {
                        let resultFilter = resultado['message'][0];
                        let fee_maker = parseFloat(resultFilter.makerCommission) * 100;
                        let fee_taket = parseFloat(resultFilter.takerCommission) * 100;

                        fee_level_taker.html(fee_taket.toFixed(3));
                        fee_level_maker.html(fee_maker.toFixed(3));
                    }
                });

                // Retorna informações sobre o mercado
                $.ajax({
                    url: "actions/market/get_market_last_24h.php",
                    type: "POST",
                    data: {
                        binance_api_key:saved_binance_api_key,
                        binance_api_secret:saved_binance_api_key_secret,
                        trade_pair:trade_pair
                    },
                    dataType: "json",
                    success: function (resultado) {
                        let resultFilter = resultado['message'];
                        let lastPrice = parseFloat(resultFilter.lastPrice);
                        let priceChangePercent = parseFloat(resultFilter.priceChangePercent);

                        console.log(resultFilter)

                        market_last_price.html(lastPrice.toFixed(8));
                        last_24_hours.html(priceChangePercent.toFixed(4));

                        if(priceChangePercent > 0){
                            last_24_hours.addClass('text-success');
                        } else {
                            last_24_hours.addClass('text-danger');
                        }
                    }
                });

                trade_market_selected.html(trade_pair);
            }
        });
    </script>
</body>
</html>

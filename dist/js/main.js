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
    let input_analysis_interval = $('#input_analysis_interval');
    let input_analysis_candles_depth = $('#input_analysis_candles_depth');
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
    let last_5_minutes = $('#last_5_minutes');
    let trade_market_selected = $('#trade_market_selected');
    let balance_pair_one = $('#balance_pair_one');
    let balance_pair_two = $('#balance_pair_two');

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

    if (!localStorage.getItem("local_market_settings")) {
        localStorage.setItem("local_market_settings", JSON.stringify([]));
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
        * INÍCIO DAS AÇÕES PARA USUÁRIO AUTENTICADO
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

        // Evento de clique no botão "SAVE SETTINGS"
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
                            input_analysis_interval:parseInt(input_analysis_interval.val()),
                            input_analysis_candles_depth:parseInt(input_analysis_candles_depth.val()),
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

        // Evento de clique no botão "CLEAR SETTINGS"
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
            let saved_analysis_interval = settingsData.input_analysis_interval;
            let saved_analysis_candles_depth = settingsData.input_analysis_candles_depth;
            let saved_target_profit = settingsData.input_target_profit;
            let saved_max_lose = settingsData.input_max_lose;
            let saved_max_profit = settingsData.input_max_profit;

            let options = `<option class="text-capitalize" value=""> ${saved_trade_pair_coin}</option>`;
            select_trade_pair_coin.html(options)
                                .addClass('bg-secondary text-white')
                                .attr('disabled', true);
            input_analysis_interval.addClass('bg-secondary text-white').attr('readonly', true).val(saved_analysis_interval);
            input_analysis_candles_depth.addClass('bg-secondary text-white').attr('readonly', true).val(saved_analysis_candles_depth);
            input_target_profit.addClass('bg-secondary text-white').attr('readonly', true).val(saved_target_profit);
            input_max_lose.addClass('bg-secondary text-white').attr('readonly', true).val(saved_max_lose);
            input_max_profit.addClass('bg-secondary text-white').attr('readonly', true).val(saved_max_profit);

            getMarketData(saved_binance_api_key, saved_binance_api_key_secret);
            setInterval(() => {
                getMarketData(saved_binance_api_key, saved_binance_api_key_secret);
            }, 30000);

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
                            options = options + `<option class="text-capitalize" value="${value.symbol}" data-base="${value.baseAsset}" data-quote="${value.quoteAsset}"> ${value.baseAsset} <-> ${value.quoteAsset}</option>`;
                        }
                    });
                    
                    select_trade_pair_coin.html(options);

                    // Event listener for select change
                    select_trade_pair_coin.change(function() {
                        var selectedOption = $(this).find(':selected');
                        var symbol = selectedOption.val();
                        var baseAsset = selectedOption.data('base');
                        var quoteAsset = selectedOption.data('quote');

                        // Armazena as connfigurações de trade no LocalStorage e recarrega a página
                        let market_user_settings = ({
                            symbol:symbol,
                            baseAsset:baseAsset,
                            quoteAsset:quoteAsset
                        });
                        
                        localStorage.setItem("local_market_settings", JSON.stringify(market_user_settings));
                        getMarketData(saved_binance_api_key, saved_binance_api_key_secret);
                    });
                }
            });
        }

        // Evento de clique no botão "START"
        btn_start_bot.click((e) => {
            e.preventDefault();
            let market_settings = JSON.parse(localStorage.getItem("local_market_settings"));
            // Recupera as credenciais do objeto
            let marketSettingsData = market_settings;
    
            // Atribui as credenciais a variáveis separadas
            let saved_trade_baseAsset = marketSettingsData.baseAsset;
            let saved_trade_quoteAsset = marketSettingsData.quoteAsset;
            let saved_trade_symbol = marketSettingsData.symbol;

            setInterval(() => {
                start_bot(saved_binance_api_key, saved_binance_api_key_secret, saved_trade_symbol, currentBalance, currentPrice, interval, limit);
            }, 30000);
        }); // Atualiza a cada 5 minutos
        /* =================================================================
        * FIM DAS AÇÕES PARA USUÁRIO AUTENTICADO
        * ==================================================================
        */

    }

    function getMarketData(binance_api_key, binance_api_secret){
        let market_settings = JSON.parse(localStorage.getItem("local_market_settings"));
        // Recupera as credenciais do objeto
        let marketSettingsData = market_settings;

        // Atribui as credenciais a variáveis separadas
        let saved_trade_baseAsset = marketSettingsData.baseAsset;
        let saved_trade_quoteAsset = marketSettingsData.quoteAsset;
        let saved_trade_symbol = marketSettingsData.symbol;


        // Retorna as taxas do mercado selecionado
        $.ajax({
            url: "actions/account/get_trade_fee.php",
            type: "POST",
            data: {
                binance_api_key:saved_binance_api_key,
                binance_api_secret:saved_binance_api_key_secret,
                trade_pair:saved_trade_symbol
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

        // Retorna informações do último preço
        $.ajax({
            url: "actions/market/get_market_last_price.php",
            type: "POST",
            data: {
                binance_api_key:saved_binance_api_key,
                binance_api_secret:saved_binance_api_key_secret,
                trade_pair:saved_trade_symbol
            },
            dataType: "json",
            success: function (resultado) {
                let resultFilter = resultado['message'];
                let lastPrice = parseFloat(resultFilter.price);

                market_last_price.html(lastPrice.toFixed(8));
            }
        });

        // Retorna informações sobre o mercado a cada 24h
        $.ajax({
            url: "actions/market/get_market_last_24h.php",
            type: "POST",
            data: {
                binance_api_key:saved_binance_api_key,
                binance_api_secret:saved_binance_api_key_secret,
                trade_pair:saved_trade_symbol
            },
            dataType: "json",
            success: function (resultado) {
                let resultFilter = resultado['message'];
                let priceChangePercent = parseFloat(resultFilter.priceChangePercent);

                last_24_hours.html(priceChangePercent.toFixed(4));

                if(priceChangePercent > 0){
                    last_24_hours.removeClass('text-danger').addClass('text-success');
                } else {
                    last_24_hours.removeClass('text-success').addClass('text-danger');
                }
            }
        });

        // Retorna informações sobre o mercado a cada 5 minutos
        $.ajax({
            url: "actions/market/get_market_last_5min.php",
            type: "POST",
            data: {
                binance_api_key:saved_binance_api_key,
                binance_api_secret:saved_binance_api_key_secret,
                trade_pair:saved_trade_symbol
            },
            dataType: "json",
            success: function (resultado) {
                let resultFilter = resultado['message'];
                let priceChangePercent = parseFloat(resultFilter.priceChangePercent);

                last_5_minutes.html(priceChangePercent.toFixed(4));

                if(priceChangePercent > 0){
                    last_5_minutes.removeClass('text-danger').addClass('text-success');
                } else {
                    last_5_minutes.removeClass('text-success').addClass('text-danger');
                }
            }
        });

         // Retorna informações sobre o saldo do usuário nos pares selecionados
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
                var pair_one = "";
                var pair_two = "";
                $.each(resultFilter, function (index, value) {
                    if(value.asset === saved_trade_baseAsset) {
                        pair_one = parseFloat(value.free).toFixed(8);
                    }

                    if(value.asset === saved_trade_quoteAsset) {
                        pair_two = parseFloat(value.free).toFixed(8);
                    }
                });

                balance_pair_one.html(pair_one);
                balance_pair_two.html(pair_two);
            }
        });

        trade_market_selected.html(saved_trade_symbol);

        console.log("Market data successfully updated at", new Date().toLocaleString());
    }

    function start_bot(binance_api_key, binance_api_secret, trade_pair, currentBalance, currentPrice, interval, limit){
        $.ajax({
            url: "actions/trade/bot.php",
            type: "POST",
            data: {
                binance_api_key:binance_api_key,
                binance_api_secret:binance_api_secret,
                trade_pair:trade_pair,
                currentBalance:currentBalance,
                currentPrice:currentPrice,
                interval:interval,
                limit:limit
            },
            dataType: "json",
            success: function (resultado) {
                
            }
        });
    }
});
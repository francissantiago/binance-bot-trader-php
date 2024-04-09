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
</head>
<body class="bg-black text-white">
    <div class="container">
        <div class="row bg-dark rounded-bottom-2">
            <div class="col-md-12 text-center p-1">
                <h3 class="display-5 p-3 text-uppercase fw-bold">
                    <?php echo $app_title; ?> <!-- Exibe o título do aplicativo -->
                </h3>
                <span class="float-start">Developer: Francis Santiago</span>
                <span class="float-end">Version: <?php echo $app_version; ?></span> <!-- Exibe a versão do aplicativo -->
            </div>
        </div>
        <div class="row bg-light text-dark d-flex align-items-center">
            <div class="col-md-5">
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text">@</span>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="input_binance_api_key" placeholder="Binance API Key">
                        <label for="input_binance_api_key">Binance API Key</label>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group mb-3 mt-3 input-group">
                    <span class="input-group-text">@</span>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="input_binance_api_secret" placeholder="Binance API Secret">
                        <label for="input_binance_api_secret">Binance API Secret</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group mb-3 mt-3" id="div_connect_binance">
                    <a class="btn btn-success form-control btn-md fw-bold" id="connect_binance_btn">CONNECT</a>
                </div>
                <div class="input-group mb-3 mt-3" style="display: none" id="disconnect_binance">
                    <a class="btn btn-danger form-control btn-md fw-bold"  id="disconnect_binance">DISCONNECT</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script type="text/javascript">
        $(document).ready(() => {
            let input_binance_api_key = $('#input_binance_api_key');
            let input_binance_api_secret = $('#input_binance_api_secret');
            let btn_connect_binance = $('#connect_binance_btn');
            let div_connect_binance = $('#div_connect_binance');
            let disconnect_binance = $('#disconnect_binance');

            // Cria um objeto "local_credentials" no LocalStorage se não existir
            if (!localStorage.getItem("local_credentials")) {
                localStorage.setItem("local_credentials", JSON.stringify([]));
            }

            // Obtém os dados do LocalStorage
            var credentials = JSON.parse(localStorage.getItem("local_credentials"));

            // Se a conversão for bem-sucedida, a chave possui dados
            if (credentials.length > 0) {
                input_binance_api_key.attr('disabled', true).val('*****************************');
                input_binance_api_secret.attr('disabled', true).val('*****************************');
                div_connect_binance.hide();
                disconnect_binance.show();
            }

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
                        url: "actions/get_account_data.php",
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
        });
    </script>
</body>
</html>

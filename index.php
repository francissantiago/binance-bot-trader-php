<?php
require_once 'src/database.php';
require_once 'config/vars.php';
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
                    <?php echo $app_title; ?>
                </h3>
                <span class="float-start">Developer: Francis Santiago</span>
                <span class="float-end">Version: <?php echo $app_version; ?></span>
            </div>
        </div>
        <div class="row bg-light text-dark d-flex align-items-center">
            <div class="col-md-5">
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text">@</span>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="binance_api_key" placeholder="Binance API Key">
                        <label for="binance_api_key">Binance API Key</label>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group mb-3 mt-3 input-group">
                    <span class="input-group-text">@</span>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="binance_api_secret" placeholder="Binance API Secret">
                        <label for="binance_api_secret">Binance API Secret</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group mb-3 mt-3">
                    <button type="submit" class="btn btn-success form-control btn-md fw-bold">CONNECT</button>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
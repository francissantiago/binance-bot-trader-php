CREATE DATABASE binance_crypto_trader_db;

USE binance_crypto_trader_db;

CREATE TABLE trades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    symbol VARCHAR(20) NOT NULL,
    amount DECIMAL(18,8) NOT NULL,
    price DECIMAL(18,8) NOT NULL,
    action ENUM('buy', 'sell') NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

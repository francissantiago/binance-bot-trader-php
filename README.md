# Binance Trader Bot

## Under Construction!

### System Requirements:
- PHP Version 8.2.0
- MySQL 5.7.40
- Composer

### Generate API keys
- Access the link and generate a new API key: https://www.binance.com/pt-BR/my/settings/api-management
- Edit restrictions and add settings for: Enable Reading, Enable Spot & Margin Trading, Restrict access to trusted IPs only (recommended).
- Under Restrict access to only trusted IPs (recommended), add your IP so that only the machine on which the script is running can connect to your API key.
- Save the settings.

### Install dependences
```sh
composer require binance/binance-connector-php
```

### Configure SSL Certificate for Wamp Server(Windows)
- Download the certificate bundle.(https://curl.se/docs/caextract.html)
- Put it somewhere. In my case, that was c:\wamp\ directory (if you are using Wamp 64 bit then it's c:\wamp64\).
- Enable mod_ssl in Apache and `extension=openssl` in php.ini (uncomment them by removing ; at the beginning). But be careful, my problem was that I had two php.ini files and I need to do this in both of them. One is the one you get from your WAMP taskbar icon, and another one is, in my case, in `C:\wamp\bin\php\php8.2.0\`
- Add these lines to your cert in both php.ini files:
```sh
curl.cainfo="C:/wamp/cacert.pem"
openssl.cafile="C:/wamp/cacert.pem"
```
- Restart Wamp services.

Credits: https://stackoverflow.com/users/1090395/mladen-janjetovic<br>
Reference: https://stackoverflow.com/questions/28858351/php-ssl-certificate-error-unable-to-get-local-issuer-certificate
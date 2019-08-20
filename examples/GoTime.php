<?php
include '../vendor/autoload.php';

use KuCoin\SDK\Http\SwooleHttp;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PublicApi\Time;

// Set the base uri, default "https://api.kucoin.com" for production environment.
// KuCoinApi::setBaseUri('https://api.kucoin.com');

// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode
go(function () {
    $api = new Time(null, new SwooleHttp);
    $timestamp = $api->timestamp();
    var_dump($timestamp);
});
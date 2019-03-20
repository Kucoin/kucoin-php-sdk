<?php
include '../vendor/autoload.php';

use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PublicApi\Time;
use KuCoin\SDK\Http\SwooleHttp;

// Set the base uri, default "https://openapi-v2.kucoin.com" for production environment.
// KuCoinApi::setBaseUri('https://openapi-v2.kucoin.com');

// Use Coroutine http client "Saber":
// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode
go(function () {
    $api = new Time(null, new SwooleHttp());
    $timestamp = $api->timestamp();
    var_dump($timestamp);
});
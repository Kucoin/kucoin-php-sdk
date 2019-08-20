<?php
include '../vendor/autoload.php';

use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PublicApi\Time;

// Set the base uri, default "https://api.kucoin.com" for production environment.
// KuCoinApi::setBaseUri('https://api.kucoin.com');

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);

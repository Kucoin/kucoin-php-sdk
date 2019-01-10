<?php
include '../vendor/autoload.php';

use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PublicApi\Time;

// Set base uri for other environments, default https://openapi-v2.kucoin.com
// KuCoinApi::setBaseUri('https://openapi-v2.kucoin.com');

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);

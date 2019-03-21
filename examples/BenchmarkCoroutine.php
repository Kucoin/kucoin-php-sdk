<?php
include '../vendor/autoload.php';

use Swoole\Event;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PublicApi\Time;
use KuCoin\SDK\Http\SwooleHttp;

$maxCount = 100;
$start = microtime(true);

//syncIO();
asyncIO();

$cost = microtime(true) - $start;
echo 'Cost: ', $cost, PHP_EOL;


function syncIO()
{
    global $maxCount;
    $api = new Time();
    for ($i = 0; $i < $maxCount; $i++) {
        $timestamp = $api->timestamp();
        var_dump($timestamp);
    }
}

function asyncIO()
{
    global $maxCount;
    $api = new Time(null, new SwooleHttp);
    for ($i = 0; $i < $maxCount; $i++) {
        go(function () use ($api) {
            $timestamp = $api->timestamp();
            var_dump($timestamp);
        });
    }
    Event::wait();
}
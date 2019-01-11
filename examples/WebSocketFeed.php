<?php
include '../vendor/autoload.php';

use KuCoin\SDK\Auth;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

// Set the base uri, default "https://openapi-v2.kucoin.com" for production environment.
// KuCoinApi::setBaseUri('https://openapi-v2.kucoin.com');

$auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

$query = ['connectId' => uniqid('', true)];
$channel = [
    'type'  => 'subscribe',
    'topic' => '/market/snapshot:BTC-USDT',
];

$options = [
    'tls' => [
        'verify_peer' => false,
    ],
];
$api->subscribePublicChannel($query, $channel, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    // ping
    // $ws->send(json_encode($api->createPingMessage()));
    var_dump($message);

    // stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
}, $options);

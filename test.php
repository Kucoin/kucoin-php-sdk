<?php
require_once "./vendor/autoload.php";

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;


$auth = null;
// Need to pass the Auth parameter when subscribing to a private channel($api->subscribePrivateChannel()).
// $auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);



$query = ['connectId' => uniqid('', true)];
$channels = [
    ['topic' => '/market/ticker:BTC-USDT'], // Subscribe multiple channels
    //['topic' => '/market/ticker:ETH-BTC'],
];

$symbolApi = new \KuCoin\SDK\PublicApi\Symbol();
$symbolsData = $symbolApi->getListV2();

// 创建订阅表
$channels = array();
foreach ($symbolsData as $item) {
    $channels[] = ['topic' => '/market/ticker:' . $item['symbol']];
}

$channels = array_slice($channels, 0, 100);
$channels[] = ['topic' => '/market/ticker:BTC-USDT'];
$api->subscribePublicChannels($query, $channels, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    var_dump($message);
    //sleep(30);
    //sleep(1);
    // Subscribe another channel
    // $ws->send(json_encode($api->createSubscribeMessage('/market/ticker:LTC-BTC')));

    // Unsubscribe the channel
    // $ws->send(json_encode($api->createUnsubscribeMessage('/market/ticker:ETH-BTC')));

    // Stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
}, ['tls' => ['verify_peer' => false]]);
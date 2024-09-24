<?php
include '../vendor/autoload.php';

use KuCoin\SDK\Auth;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;

// Set the base uri, default "https://api.kucoin.com" for production environment.
// KuCoinApi::setBaseUri('https://api.kucoin.com');

$auth = null;
// Need to pass the Auth parameter when subscribing to a private channel($api->subscribePrivateChannel()).
// $auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

// Use a custom event loop instance if you like
//$loop = Loop::get();
//$loop->addPeriodicTimer(1, function () {
//    var_dump(date('Y-m-d H:i:s'));
//});
//$api->setLoop($loop);

$query = ['connectId' => uniqid('', true)];
$channels = [
    ['topic' => '/market/ticker:KCS-BTC'], // Subscribe multiple channels
    ['topic' => '/market/ticker:ETH-BTC'],
];

// Optional Configuration
$options = [
    'tcp'              => [
        'tcp_nodelay'    => true,   // Socket context options: https://www.php.net/manual/en/context.socket.php
        'socket_options' => [       // Socket options: https://www.php.net/manual/en/function.socket-set-option.php
              SO_RCVBUF => 1048576, // Example: set SO_RCVBUF=1MB
                                    // ...
        ],
    ],
    'enable_reconnect' => true, // Enable automatic reconnection, true or false, default false.
    'max_reconnects'   => 10,    // Maximum number of reconnections, >=0, default 10. The total number of connections is max_reconnects+1, 0 means infinite reconnection.
    'reconnect_delay'  => 1000, // How many milliseconds to wait before reconnecting, >=0, default 1000ms. 0 means no delay.
];
$api->subscribePublicChannels($query, $channels, function (array $message, WebSocket $ws, LoopInterface $loop, array $connectInfo) use ($api) {
    var_dump($message);

    // Subscribe another channel
    // $ws->send(json_encode($api->createSubscribeMessage('/market/ticker:LTC-BTC')));

    // Unsubscribe the channel
    // $ws->send(json_encode($api->createUnsubscribeMessage('/market/ticker:ETH-BTC')));

    // Stop loop
    // $loop->stop();
}, function ($code, $reason, array $connectInfo) {
    echo "OnClose: code={$code} reason={$reason} connectId={$connectInfo['connectId']} connectToken={$connectInfo['token']}\n";
}, $options);

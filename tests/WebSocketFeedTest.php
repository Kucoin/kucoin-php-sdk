<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

class WebSocketFeedTest extends TestCase
{
    public function testNewAuth()
    {
        $auth = new Auth($this->apiKey, $this->apiSecret, $this->apiPassPhrase);
        $this->assertInstanceOf(Auth::class, $auth);
        return $auth;
    }

    /**
     * @depends testNewAuth
     * @param Auth $auth
     * @return WebSocketFeed
     */
    public function testNewWebSocketFeed(Auth $auth)
    {
        $api = new WebSocketFeed($auth);
        $this->assertInstanceOf(WebSocketFeed::class, $api);
        return $api;
    }

    /**
     * @depends testNewWebSocketFeed
     * @param WebSocketFeed $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetPublicBullet(WebSocketFeed $api)
    {
        $data = $api->getPublicBullet();
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('instanceServers', $data);
        $this->assertInternalType('array', $data['instanceServers']);
        foreach ($data['instanceServers'] as $instanceServer) {
            $this->assertArrayHasKey('endpoint', $instanceServer);
            $this->assertArrayHasKey('protocol', $instanceServer);
            $this->assertArrayHasKey('encrypt', $instanceServer);
            $this->assertInternalType('array', $instanceServer);
        }
    }

    /**
     * @depends testNewWebSocketFeed
     * @param WebSocketFeed $api
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetPrivateBullet(WebSocketFeed $api)
    {
        $data = $api->getPrivateBullet();
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('instanceServers', $data);
        $this->assertInternalType('array', $data['instanceServers']);

        $wsAddress = null;
        foreach ($data['instanceServers'] as $instanceServer) {
            $this->assertInternalType('array', $instanceServer);
            $this->assertArrayHasKey('endpoint', $instanceServer);
            $this->assertArrayHasKey('protocol', $instanceServer);
            $this->assertArrayHasKey('encrypt', $instanceServer);
            if ($instanceServer['protocol'] === 'websocket') {
                $wsAddress = $instanceServer['endpoint'];
            }
        }
        return ['address' => $wsAddress, 'token' => $data['token']];
    }

    /**
     * @depends testNewWebSocketFeed
     * @param WebSocketFeed $api
     * @throws \Exception|\Throwable
     */
    public function testSubscribePublicChannel(WebSocketFeed $api)
    {
        $query = [
            'connectId' => uniqid('', true),
        ];
        $channel = [
            'topic' => '/market/ticker:KCS-BTC',
            //'response' => true,
        ];

        $options = [
//            'tls' => [
//                'verify_peer' => false,
//            ],
        ];
        $api->subscribePublicChannel($query, $channel, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
            $this->assertInternalType('array', $message);
            $this->assertArrayHasKey('type', $message);
            $this->assertEquals('message', $message['type']);

            // Dynamic output
            fputs(STDIN, print_r($message, true));

            // Stop for phpunit
            $loop->stop();
        }, function ($code, $reason) {
            echo "OnClose: {$code} {$reason}\n";
        }, $options);
    }

    /**
     * @depends testNewWebSocketFeed
     * @param WebSocketFeed $api
     * @throws \Exception|\Throwable
     */
    public function testSubscribePrivateChannel(WebSocketFeed $api)
    {
        $query = [
            'connectId' => uniqid('', true),
        ];
        $channel = [
            'topic' => '/market/match:KCS-BTC',
            //'response' => true,
        ];

        $options = [
//            'tls' => [
//                'verify_peer' => false,
//            ],
        ];
        $api->subscribePrivateChannel($query, $channel, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
            $this->assertInternalType('array', $message);
            $this->assertArrayHasKey('type', $message);
            $this->assertEquals('message', $message['type']);
            // Dynamic output
            fputs(STDIN, print_r($message, true));

            // Stop for phpunit
            $loop->stop();
        }, function ($code, $reason) {
            echo "OnClose: {$code} {$reason}\n";
        }, $options);
    }
}
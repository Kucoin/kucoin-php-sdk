<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\Exceptions\NoAvailableWebSocketServerException;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\EventLoop\TimerInterface;
use React\Socket\Connector as SocketConnector;
use Ratchet\Client\Connector as RatchetConnector;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * Class WebSocketFeed
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/#websocket-feed
 */
class WebSocketFeed extends KuCoinApi
{
    /**
     * Get the server list and temporary token
     * @return array
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPublicBullet()
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/bullet-public');
        return $response->getApiData();
    }

    /**
     * Get the server list and authorized token
     * @return array
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPrivateBullet()
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/bullet-private');
        return $response->getApiData();
    }

    /**
     * Get the url of WebSocket
     * @param bool $private
     * @param array $params
     * @return array
     * @throws NoAvailableWebSocketServerException
     */
    public function getServer($private = false, array $params = [])
    {
        $bulletMethod = $private ? 'getPrivateBullet' : 'getPublicBullet';
        $bullet = $this->$bulletMethod();
        if (empty($bullet['instanceServers'])) {
            throw new NoAvailableWebSocketServerException();
        }
        $server = $bullet['instanceServers'][array_rand($bullet['instanceServers'])];
        $params['token'] = $bullet['token'];
        $url = sprintf('%s%s%s', $server['endpoint'], strpos($server['endpoint'], '?') === false ? '?' : '&', http_build_query($params));
        $server['wsUrl'] = $url;
        return $server;
    }

    /**
     * Get the url of WebSocket for public channels
     * @param array $params
     * @return array
     * @throws NoAvailableWebSocketServerException
     */
    public function getPublicServer(array $params = [])
    {
        return $this->getServer(false, $params);
    }

    /**
     * Get the url of WebSocket for private channels
     * @param array $params
     * @return array
     * @throws NoAvailableWebSocketServerException
     */
    public function getPrivateServer(array $params = [])
    {
        return $this->getServer(true, $params);
    }

    /**
     * Subscribe channel by url
     * @param array $server
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribeChannel(array $server, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $loop = Factory::create();
        $reactConnector = new SocketConnector($loop, $options);
        $connector = new RatchetConnector($loop, $reactConnector);
        /**
         * @var \Exception|\Throwable $exception
         */
        $exception = null;
        $connector($server['wsUrl'])->then(function (WebSocket $ws) use ($server, $channel, $onMessage, $onClose, $loop) {
            // Add timer to send ping message
            $pingTimer = $loop->addPeriodicTimer($server['pingInterval'] / 1000 - 1, function () use ($ws) {
                try {
                    $ping = $this->createPingMessage();
                    $ws->send(json_encode($ping));
                } catch (\Exception $e) {
                    
                }
            });
            $ws->on('message', function (MessageInterface $msg) use ($ws, $channel, $onMessage, $loop, $pingTimer) {
                $msgStr = $msg->__toString();
                $msgArray = json_decode($msgStr, true);
                if (!isset($msgArray['type'])) {
                    throw new BusinessException('Invalid format of message without type: ' . $msgStr);
                }
                switch ($msgArray['type']) {
                    case 'welcome':
                        // Do subscribe
                        if ($msgArray['id'] === $channel['id']) {
                            $channel['type'] = 'subscribe';
                            $ws->send(json_encode($channel));
                        }
                        break;
                    case 'ack':
                    case 'ping':
                    case 'pong':
                        break;
                    case 'error':
                        $loop->cancelTimer($pingTimer);
                        throw new BusinessException('Error: ' . $msg);
                    default:
                        // 'message' and the others
                        call_user_func($onMessage, $msgArray, $ws, $loop);
                        break;
                }
            });
            $ws->on('close', function ($code = null, $reason = null) use ($onClose, $loop, $pingTimer) {
                if (is_callable($onClose)) {
                    call_user_func($onClose, $code, $reason);
                }
                $loop->cancelTimer($pingTimer);
            });
        }, function ($e) use ($loop, &$exception) {
            $exception = $e;
        });

        $loop->run();

        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * Subscribe public channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePublicChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($query['connectId']) || !isset($channel['id'])) {
            $channel['id'] = $query['connectId'] = uniqid('', true);
        }
        $server = $this->getPublicServer($query);
        $this->subscribeChannel($server, $channel, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe private channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePrivateChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($query['connectId']) || !isset($channel['id'])) {
            $channel['id'] = $query['connectId'] = uniqid('', true);
        }
        $server = $this->getPrivateServer($query);
        $this->subscribeChannel($server, $channel, $onMessage, $onClose, $options);
    }

    /**
     * Create message for ping
     * @param string $id
     * @return array
     */
    public function createPingMessage($id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'ping'];
    }
}
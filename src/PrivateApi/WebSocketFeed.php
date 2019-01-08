<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\Socket\Connector as SocketConnector;
use Ratchet\Client\Connector as RatchetConnector;
use Ratchet\RFC6455\Messaging\MessageInterface;

/**
 * Class WebSocketFeed
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/?json#websocket-feed
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
        $response = $this->call(Request::METHOD_POST, '/api/v1/bullet-public?source=api');
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
        $response = $this->call(Request::METHOD_POST, '/api/v1/bullet-private?source=api');
        return $response->getApiData();
    }

    /**
     * Get the url of WebSocket
     * @param bool $private
     * @param array $params
     * @return string
     */
    public function getWsUrl($private = false, array $params = [])
    {
        $url = null;
        $bulletMethod = $private ? 'getPrivateBullet' : 'getPublicBullet';
        $bullet = $this->$bulletMethod();
        foreach ($bullet['instanceServers'] as $instanceServer) {
            if ($instanceServer['protocol'] === 'websocket') {
                $url = $instanceServer['endpoint'];
                break;
            }
        }
        $params['token'] = $bullet['token'];
        $url .= strpos($url, '?') === false ? '?' : '&';
        $url .= http_build_query($params);
        return $url;
    }

    /**
     * Get the url of WebSocket for public channels
     * @param array $params
     * @return string
     */
    public function getPublicWsUrl(array $params = [])
    {
        return $this->getWsUrl(false, $params);
    }

    /**
     * Get the url of WebSocket for private channels
     * @param array $params
     * @return string
     */
    public function getPrivateWsUrl(array $params = [])
    {
        return $this->getWsUrl(true, $params);
    }

    /**
     * Subscribe channel by url
     * @param string $url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     */
    public function subscribeChannel($url, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $loop = Factory::create();
        $reactConnector = new SocketConnector($loop, $options);
        $connector = new RatchetConnector($loop, $reactConnector);
        $connector($url)->then(function (WebSocket $ws) use ($channel, $onMessage, $onClose, $loop) {
            $ws->on('message', function (MessageInterface $msg) use ($ws, $channel, $onMessage, $loop) {
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
                        throw new BusinessException('Error: ' . $msg);
                    default:
                        // 'message' and the others
                        call_user_func($onMessage, $msgArray, $ws, $loop);
                        break;
                }
            });
            $ws->on('close', function ($code = null, $reason = null) use ($onClose) {
                if (is_callable($onClose)) {
                    call_user_func($onClose, $code, $reason);
                }
            });
        }, function (\Exception $e) use ($loop) {
            $loop->stop();
            throw new BusinessException($e->getMessage(), $e->getCode(), $e);
        });
        $loop->run();
    }

    /**
     * Subscribe public channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     */
    public function subscribePublicChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($query['connectId']) || !isset($channel['id'])) {
            $channel['id'] = $query['connectId'] = uniqid('', true);
        }
        $url = $this->getPublicWsUrl($query);
        $this->subscribeChannel($url, $channel, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe private channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     */
    public function subscribePrivateChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($query['connectId']) || !isset($channel['id'])) {
            $channel['id'] = $query['connectId'] = uniqid('', true);
        }
        $url = $this->getPrivateWsUrl($query);
        $this->subscribeChannel($url, $channel, $onMessage, $onClose, $options);
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
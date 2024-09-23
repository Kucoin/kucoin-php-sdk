<?php

namespace KuCoin\SDK\PrivateApi;

use GuzzleHttp\Exception\GuzzleException;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\NoAvailableWebSocketServerException;
use KuCoin\SDK\Exceptions\WebSocketCloseException;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\Socket\TcpConnector;
use Ratchet\Client\Connector as RatchetConnector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use React\Socket\Connector as SocketConnector;
use React\Socket\ConnectorInterface;

/**
 * Class WebSocketFeed
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/#websocket-feed
 */
class WebSocketFeed extends KuCoinApi
{
    /** @var LoopInterface */
    protected $loop;

    /**
     * Get the event loop instance, default return Loop::get()
     * @return LoopInterface
     */
    public function getLoop()
    {
        if ($this->loop === null) {
            $this->loop = Loop::get();
        }
        return $this->loop;
    }

    /**
     * Set the event loop instance
     * @param LoopInterface $loop
     */
    public function setLoop(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

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
        $server['token'] = $params['token'] = $bullet['token'];
        $url = sprintf('%s%s%s', $server['endpoint'], strpos($server['endpoint'], '?') === false ? '?' : '&', http_build_query($params));
        $server['connectUrl'] = $url;
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
     * Subscribe multiple channels by url
     * @param array $server
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribeChannels(array $server, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($options['tls']['verify_peer'])) {
            $options['tls']['verify_peer'] = !static::isSkipVerifyTls();
        }

        $loop = $this->getLoop();

        if (!isset($options['tcp']) || !($options['tcp'] instanceof ConnectorInterface)) {
            $options['tcp'] = new TcpConnector(
                $loop,
                is_array($options['tcp']) ? $options['tcp'] : []
            );
        }

        $reactConnector = new SocketConnector($loop, $options);
        $connector = new RatchetConnector($loop, $reactConnector);
        /**
         * @var \Exception|\Throwable $exception
         */
        $exception = null;
        $connector($server['connectUrl'])->then(function (WebSocket $ws) use ($server, $channels, $onMessage, $onClose, $loop, $options) {
            // Add timer to send ping message
            $pingTimer = $loop->addPeriodicTimer($server['pingInterval'] / 1000 - 1, function () use ($ws) {
                try {
                    $ping = $this->createPingMessage();
                    $pingStr = json_encode($ping);
                    if (self::isDebugMode()) {
                        static::getLogger()->debug(sprintf('Sent a WebSocket message: %s', $pingStr));
                    }
                    // fputs(STDIN, print_r($ping, true));
                    $ws->send($pingStr);
                } catch (\Exception $e) {
                    // Ignore this exception
                }
            });
            $ws->on('message', function (MessageInterface $msg) use ($server, $ws, $channels, $onMessage, $loop, $pingTimer) {
                $msgStr = $msg->__toString();
                if (self::isDebugMode()) {
                    static::getLogger()->debug(sprintf('Received a WebSocket message: %s', $msgStr));
                }
                $msgArray = json_decode($msgStr, true);
                if (!isset($msgArray['type'])) {
                    throw new BusinessException('Invalid format of message without type: ' . $msgStr);
                }
                switch ($msgArray['type']) {
                    case 'welcome':
                        // Do subscribe
                        if (!isset($msgArray['id']) || $msgArray['id'] === $server['connectId']) {
                            foreach ($channels as $channel) {
                                $ws->send(json_encode($channel));
                            }
                        }
                        break;
                    case 'ack':
                    case 'ping':
                    case 'pong':
                        // fputs(STDIN, print_r($msgArray, true));
                        break;
                    case 'error':
                        $loop->cancelTimer($pingTimer);
                        throw new BusinessException('Error: ' . $msg);
                    case 'message':
                        $onMessage($msgArray, $ws, $loop, $server);
                        break;
                    default:
                        throw new BusinessException('Unknown type: ' . $msgArray['type']);
                }
            });
            $ws->on('close', function ($code = null, $reason = null) use ($onClose, $loop, $pingTimer, $server, $options) {
                $loop->cancelTimer($pingTimer);
                if (is_callable($onClose)) {
                    $onClose($code, $reason, $server);
                }
                if ($options['enable_reconnect']) {
                    // To support reconnection
                    throw new WebSocketCloseException($reason, $code);
                }
            });
        }, function ($e) use (&$exception) {
            $exception = $e;
        });

        $loop->run();

        if ($exception !== null) {
            throw $exception;
        }
    }

    /**
     * Subscribe multiple public channels
     * @param array $query The query of websocket url
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePublicChannels(array $query, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribeChannelsInternal(false, $query, $channels, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe multiple private channels
     * @param array $query The query of websocket url
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePrivateChannels(array $query, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribeChannelsInternal(true, $query, $channels, $onMessage, $onClose, $options);
    }

    /**
     * Subscribe multiple channels for internal calls
     * @param bool $private
     * @param array $query The query of websocket url
     * @param array $channels
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws GuzzleException
     * @throws \Throwable
     */
    protected function subscribeChannelsInternal($private, array $query, array $channels, callable $onMessage, callable $onClose = null, array $options = [])
    {
        if (!isset($channels[0])) {
            $channels = [$channels];
        }
        array_walk($channels, static function (&$channel) use ($private) {
            if (!isset($channel['id'])) {
                $channel['id'] = uniqid('', true);
            }
            $channel['type'] = 'subscribe';
            $channel['privateChannel'] = $private;
        });
        if (!isset($query['connectId'])) {
            $query['connectId'] = uniqid('', true);
        }
        if (!isset($options['enable_reconnect'])) {
            $options['enable_reconnect'] = false;
        }
        if (!isset($options['max_reconnects'])) {
            $options['max_reconnects'] = 10;
        }
        if (!isset($options['reconnect_delay'])) {
            $options['reconnect_delay'] = 1000;
        }
        $connectTimes = 1;
        do {
            $lastException = null;

            try {
                $server = $private ? $this->getPrivateServer($query) : $this->getPublicServer($query);
                $server['connectId'] = $query['connectId'];
                $this->subscribeChannels($server, $channels, $onMessage, $onClose, $options);
            } catch (\Exception $e) {
                if (self::isDebugMode()) {
                    static::getLogger()->debug(sprintf('[%d]Failed to subscribe to the channels: %s', $connectTimes, $e->getMessage()));
                }
                $lastException = $e;
                if (!($e instanceof GuzzleException || $e instanceof HttpException || $e instanceof NoAvailableWebSocketServerException || $e instanceof WebSocketCloseException)) {
                    throw $e;
                }
            }

            $connectTimes++;

            if ($options['reconnect_delay'] > 0) {
                usleep($options['reconnect_delay'] * 1000);
            }
        } while ($options['enable_reconnect'] && (!$options['max_reconnects'] || $connectTimes <= $options['max_reconnects'] + 1));

        if ($lastException) {
            throw $lastException;
        }
    }

    /**
     * Subscribe one public channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePublicChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribePublicChannels($query, [$channel], $onMessage, $onClose, $options);
    }

    /**
     * Subscribe one private channel
     * @param array $query The query of websocket url
     * @param array $channel
     * @param callable $onMessage
     * @param callable|null $onClose
     * @param array $options
     * @throws \Exception|\Throwable
     */
    public function subscribePrivateChannel(array $query, array $channel, callable $onMessage, callable $onClose = null, array $options = [])
    {
        $this->subscribePrivateChannels($query, [$channel], $onMessage, $onClose, $options);
    }

    /**
     * Create a ping message
     * @param string $id
     * @return array
     */
    public function createPingMessage($id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'ping'];
    }

    /**
     * Create a subscription message
     * @param string $topic
     * @param bool $privateChannel
     * @param bool $response
     * @param string $id
     * @return array
     */
    public function createSubscribeMessage($topic, $privateChannel = false, $response = true, $id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'subscribe', 'topic' => $topic, 'privateChannel' => $privateChannel, 'response' => $response];
    }

    /**
     * Create an unsubscribe message
     * @param string $topic
     * @param bool $privateChannel
     * @param bool $response
     * @param string $id
     * @return array
     */
    public function createUnsubscribeMessage($topic, $privateChannel = false, $response = true, $id = null)
    {
        return ['id' => $id ?: uniqid('', true), 'type' => 'unsubscribe', 'topic' => $topic, 'privateChannel' => $privateChannel, 'response' => $response];
    }
}

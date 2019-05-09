<?php

namespace KuCoin\SDK;

use KuCoin\SDK\Http\GuzzleHttp;
use KuCoin\SDK\Http\IHttp;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\Http\Response;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class Api
{
    /**
     * @var string
     */
    protected static $baseUri = 'https://openapi-v2.kucoin.com';

    /**
     * @var bool
     */
    protected static $skipVerifyTls = false;

    /**
     * @var bool
     */
    protected static $debugMode = false;

    /**
     * @var IAuth $auth
     */
    protected $auth;

    /**
     * @var IHttp $http
     */
    protected $http;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    public function __construct(IAuth $auth = null, IHttp $http = null)
    {
        if ($http === null) {
            $http = new GuzzleHttp(['skipVerifyTls' => &self::$skipVerifyTls]);
        }
        $this->auth = $auth;
        $this->http = $http;
    }

    /**
     * @return string
     */
    public static function getBaseUri()
    {
        return static::$baseUri;
    }

    /**
     * @param string $baseUri
     */
    public static function setBaseUri($baseUri)
    {
        static::$baseUri = $baseUri;
    }

    /**
     * @return bool
     */
    public static function isSkipVerifyTls()
    {
        return static::$skipVerifyTls;
    }

    /**
     * @param bool $skipVerifyTls
     */
    public static function setSkipVerifyTls($skipVerifyTls)
    {
        static::$skipVerifyTls = $skipVerifyTls;
    }

    /**
     * @return bool
     */
    public static function isDebugMode()
    {
        return self::$debugMode;
    }

    /**
     * @param bool $debugMode
     */
    public static function setDebugMode($debugMode)
    {
        self::$debugMode = $debugMode;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Logger|LoggerInterface
     * @throws \Exception
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new Logger('kucoin-sdk');
            $handler = new RotatingFileHandler('/tmp/kucoin-sdk.log', 0, Logger::DEBUG);
            $formatter = new LineFormatter(null, null, false, true);
            $handler->setFormatter($formatter);
            $this->logger->pushHandler($handler);
        }
        return $this->logger;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param int $timeout
     * @return Response
     * @throws Exceptions\HttpException
     * @throws Exceptions\InvalidApiUriException
     */
    public function call($method, $uri, array $params = [], array $headers = [], $timeout = 30)
    {
        $request = new Request();
        $request->setMethod($method);
        $request->setBaseUri(static::getBaseUri());
        $request->setUri($uri);
        $request->setParams($params);

        if ($this->auth) {
            $authHeaders = $this->auth->getHeaders(
                $request->getMethod(),
                $request->getRequestUri(),
                $request->getBodyParams()
            );
            $headers = array_merge($headers, $authHeaders);
        }
        $request->setHeaders($headers);

        $requestId = uniqid();

        if (self::isDebugMode()) {
            $this->getLogger()->debug(sprintf('Sent a HTTP request#%s: %s', $requestId, $request));
        }
        $response = $this->http->request($request, $timeout);
        if (self::isDebugMode()) {
            $this->getLogger()->debug(sprintf('Received a HTTP response#%s: %s', $requestId, $response));
        }

        return $response;
    }
}
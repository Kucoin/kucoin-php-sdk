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
     * @var string SDK Version
     */
    const VERSION = '1.1.19';

    /**
     * @var string SDK update date
     */
    const UPDATE_DATE = '2020.12.28';

    /**
     * @var string
     */
    protected static $baseUri = 'https://api.kucoin.com';

    /**
     * @var bool
     */
    protected static $skipVerifyTls = false;

    /**
     * @var bool
     */
    protected static $debugMode = false;

    /**
     * @var string
     */
    protected static $logPath = '/tmp';

    /**
     * @var LoggerInterface $logger
     */
    protected static $logger;

    /**
     * @var int
     */
    protected static $logLevel = Logger::DEBUG;

    /**
     * @var array
     */
    protected static $customHeaders;

    /**
     * @var IAuth $auth
     */
    protected $auth;

    /**
     * @var IHttp $http
     */
    protected $http;

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
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return Logger|LoggerInterface
     * @throws \Exception
     */
    public static function getLogger()
    {
        if (self::$logger === null) {
            self::$logger = new Logger('kucoin-sdk');
            $handler = new RotatingFileHandler(static::getLogPath() . '/kucoin-sdk.log', 0, static::$logLevel);
            $formatter = new LineFormatter(null, null, false, true);
            $handler->setFormatter($formatter);
            self::$logger->pushHandler($handler);
        }
        return self::$logger;
    }

    /**
     * @return string
     */
    public static function getLogPath()
    {
        return self::$logPath;
    }

    /**
     * @param string $logPath
     */
    public static function setLogPath($logPath)
    {
        self::$logPath = $logPath;
    }

    /**
     * @return int
     */
    public static function getLogLevel()
    {
        return self::$logLevel;
    }

    /**
     * @param int $logLevel
     */
    public static function setLogLevel($logLevel)
    {
        self::$logLevel = $logLevel;
    }

    /**
     * @param array $headers
     */
    public static function setCustomHeaders(array $headers)
    {
        self::$customHeaders = $headers;
    }

    /**
     * @return array
     */
    public static function getCustomHeaders()
    {
        return self::$customHeaders;
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
        $headers['User-Agent'] = 'KuCoin-PHP-SDK/' . static::VERSION;

        if (self::$customHeaders) {
            $headers = array_merge($headers, self::$customHeaders);
        }

        $request->setHeaders($headers);

        $requestId = uniqid();

        if (self::isDebugMode()) {
            static::getLogger()->debug(sprintf('Sent a HTTP request#%s: %s', $requestId, $request));
        }
        $requestStart = microtime(true);
        $response = $this->http->request($request, $timeout);
        if (self::isDebugMode()) {
            $cost = (microtime(true) - $requestStart) * 1000;
            static::getLogger()->debug(sprintf('Received a HTTP response#%s: cost %.2fms, %s', $requestId, $cost, $response));
        }

        return $response;
    }
}
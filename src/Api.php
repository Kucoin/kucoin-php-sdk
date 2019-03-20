<?php

namespace KuCoin\SDK;

use KuCoin\SDK\Http\GuzzleHttp;
use KuCoin\SDK\Http\IHttp;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\Http\Response;

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

        $response = $this->http->request($request, $timeout);
        return $response;
    }
}
<?php

namespace KuCoin\SDK\Http;

use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\InvalidApiUriException;
use Swlib\Http\ContentType;
use Swlib\Http\Exception\RequestException;
use Swlib\Saber;
use Swoole\Runtime;

class SwooleHttp extends BaseHttp
{
    protected static $clients = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        Runtime::enableCoroutine();
    }

    /**
     * @param array $config
     * @return Saber
     */
    protected static function getClient(array $config)
    {
        $key = md5(json_encode($config));
        if (isset(static::$clients[$key])) {
            return static::$clients[$key];
        }

        static::$clients[$key] = Saber::create($config);
        return static::$clients[$key];
    }

    /**
     * @param Request $request
     * @param float|int $timeout in seconds
     * @return Response
     * @throws HttpException
     * @throws InvalidApiUriException
     */
    public function request(Request $request, $timeout = 30)
    {
        if (!$request->getBaseUri() && strpos($request->getUri(), '://') === false) {
            $exception = new InvalidApiUriException('Invalid base_uri or uri, must set base_uri or set uri to a full url');
            $exception->setBaseUri($request->getBaseUri());
            $exception->setUri($request->getUri());
            throw $exception;
        }

        $config = [
            'base_uri'        => $request->getBaseUri(),
            'timeout'         => $timeout,
            'use_pool'        => true,
            'ssl_verify_peer' => empty($this->config['skipVerifyTls']),
        ];
        $client = static::getClient($config);
        $options['headers'] = $request->getHeaders();

        $method = $request->getMethod();
        $requestUri = $request->getRequestUri();
        try {
            switch ($method) {
                case Request::METHOD_GET:
                case Request::METHOD_DELETE:
                    /**@var \Swlib\Saber\Response $saberResponse */
                    $saberResponse = $client->{strtolower($method)}($requestUri, $options);
                    break;
                case Request::METHOD_PUT:
                case Request::METHOD_POST:
                    $data = $request->getBodyParams();
                    $options['headers']['Content-Type'] = ContentType::JSON;
                    /**@var \Swlib\Saber\Response $saberResponse */
                    $saberResponse = $client->{strtolower($method)}($requestUri, $data, $options);
                    break;
                default:
                    $exception = new HttpException('Unsupported method ' . $method, 0);
                    $exception->setRequest($request);
                    throw $exception;
            }
            $response = new Response($saberResponse->getBody()->__toString(), $saberResponse->getStatusCode(), $saberResponse->getHeaders());
            $response->setRequest($request);
            return $response;
        } catch (\Exception $e) {
            if ($e instanceof RequestException && $e->hasResponse()) {
                $saberResponse = $e->getResponse();
                $response = new Response($saberResponse->getBody()->__toString(), $saberResponse->getStatusCode(), $saberResponse->getHeaders());
                $response->setRequest($request);
                return $response;
            }
            $exception = new HttpException($e->getMessage(), $e->getCode(), $e);
            $exception->setRequest($request);
            throw $exception;
        }
    }
}

<?php

namespace KuCoin\SDK\Http;

use GuzzleHttp\Client;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\InvalidApiUriException;

class GuzzleHttp extends BaseHttp
{
    protected static $clients = [];

    /**
     * @param array $config
     * @return Client
     */
    protected static function getClient(array $config)
    {
        $key = md5(json_encode($config));
        if (isset(static::$clients[$key])) {
            return static::$clients[$key];
        }

        static::$clients[$key] = new Client($config);
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
            'http_errors'     => false,
            'connect_timeout' => 30,
            'verify'          => !$this->api->isSkipVerifyTls(),
        ];
        $client = static::getClient($config);
        $options = [
            'headers' => $request->getHeaders(),
        ];
        $method = $request->getMethod();
        $params = $request->getParams();
        $hasParam = !empty($params);
        switch ($method) {
            case Request::METHOD_GET:
            case Request::METHOD_DELETE:
                $hasParam AND $options['query'] = $params;
                break;
            case Request::METHOD_PUT:
            case Request::METHOD_POST:
                if ($hasParam) {
                    $options['headers']['Content-Type'] = 'application/json';
                    $options['body'] = json_encode($params, JSON_UNESCAPED_SLASHES);
                }
                break;
            default:
                $exception = new HttpException('Unsupported method ' . $method, 0);
                $exception->setRequest($request);
                throw $exception;
        }
        try {
            $guzzleResponse = $client->request($request->getMethod(), $request->getUri(), $options);
            $response = new Response($guzzleResponse->getBody()->__toString(), $guzzleResponse->getStatusCode(), $guzzleResponse->getHeaders());
            $response->setRequest($request);
            return $response;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            $exception = new HttpException($e->getMessage(), $e->getCode(), $e);
            $exception->setRequest($request);
            throw $exception;
        } catch (\Exception $e) {
            $exception = new HttpException($e->getMessage(), $e->getCode(), $e);
            $exception->setRequest($request);
            throw $exception;
        }
    }
}
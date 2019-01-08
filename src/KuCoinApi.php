<?php

namespace KuCoin\SDK;

use KuCoin\SDK\Http\ApiResponse;

abstract class KuCoinApi extends Api
{
    /**
     * Call an API
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @param int $timeout
     * @return ApiResponse
     * @throws Exceptions\HttpException
     * @throws Exceptions\InvalidApiUriException
     */
    public function call($method, $uri, array $params = [], array $headers = [], $timeout = 30)
    {
        $response = parent::call($method, $uri, $params, $headers, $timeout);
        return new ApiResponse($response);
    }
}
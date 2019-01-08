<?php

namespace KuCoin\SDK\PublicApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Time
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/?json#time
 */
class Time extends KuCoinApi
{
    /**
     * Get the timestamp of Server in milliseconds
     * @return int
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function timestamp()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/timestamp');
        return $response->getApiData();
    }
}
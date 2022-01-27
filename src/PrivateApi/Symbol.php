<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

class Symbol extends KuCoinApi
{
    /**
     * Get full order book(aggregated) v3
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAggregatedFullOrderBook($symbol)
    {
        $response = $this->call(Request::METHOD_GET, 'api/v3/market/orderbook/level2', compact('symbol'));
        return $response->getApiData();
    }
}
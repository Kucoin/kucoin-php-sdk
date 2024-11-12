<?php

namespace KuCoin\SDK\PublicApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Margin
 * @package KuCoin\SDK\PublicApi
 * @see https://www.kucoin.com/docs/rest/margin-trading/margin-info/get-all-margin-trading-pairs-mark-prices
 */
class Margin extends KuCoinApi
{
    /**
     * This endpoint returns the current mark prices for all margin trading pairs.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSymbolsMarkPrice()
    {
        return $this->call(Request::METHOD_GET, '/api/v3/mark-price/all-symbols')->getApiData();
    }
}
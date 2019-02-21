<?php

namespace KuCoin\SDK\PublicApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Currency
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/#currencies
 */
class Currency extends KuCoinApi
{
    /**
     * Get a list of currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/currencies');
        return $response->getApiData();
    }

    /**
     * Get the details of a currency
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/currencies/' . $currency);
        return $response->getApiData();
    }

    /**
     * Get fiat prices for currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPrices()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/prices');
        return $response->getApiData();
    }
}
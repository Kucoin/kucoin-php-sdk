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
     * @param string|null $chain
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($currency, $chain = null)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/currencies/' . $currency, compact('chain'));
        return $response->getApiData();
    }

    /**
     * Get fiat prices for currency
     * @param string|null $base
     * @param string|null $currencies
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPrices($base = null, $currencies = null)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/prices', compact('base', 'currencies'));
        return $response->getApiData();
    }
}
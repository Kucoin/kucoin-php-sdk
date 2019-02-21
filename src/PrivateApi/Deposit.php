<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Deposits
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#deposits
 */
class Deposit extends KuCoinApi
{
    /**
     * Create deposit address
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createAddress($currency)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/deposit-addresses', compact('currency'));
        return $response->getApiData();
    }

    /**
     * Get deposit address of currency for deposit
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAddress($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/deposit-addresses', compact('currency'));
        return $response->getApiData();
    }

    /**
     * Get deposit list
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDeposits(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/deposits', $params);
        return $response->getApiData();
    }
}
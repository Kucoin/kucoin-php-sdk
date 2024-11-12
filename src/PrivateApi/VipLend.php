<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 *
 * Class VipLend
 * @package KuCoin\SDK\PrivateApi
 *
 * @see https://www.kucoin.com/docs/rest/vip-lending/introduction
 *
 */
class VipLend extends KuCoinApi
{
    /**
     * This endpoint is only for querying accounts that are currently involved in loans.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLoan()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/otc-loan/loan');
        return $response->getApiData();
    }

    /**
     * This endpoint is only for querying accounts that are currently involved in off-exchange funding and loans.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAccounts()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/otc-loan/accounts');
        return $response->getApiData();
    }
}
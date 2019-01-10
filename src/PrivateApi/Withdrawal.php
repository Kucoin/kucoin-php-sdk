<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Withdrawal
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#withdrawals
 */
class Withdrawal extends KuCoinApi
{
    /**
     * Get withdraw quotas
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getQuotas($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/withdrawals/quotas', compact('currency'));
        return $response->getApiData();
    }

    /**
     * Get a list of withdrawal
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/withdrawals', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Apply a withdrawal
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function apply(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/withdrawals', $params);
        return $response->getApiData();
    }

    /**
     * Cancel a withdrawal
     * @param string $withdrawId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancel($withdrawId)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/withdrawals/' . $withdrawId);
        return $response->getApiData();
    }
}
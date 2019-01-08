<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Withdrawal
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/?json#withdrawal
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
        $response = $this->call(Request::METHOD_GET, '/api/v1/withdraw/quotas', compact('currency'));
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
        $response = $this->call(Request::METHOD_GET, '/api/v1/withdraws', $params + $pagination);
        return $response->getApiData();
    }


    /**
     * Get a withdrawal
     * @param string $withdrawId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($withdrawId)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/withdraws/' . $withdrawId);
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
        $response = $this->call(Request::METHOD_POST, '/api/v1/withdraws', $params);
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
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/withdraws/' . $withdrawId);
        return $response->getApiData();
    }

    /**
     * Cancel some withdrawals
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelMany(array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/withdraws', $params);
        return $response->getApiData();
    }
}
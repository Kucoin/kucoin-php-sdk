<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Account
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#accounts
 */
class Account extends KuCoinApi
{
    /**
     * Get a list of accounts
     * @param string $type "main" or "trade"
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList($type)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/accounts', compact('type'));
        return $response->getApiData();
    }


    /**
     * Create an account
     * @param string $type
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function create($type, $currency)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/accounts', compact('type', 'currency'));
        return $response->getApiData();
    }

    /**
     * Get an account
     * @param string $accountId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($accountId)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/accounts/' . $accountId);
        return $response->getApiData();
    }

    /**
     * Get account history
     * @param string $accountId
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHistory($accountId, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/accounts/' . $accountId . '/ledgers', $pagination);
        return $response->getApiData();
    }

    /**
     * @param string $accountId
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHolds($accountId, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/accounts/' . $accountId . '/holds', $pagination);
        return $response->getApiData();
    }

    /**
     * Inner transfer
     * @param string $clientOid
     * @param string $payAccountId
     * @param string $recAccountId
     * @param float $amount
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function innerTransfer($clientOid, $payAccountId, $recAccountId, $amount)
    {
        $response = $this->call(
            Request::METHOD_POST,
            '/api/v1/accounts/inner-transfer',
            compact('clientOid', 'payAccountId', 'recAccountId', 'amount')
        );
        return $response->getApiData();
    }
}
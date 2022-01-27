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
     * Create an account
     * @param string $type "main" or "trade"
     * @param string $currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function create($type, $currency)
    {
        $response = $this->call(Request::METHOD_POST, 'api/v1/accounts', compact('type', 'currency'));
        return $response->getApiData();
    }

    /**
     * Get a list of accounts
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList(array $params = [])
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/accounts', $params);
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
        $response = $this->call(Request::METHOD_GET, 'api/v1/accounts/' . $accountId);
        return $response->getApiData();
    }

    /**
     * @deprecated Use getLedgersV1 instead
     *
     * Get account ledgers
     * @param string $accountId
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLedgers($accountId, array $params = [], array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/accounts/' . $accountId . '/ledgers', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get holds
     * @param string $accountId
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHolds($accountId, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/accounts/' . $accountId . '/holds', $pagination);
        return $response->getApiData();
    }

    /**
     * Inner transfer
     * @param string $clientOid Request ID
     * @param string $payAccountId Account ID of payer
     * @param string $recAccountId Account ID of receiver
     * @param string $amount Transfer amount, a quantity that exceeds the precision of the currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     * @deprecated This interface was discontinued on August 29, 2019. Please use the transfer v2 interface provided below.
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

    /**
     * Inner transfer V2, recommended for use on June 5, 2019
     * @param string $clientOid Request ID
     * @param string $currency Currency
     * @param string $from The account type of payer main or trade
     * @param string $to The account type of payee main or trade
     * @param string $amount Transfer amount, a quantity that exceeds the precision of the currency
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function innerTransferV2($clientOid, $currency, $from, $to, $amount)
    {
        $response = $this->call(
            Request::METHOD_POST,
            '/api/v2/accounts/inner-transfer',
            compact('clientOid', 'currency', 'from', 'to', 'amount')
        );
        return $response->getApiData();
    }

    /**
     * Get user info of all sub-accounts
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSubAccountUsers()
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/sub/user');
        return $response->getApiData();
    }

    /**
     * Get account balance of a sub-account
     * @param string $subUserId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSubAccountDetail($subUserId)
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/sub-accounts/' . $subUserId);
        return $response->getApiData();
    }

    /**
     * Get the aggregated balance of all sub-accounts of the current user
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSubAccountList()
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/sub-accounts');
        return $response->getApiData();
    }

    /**
     * @deprecated Use subTransferV2 instead.
     * Transfer between master account and sub-account
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function subTransfer(array $params)
    {
        $response = $this->call(Request::METHOD_POST, 'api/v1/accounts/sub-transfer', $params);
        return $response->getApiData();
    }

    /**
     * Transfer between master account and sub-account
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function subTransferV2(array $params)
    {
        $response = $this->call(Request::METHOD_POST, 'api/v2/accounts/sub-transfer', $params);
        return $response->getApiData();
    }

    /**
     * Get account ledgers
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLedgersV2(array $params = [], array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/accounts/ledgers', $params + $pagination);
        return $response->getApiData();
    }
}
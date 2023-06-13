<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class IsolatedMargin
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#isolated-margin
 */
class IsolatedMargin extends KuCoinApi
{

    /**
     * Get the current isolated margin trading pair configuration
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSymbols()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/isolated/symbols');
        return $response->getApiData();
    }

    /**
     * Get all isolated margin accounts of the current user
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAccountList(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/isolated/accounts', $params);
        return $response->getApiData();
    }

    /**
     * Get the info on a single isolated margin account of the current user
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAccountDetail($symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/isolated/account/' . $symbol);
        return $response->getApiData();
    }

    /**
     * Initiates isolated margin borrowing.
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function borrow(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/isolated/borrow', $params);
        return $response->getApiData();
    }


    /**
     * Get outstanding repayment records of isolated margin
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOutstanding(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/isolated/borrow/outstanding', $params);
        return $response->getApiData();
    }

    /**
     * Get repayment records of isolated margin positions.
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRepaid(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/isolated/borrow/repaid', $params);
        return $response->getApiData();
    }

    /**
     * Initiate quick repayment for isolated margin accounts
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function repayAll(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/isolated/repay/all', $params);
        return $response->getApiData();
    }

    /**
     * Initiate quick repayment for single margin accounts
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function repaySingle(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/isolated/repay/single', $params);
        return $response->getApiData();
    }
}

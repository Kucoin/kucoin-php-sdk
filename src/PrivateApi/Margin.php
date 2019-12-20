<?php

namespace KuCoin\SDK\PrivateApi;


use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;


/**
 * Class Margin
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#margin-info
 */
class Margin extends KuCoinApi
{
    /**
     * Get Mark Price.
     *
     * @param string $symbol Symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarkPrice($symbol)
    {
        $response = $this->call(Request::METHOD_GET, sprintf('/api/v1/mark-price/%s/current', $symbol));
        return $response->getApiData();
    }

    /**
     * Get Margin Configuration Info.
     *
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getConfig()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/config');
        return $response->getApiData();
    }


    /**
     * Get Margin Account
     *
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getAccount()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/account');
        return $response->getApiData();
    }

    /**
     * Post Borrow Order.
     *
     * @param array $params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function borrow(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/borrow', $params);
        return $response->getApiData();
    }

    /**
     * Get Borrow Order
     *
     * @param string $orderId
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getBorrow($orderId)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/borrow', compact('orderId'));
        return $response->getApiData();
    }

    /**
     * Get Repay Record.
     *
     * @param string $currency Currency
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOutstanding($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/borrow/outstanding', compact('currency'));
        return $response->getApiData();
    }

    /**
     * Get Repayment Record.
     *
     * @param string $currency Currency
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRepayRecord($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/borrow/repaid', compact('currency'));
        return $response->getApiData();
    }

    /**
     * One-Click Repayment
     *
     * @param array $params Params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function repayAll(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/repay/all', $params);
        return $response->getApiData();
    }

    /**
     * Repay a Single Order
     *
     * @param array $params Params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function repaySingle(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/repay/single', $params);
        return $response->getApiData();
    }

    /**
     * Post Lend Order.
     *
     * @param array $params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function lend(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/lend', $params);
        return $response->getApiData();
    }

    /**
     * Cancel Lend Order.
     *
     * @param string $orderId Lend order ID
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelLend($orderId)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/margin/lend/'.$orderId);
        return $response->getApiData();
    }

    /**
     * Set Auto-lend.
     *
     * @param array $params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function setAutoLend(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/toggle-auto-lend', $params);
        return $response->getApiData();
    }

    /**
     * Get Active Order.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLendActive(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/lend/active', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get Lent History.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLendDone(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/lend/done', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get Active Lend Order List.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getUnsettled(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/lend/trade/unsettled', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get Settled Lend Order History
     *
     * @param array $params
     * @param array $pagination
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSettled(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/lend/trade/settled', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get Account Lend Record
     *
     * @param string $currency
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getLendAssets($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/lend/assets', compact('currency'));
        return $response->getApiData();
    }

    /**
     * Lending Market Data.
     *
     * @param array $params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarket(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/market', $params);
        return $response->getApiData();
    }

    /**
     * Margin Trade Data
     *
     * @param string $currency
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getTradeLast($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/trade/last', compact('currency'));
        return $response->getApiData();
    }
}
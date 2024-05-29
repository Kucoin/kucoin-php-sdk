<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Lend
 * @package KuCoin\SDK\PrivateApi
 *
 * @see https://www.kucoin.com/docs/rest/margin-trading/lending-market-v3-/get-currency-information
 */
class Lend extends KuCoinApi
{
    /**
     * This API endpoint is used to get the information about the currencies available for lending.
     *
     * @param string $currency
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getCurrencies($currency = '')
    {
        $params = $currency ? ['currency' => $currency] : [];
        $response = $this->call(Request::METHOD_GET, '/api/v3/project/list', $params);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to get the interest rates of the margin lending market over the past 7 days.
     *
     * @param $currency
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarketInterestRate($currency)
    {
        $params = ['currency' => $currency];
        $response = $this->call(Request::METHOD_GET, '/api/v3/project/marketInterestRate', $params);
        return $response->getApiData();
    }

    /**
     * Initiate subscriptions of margin lending.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function purchase(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/purchase', $params);
        return $response->getApiData();
    }

    /**
     * Initiate redemptions of margin lending.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function redeem(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/redeem', $params);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to update the interest rates of subscription orders, which will take effect at the beginning of the next hour.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function purchaseUpdate(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/lend/purchase/update', $params);
        return $response->getApiData();
    }

    /**
     * This API endpoint provides pagination query for the redemption orders.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRedeemOrders(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/redeem/orders', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * This API endpoint provides pagination query for the subscription orders.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPurchaseOrders(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/purchase/orders', $params + $pagination);
        return $response->getApiData();
    }
}
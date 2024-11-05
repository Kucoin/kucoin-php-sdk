<?php

namespace KuCoin\SDK\PrivateApi;


use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;


/**
 * Class Margin
 * @package KuCoin\SDK\PrivateApi
 *
 * @see https://www.kucoin.com/docs/rest/margin-trading/margin-trading-v3-/margin-borrowing
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
     * @param array $params
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     *
     * @deprecated Call borrowV3 instead.
     *
     * Post Borrow Order.
     *
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
     *
     * @deprecated Call getBorrowV3 instead.
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
     *
     * @deprecated Call getAccount instead.
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
     *
     * @deprecated
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
     *
     * @deprecated Call repayV3 instead.
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
     *
     * @deprecated Call repayV3 instead.
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
     *
     * @deprecated
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
     *
     * @deprecated
     */
    public function cancelLend($orderId)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/margin/lend/' . $orderId);
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
     *
     * @deprecated
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
     *
     * @deprecated
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
     *
     * @deprecated
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
     *
     * @deprecated
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
     * @throws \KuCoin\SDK\Exceptions\
     *
     * @deprecated
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
     *
     * @deprecated
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
     *
     * @deprecated
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
     *
     * @deprecated
     */
    public function getTradeLast($currency)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/margin/trade/last', compact('currency'));
        return $response->getApiData();
    }

    /**
     * This endpoint can query the cross/isolated margin risk limit.
     * This endpoint requires the "General" permission
     * @param string $marginModel corss（corss margin）, isolated (isolated margin)
     * @return mixed
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getStrategyRiskLimit(string $marginModel)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/risk/limit/strategy', ['marginModel' => $marginModel]);
        return $response->getApiData();
    }

    /**
     * This interface returns leveraged token information.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getEtfInfo()
    {
        return $this->call(Request::METHOD_GET, '/api/v3/etf/info')->getApiData();
    }

    /**
     * This API endpoint is used to initiate an application for cross or isolated margin borrowing.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function borrowV3(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/margin/borrow', $params);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to initiate an application for the repayment of cross or isolated margin borrowing.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function repayV3(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/margin/repay', $params);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to get the borrowing orders for cross and isolated margin accounts.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getBorrowV3(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/margin/borrow', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to get the repayment orders for cross and isolated margin accounts.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRepayV3(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/margin/repay', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * This API endpoint is used to get the interest orders for cross and isolated margin accounts.
     *
     * @param array $params
     * @param array $pagination
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getInterestV3(array $params, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/margin/interest', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * This endpoint allows querying the configuration of cross margin trading pairs.
     *
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSymbolV3($symbol = '')
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/margin/symbols', ['symbol' => $symbol]);
        return $response->getApiData();
    }

    /**
     * This endpoint allows modifying the leverage multiplier for cross margin or isolated margin.
     *
     * @param $leverage
     * @param $symbol
     * @param $isIsolated
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function modifyLeverageMultiplierV3($leverage, $symbol = '', $isIsolated = false)
    {
        $response = $this->call(Request::METHOD_POST,
            '/api/v3/position/update-user-leverage',
           compact('leverage', 'symbol', 'isIsolated')
        );
        return $response->getApiData();
    }
}
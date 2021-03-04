<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class StopOrder
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#stop-order
 */
class StopOrder extends KuCoinApi
{

    /**
     * Place a new order
     * @param array $stopOrder
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function create(array $stopOrder)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/stop-order', $stopOrder);
        return $response->getApiData();
    }

    /**
     * cancel by cond
     * @param array $params
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelBatch(array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/stop-order/cancel', $params);
        return $response->getApiData();
    }

    /**
     * Cancel an order
     * @param $orderId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancel($orderId)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/stop-order/' . $orderId);
        return $response->getApiData();
    }

    /**
     * List orders
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList(array $params = [], array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/stop-order', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get an order
     * @param $orderId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($orderId)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/stop-order/' . $orderId, []);
        return $response->getApiData();
    }


    /**
     * Get an order
     *
     * @param $clientOid
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetailByClientOid($clientOid, $symbol = null)
    {
        $params = compact('clientOid', 'symbol');
        $response = $this->call(Request::METHOD_GET, '/api/v1/stop-order/queryOrderByClientOid', $params);
        return $response->getApiData();
    }

    /**
     * Cancel an order
     * @param $clientOid
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelByClientOid($clientOid, $symbol = null)
    {
        $params = compact('clientOid', 'symbol');
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/stop-order/cancelOrderByClientOid', $params);
        return $response->getApiData();
    }
}
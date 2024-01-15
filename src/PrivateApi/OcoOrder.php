<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class OcoOrder
 * @package KuCoin\SDK\PrivateApi
 * @see https://www.kucoin.com/docs/rest/spot-trading/oco-order/introduction
 */
class OcoOrder extends KuCoinApi
{
    /**
     * Place a new order.
     *
     * @param array $order
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function create(array $order)
    {
        return $this->call(Request::METHOD_POST, '/api/v3/oco/order', $order)->getApiData();
    }

    /**
     * Cancel Order by orderId.
     *
     * @param $orderId
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancel($orderId)
    {
        return $this->call(Request::METHOD_DELETE, '/api/v3/oco/order/' . $orderId)->getApiData();
    }

    /**
     * Cancel an order by clientOid.
     *
     * @param $clientOid
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelByClientOid($clientOid)
    {
        return $this->call(Request::METHOD_DELETE, '/api/v3/oco/client-order/' . $clientOid)->getApiData();
    }

    /**
     * Cancel Multiple Orders.
     *
     * @param string $symbol trading pair. If not passed, the oco orders of all symbols will be canceled by default.
     * @param array $orderIds Specify the order number, there can be multiple orders, separated by commas. If not passed, all oco orders will be canceled by default.
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelMulti($symbol = '', array $orderIds = [])
    {
        return $this->call(Request::METHOD_DELETE, '/api/v3/oco/orders', [
            'symbol'   => $symbol,
            'orderIds' => implode(',', $orderIds),
        ])->getApiData();
    }

    /**
     * Get Order Info by orderId.
     *
     * @param $orderId
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetail($orderId)
    {
        return $this->call(Request::METHOD_GET, '/api/v3/oco/order/' . $orderId)->getApiData();
    }

    /**
     * Get Order Detail by orderId.
     *
     * @param $orderId
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOrderDetail($orderId)
    {
        return $this->call(Request::METHOD_GET, '/api/v3/oco/order/details/' . $orderId)->getApiData();
    }

    /**
     * Get Order Info by clientOid.
     *
     * @param $clientOid
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetailByClientOid($clientOid)
    {
        return $this->call(Request::METHOD_GET, '/api/v3/oco/client-order/' . $clientOid)->getApiData();
    }

    /**
     *
     * Get Order List.
     *
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList(array $params = [], array $pagination = [])
    {
        return $this->call(Request::METHOD_GET, '/api/v3/oco/orders', $params + $pagination)->getApiData();
    }
}

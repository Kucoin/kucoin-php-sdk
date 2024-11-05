<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Order
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#orders
 */
class Order extends KuCoinApi
{

    /**
     * Place a new order
     * @param array $order
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function create(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/orders', $order);
        return $response->getApiData();
    }

    /**
     * Place Bulk Orders
     * @param $symbol
     * @param array $orderList
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createMulti($symbol, array $orderList)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/orders/multi', compact('symbol', 'orderList'));
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
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/orders/' . $orderId);
        return $response->getApiData();
    }

    /**
     * Cancel all orders
     * @param string|null $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelAll($symbol = null)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/orders', compact('symbol'));
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
        $response = $this->call(Request::METHOD_GET, '/api/v1/orders', $params + $pagination);
        return $response->getApiData();
    }

    /**
     * Get v1 historical orders list
     * @param array $params
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     * @deprecated
     */
    public function getV1List(array $params = [], array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hist-orders', $params + $pagination);
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
        $response = $this->call(Request::METHOD_GET, '/api/v1/orders/' . $orderId, []);
        return $response->getApiData();
    }

    /**
     * Get the recent orders of the latest transactions within 24 hours.
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRecentList()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/limit/orders');
        return $response->getApiData();
    }

    /**
     * Get an order by clientOid
     *
     * @param $clientOid
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDetailByClientOid($clientOid)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/order/client-order/' . $clientOid, []);
        return $response->getApiData();
    }

    /**
     * Cancel an order by clientOid
     *
     * @param $clientOid
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelByClientOid($clientOid)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/order/client-order/' . $clientOid);
        return $response->getApiData();
    }


    /**
     * Place a new margin order
     *
     * @param array $order
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createMarginOrder(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/margin/order', $order);
        return $response->getApiData();
    }

    /**
     * Place a high-frequency new order.
     * @param array $order
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfCreate(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders', $order);
        return $response->getApiData();
    }

    /**
     * Place synchronize high-frequency new order.
     * @param array $order
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCreate(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/sync', $order);
        return $response->getApiData();
    }

    /**
     * Place high-frequency bulk orders.
     * @param array $orderList
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfCreateMulti(array $orderList)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/multi', $orderList);
        return $response->getApiData();
    }

    /**
     * Place synchronize high-frequency bulk orders.
     * @param array $orderList
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCreateMulti(array $orderList)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/multi/sync', $orderList);
        return $response->getApiData();
    }

    /**
     * Modify the price and quantity of the order according to orderId or clientOid.
     * @param array $orderList
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfModify(array $orderList)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/alter', $orderList);
        return $response->getApiData();
    }

    /**
     * Cancel a high-frequency order by orderId.
     * @param $orderId
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfCancel($orderId, array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/' . $orderId, $params);
        return $response->getApiData();
    }

    /**
     * Cancel synchronize  high-frequency order by orderId.
     * @param $orderId
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCancel($orderId, array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/sync/' . $orderId, $params);
        return $response->getApiData();
    }

    /**
     * Cancel a high-frequency order by ClientOid.
     * @param $orderId
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfCancelByClientOid($clientOid, array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/client-order/' . $clientOid, $params);
        return $response->getApiData();
    }

    /**
     * Cancel synchronize  high-frequency order by ClientOid.
     * @param $clientOid
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCancelByClientOid($clientOid, array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/sync/client-order/' . $clientOid, $params);
        return $response->getApiData();
    }

    /**
     * Cancel the specified quantity of the order according to the orderId.
     * @param $orderId
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCancelSize($orderId, array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/cancel' . $orderId, $params);
        return $response->getApiData();
    }

    /**
     * Cancel the specified quantity of the order according to the orderId.
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfSyncCancelAll(array $params)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders', $params);
        return $response->getApiData();
    }

    /**
     *  Get a list of all active HF orders. The return data is sorted in descending order based on the latest update times.
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getActiveOrderList(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/active', $params);
        return $response->getApiData();
    }

    /**
     * Get all trading pairs that the user has active orders.
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getActiveSymbols()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/active/symbols');
        return $response->getApiData();
    }

    /**
     * Get a list of filled HF orders and returns paginated data. The returned data is sorted in descending order based on the latest order update times.
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getDoneOrderList(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/done', $params);
        return $response->getApiData();
    }

    /**
     * Get information for a single HF order using the order id.
     * @param $orderId
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHfDetail($orderId, array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/' . $orderId, $params);
        return $response->getApiData();
    }

    /**
     * Get information about a single order using clientOid. If the order does not exist, then there will be a prompt saying that the order does not exist.
     * @param $clientOid
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHfDetailByClientOid($clientOid, array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/client-order/' . $clientOid, $params);
        return $response->getApiData();
    }

    /**
     *  Cancel all orders of the set trading pair after the specified time.
     *  If this interface is not called again for renewal or cancellation before the set time,
     *  the system will help the user to cancel the order of the corresponding trading pair.
     *  Otherwise, it will not.Call this interface to automatically cancel all orders of the set trading pair after the specified time.
     * If this interface is not called again for renewal or cancellation before the set time,
     * the system will help the user to cancel the order of the corresponding trading pair. Otherwise it will not.
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfAutoCancel(array $params)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/dead-cancel-all', $params);
        return $response->getApiData();
    }

    /**
     * Get the settings of automatic order cancellation.
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHfAutoCancel()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/orders/dead-cancel-all/query');
        return $response->getApiData();
    }

    /**
     * Get a list of the latest HF transaction details. The returned results are paginated. The data is sorted in descending order according to time.
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHfFills(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/hf/fills', $params);
        return $response->getApiData();
    }

    /**
     * Cancel all HF orders.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function hfCancelAll()
    {
        return $this->call(Request::METHOD_DELETE, '/api/v1/hf/orders/cancelAll')->getApiData();
    }

    /**
     * This interface is used to place cross-margin or isolated-margin high-frequency margin trading.
     *
     * @param array $order
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createHfMarginOrder(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/hf/margin/order', $order);
        return $response->getApiData();
    }

    /**
     * Cancel a single order by orderId. If the order cannot be canceled (sold or canceled), an error message will be returned, and the reason can be obtained according to the returned msg.
     *
     * @param $orderId
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelMarginHfOrder($orderId, $symbol)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v3/hf/margin/orders/' . $orderId, ['symbol' => $symbol]);
        return $response->getApiData();
    }

    /**
     * Cancel a single order by clientOid.
     *
     * @param $clientOid
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelMarginHfOrderByClientOid($clientOid, $symbol)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v3/hf/margin/orders/client-order/' . $clientOid, ['symbol' => $symbol]);
        return $response->getApiData();
    }

    /**
     * This interface can cancel all open high-frequency Margin orders.
     *
     * @param $symbol
     * @param $tradeType
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function cancelAllMarginHfOrder($symbol, $tradeType)
    {
        $response = $this->call(Request::METHOD_DELETE, '/api/v3/hf/margin/orders', ['symbol' => $symbol, 'tradeType' => $tradeType]);
        return $response->getApiData();
    }

    /**
     * This interface is to obtain all active hf margin order lists, and the return value of the active order interface is the paged data of all uncompleted order lists. The returned data is sorted in descending order according to the latest update time of the order.
     *
     * @param $symbol
     * @param $tradeType
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfActiveOrders($symbol, $tradeType)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/orders/active', ['symbol' => $symbol, 'tradeType' => $tradeType]);
        return $response->getApiData();
    }

    /**
     * This endpoint obtains a list of filled margin HF orders and returns paginated data. The returned data is sorted in descending order based on the latest order update times.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfFilledOrders(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/orders/done', $params);
        return $response->getApiData();
    }

    /**
     * This endpoint can be used to obtain information for a single margin HF order using the order id.
     *
     * @param $orderId
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfDetail($orderId, $symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/orders/' . $orderId, ['symbol' => $symbol]);
        return $response->getApiData();
    }

    /**
     * This endpoint can be used to obtain information for a single margin HF order using the clientOid.
     *
     * @param $clientOid
     * @param $symbol
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfDetailByClientOid($clientOid, $symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/orders/client-order/' . $clientOid, ['symbol' => $symbol]);
        return $response->getApiData();
    }

    /**
     * This endpoint can be used to obtain a list of the latest margin HF transaction details. The returned results are paginated. The data is sorted in descending order according to time.
     *
     * @param array $params
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfFills(array $params)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/fills', $params);
        return $response->getApiData();
    }

    /**
     * Order test endpoint, the request parameters and return parameters of this endpoint are exactly the same as the order endpoint, and can be used to verify whether the signature is correct and other operations. After placing an order, the order will not enter the matching system, and the order cannot be queried.
     *
     * @param array $order
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createHfTestOrder(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/hf/orders/test', $order);
        return $response->getApiData();
    }

    /**
     * Order test endpoint, the request parameters and return parameters of this endpoint are exactly the same as the order endpoint, and can be used to verify whether the signature is correct and other operations. After placing an order, the order will not enter the matching system, and the order cannot be queried.
     *
     * @param array $order
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createTestOrder(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v1/orders/test', $order);
        return $response->getApiData();
    }

    /**
     * Order test endpoint, the request parameters and return parameters of this endpoint are exactly the same as the order endpoint, and can be used to verify whether the signature is correct and other operations. After placing an order, the order will not enter the matching system, and the order cannot be queried.
     *
     * @param array $order
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function createMarginTestOrder(array $order)
    {
        $response = $this->call(Request::METHOD_POST, '/api/v3/hf/margin/order/test', $order);
        return $response->getApiData();
    }

    /**
     * This endpoint allows users to query all trading pairs with active orders.
     *
     * @param $tradeType
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getMarginHfActiveSymbols($tradeType)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v3/hf/margin/order/active/symbols', ['tradeType' => $tradeType]);
        return $response->getApiData();
    }
}

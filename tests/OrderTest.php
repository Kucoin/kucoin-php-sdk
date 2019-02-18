<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Order;

class OrderTest extends TestCase
{
    public function testNewAuth()
    {
        $auth = new Auth($this->apiKey, $this->apiSecret, $this->apiPassPhrase);
        $this->assertInstanceOf(Auth::class, $auth);
        return $auth;
    }

    /**
     * @depends testNewAuth
     * @param Auth $auth
     * @return Order
     */
    public function testNewOrder(Auth $auth)
    {
        $api = new Order($auth);
        $this->assertInstanceOf(Order::class, $api);
        return $api;
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateLimit(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'limit',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'Test Order ' . time(),

            'price' => 100,
            'size'  => 0.001,
        ];
        $data = $api->create($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        var_dump($data['orderId']);
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateMarket(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'market',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'Test Order ' . time(),

            'size' => 0.001,
        ];
        $data = $api->create($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        var_dump($data['orderId']);
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Order $api)
    {
        $data = $api->getList(null, 'BTC-USDT', ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('stopTriggered', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('dealFunds', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('stopPrice', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('cancelExist', $item);
            $this->assertArrayHasKey('clientOid', $item);
        }
        return $data['items'];
    }

    /**
     * @depends testNewOrder
     * @depends testGetList
     * @param Order $api
     * @param array $orders
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Order $api, array $orders)
    {
        if (isset($orders[0])) {
            $order = $api->getDetail($orders[0]['id']);
            $this->assertArrayHasKey('symbol', $order);
            $this->assertArrayHasKey('hidden', $order);
            $this->assertArrayHasKey('opType', $order);
            $this->assertArrayHasKey('fee', $order);
            $this->assertArrayHasKey('channel', $order);
            $this->assertArrayHasKey('feeCurrency', $order);
            $this->assertArrayHasKey('remark', $order);
            $this->assertArrayHasKey('type', $order);
            $this->assertArrayHasKey('iceberge', $order);
            $this->assertArrayHasKey('createdAt', $order);
            $this->assertArrayHasKey('visibleSize', $order);
            $this->assertArrayHasKey('price', $order);
            $this->assertArrayHasKey('stopTriggered', $order);
            $this->assertArrayHasKey('funds', $order);
            $this->assertArrayHasKey('id', $order);
            $this->assertArrayHasKey('timeInForce', $order);
            $this->assertArrayHasKey('side', $order);
            $this->assertArrayHasKey('dealSize', $order);
            $this->assertArrayHasKey('cancelAfter', $order);
            $this->assertArrayHasKey('dealFunds', $order);
            $this->assertArrayHasKey('stp', $order);
            $this->assertArrayHasKey('postOnly', $order);
            $this->assertArrayHasKey('stopPrice', $order);
            $this->assertArrayHasKey('size', $order);
            $this->assertArrayHasKey('stop', $order);
            $this->assertArrayHasKey('cancelExist', $order);
            $this->assertArrayHasKey('clientOid', $order);
        }
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancel($api)
    {
        $result = $api->cancel('5c1b6bcf03aa670b44027723');
        var_dump($result);
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelAll($api)
    {
        $result = $api->cancelAll('BTC-USDT');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('cancelledOrderIds', $result);
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLimitFills(Order $api)
    {
        $fills = $api->getLimitFills();
        foreach ($fills as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('stopTriggered', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('dealFunds', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('stopPrice', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('cancelExist', $item);
            $this->assertArrayHasKey('clientOid', $item);
        }
    }

    /**
     * @depends testNewOrder
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLimitOrders(Order $api)
    {
        $fills = $api->getLimitOrders();
        foreach ($fills as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('stopTriggered', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('dealFunds', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('stopPrice', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('cancelExist', $item);
            $this->assertArrayHasKey('clientOid', $item);
        }
    }
}

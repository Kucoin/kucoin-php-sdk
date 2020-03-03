<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PrivateApi\Order;

class OrderTest extends TestCase
{
    protected $apiClass    = Order::class;
    protected $apiWithAuth = true;

    /**
     * @dataProvider apiProvider
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
            'remark'    => '\中文备注 ',

            'price' => 100,
            'size'  => 0.001,
        ];
        $data = $api->create($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        var_dump($data['orderId']);
    }

    /**
     * @dataProvider apiProvider
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
     * @dataProvider apiProvider
     * @param Order $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateMulti(Order $api)
    {
        $order = [
            'type'   => 'limit',
            'side'   => 'buy',
            'remark' => 'Multi 1',
            'price'  => 100,
            'size'   => 0.001,
        ];
        $symbol = 'BTC-USDT';
        $orderList = [];
        for ($i = 0; $i < 5; $i++) {
            $order['clientOid'] = uniqid();
            $order['remark'] = 'Multi ' . $i;
            $orderList[] = $order;
        }
        $response = $api->createMulti($symbol, $orderList);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('data', $response);
        $data = $response['data'];
        $this->assertInternalType('array', $data);
        var_dump($data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Order $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
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
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetV1List(Order $api)
    {
        $this->markTestSkipped();
        return;
        $data = $api->getV1List(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('dealValue', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('dealPrice', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Order $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        $orders = $data['items'];
        if (isset($orders[0])) {
            $order = $api->getDetail($orders[0]['id']);
            $this->assertArrayHasKey('symbol', $order);
            $this->assertArrayHasKey('hidden', $order);
            $this->assertArrayHasKey('opType', $order);
            $this->assertArrayHasKey('fee', $order);
            $this->assertArrayHasKey('channel', $order);
            $this->assertArrayHasKey('feeCurrency', $order);
            $this->assertArrayHasKey('type', $order);
            $this->assertArrayHasKey('iceberg', $order);
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
        }
    }

    /**
     * @dataProvider apiProvider
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
     * @dataProvider apiProvider
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
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRecentList(Order $api)
    {
        $items = $api->getRecentList();
        foreach ($items as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
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
        }
    }
}

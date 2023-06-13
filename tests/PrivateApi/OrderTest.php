<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\Order;

class OrderTest extends TestCase
{
    protected $apiClass = Order::class;

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


    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetailByClientOid(Order $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT', 'status' => 'active'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        $orders = $data['items'];
        if (isset($orders[0])) {
            $order = $api->getDetailByClientOid($orders[0]['clientOid']);
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
    public function testCancelByClientOid(Order $api)
    {
        $clientOid = uniqid();
        $order = [
            'clientOid' => $clientOid,
            'type'      => 'limit',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => '\中文备注 ',

            'price' => 100,
            'size'  => 0.001,
        ];
        $data = $api->create($order);
        if (isset($data['orderId'])) {
            $data = $api->cancelByClientOid($clientOid);
            $this->assertInternalType('array', $data);
            $this->assertArrayHasKey('cancelledOrderId', $data);
            $this->assertArrayHasKey('clientOid', $data);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateMarginLimit(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'limit',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => '\中文备注 ',

            'price' => 1,
            'size'  => 0.1,
        ];
        $data = $api->createMarginOrder($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfCreateLimit(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'limit',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'test',
            'price'     => 100,
            'size'      => 1,
        ];
        $data = $api->hfCreate($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfCreateMarket(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'market',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'Test Order ',
            'size'      => 2,
        ];
        $data = $api->hfCreate($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCreateLimit(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'limit',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'test',
            'price'     => "1",
            'size'      => "1",
        ];
        $data = $api->hfSyncCreate($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('orderTime', $data);
        $this->assertArrayHasKey('originSize', $data);
        $this->assertArrayHasKey('dealSize', $data);
        $this->assertArrayHasKey('remainSize', $data);
        $this->assertArrayHasKey('canceledSize', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('matchTime', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCreateMarket(Order $api)
    {
        $order = [
            'clientOid' => uniqid(),
            'type'      => 'market',
            'side'      => 'buy',
            'symbol'    => 'BTC-USDT',
            'remark'    => 'Test Order ',
            'size'      => 2,
        ];
        $data = $api->hfSyncCreate($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('orderTime', $data);
        $this->assertArrayHasKey('originSize', $data);
        $this->assertArrayHasKey('dealSize', $data);
        $this->assertArrayHasKey('remainSize', $data);
        $this->assertArrayHasKey('canceledSize', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('matchTime', $data);
        var_dump($data['orderId'], $order);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfCreateMulti(Order $api)
    {
        $order = [
            'type'  => 'limit',
            'side'  => 'buy',
            'price' => 100,
            'size'  => 1,
        ];
        $orderList = [];
        for ($i = 0; $i < 5; $i++) {
            $order['clientOid'] = uniqid();
            $order['remark'] = 'Multi';
            $order['symbol'] = 'BTC-USDT';
            $orderList[] = $order;
        }
        $data = $api->hfCreateMulti($orderList);
        foreach ($data as $item) {
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('success', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCreateMulti(Order $api)
    {
        $order = [
            'type'  => 'limit',
            'side'  => 'buy',
            'price' => 100,
            'size'  => 1,
        ];
        $orderList = [];
        for ($i = 0; $i < 5; $i++) {
            $order['clientOid'] = uniqid();
            $order['symbol'] = 'BTC-USDT';
            $order['remark'] = 'Multi';
            $orderList[] = $order;
        }

        $data = $api->hfSyncCreateMulti($orderList);
        $this->assertInternalType('array', $data);
        foreach ($data as $item) {
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('orderTime', $item);
            $this->assertArrayHasKey('originSize', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('remainSize', $item);
            $this->assertArrayHasKey('canceledSize', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('matchTime', $item);
            $this->assertArrayHasKey('success', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfModify(Order $api)
    {
        $order = [
            'symbol'    => 'BTC-USDT',
            'clientOid' => '64819b02d066b',
//            'orderId' => '64819b035b13c7000179854c',
            'newPrice'  => 2,
        ];
        $data = $api->hfModify($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfCancel(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $orderId = '64819b035b13c7000179854c';
        $data = $api->hfCancel($orderId, $params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCancel(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $orderId = '64819b035b13c7000179854c';
        $data = $api->hfSyncCancel($orderId, $params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('originSize', $data);
        $this->assertArrayHasKey('originFunds', $data);
        $this->assertArrayHasKey('dealSize', $data);
        $this->assertArrayHasKey('remainSize', $data);
        $this->assertArrayHasKey('canceledSize', $data);
        $this->assertArrayHasKey('status', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfCancelByClientOid(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $clientOid = '64819b02d066b';
        $data = $api->hfCancelByClientOid($clientOid, $params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('clientOid', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCancelByClientOid(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $clientOid = '64819b02d066b';
        $data = $api->hfSyncCancelByClientOid($clientOid, $params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('originSize', $data);
        $this->assertArrayHasKey('originFunds', $data);
        $this->assertArrayHasKey('dealSize', $data);
        $this->assertArrayHasKey('remainSize', $data);
        $this->assertArrayHasKey('canceledSize', $data);
        $this->assertArrayHasKey('status', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCancelSize(Order $api)
    {
        $params = [
            'symbol'     => 'BTC-USDT',
            'cancelSize' => 1,
        ];
        $orderId = '64819b035b13c7000179854c';
        $data = $api->hfSyncCancelSize($orderId, $params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('cancelSize', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfSyncCancelAll(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $api->hfSyncCancelAll($params);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetActiveOrderList(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $data = $api->getActiveOrderList($params);
        $this->assertInternalType('array', $data);
        foreach ($data as $item) {
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('cancelledSize', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('remainSize', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('cancelledFunds', $item);
            $this->assertArrayHasKey('dealFunds', $item);
            $this->assertArrayHasKey('remainFunds', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('clientOid', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('tags', $item);
            $this->assertArrayHasKey('active', $item);
            $this->assertArrayHasKey('inOrderBook', $item);
            $this->assertArrayHasKey('cancelExist', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('lastUpdatedAt', $item);
            $this->assertArrayHasKey('tradeType', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetActiveSymbols(Order $api)
    {
        $data = $api->getActiveSymbols();
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('symbols', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDoneOrderList(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $response = $api->getDoneOrderList($params);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('lastId', $response);
        foreach ($response['items'] as $item) {
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('opType', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('cancelledSize', $item);
            $this->assertArrayHasKey('dealSize', $item);
            $this->assertArrayHasKey('remainSize', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('cancelledFunds', $item);
            $this->assertArrayHasKey('dealFunds', $item);
            $this->assertArrayHasKey('remainFunds', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('clientOid', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('tags', $item);
            $this->assertArrayHasKey('active', $item);
            $this->assertArrayHasKey('inOrderBook', $item);
            $this->assertArrayHasKey('cancelExist', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('lastUpdatedAt', $item);
            $this->assertArrayHasKey('tradeType', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHfDetail(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $orderId = '64819b035b13c7000179854c';
        $item = $api->getHfDetail($orderId, $params);
        $this->assertInternalType('array', $item);
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('symbol', $item);
        $this->assertArrayHasKey('opType', $item);
        $this->assertArrayHasKey('type', $item);
        $this->assertArrayHasKey('side', $item);
        $this->assertArrayHasKey('price', $item);
        $this->assertArrayHasKey('size', $item);
        $this->assertArrayHasKey('cancelledSize', $item);
        $this->assertArrayHasKey('dealSize', $item);
        $this->assertArrayHasKey('remainSize', $item);
        $this->assertArrayHasKey('funds', $item);
        $this->assertArrayHasKey('cancelledFunds', $item);
        $this->assertArrayHasKey('dealFunds', $item);
        $this->assertArrayHasKey('remainFunds', $item);
        $this->assertArrayHasKey('fee', $item);
        $this->assertArrayHasKey('feeCurrency', $item);
        $this->assertArrayHasKey('stp', $item);
        $this->assertArrayHasKey('timeInForce', $item);
        $this->assertArrayHasKey('postOnly', $item);
        $this->assertArrayHasKey('hidden', $item);
        $this->assertArrayHasKey('iceberg', $item);
        $this->assertArrayHasKey('visibleSize', $item);
        $this->assertArrayHasKey('cancelAfter', $item);
        $this->assertArrayHasKey('channel', $item);
        $this->assertArrayHasKey('clientOid', $item);
        $this->assertArrayHasKey('remark', $item);
        $this->assertArrayHasKey('tags', $item);
        $this->assertArrayHasKey('active', $item);
        $this->assertArrayHasKey('inOrderBook', $item);
        $this->assertArrayHasKey('cancelExist', $item);
        $this->assertArrayHasKey('createdAt', $item);
        $this->assertArrayHasKey('lastUpdatedAt', $item);
        $this->assertArrayHasKey('tradeType', $item);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHfDetailByClientOid(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $clientOid = '64819b02d066b';
        $item = $api->getHfDetailByClientOid($clientOid, $params);
        $this->assertInternalType('array', $item);
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('symbol', $item);
        $this->assertArrayHasKey('opType', $item);
        $this->assertArrayHasKey('type', $item);
        $this->assertArrayHasKey('side', $item);
        $this->assertArrayHasKey('price', $item);
        $this->assertArrayHasKey('size', $item);
        $this->assertArrayHasKey('cancelledSize', $item);
        $this->assertArrayHasKey('dealSize', $item);
        $this->assertArrayHasKey('remainSize', $item);
        $this->assertArrayHasKey('funds', $item);
        $this->assertArrayHasKey('cancelledFunds', $item);
        $this->assertArrayHasKey('dealFunds', $item);
        $this->assertArrayHasKey('remainFunds', $item);
        $this->assertArrayHasKey('fee', $item);
        $this->assertArrayHasKey('feeCurrency', $item);
        $this->assertArrayHasKey('stp', $item);
        $this->assertArrayHasKey('timeInForce', $item);
        $this->assertArrayHasKey('postOnly', $item);
        $this->assertArrayHasKey('hidden', $item);
        $this->assertArrayHasKey('iceberg', $item);
        $this->assertArrayHasKey('visibleSize', $item);
        $this->assertArrayHasKey('cancelAfter', $item);
        $this->assertArrayHasKey('channel', $item);
        $this->assertArrayHasKey('clientOid', $item);
        $this->assertArrayHasKey('remark', $item);
        $this->assertArrayHasKey('tags', $item);
        $this->assertArrayHasKey('active', $item);
        $this->assertArrayHasKey('inOrderBook', $item);
        $this->assertArrayHasKey('cancelExist', $item);
        $this->assertArrayHasKey('createdAt', $item);
        $this->assertArrayHasKey('lastUpdatedAt', $item);
        $this->assertArrayHasKey('tradeType', $item);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testHfAutoCancel(Order $api)
    {
        $params = ['timeout' => 20];
        $data = $api->hfAutoCancel($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('currentTime', $data);
        $this->assertArrayHasKey('triggerTime', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHfAutoCancel(Order $api)
    {
        $data = $api->getHfAutoCancel();
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('timeout', $data);
        $this->assertArrayHasKey('symbols', $data);
        $this->assertArrayHasKey('currentTime', $data);
        $this->assertArrayHasKey('triggerTime', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Order $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHfFills(Order $api)
    {
        $params = [
            'symbol' => 'BTC-USDT',
        ];
        $data = $api->getHfFills($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('lastId', $data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('tradeId', $item);
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('counterOrderId', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('liquidity', $item);
            $this->assertArrayHasKey('forceTaker', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('feeRate', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('tradeType', $item);
        }
    }

}

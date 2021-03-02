<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PrivateApi\StopOrder;

class StopOrderTest extends TestCase
{
    protected $apiClass    = StopOrder::class;
    protected $apiWithAuth = true;

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateLimit(StopOrder $api)
    {
        $clientOid = uniqid('', true);
        $order = [
            'clientOid' => $clientOid,
            'type'      => 'limit',
            'side'      => 'sell',
            'symbol'    => 'BTC-USDT',
            'remark'    => '\中文备注 ',
            'stopPrice' => '10',
            'price'     => '5',
            'size'      => '0.0001',
        ];
        $data = $api->create($order);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        var_dump($data['orderId'], $clientOid);
    }

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(StopOrder $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('userId', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('stp', $item);
            $this->assertArrayHasKey('timeInForce', $item);
            $this->assertArrayHasKey('cancelAfter', $item);
            $this->assertArrayHasKey('postOnly', $item);
            $this->assertArrayHasKey('hidden', $item);
            $this->assertArrayHasKey('iceberg', $item);
            $this->assertArrayHasKey('visibleSize', $item);
            $this->assertArrayHasKey('channel', $item);
            $this->assertArrayHasKey('clientOid', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('tags', $item);
            $this->assertArrayHasKey('tradeType', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('stopPrice', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(StopOrder $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        $orders = $data['items'];
        if (isset($orders[0])) {
            $order = $api->getDetail($orders[0]['id']);
            $this->assertArrayHasKey('id', $order);
            $this->assertArrayHasKey('symbol', $order);
            $this->assertArrayHasKey('userId', $order);
            $this->assertArrayHasKey('type', $order);
            $this->assertArrayHasKey('side', $order);
            $this->assertArrayHasKey('price', $order);
            $this->assertArrayHasKey('size', $order);
            $this->assertArrayHasKey('funds', $order);
            $this->assertArrayHasKey('stp', $order);
            $this->assertArrayHasKey('timeInForce', $order);
            $this->assertArrayHasKey('cancelAfter', $order);
            $this->assertArrayHasKey('postOnly', $order);
            $this->assertArrayHasKey('hidden', $order);
            $this->assertArrayHasKey('iceberg', $order);
            $this->assertArrayHasKey('visibleSize', $order);
            $this->assertArrayHasKey('channel', $order);
            $this->assertArrayHasKey('clientOid', $order);
            $this->assertArrayHasKey('remark', $order);
            $this->assertArrayHasKey('tags', $order);
            $this->assertArrayHasKey('tradeType', $order);
            $this->assertArrayHasKey('feeCurrency', $order);
            $this->assertArrayHasKey('createdAt', $order);
            $this->assertArrayHasKey('stop', $order);
            $this->assertArrayHasKey('stopPrice', $order);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancel(StopOrder $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        $orders = $data['items'];
        if (isset($orders[0])) {
            $result = $api->cancel($orders[0]['id']);
            $this->assertInternalType('array', $result);
            $this->assertArrayHasKey('cancelledOrderIds', $result);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetailByOClient(StopOrder $api)
    {
        $data = $api->getList(['symbol' => 'BTC-USDT', 'status' => 'active'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        $orders = $data['items'];
        if (isset($orders[0])) {
            $orders = $api->getDetailByClient($orders[0]['clientOid']);
            if (isset($orders[0])) {
                $order = $orders[0];
                $this->assertArrayHasKey('id', $order);
                $this->assertArrayHasKey('symbol', $order);
                $this->assertArrayHasKey('userId', $order);
                $this->assertArrayHasKey('type', $order);
                $this->assertArrayHasKey('side', $order);
                $this->assertArrayHasKey('price', $order);
                $this->assertArrayHasKey('size', $order);
                $this->assertArrayHasKey('funds', $order);
                $this->assertArrayHasKey('stp', $order);
                $this->assertArrayHasKey('timeInForce', $order);
                $this->assertArrayHasKey('cancelAfter', $order);
                $this->assertArrayHasKey('postOnly', $order);
                $this->assertArrayHasKey('hidden', $order);
                $this->assertArrayHasKey('iceberg', $order);
                $this->assertArrayHasKey('visibleSize', $order);
                $this->assertArrayHasKey('channel', $order);
                $this->assertArrayHasKey('clientOid', $order);
                $this->assertArrayHasKey('remark', $order);
                $this->assertArrayHasKey('tags', $order);
                $this->assertArrayHasKey('tradeType', $order);
                $this->assertArrayHasKey('feeCurrency', $order);
                $this->assertArrayHasKey('createdAt', $order);
                $this->assertArrayHasKey('stop', $order);
                $this->assertArrayHasKey('stopPrice', $order);
            }
        }
    }

    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelByClient(StopOrder $api)
    {
        $clientOid = uniqid();
        $order = [
            'clientOid' => $clientOid,
            'type'      => 'limit',
            'side'      => 'sell',
            'symbol'    => 'BTC-USDT',
            'remark'    => '\中文备注 ',
            'stopPrice' => '90',
            'price'     => '5',
            'size'      => '0.001',
        ];

        $data = $api->create($order);
        if (isset($data['orderId'])) {
            $data = $api->cancelByClient($clientOid);
            $this->assertInternalType('array', $data);
            $this->assertArrayHasKey('cancelledOrderId', $data);
            $this->assertArrayHasKey('clientOid', $data);
        }
    }


    /**
     * @dataProvider apiProvider
     * @param StopOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelByCond(StopOrder $api)
    {
        $clientOid = uniqid();
        $order = [
            'clientOid' => $clientOid,
            'type'      => 'limit',
            'side'      => 'sell',
            'symbol'    => 'BTC-USDT',
            'remark'    => '\中文备注 ',
            'stopPrice' => '90',
            'price'     => '5',
            'size'      => '0.001',
        ];

        $data = $api->create($order);
        if (isset($data['orderId'])) {
            $data = $api->cancelByCond(['symbol' => 'BTC-USDT']);
            $this->assertInternalType('array', $data);
            $this->assertArrayHasKey('cancelledOrderIds', $data);
        }
    }
}

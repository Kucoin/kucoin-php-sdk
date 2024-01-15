<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\OcoOrder;
use KuCoin\SDK\PublicApi\Symbol;

class OcoOrderTest extends TestCase
{
    protected $apiClass = OcoOrder::class;

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreate(OcoOrder $api)
    {
        $data = $this->create($api);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancel(OcoOrder $api)
    {
        $data = $this->create($api);
        $result = $api->cancel($data['orderId']);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('cancelledOrderIds', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelByClientOid(OcoOrder $api)
    {
        $data = $this->create($api);
        if (isset($data['orderId'])) {
            $result = $api->cancelByClientOid($data['clientOid']);
            $this->assertInternalType('array', $result);
            $this->assertArrayHasKey('cancelledOrderIds', $result);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(OcoOrder $api)
    {
        $data = $api->getList(['symbol' => 'DOT-USDT'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('clientOid', $item);
            $this->assertArrayHasKey('orderTime', $item);
            $this->assertArrayHasKey('status', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelMulti(OcoOrder $api)
    {
        $order = $this->create($api);
        $result = $api->cancelMulti($order['symbol']);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('cancelledOrderIds', $result);
        $order = $this->create($api);
        $result = $api->cancelMulti('', [$order['orderId']]);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('cancelledOrderIds', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(OcoOrder $api)
    {
        $order = $this->create($api);
        $result = $api->getDetail($order['orderId']);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('symbol', $result);
        $this->assertArrayHasKey('orderId', $result);
        $this->assertArrayHasKey('clientOid', $result);
        $this->assertArrayHasKey('orderTime', $result);
        $this->assertArrayHasKey('status', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetailByClientOid(OcoOrder $api)
    {
        $order = $this->create($api);
        $result = $api->getDetailByClientOid($order['clientOid']);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('symbol', $result);
        $this->assertArrayHasKey('orderId', $result);
        $this->assertArrayHasKey('clientOid', $result);
        $this->assertArrayHasKey('orderTime', $result);
        $this->assertArrayHasKey('status', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param OcoOrder $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetOrderDetail(OcoOrder $api)
    {
        $order = $this->create($api);
        $result = $api->getOrderDetail($order['orderId']);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('symbol', $result);
        $this->assertArrayHasKey('orderId', $result);
        $this->assertArrayHasKey('clientOid', $result);
        $this->assertArrayHasKey('orderTime', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('orders', $result);
        foreach ($result['orders'] as $order) {
            $this->assertArrayHasKey('id', $order);
            $this->assertArrayHasKey('symbol', $order);
            $this->assertArrayHasKey('side', $order);
            $this->assertArrayHasKey('price', $order);
            $this->assertArrayHasKey('stopPrice', $order);
            $this->assertArrayHasKey('size', $order);
            $this->assertArrayHasKey('status', $order);
        }
    }

    /**
     * clean active order
     *
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public static function tearDownAfterClass()
    {
        $apiIns = (new OcoOrderTest)->apiProvider();
        foreach ($apiIns as $item) {
            /**
             * @var $api OcoOrder
             */
            $api = current($item);
            try {
                $api->cancelMulti();
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @param OcoOrder $api
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    protected function create(OcoOrder $api)
    {
        $symbolApi = new Symbol();
        $symbol = 'DOT-USDT';

        $tradeStats = $symbolApi->get24HStats($symbol);
        $nowPrice = $tradeStats['last'];
        $openPrice = (string) round($nowPrice * '0.7', 2);
        $stopPrice = (string) round($nowPrice * '1.3', 2);
        $order = [
            'clientOid'  => uniqid(),
            'type'       => 'limit',
            'side'       => 'buy',
            'symbol'     => $symbol,
            'remark'     => 'test oco order',
            'price'      => $openPrice,
            'size'       => 0.1,
            'stopPrice'  => $stopPrice,
            'limitPrice' => $stopPrice,
        ];

        $result = $api->create($order);
        $order['orderId'] = $result['orderId'];
        return $order;
    }
}

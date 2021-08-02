<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\Fill;

class FillTest extends TestCase
{
    protected $apiClass = Fill::class;

    /**
     * @dataProvider apiProvider
     * @param Fill $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Fill $api)
    {
        $data = $api->getList([], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('forceTaker', $item);
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('liquidity', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('feeRate', $item);
            $this->assertArrayHasKey('counterOrderId', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('tradeId', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Fill $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRecentList(Fill $api)
    {
        $items = $api->getRecentList();
        foreach ($items as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('side', $item);
            $this->assertArrayHasKey('forceTaker', $item);
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('liquidity', $item);
            $this->assertArrayHasKey('type', $item);
            $this->assertArrayHasKey('feeRate', $item);
            $this->assertArrayHasKey('counterOrderId', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('tradeId', $item);
        }
    }
}
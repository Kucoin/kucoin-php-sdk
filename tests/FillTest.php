<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Fill;

class FillTest extends TestCase
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
     * @return Fill
     */
    public function testNewFill(Auth $auth)
    {
        $api = new Fill($auth);
        $this->assertInstanceOf(Fill::class, $api);
        return $api;
    }

    /**
     * @depends testNewFill
     * @param Fill $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Fill $api)
    {
        $data = $api->getList('5c1b409c03aa6732f1f89ed3', 'BTC-USDT', ['pageSize' => 2]);
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
            $this->assertArrayHasKey('displayType', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('stop', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('funds', $item);
            $this->assertArrayHasKey('tradeId', $item);
        }
    }
}
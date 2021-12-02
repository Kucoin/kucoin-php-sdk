<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\Symbol;

class SymbolTest extends TestCase
{
    protected $apiClass = Symbol::class;

    /**
     * @dataProvider apiProvider
     * @param Symbol $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetAggregatedFullOrderBook(Symbol $api)
    {
        $data = $api->getAggregatedFullOrderBook('ETH-BTC');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('sequence', $data);
        $this->assertArrayHasKey('bids', $data);
        $this->assertArrayHasKey('asks', $data);
        $this->assertArrayHasKey('time', $data);
    }
}
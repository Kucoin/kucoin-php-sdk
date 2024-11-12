<?php

namespace KuCoin\SDK\Tests\PublicApi;

use KuCoin\SDK\PublicApi\Margin;

class MarginTest extends TestCase
{
    protected $apiClass = Margin::class;

    /**
     * @dataProvider apiProvider
     *
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSymbolsMarkPrice(Margin $api)
    {
        $data = $api->getSymbolsMarkPrice();
        $this->assertInternalType('array', $data);
        foreach ($data as $item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('timePoint', $item);
            $this->assertArrayHasKey('value', $item);
        }
    }
}
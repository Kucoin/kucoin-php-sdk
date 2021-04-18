<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PrivateApi\TradeFee;

class TradeFeeTest extends TestCase
{
    protected $apiClass    = TradeFee::class;
    protected $apiWithAuth = true;

    /**
     * @dataProvider apiProvider
     * @param TradeFee $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetBaseFee(TradeFee $api)
    {
        $data = $api->getBaseFee();
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('takerFeeRate', $data);
        $this->assertArrayHasKey('makerFeeRate', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param TradeFee $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetTradeFees(TradeFee $api)
    {
        $symbols = ['BTC-USDT', 'ETH-USDT'];
        $data = $api->getTradeFees($symbols);
        $this->assertInternalType('array', $data);
        foreach ($data as $v) {
            $this->assertArrayHasKey('symbol', $v);
            $this->assertArrayHasKey('takerFeeRate', $v);
            $this->assertArrayHasKey('makerFeeRate', $v);
            $this->assertContains($v['symbol'], $symbols);
        }
    }
}

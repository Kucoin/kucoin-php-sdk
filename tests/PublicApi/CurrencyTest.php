<?php

namespace KuCoin\SDK\Tests\PublicApi;

use KuCoin\SDK\PublicApi\Currency;

class CurrencyTest extends TestCase
{
    protected $apiClass = Currency::class;

    /**
     * @dataProvider apiProvider
     * @param Currency $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Currency $api)
    {
        $currencies = $api->getList();
        $this->assertInternalType('array', $currencies);
        foreach ($currencies as $currency) {
            $this->assertArrayHasKey('currency', $currency);
            $this->assertArrayHasKey('name', $currency);
            $this->assertArrayHasKey('fullName', $currency);
            $this->assertArrayHasKey('precision', $currency);
            $this->assertArrayHasKey('withdrawalMinSize', $currency);
            $this->assertArrayHasKey('withdrawalMinFee', $currency);
            $this->assertArrayHasKey('isWithdrawEnabled', $currency);
            $this->assertArrayHasKey('isDepositEnabled', $currency);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Currency $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Currency $api)
    {
        $currency = $api->getDetail('BTC');
        $this->assertInternalType('array', $currency);
        $this->assertArrayHasKey('currency', $currency);
        $this->assertArrayHasKey('name', $currency);
        $this->assertArrayHasKey('fullName', $currency);
        $this->assertArrayHasKey('precision', $currency);
        $this->assertArrayHasKey('withdrawalMinSize', $currency);
        $this->assertArrayHasKey('withdrawalMinFee', $currency);
        $this->assertArrayHasKey('isWithdrawEnabled', $currency);
        $this->assertArrayHasKey('isDepositEnabled', $currency);
    }

    /**
     * @dataProvider apiProvider
     * @param Currency $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetPrices(Currency $api)
    {
        $prices = $api->getPrices('USD', 'BTC,KCS');
        $this->assertInternalType('array', $prices);
        $this->assertNotEmpty($prices);
    }
}
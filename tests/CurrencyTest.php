<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PublicApi\Currency;

class CurrencyTest extends TestCase
{
    /**
     * @return Currency
     */
    public function testNewCurrency()
    {
        $api = new Currency();
        $this->assertInstanceOf(Currency::class, $api);
        return $api;
    }

    /**
     * @depends testNewCurrency
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
     * @depends testNewCurrency
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
}
<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PublicApi\Currency;

class CurrencyTest extends TestCase
{
    protected $apiClass    = Currency::class;
    protected $apiWithAuth = false;

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

    /**
     * @dataProvider apiProvider
     * @param Currency $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetV2Detail(Currency $api)
    {
        $currency = $api->getV2Detail('BTC');
        $this->assertInternalType('array', $currency);
        $this->assertArrayHasKey('currency', $currency);
        $this->assertArrayHasKey('name', $currency);
        $this->assertArrayHasKey('fullName', $currency);
        $this->assertArrayHasKey('precision', $currency);
        $this->assertArrayHasKey('confirms', $currency);
        $this->assertArrayHasKey('contractAddress', $currency);
        $this->assertArrayHasKey('isMarginEnabled', $currency);
        $this->assertArrayHasKey('isDebitEnabled', $currency);
        $this->assertArrayHasKey('chains', $currency);
        $this->assertInternalType('array', $currency['chains']);
    }
}
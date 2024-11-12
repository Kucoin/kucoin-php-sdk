<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\VipLend;

class VipLendTest extends TestCase
{
    protected $apiClass = VipLend::class;

    /**
     * @dataProvider apiProvider
     *
     * @param VipLend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLoan(VipLend $api)
    {
        $result = $api->getLoan();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('orders', $result);
        $this->assertArrayHasKey('ltv', $result);
        $this->assertArrayHasKey('totalMarginAmount', $result);
        $this->assertArrayHasKey('transferMarginAmount', $result);
        $this->assertArrayHasKey('margins', $result);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param VipLend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetAccounts(VipLend $api)
    {
        $result = $api->getAccounts();
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertArrayHasKey('uid', $item);
            $this->assertArrayHasKey('marginCcy', $item);
            $this->assertArrayHasKey('marginQty', $item);
            $this->assertArrayHasKey('marginFactor', $item);
            $this->assertArrayHasKey('accountType', $item);
            $this->assertArrayHasKey('isParent', $item);
        }
    }
}

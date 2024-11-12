<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\Enums\AccountType;
use KuCoin\SDK\PrivateApi\Affiliate;
use KuCoin\SDK\PrivateApi\Earn;

class AffiliateTest extends TestCase
{
    protected $apiClass = Affiliate::class;

    /**
     * @dataProvider apiProvider
     *
     * @param Affiliate $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetInviterStatistics(Affiliate $api)
    {
        $statistics = $api->getInviterStatistics(date('Ymd', strtotime('-10 day')));
        $this->assertInternalType('array', $statistics);
    }
}

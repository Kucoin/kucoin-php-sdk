<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PublicApi\Time;

class TimeTest extends TestCase
{
    protected $apiClass    = Time::class;
    protected $apiWithAuth = false;

    /**
     * @dataProvider apiProvider
     * @param Time $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testTimestamp(Time $api)
    {
        $timestamp = $api->timestamp();
        $this->assertInternalType('int', $timestamp);
    }
}
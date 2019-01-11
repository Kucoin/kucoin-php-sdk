<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\PublicApi\Time;

class TimeTest extends TestCase
{
    /**
     * @return Time
     */
    public function testNewTime()
    {
        $api = new Time();
        $this->assertInstanceOf(Time::class, $api);
        return $api;
    }

    /**
     * @depends testNewTime
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
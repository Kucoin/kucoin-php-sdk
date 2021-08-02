<?php

namespace KuCoin\SDK\Tests\PublicApi;

use KuCoin\SDK\PublicApi\ServiceStatus;

class ServiceStatusTest extends TestCase
{
    protected $apiClass = ServiceStatus::class;

    /**
     * @dataProvider apiProvider
     * @param ServiceStatus $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetStatus(ServiceStatus $api)
    {
        $status = $api->getStatus();
        $this->assertInternalType('array', $status);
        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('msg', $status);
    }
}
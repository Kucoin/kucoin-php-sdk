<?php

namespace KuCoin\SDK\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $apiKey;
    protected $apiSecret;
    protected $apiPassPhrase;

    protected function setUp()
    {
        parent::setUp();

        $this->apiKey = constant('API_KEY');
        $this->apiSecret = constant('API_SECRET');
        $this->apiPassPhrase = constant('API_PASSPHRASE');
    }

    protected function assertPagination($data)
    {
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('totalNum', $data);
        $this->assertArrayHasKey('totalPage', $data);
        $this->assertArrayHasKey('pageSize', $data);
        $this->assertArrayHasKey('currentPage', $data);
        $this->assertArrayHasKey('items', $data);
        $this->assertInternalType('array', $data['items']);
    }
}
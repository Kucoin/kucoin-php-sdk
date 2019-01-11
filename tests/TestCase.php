<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\KuCoinApi;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $apiKey;
    protected $apiSecret;
    protected $apiPassPhrase;
    protected $apiBaseUri;

    protected function setUp()
    {
        parent::setUp();

        $this->apiKey = getenv('API_KEY');
        $this->apiSecret = getenv('API_SECRET');
        $this->apiPassPhrase = getenv('API_PASSPHRASE');
        $this->apiBaseUri = getenv('API_BASE_URI');
        if ($this->apiBaseUri) {
            KuCoinApi::setBaseUri($this->apiBaseUri);
        }
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
<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Deposits;

class DepositsTest extends TestCase
{
    public function testNewAuth()
    {
        $auth = new Auth($this->apiKey, $this->apiSecret, $this->apiPassPhrase);
        $this->assertInstanceOf(Auth::class, $auth);
        return $auth;
    }

    /**
     * @depends testNewAuth
     * @param Auth $auth
     * @return Deposits
     */
    public function testNewDeposits(Auth $auth)
    {
        $api = new Deposits($auth);
        $this->assertInstanceOf(Deposits::class, $api);
        return $api;
    }

    /**
     * @depends testNewDeposits
     * @param Deposits $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateAddress(Deposits $api)
    {
        $address = $api->createAddress('EOS', false, 'test address');
        $this->assertInternalType('array', $address);
        $this->assertArrayHasKey('address', $address);
        $this->assertArrayHasKey('currency', $address);
        $this->assertArrayHasKey('remark', $address);
        $this->assertArrayHasKey('addressId', $address);
    }

    /**
     * @depends testNewDeposits
     * @param Deposits $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetAddresses(Deposits $api)
    {
        $address = $api->getAddresses('ETH');
        $this->assertInternalType('array', $address);
        $this->assertArrayHasKey('address', $address);
        $this->assertArrayHasKey('addressId', $address);
        $this->assertArrayHasKey('currency', $address);
        $this->assertArrayHasKey('isAutoTransfer', $address);
        $this->assertArrayHasKey('remark', $address);
    }

    /**
     * @depends testNewDeposits
     * @param Deposits $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDeposits(Deposits $api)
    {
        $params = [
            'currency' => 'ETH',
            'pageNum'  => 1,
            'pageSize' => 10,
        ];
        $data = $api->getDeposits($params);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('address', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('confirmation', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currencyName', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('isInner', $item);
            $this->assertArrayHasKey('remark', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('updatedAt', $item);
            $this->assertArrayHasKey('walletTxId', $item);
        }
    }
}
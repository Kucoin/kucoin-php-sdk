<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\PrivateApi\Deposit;

class DepositTest extends TestCase
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
     * @return Deposit
     */
    public function testNewDeposit(Auth $auth)
    {
        $api = new Deposit($auth);
        $this->assertInstanceOf(Deposit::class, $api);
        return $api;
    }

    /**
     * @depends testNewDeposit
     * @param Deposit $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateAddress(Deposit $api)
    {
        $address = $api->createAddress('BTC');
        $this->assertInternalType('array', $address);
        $this->assertArrayHasKey('address', $address);
        $this->assertArrayHasKey('memo', $address);
    }

    /**
     * @depends testNewDeposit
     * @param Deposit $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetAddress(Deposit $api)
    {
        try {
            $address = $api->getAddress('BTC');
            if ($address !== null) {
                $this->assertInternalType('array', $address);
                $this->assertArrayHasKey('address', $address);
                $this->assertArrayHasKey('memo', $address);
            }
        } catch (BusinessException $e) {
            // deposit.disabled
            if ($e->getResponse()->getApiCode() == '260200') {
                return;
            }
            throw $e;
        }
    }

    /**
     * @depends testNewDeposit
     * @param Deposit $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDeposits(Deposit $api)
    {
        $data = $api->getDeposits(['currency' => 'BTC'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('address', $item);
            $this->assertArrayHasKey('memo', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('isInner', $item);
            $this->assertArrayHasKey('walletTxId', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('updatedAt', $item);
        }
    }
}
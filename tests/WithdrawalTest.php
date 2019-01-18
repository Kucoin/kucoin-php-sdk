<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Withdrawal;

class WithdrawalTest extends TestCase
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
     * @return Withdrawal
     */
    public function testNewWithdrawal(Auth $auth)
    {
        $api = new Withdrawal($auth);
        $this->assertInstanceOf(Withdrawal::class, $api);
        return $api;
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetQuotas(Withdrawal $api)
    {
        $data = $api->getQuotas('BTC');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('limitBTCAmount', $data);
        $this->assertArrayHasKey('withdrawMinFee', $data);
        $this->assertArrayHasKey('innerWithdrawMinFee', $data);
        $this->assertArrayHasKey('availableAmount', $data);
        $this->assertArrayHasKey('remainAmount', $data);
//        $this->assertArrayHasKey('usedAmount', $data);
        $this->assertArrayHasKey('precision', $data);
//        $this->assertArrayHasKey('limitAmount', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('isWithdrawEnabled', $data);
        $this->assertArrayHasKey('withdrawMinSize', $data);
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testApply(Withdrawal $api)
    {
        $params = [
            'currency' => 'BTC',
            'address'  => '1BcTdvq6Qdh7GnviHTYHq4tBvU32FfUbGz',
            'amount'   => 0.3,
            'remark'   => 'test apply withdrawal',
        ];
        $data = $api->apply($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('withdrawId', $data);
    }


    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetList(Withdrawal $api)
    {
        $params = [
            'currency' => 'BTC',
        ];
        $pagination = [
            'pageNum'  => 1,
            'pageSize' => 5,
        ];
        $data = $api->getList($params, $pagination);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('address', $item);
            $this->assertArrayHasKey('memo', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('fee', $item);
            $this->assertArrayHasKey('walletTxId', $item);
            $this->assertArrayHasKey('isInner', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('updatedAt', $item);
        }
        return $data['items'];
    }

    /**
     * @depends testNewWithdrawal
     * @param Withdrawal $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancel(Withdrawal $api)
    {
        $data = $api->cancel('5c1cb7bb03aa6774239b772c');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('cancelledWithdrawIds', $data);
        var_dump($data);
    }
}
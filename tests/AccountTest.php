<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\ApiCode;
use KuCoin\SDK\Auth;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\PrivateApi\Account;

class AccountTest extends TestCase
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
     * @return Account
     */
    public function testNewAccount(Auth $auth)
    {
        $api = new Account($auth);
        $this->assertInstanceOf(Account::class, $api);
        return $api;
    }

    /**
     * @depends testNewAccount
     * @param Account $api
     * @return array|string
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetMainList(Account $api)
    {
        $accounts = $api->getList(['type' => 'main']);
        $this->assertInternalType('array', $accounts);
        foreach ($accounts as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('balance', $item);
            $this->assertArrayHasKey('available', $item);
            $this->assertArrayHasKey('holds', $item);
            $this->assertArrayHasKey('type', $item);
        }
        var_dump($accounts);
        return $accounts;
    }

    /**
     * @depends testNewAccount
     * @param Account $api
     * @return array|string
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetTradeList(Account $api)
    {
        $accounts = $api->getList(['type' => 'trade']);
        $this->assertInternalType('array', $accounts);
        foreach ($accounts as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('balance', $item);
            $this->assertArrayHasKey('available', $item);
            $this->assertArrayHasKey('holds', $item);
            $this->assertArrayHasKey('type', $item);
        }
        var_dump($accounts);
        return $accounts;
    }

    /**
     * @depends testNewAccount
     * @param Account $api
     * @return array|string
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCreateAccount(Account $api)
    {
        try {
            $data = $api->create('trade', 'ETH');
            $this->assertArrayHasKey('id', $data);
        } catch (BusinessException $e) {
            if ($e->getResponse()->getApiCode() != ApiCode::ACCOUNT_EXISTS) { //account already exists
                throw $e;
            }
        }
    }

    /**
     * @depends testNewAccount
     * @depends testGetMainList
     * @param Account $api
     * @param array $accounts
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Account $api, array $accounts)
    {
        if (isset($accounts[0])) {
            $account = $api->getDetail($accounts[0]['id']);
            $this->assertArrayHasKey('currency', $account);
            $this->assertArrayHasKey('balance', $account);
            $this->assertArrayHasKey('available', $account);
            $this->assertArrayHasKey('holds', $account);
            var_dump($account);
        }
    }

    /**
     * @depends testNewAccount
     * @depends testGetMainList
     * @param Account $api
     * @param array $accounts
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHistory(Account $api, array $accounts)
    {
        if (isset($accounts[0])) {
            $data = $api->getHistory($accounts[0]['id'], ['currentPage' => 1, 'pageSize' => 10]);
            $this->assertPagination($data);
            foreach ($data['items'] as $item) {
                $this->assertArrayHasKey('currency', $item);
                $this->assertArrayHasKey('amount', $item);
                $this->assertArrayHasKey('fee', $item);
                $this->assertArrayHasKey('balance', $item);
                $this->assertArrayHasKey('bizType', $item);
                $this->assertArrayHasKey('direction', $item);
                $this->assertArrayHasKey('createdAt', $item);
                $this->assertArrayHasKey('context', $item);
            }
        }
    }

    /**
     * @depends testNewAccount
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testInnerTransfer(Account $api)
    {
        $main = $api->getList(['type' => 'main']);
        $trade = $api->getList(['type' => 'trade']);

        $mainUsdt = null;
        foreach ($main as $item) {
            if ($item['currency'] == 'USDT') {
                $mainUsdt = $item['id'];
                break;
            }
        }

        $tradeUsdt = null;
        foreach ($trade as $item) {
            if ($item['currency'] == 'USDT') {
                $tradeUsdt = $item['id'];
                break;
            }
        }
        if (isset($mainUsdt, $tradeUsdt)) {
            $order = $api->innerTransfer(uniqid(), $mainUsdt, $tradeUsdt, 2);
            $this->assertArrayHasKey('orderId', $order);
        }
    }

    /**
     * @depends testNewAccount
     * @depends testGetMainList
     * @param Account $api
     * @param array $accounts
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHolds(Account $api, array $accounts)
    {
        if (isset($accounts[0])) {
            $data = $api->getHolds($accounts[1]['id'], ['currentPage' => 1, 'pageSize' => 10]);
            $this->assertPagination($data);
            foreach ($data['items'] as $item) {
                $this->assertArrayHasKey('createdAt', $item);
                $this->assertArrayHasKey('bizType', $item);
                $this->assertArrayHasKey('orderId', $item);
                $this->assertArrayHasKey('holdAmount', $item);
                $this->assertArrayHasKey('currency', $item);
                $this->assertArrayHasKey('updatedAt', $item);
            }
        }
    }

}

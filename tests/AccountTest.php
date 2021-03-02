<?php

namespace KuCoin\SDK\Tests;

use KuCoin\SDK\ApiCode;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\PrivateApi\Account;

class AccountTest extends TestCase
{
    protected $apiClass    = Account::class;
    protected $apiWithAuth = true;

    /**
     * @dataProvider apiProvider
     * @param Account $api
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
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
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
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetDetail(Account $api)
    {
        $accounts = $api->getList(['type' => 'main']);
        if (isset($accounts[0])) {
            $account = $api->getDetail($accounts[0]['id']);
            $this->assertArrayHasKey('currency', $account);
            $this->assertArrayHasKey('balance', $account);
            $this->assertArrayHasKey('available', $account);
            $this->assertArrayHasKey('holds', $account);
        }
    }

    /**
     * @dataProvider apiProvider
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
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLedgers(Account $api)
    {
        $accounts = $api->getList(['type' => 'trade']);
        if (isset($accounts[0])) {
            $data = $api->getLedgers($accounts[0]['id'], [], ['currentPage' => 1, 'pageSize' => 10]);
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
     * @dataProvider apiProvider
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
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testInnerTransferV2(Account $api)
    {
        $order = $api->innerTransferV2(uniqid(), 'KCS', 'main', 'trade', '2');
        $this->assertArrayHasKey('orderId', $order);
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHolds(Account $api)
    {
        $accounts = $api->getList(['type' => 'trade']);
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

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @return array
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSubAccountUsers(Account $api)
    {
        $users = $api->getSubAccountUsers();
        $this->assertInternalType('array', $users);
        foreach ($users as $item) {
            $this->assertArrayHasKey('userId', $item);
            $this->assertArrayHasKey('subName', $item);
            $this->assertArrayHasKey('remarks', $item);
        }
        return $users;
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSubAccountList(Account $api)
    {
        $accounts = $api->getSubAccountList();
        $this->assertInternalType('array', $accounts);
        foreach ($accounts as $account) {
            $this->assertInternalType('array', $account);
            $this->assertArrayHasKey('subUserId', $account);
            $this->assertArrayHasKey('subName', $account);
            $this->assertArrayHasKey('mainAccounts', $account);
            $this->assertArrayHasKey('tradeAccounts', $account);
            $this->assertInternalType('array', $account['mainAccounts']);
            $this->assertInternalType('array', $account['tradeAccounts']);
            foreach ($account['mainAccounts'] as $item) {
                $this->assertArrayHasKey('currency', $item);
                $this->assertArrayHasKey('balance', $item);
                $this->assertArrayHasKey('available', $item);
                $this->assertArrayHasKey('holds', $item);
            }
            foreach ($account['tradeAccounts'] as $item) {
                $this->assertArrayHasKey('currency', $item);
                $this->assertArrayHasKey('balance', $item);
                $this->assertArrayHasKey('available', $item);
                $this->assertArrayHasKey('holds', $item);
            }
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSubAccountDetail(Account $api)
    {
        $users = $api->getSubAccountUsers();
        if (empty($users)) {
            return;
        }
        $account = $api->getSubAccountDetail($users[0]['userId']);
        $this->assertInternalType('array', $account);
        $this->assertArrayHasKey('subUserId', $account);
        $this->assertArrayHasKey('subName', $account);
        $this->assertArrayHasKey('mainAccounts', $account);
        $this->assertArrayHasKey('tradeAccounts', $account);
        $this->assertInternalType('array', $account['mainAccounts']);
        $this->assertInternalType('array', $account['tradeAccounts']);
        foreach ($account['mainAccounts'] as $item) {
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('balance', $item);
            $this->assertArrayHasKey('available', $item);
            $this->assertArrayHasKey('holds', $item);
        }
        foreach ($account['tradeAccounts'] as $item) {
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('balance', $item);
            $this->assertArrayHasKey('available', $item);
            $this->assertArrayHasKey('holds', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testSubTransfer(Account $api)
    {
        $transfer = [
            'clientOid'      => uniqid(),
            'amount'         => 1,
            'direction'      => 'OUT',
            'currency'       => 'KCS',
            'accountType'    => 'main',
            'subAccountType' => 'trade',
            'subUserId'      => '5cc5b31c38300c336230d071',
        ];
        $result = $api->subTransfer($transfer);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('orderId', $result);
    }


    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testSubTransferV2(Account $api)
    {
        $transfer = [
            'clientOid'      => uniqid(),
            'amount'         => '1',
            'direction'      => 'OUT',
            'currency'       => 'USDT',
            'accountType'    => 'MAIN',
            'subAccountType' => 'TRADE',
            'subUserId'      => '6034c8b8a2644e0006e7e891',
        ];
        $result = $api->subTransferV2($transfer);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('orderId', $result);
    }


    /**
     * @dataProvider apiProvider
     * @param Account $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLedgersV2(Account $api)
    {
        $data = $api->getLedgersV2([], ['currentPage' => 1, 'pageSize' => 10]);
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

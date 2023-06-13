<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\PrivateApi\IsolatedMargin;

class IsolatedMarginTest extends TestCase
{
    protected $apiClass = IsolatedMargin::class;

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSymbols(IsolatedMargin $api)
    {
        $symbols = $api->getSymbols();
        $this->assertInternalType('array', $symbols);
        foreach ($symbols as $symbol) {
            $this->assertInternalType('array', $symbol);
            $this->assertArrayHasKey('symbol', $symbol);
            $this->assertArrayHasKey('symbolName', $symbol);
            $this->assertArrayHasKey('baseCurrency', $symbol);
            $this->assertArrayHasKey('quoteCurrency', $symbol);
            $this->assertArrayHasKey('maxLeverage', $symbol);
            $this->assertArrayHasKey('flDebtRatio', $symbol);
            $this->assertArrayHasKey('tradeEnable', $symbol);
            $this->assertArrayHasKey('autoRenewMaxDebtRatio', $symbol);
            $this->assertArrayHasKey('baseBorrowEnable', $symbol);
            $this->assertArrayHasKey('quoteBorrowEnable', $symbol);
            $this->assertArrayHasKey('baseTransferInEnable', $symbol);
            $this->assertArrayHasKey('quoteTransferInEnable', $symbol);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testAccountList(IsolatedMargin $api)
    {
        $params = [
            'balanceCurrency' => 'USDT',
        ];
        $accounts = $api->getAccountList($params);
        $this->assertInternalType('array', $accounts);
        $this->assertArrayHasKey('totalConversionBalance', $accounts);
        $this->assertArrayHasKey('liabilityConversionBalance', $accounts);
        $this->assertInternalType('array', $accounts['assets']);
        foreach ($accounts['assets'] as $asset) {
            $this->assertInternalType('array', $asset);
            $this->assertArrayHasKey('symbol', $asset);
            $this->assertArrayHasKey('status', $asset);
            $this->assertArrayHasKey('debtRatio', $asset);
            $this->assertInternalType('array', $asset['baseAsset']);
            $this->assertArrayHasKey('currency', $asset['baseAsset']);
            $this->assertArrayHasKey('totalBalance', $asset['baseAsset']);
            $this->assertArrayHasKey('holdBalance', $asset['baseAsset']);
            $this->assertArrayHasKey('availableBalance', $asset['baseAsset']);
            $this->assertArrayHasKey('liability', $asset['baseAsset']);
            $this->assertArrayHasKey('interest', $asset['baseAsset']);
            $this->assertArrayHasKey('borrowableAmount', $asset['baseAsset']);
            $this->assertInternalType('array', $asset['quoteAsset']);
            $this->assertArrayHasKey('currency', $asset['quoteAsset']);
            $this->assertArrayHasKey('totalBalance', $asset['quoteAsset']);
            $this->assertArrayHasKey('holdBalance', $asset['quoteAsset']);
            $this->assertArrayHasKey('availableBalance', $asset['quoteAsset']);
            $this->assertArrayHasKey('liability', $asset['quoteAsset']);
            $this->assertArrayHasKey('interest', $asset['quoteAsset']);
            $this->assertArrayHasKey('borrowableAmount', $asset['quoteAsset']);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testAccountDetail(IsolatedMargin $api)
    {
        $symbol = 'BTC-USDT';
        $account = $api->getAccountDetail($symbol);
        $this->assertInternalType('array', $account);
        $this->assertArrayHasKey('symbol', $account);
        $this->assertArrayHasKey('status', $account);
        $this->assertArrayHasKey('debtRatio', $account);
        $this->assertInternalType('array', $account['baseAsset']);
        $this->assertArrayHasKey('currency', $account['baseAsset']);
        $this->assertArrayHasKey('totalBalance', $account['baseAsset']);
        $this->assertArrayHasKey('holdBalance', $account['baseAsset']);
        $this->assertArrayHasKey('availableBalance', $account['baseAsset']);
        $this->assertArrayHasKey('liability', $account['baseAsset']);
        $this->assertArrayHasKey('interest', $account['baseAsset']);
        $this->assertArrayHasKey('borrowableAmount', $account['baseAsset']);
        $this->assertInternalType('array', $account['quoteAsset']);
        $this->assertArrayHasKey('currency', $account['quoteAsset']);
        $this->assertArrayHasKey('totalBalance', $account['quoteAsset']);
        $this->assertArrayHasKey('holdBalance', $account['quoteAsset']);
        $this->assertArrayHasKey('availableBalance', $account['quoteAsset']);
        $this->assertArrayHasKey('liability', $account['quoteAsset']);
        $this->assertArrayHasKey('interest', $account['quoteAsset']);
        $this->assertArrayHasKey('borrowableAmount', $account['quoteAsset']);
    }

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testBorrow(IsolatedMargin $api)
    {
        $params = ['symbol' => 'BTC-USDT', 'currency' => 'USDT', 'size' => 9, 'borrowStrategy' => 'IOC', 'period' => '7,14'];
        $result = $api->borrow($params);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('orderId', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('actualSize', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetOutstanding(IsolatedMargin $api)
    {
        $params = ['symbol' => 'BTC-USDT', 'currency' => 'USDT', 'pageSize' => 10, 'currentPage' => 1];
        $records = $api->getOutstanding($params);
        $this->assertInternalType('array', $records['items']);
        foreach ($records['items'] as $item) {
            $this->assertArrayHasKey('loanId', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('liabilityBalance', $item);
            $this->assertArrayHasKey('principalTotal', $item);
            $this->assertArrayHasKey('interestBalance', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('maturityTime', $item);
            $this->assertArrayHasKey('period', $item);
            $this->assertArrayHasKey('repaidSize', $item);
            $this->assertArrayHasKey('dailyInterestRate', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRepaid(IsolatedMargin $api)
    {
        $params = ['symbol' => 'BTC-USDT', 'currency' => 'USDT', 'pageSize' => 10, 'currentPage' => 1];
        $records = $api->getRepaid($params);
        $this->assertInternalType('array', $records['items']);
        foreach ($records['items'] as $item) {
            $this->assertArrayHasKey('loanId', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('principalTotal', $item);
            $this->assertArrayHasKey('interestBalance', $item);
            $this->assertArrayHasKey('repaidSize', $item);
            $this->assertArrayHasKey('createdAt', $item);
            $this->assertArrayHasKey('period', $item);
            $this->assertArrayHasKey('dailyInterestRate', $item);
            $this->assertArrayHasKey('repayFinishAt', $item);
        }
    }
    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRepayAll(IsolatedMargin $api)
    {
        $params = ['symbol' => 'BTC-USDT', 'currency' => 'USDT', 'size' => 10, 'seqStrategy' => 'RECENTLY_EXPIRE_FIRST'];
        $result = $api->repayAll($params);
        $this->assertInternalType('array', $result);
    }
    /**
     * @dataProvider apiProvider
     * @param IsolatedMargin $api
     * @throws BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRepaySingle(IsolatedMargin $api)
    {
        $params = ['symbol' => 'BTC-USDT', 'currency' => 'USDT', 'size' => 10, 'loanId' => '123456789123456789123451'];
        $result = $api->repaySingle($params);
        $this->assertInternalType('array', $result);
    }
}

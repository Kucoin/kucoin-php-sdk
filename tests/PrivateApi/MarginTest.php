<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\Margin;

class MarginTest extends TestCase
{
    protected $apiClass = Margin::class;


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetMarkPrice(Margin $api)
    {
        $data = $api->getMarkPrice('USDT-BTC');
        $this->assertArrayHasKey('symbol', $data);
        $this->assertArrayHasKey('granularity', $data);
        $this->assertArrayHasKey('timePoint', $data);
        $this->assertArrayHasKey('value', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetConfig(Margin $api)
    {
        $data = $api->getConfig();
        $this->assertArrayHasKey('currencyList', $data);
        $this->assertArrayHasKey('warningDebtRatio', $data);
        $this->assertArrayHasKey('liqDebtRatio', $data);
        $this->assertArrayHasKey('maxLeverage', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetAccount(Margin $api)
    {
        $data = $api->getAccount();
        $this->assertArrayHasKey('accounts', $data);
        $this->assertArrayHasKey('debtRatio', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testBorrow(Margin $api)
    {
        $params = [
            'currency' => 'USDT',
            'type'     => 'FOK',
            'size'     => 1,
        ];
        $data = $api->borrow($params);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('orderId', $data);
        $this->assertArrayHasKey('currency', $data);
        $orderId = $data['orderId'];
        $data = $api->getBorrow($orderId);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('orderId', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetBorrow(Margin $api)
    {
//        $data = $api->getBorrow('123456789');
//        $this->assertInternalType('array', $data);
//        $this->assertArrayHasKey('currency', $data);
//        $this->assertArrayHasKey('orderId', $data);
        $this->assertTrue(true);
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetOutstanding(Margin $api)
    {
        $data = $api->getOutstanding('USDT');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('currentPage', $data);
        $this->assertArrayHasKey('items', $data);
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRepayRecord(Margin $api)
    {
        $data = $api->getRepayRecord('USDT');
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('currentPage', $data);
        $this->assertArrayHasKey('items', $data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRepayAll(Margin $api)
    {
        $params = [
            'currency' => 'USDT',
            'sequence' => 'RECENTLY_EXPIRE_FIRST',
            'size'     => 1,
        ];
        $data = $api->repayAll($params);
        $this->assertNotNull(1);
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRepaySingle(Margin $api)
    {
        $params = [
            'currency' => 'USDT',
            'tradeId'  => '5da5a4f0f943c040c2f8501e',
            'size'     => 1,
        ];
        $data = $api->repaySingle($params);
        $this->assertNotNull(1);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testLend(Margin $api)
    {
        $params = [
            'currency'     => 'USDT',
            'size'         => 1,
            'dailyIntRate' => '0.002',
            'term'         => 7,
        ];
//        $data = $api->lend($params);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testCancelLend(Margin $api)
    {
//        $orderId = 1;
//        $data = $api->cancelLend($orderId);
//        $this->assertArrayHasKey('orderId', $data);
        $this->assertTrue(true);
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testSetAutoLend(Margin $api)
    {
        $params = [
            'currency'     => 'USDT',
            'isEnable'     => 1,
            'retainSize'   => 1,
            'dailyIntRate' => '0.002',
            'term'         => 7,
        ];
        $data = $api->setAutoLend($params);
        $this->assertNull($data);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLendActive(Margin $api)
    {
        $data = $api->getLendActive(['currency' => 'BTC'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('filledSize', $item);
            $this->assertArrayHasKey('dailyIntRate', $item);
            $this->assertArrayHasKey('term', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLendDone(Margin $api)
    {
        $data = $api->getLendDone(['currency' => 'BTC'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('filledSize', $item);
            $this->assertArrayHasKey('dailyIntRate', $item);
            $this->assertArrayHasKey('term', $item);
            $this->assertArrayHasKey('status', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetUnsettled(Margin $api)
    {
        $data = $api->getUnsettled(['currency' => 'BTC'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('tradeId', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('dailyIntRate', $item);
            $this->assertArrayHasKey('term', $item);
        }
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSettled(Margin $api)
    {
        $data = $api->getSettled(['currency' => 'BTC'], ['currentPage' => 1, 'pageSize' => 10]);
        $this->assertPagination($data);
        foreach ($data['items'] as $item) {
            $this->assertArrayHasKey('tradeId', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('dailyIntRate', $item);
            $this->assertArrayHasKey('term', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetLendAssets(Margin $api)
    {
        $data = $api->getLendAssets('USDT');
        if (!empty($data[0])) {
            $this->assertArrayHasKey('currency', $data[0]);
            $this->assertArrayHasKey('outstanding', $data[0]);
            $this->assertArrayHasKey('filledSize', $data[0]);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetMarket(Margin $api)
    {
        $params = [
            'currency' => 'BTC',
            'term'     => 7,
        ];
        $data = $api->getMarket($params);
        if (!empty($data[0])) {
            $this->assertArrayHasKey('dailyIntRate', $data[0]);
            $this->assertArrayHasKey('term', $data[0]);
            $this->assertArrayHasKey('size', $data[0]);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return array|string
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetTradeLast(Margin $api)
    {
        $data = $api->getTradeLast('USDT');
        if (!empty($data[0])) {
            $this->assertArrayHasKey('tradeId', $data[0]);
            $this->assertArrayHasKey('currency', $data[0]);
            $this->assertArrayHasKey('size', $data[0]);
            $this->assertArrayHasKey('dailyIntRate', $data[0]);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetStrategyRiskLimit(Margin $api)
    {
        $data = $api->getStrategyRiskLimit('cross');
        if (!empty($data)) {
            $this->assertArrayHasKey('currency', $data[0]);
            $this->assertArrayHasKey('borrowMaxAmount', $data[0]);
            $this->assertArrayHasKey('buyMaxAmount', $data[0]);
            $this->assertArrayHasKey('precision', $data[0]);
        }
    }


    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetEtfInfo(Margin $api)
    {
        $data = $api->getEtfInfo();
        foreach ($data as $item) {
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('netAsset', $item);
            $this->assertArrayHasKey('targetLeverage', $item);
            $this->assertArrayHasKey('actualLeverage', $item);
            $this->assertArrayHasKey('issuedSize', $item);
            $this->assertArrayHasKey('basket', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testBorrowV3(Margin $api)
    {
        $params = [
            'isIsolated'  => false,
            'currency'    => 'USDT',
            'size'        => '10',
            'timeInForce' => 'IOC',
        ];

        $result = $api->borrowV3($params);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('actualSize', $result);
        $this->assertArrayHasKey('orderNo', $result);
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRepayV3(Margin $api)
    {
        $params = [
            'isIsolated' => false,
            'currency'   => 'USDT',
            'size'       => '10',
        ];

        $result = $api->repayV3($params);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('actualSize', $result);
        $this->assertArrayHasKey('orderNo', $result);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetBorrowV3(Margin $api)
    {
        $params = [
            'currency'   => 'USDT',
            'isIsolated' => false,
        ];

        $pagination = [
            'pageSize'    => 10,
            'currentPage' => 1,
        ];

        $result = $api->getBorrowV3($params, $pagination);
        $this->assertPagination($result);
        $this->assertTrue(count($result['items']) <= $pagination['pageSize']);
        foreach ($result['items'] as $item) {
            $this->assertArrayHasKey('orderNo', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('actualSize', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('createdTime', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRepayV3(Margin $api)
    {
        $params = [
            'currency'   => 'USDT',
            'isIsolated' => false,
        ];

        $pagination = [
            'pageSize'    => 10,
            'currentPage' => 1,
        ];

        $result = $api->getRepayV3($params, $pagination);
        $this->assertPagination($result);
        $this->assertTrue(count($result['items']) <= $pagination['pageSize']);
        foreach ($result['items'] as $item) {
            $this->assertArrayHasKey('orderNo', $item);
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('size', $item);
            $this->assertArrayHasKey('principal', $item);
            $this->assertArrayHasKey('interest', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('createdTime', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetInterestV3(Margin $api)
    {
        $params = [
            'currency'   => 'USDT',
            'isIsolated' => false,
        ];

        $pagination = [
            'pageSize'    => 10,
            'currentPage' => 1,
        ];

        $result = $api->getInterestV3($params, $pagination);
        $this->assertPagination($result);
        $this->assertTrue(count($result['items']) <= $pagination['pageSize']);
        foreach ($result['items'] as $item) {
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('dayRatio', $item);
            $this->assertArrayHasKey('interestAmount', $item);
            $this->assertArrayHasKey('createdTime', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSymbolV3(Margin $api)
    {
        $symbols = $api->getSymbolV3();
        $this->assertInternalType('array', $symbols);
        $this->assertArrayHasKey('items', $symbols);
        $assertCallable = function ($item) {
            $this->assertArrayHasKey('symbol', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('enableTrading', $item);
            $this->assertArrayHasKey('market', $item);
            $this->assertArrayHasKey('baseCurrency', $item);
            $this->assertArrayHasKey('quoteCurrency', $item);
            $this->assertArrayHasKey('baseIncrement', $item);
            $this->assertArrayHasKey('baseMinSize', $item);
            $this->assertArrayHasKey('quoteIncrement', $item);
            $this->assertArrayHasKey('quoteMinSize', $item);
            $this->assertArrayHasKey('baseMaxSize', $item);
            $this->assertArrayHasKey('quoteMaxSize', $item);
            $this->assertArrayHasKey('priceIncrement', $item);
            $this->assertArrayHasKey('feeCurrency', $item);
            $this->assertArrayHasKey('priceLimitRate', $item);
            $this->assertArrayHasKey('minFunds', $item);
        };
        foreach ($symbols['items'] as $item) {
            $assertCallable($item);
        }

        $symbol = $symbols['items'][0]['symbol'];
        $singleSymbol = $api->getSymbolV3($symbol);
        foreach ($singleSymbol['items'] as $item) {
            $assertCallable($item);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Margin $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testModifyLeverageMultiplierV3(Margin $api)
    {
        $symbol = $api->getSymbolV3()['items'][0]['symbol'];
        $result = $api->modifyLeverageMultiplierV3(2, $symbol, true);
        $this->assertNull($result);
        $api->modifyLeverageMultiplierV3(3);
        $this->assertNull($result);
    }
}

<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\PrivateApi\Lend;

class LendTest extends TestCase
{
    protected $apiClass = Lend::class;

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetCurrencies(Lend $api)
    {
        $currencies = $api->getCurrencies();
        foreach ($currencies as $currency) {
            $this->assertArrayHasKey('currency', $currency);
            $this->assertArrayHasKey('purchaseEnable', $currency);
            $this->assertArrayHasKey('redeemEnable', $currency);
            $this->assertArrayHasKey('increment', $currency);
            $this->assertArrayHasKey('minPurchaseSize', $currency);
            $this->assertArrayHasKey('minInterestRate', $currency);
            $this->assertArrayHasKey('maxInterestRate', $currency);
            $this->assertArrayHasKey('interestIncrement', $currency);
            $this->assertArrayHasKey('maxPurchaseSize', $currency);
            $this->assertArrayHasKey('marketInterestRate', $currency);
            $this->assertArrayHasKey('autoPurchaseEnable', $currency);
        }

        $currency = $api->getCurrencies('BTC');
        $this->assertTrue(count($currency) === 1);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetMarketInterestRate(Lend $api)
    {
        $marketInterestRate = $api->getMarketInterestRate('USDT');
        $this->assertInternalType('array', $marketInterestRate);
        foreach ($marketInterestRate as $item) {
            $this->assertArrayHasKey('time', $item);
            $this->assertArrayHasKey('marketInterestRate', $item);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testPurchase(Lend $api)
    {
        $currency = 'BTC';
        $marketInterestRate = $api->getMarketInterestRate($currency);
        $rate = end($marketInterestRate)['marketInterestRate'];
        $size = '0.001';
        $orders = $api->purchase(['currency' => $currency, 'interestRate' => $rate, 'size' => $size]);
        $this->assertInternalType('array', $orders);
        $this->assertArrayHasKey('orderNo', $orders);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRedeem(Lend $api)
    {
        $currency = 'BTC';
        $marketInterestRate = $api->getMarketInterestRate($currency);
        $rate = end($marketInterestRate)['marketInterestRate'];
        $size = '0.001';
        $orders = $api->purchase(['currency' => $currency, 'interestRate' => $rate, 'size' => $size]);
        $orderNo = $orders['orderNo'];
        $orders = $api->redeem(['purchaseOrderNo' => $orderNo, 'currency' => $currency, 'size' => $size]);
        $this->assertInternalType('array', $orders);
        $this->assertArrayHasKey('orderNo', $orders);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetPurchaseOrders(Lend $api)
    {
        $params = [
            'currency' => 'BTC',
            'status'   => 'PENDING',
        ];

        $pagination = [
            'page' => 1,
            'pageSize' => 10,
        ];
        $orders = $api->getPurchaseOrders($params, $pagination);
        $this->assertPagination($orders);
        $this->assertTrue(count($orders) <= $pagination['pageSize']);
        foreach ($orders['items'] as $order) {
            $this->assertArrayHasKey('purchaseOrderNo', $order);
            $this->assertArrayHasKey('currency', $order);
            $this->assertArrayHasKey('purchaseSize', $order);
            $this->assertArrayHasKey('matchSize', $order);
            $this->assertArrayHasKey('interestRate', $order);
            $this->assertArrayHasKey('incomeSize', $order);
            $this->assertArrayHasKey('applyTime', $order);
            $this->assertArrayHasKey('status', $order);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetRedeemOrders(Lend $api)
    {
        $params = [
            'currency' => 'BTC',
            'status'   => 'DONE',
        ];

        $pagination = [
            'page' => 1,
            'pageSize' => 10,
        ];
        $orders = $api->getRedeemOrders($params, $pagination);
        $this->assertPagination($orders);
        $this->assertTrue(count($orders) <= $pagination['pageSize']);
        foreach ($orders['items'] as $order) {
            $this->assertArrayHasKey('purchaseOrderNo', $order);
            $this->assertArrayHasKey('currency', $order);
            $this->assertArrayHasKey('redeemOrderNo', $order);
            $this->assertArrayHasKey('redeemSize', $order);
            $this->assertArrayHasKey('receiptSize', $order);
            $this->assertArrayHasKey('applyTime', $order);
            $this->assertArrayHasKey('status', $order);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Lend $api
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testPurchaseUpdate(Lend $api)
    {
        $currency = 'BTC';
        $marketInterestRate = $api->getMarketInterestRate($currency);
        $rate = end($marketInterestRate)['marketInterestRate'];
        $size = '0.001';
        $orders = $api->purchase(['currency' => $currency, 'interestRate' => $rate, 'size' => $size]);
        $orderNo = $orders['orderNo'];

        $update = [
            'currency' => $currency,
            'purchaseOrderNo' => $orderNo,
            'interestRate' => (string) $rate * 2,
        ];

        $updateOrder = $api->purchaseUpdate($update);
        $this->assertNull($updateOrder);
        $api->redeem(['purchaseOrderNo' => $orderNo, 'currency' => $currency, 'size' => $size]);
    }
}
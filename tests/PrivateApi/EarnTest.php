<?php

namespace KuCoin\SDK\Tests\PrivateApi;

use KuCoin\SDK\Enums\AccountType;
use KuCoin\SDK\PrivateApi\Earn;

class EarnTest extends TestCase
{
    protected $apiClass = Earn::class;

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetSavingProducts(Earn $earn)
    {
        $products = $earn->getSavingProducts();
        $this->assertInternalType('array', $products);
        foreach ($products as $product) {
            $this->assertProduct($product);
        }

        $currencyProducts = $earn->getSavingProducts('USDT');
        $this->assertInternalType('array', $currencyProducts);
        $currency = array_unique(array_column($currencyProducts, 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
        foreach ($currencyProducts as $currencyProduct) {
            $this->assertProduct($currencyProduct);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetPromotionProducts(Earn $earn)
    {
        $products = $earn->getPromotionProducts();
        $this->assertInternalType('array', $products);
        foreach ($products as $product) {
            $this->assertProduct($product);
        }

        $currencyProducts = $earn->getPromotionProducts('USDT');
        $this->assertInternalType('array', $currencyProducts);
        $currency = array_unique(array_column($currencyProducts, 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
        foreach ($currencyProducts as $currencyProduct) {
            $this->assertProduct($currencyProduct);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetStakingProducts(Earn $earn)
    {
        $products = $earn->getStakingProducts();
        $this->assertInternalType('array', $products);
        foreach ($products as $product) {
            $this->assertProduct($product);
        }

        $currencyProducts = $earn->getStakingProducts('DOT');
        $this->assertInternalType('array', $currencyProducts);
        $currency = array_unique(array_column($currencyProducts, 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
        foreach ($currencyProducts as $currencyProduct) {
            $this->assertProduct($currencyProduct);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetEthStakingProducts(Earn $earn)
    {
        $products = $earn->getEthStakingProducts();
        $this->assertInternalType('array', $products);
        $currency = array_unique(array_column($products, 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
        foreach ($products as $product) {
            $this->assertProduct($product);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetKcsStakingProducts(Earn $earn)
    {
        $products = $earn->getKcsStakingProducts();
        $this->assertInternalType('array', $products);
        $currency = array_unique(array_column($products, 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
        foreach ($products as $product) {
            $this->assertProduct($product);
        }
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testSubscribe(Earn $earn)
    {
        $subscribeOptions = $this->savingProductsSubscribeOption($earn);
        $order = $earn->subscribe($subscribeOptions['productId'], $subscribeOptions['amount']);
        $this->assertInternalType('array', $order);
        $this->assertArrayHasKey('orderId', $order);
        $this->assertArrayHasKey('orderTxId', $order);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testGetHoldAssets(Earn $earn)
    {
        $holdAssets = $earn->getHoldAssets([], ['pageSize' => 10]);
        $assertCallback = function ($item) {
            $this->assertArrayHasKey('orderId', $item);
            $this->assertArrayHasKey('productId', $item);
            $this->assertArrayHasKey('productCategory', $item);
            $this->assertArrayHasKey('productType', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('incomeCurrency', $item);
            $this->assertArrayHasKey('returnRate', $item);
            $this->assertArrayHasKey('holdAmount', $item);
            $this->assertArrayHasKey('redeemedAmount', $item);
            $this->assertArrayHasKey('redeemingAmount', $item);
            $this->assertArrayHasKey('lockStartTime', $item);
            $this->assertArrayHasKey('lockEndTime', $item);
            $this->assertArrayHasKey('purchaseTime', $item);
            $this->assertArrayHasKey('redeemPeriod', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('earlyRedeemSupported', $item);
        };

        $this->assertPagination($holdAssets);
        foreach ($holdAssets['items'] as $item) {
            $assertCallback($item);
        }

        $filterHoldAssets = $earn->getHoldAssets(['currency' => 'USDT']);
        $this->assertPagination($filterHoldAssets);
        foreach ($filterHoldAssets['items'] as $item) {
            $assertCallback($item);
        }

        $currency = array_unique(array_column($filterHoldAssets['items'], 'currency'));
        $this->assertLessThanOrEqual(1, count($currency));
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRedeemPreview(Earn $earn)
    {
        $subscribeOptions = $this->timeProductsSubscribeOption($earn);
        $order = $earn->subscribe($subscribeOptions['productId'], $subscribeOptions['amount']);
        $redeemPreview = $earn->redeemPreview($order['orderId']);
        $this->assertInternalType('array', $redeemPreview);
        $this->assertArrayHasKey('currency', $redeemPreview);
        $this->assertArrayHasKey('redeemAmount', $redeemPreview);
        $this->assertArrayHasKey('penaltyInterestAmount', $redeemPreview);
        $this->assertArrayHasKey('redeemPeriod', $redeemPreview);
        $this->assertArrayHasKey('deliverTime', $redeemPreview);
        $this->assertArrayHasKey('manualRedeemable', $redeemPreview);
        $this->assertArrayHasKey('redeemAll', $redeemPreview);
    }

    /**
     * @dataProvider apiProvider
     *
     * @param Earn $earn
     * @return void
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function testRedeem(Earn $earn)
    {
        $subscribeOptions = $this->savingProductsSubscribeOption($earn);
        $order = $earn->subscribe($subscribeOptions['productId'], $subscribeOptions['amount']);
        $redeem = $earn->redeem($order['orderId'], $subscribeOptions['amount']);
        $assertCallback = function ($redeem) {
            $this->assertInternalType('array', $redeem);
            $this->assertArrayHasKey('orderTxId', $redeem);
            $this->assertArrayHasKey('deliverTime', $redeem);
            $this->assertArrayHasKey('status', $redeem);
            $this->assertArrayHasKey('amount', $redeem);
        };

        $assertCallback($redeem);
        $timeSubscribeOptions = $this->timeProductsSubscribeOption($earn);
        $timeOrder = $earn->subscribe($timeSubscribeOptions['productId'], $timeSubscribeOptions['amount']);
        $earlyRedeem = $earn->redeem($timeOrder['orderId'], $timeSubscribeOptions['amount'], AccountType::MAIN, 1);
        $assertCallback($earlyRedeem);
    }

    protected function savingProductsSubscribeOption(Earn $earn, $currency = 'USDT')
    {
        $products = $earn->getSavingProducts($currency);
        if (empty($products)) {
            return [];
        }

        $product = $products[0];
        return [
            'productId' => $product['id'],
            'amount'    => $product['userLowerLimit'],
        ];
    }


    protected function timeProductsSubscribeOption(Earn $earn)
    {
        $products = $earn->getPromotionProducts();
        $products = array_filter($products, static function ($item) {
            return !empty($item['earlyRedeemSupported']);
        });

        $products = array_column($products, null, 'currency');
        $currency = ['USDT', 'MATIC', 'DOT', 'NEAR', 'ATOM', 'ADA'];
        foreach ($currency as $item) {
            if (isset($products[$item])) {
                return [
                    'productId' => $products[$item]['id'],
                    'amount'    => $products[$item]['userLowerLimit'],
                ];
            }
        }
    }

    /**
     * @param array $product
     * @return void
     */
    protected function assertProduct(array $product)
    {
        $this->assertArrayHasKey('id', $product);
        $this->assertArrayHasKey('currency', $product);
        $this->assertArrayHasKey('category', $product);
        $this->assertArrayHasKey('type', $product);
        $this->assertArrayHasKey('precision', $product);
        $this->assertArrayHasKey('productUpperLimit', $product);
        $this->assertArrayHasKey('userUpperLimit', $product);
        $this->assertArrayHasKey('userLowerLimit', $product);
        $this->assertArrayHasKey('redeemPeriod', $product);
        $this->assertArrayHasKey('lockStartTime', $product);
        $this->assertArrayHasKey('lockEndTime', $product);
        $this->assertArrayHasKey('applyStartTime', $product);
        $this->assertArrayHasKey('applyEndTime', $product);
        $this->assertArrayHasKey('returnRate', $product);
        $this->assertArrayHasKey('incomeCurrency', $product);
        $this->assertArrayHasKey('earlyRedeemSupported', $product);
        $this->assertArrayHasKey('productRemainAmount', $product);
        $this->assertArrayHasKey('status', $product);
        $this->assertArrayHasKey('redeemType', $product);
        $this->assertArrayHasKey('incomeReleaseType', $product);
        $this->assertArrayHasKey('interestDate', $product);
        $this->assertArrayHasKey('duration', $product);
        $this->assertArrayHasKey('newUserOnly', $product);
    }
}

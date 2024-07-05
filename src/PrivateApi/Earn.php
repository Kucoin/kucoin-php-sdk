<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Enums\AccountType;
use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

class Earn extends KuCoinApi
{
    /**
     * This endpoint retrieves savings products. If no savings products are available, an empty list is returned.
     *
     * @param string $currency
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getSavingProducts($currency = '')
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/saving/products', compact('currency'))->getApiData();
    }

    /**
     * This endpoint retrieves limited-time promotion products. If no products are available, an empty list is returned.
     *
     * @param string $currency
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getPromotionProducts($currency = '')
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/promotion/products', compact('currency'))->getApiData();
    }

    /**
     * This endpoint retrieves KCS Staking products. If no KCS Staking products are available, an empty list is returned.
     *
     * @param string $currency
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getStakingProducts($currency = '')
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/staking/products', compact('currency'))->getApiData();
    }

    /**
     * This endpoint retrieves ETH Staking products. If no ETH Staking products are available, an empty list is returned.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getEthStakingProducts()
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/eth-staking/products')->getApiData();
    }

    /**
     * This endpoint retrieves KCS Staking products. If no KCS Staking products are available, an empty list is returned.
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getKcsStakingProducts()
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/kcs-staking/products')->getApiData();
    }

    /**
     * This endpoint allows subscribing to fixed income products. If the subscription fails, it returns the corresponding error code.
     *
     * @param string $productId
     * @param string $amount
     * @param string $accountType
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function subscribe($productId, $amount, $accountType = AccountType::MAIN)
    {
        $parameters = [
            'productId'   => $productId,
            'amount'      => $amount,
            'accountType' => $accountType,
        ];

        return $this->call(Request::METHOD_POST, '/api/v1/earn/orders', $parameters)->getApiData();
    }

    /**
     * This endpoint allows initiating redemption by holding ID. If the current holding is fully redeemed or in the process of being redeemed, it indicates that the holding does not exist.
     *
     * @param string $orderId
     * @param string $amount
     * @param string $fromAccountType
     * @param string $confirmPunishRedeem
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function redeem($orderId, $amount, $fromAccountType = AccountType::MAIN, $confirmPunishRedeem = 0)
    {
        $parameters = [
            'orderId'             => $orderId,
            'amount'              => $amount,
            'fromAccountType'     => $fromAccountType,
            'confirmPunishRedeem' => $confirmPunishRedeem,
        ];

        return $this->call(Request::METHOD_DELETE, '/api/v1/earn/orders', $parameters)->getApiData();
    }

    /**
     * This endpoint retrieves redemption preview information by holding ID. If the current holding is fully redeemed or in the process of being redeemed, it indicates that the holding does not exist.
     *
     * @param $orderId
     * @param $fromAccountType
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function redeemPreview($orderId, $fromAccountType = AccountType::MAIN)
    {
        $parameters = [
            'orderId'         => $orderId,
            'fromAccountType' => $fromAccountType,
        ];

        return $this->call(Request::METHOD_GET, '/api/v1/earn/redeem-preview', $parameters)->getApiData();
    }

    /**
     * This endpoint retrieves current holding assets of fixed income products. If no current holding assets are available, an empty list is returned.
     *
     * @param array $params
     * @param array $pagination
     * @return \KuCoin\SDK\Http\ApiResponse
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHoldAssets(array $params = [], array $pagination = [])
    {
        return $this->call(Request::METHOD_GET, '/api/v1/earn/hold-assets', array_merge($params, $pagination))->getApiData();
    }
}
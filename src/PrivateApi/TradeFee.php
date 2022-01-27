<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class TradeFee
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#basic-user-fee
 */
class TradeFee extends KuCoinApi
{
    /**
     * This interface is for the basic fee rate of users
     *
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getBaseFee()
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/base-fee');
        return $response->getApiData();
    }

    /**
     * This interface is for the actual fee rate of the trading pair. You can inquire about fee rates of 10 trading pairs each time at most. The fee rate of your sub-account is the same as that of the master account.
     *
     * @param array $symbols
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getTradeFees(array $symbols)
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/trade-fees', ['symbols' => implode(',', $symbols)]);
        return $response->getApiData();
    }
}
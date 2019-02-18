<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Fill
 * @package KuCoin\SDK\PrivateApi
 * @see https://docs.kucoin.com/#fills
 */
class Fill extends KuCoinApi
{
    /**
     * Get a list of recent fills
     * @param string $orderId
     * @param string $symbolId
     * @param array $pagination
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList($orderId, $symbolId, array $pagination = [])
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/fills', compact('orderId', 'symbolId') + $pagination);
        return $response->getApiData();
    }

    /**
     * Get the recent fills of the latest transactions within 24 hours.
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getRecentList()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/limit/fills');
        return $response->getApiData();
    }
}
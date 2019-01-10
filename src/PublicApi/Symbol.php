<?php

namespace KuCoin\SDK\PublicApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class Symbol
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/#symbols
 */
class Symbol extends KuCoinApi
{
    /**
     * Get a list of symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getList()
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/symbols');
        return $response->getApiData();
    }

    /**
     * Get the level-1 data of order book
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOrderBookLevel1($symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/market/orderbook/level1', compact('symbol'));
        return $response->getApiData();
    }

    /**
     * Get the level-2 data of order book
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOrderBookLevel2($symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/market/orderbook/level2', compact('symbol'));
        return $response->getApiData();
    }

    /**
     * Get the level-3 data of order book
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getOrderBookLevel3($symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/market/orderbook/level3', compact('symbol'));
        return $response->getApiData();
    }

    /**
     * Get a list of the latest trades for a symbol
     * @param string $symbol
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHistories($symbol)
    {
        $response = $this->call(Request::METHOD_GET, '/api/v1/market/histories', compact('symbol'));
        return $response->getApiData();
    }

    /**
     * Get historic rates for a symbol
     * @param string $symbol
     * @param int $begin
     * @param int $end
     * @param string $type
     * @return array
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getHistoricRates($symbol, $begin, $end, $type)
    {
        $response = $this->call(
            Request::METHOD_GET,
            '/api/v1/market/candles',
            compact('symbol', 'begin', 'end', 'type')
        );
        return $response->getApiData();
    }
}
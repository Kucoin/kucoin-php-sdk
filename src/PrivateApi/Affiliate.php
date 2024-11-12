<?php

namespace KuCoin\SDK\PrivateApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 *
 * Class Affiliate
 * @package KuCoin\SDK\PrivateApi
 *
 * @see https://www.kucoin.com/docs/rest/affiliate/get-affiliate-user-rebate-information
 *
 */
class Affiliate extends KuCoinApi
{
    /**
     *
     * This endpoint allows getting affiliate user rebate information.
     *
     * @param $date
     * @param $offset
     * @param $maxCount
     * @return mixed|null
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getInviterStatistics($date, $offset = 1, $maxCount = 10)
    {
        $parameters = compact('date', 'offset', 'maxCount');
        return $this->call(Request::METHOD_GET, '/api/v2/affiliate/inviter/statistics', $parameters)->getApiData();
    }
}
<?php

namespace KuCoin\SDK\PublicApi;

use KuCoin\SDK\Http\Request;
use KuCoin\SDK\KuCoinApi;

/**
 * Class ServiceStatus
 * @package KuCoin\SDK\PublicApi
 * @see https://docs.kucoin.com/#service-status
 */
class ServiceStatus extends KuCoinApi
{
    /**
     * Get the service status
     * @return array
     * @throws \KuCoin\SDK\Exceptions\HttpException
     * @throws \KuCoin\SDK\Exceptions\BusinessException
     * @throws \KuCoin\SDK\Exceptions\InvalidApiUriException
     */
    public function getStatus()
    {
        $response = $this->call(Request::METHOD_GET, 'api/v1/status');
        return $response->getApiData();
    }
}
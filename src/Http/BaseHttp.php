<?php

namespace KuCoin\SDK\Http;

use KuCoin\SDK\Api;

abstract class BaseHttp implements IHttp
{
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }
}
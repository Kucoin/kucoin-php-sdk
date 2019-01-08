<?php

namespace KuCoin\SDK\PrivateApi;

interface IAuth
{
    public function signature($requestUri, $body, $timestamp, $method);

    public function getHeaders($method, $requestUri, $body);
}
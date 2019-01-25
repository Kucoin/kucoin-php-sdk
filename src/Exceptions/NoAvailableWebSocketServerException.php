<?php

namespace KuCoin\SDK\Exceptions;

class NoAvailableWebSocketServerException extends \Exception
{
    protected $message = 'No available websocket server';
}
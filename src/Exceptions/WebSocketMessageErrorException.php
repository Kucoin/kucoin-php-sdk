<?php

namespace KuCoin\SDK\Exceptions;

class WebSocketMessageErrorException extends \Exception
{
    protected $message = 'Websocket message error';
}
<?php

namespace KuCoin\SDK\Exceptions;

class WebSocketCloseException extends \Exception
{
    protected $message = 'Websocket connection is closed';
}
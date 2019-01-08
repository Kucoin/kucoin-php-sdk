<?php

namespace KuCoin\SDK\Http;

class Response
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body;

    /**
     * @var int
     */
    protected $statusCode;

    public function __construct($body = '', $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody($decodeJson = false)
    {
        return $decodeJson ? json_decode($this->body, true) : $this->body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function isSuccessful()
    {
        return $this->statusCode == 200;
    }

}
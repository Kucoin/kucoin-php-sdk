<?php

namespace KuCoin\SDK\Http;

class Request
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $requestUri;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $bodyParams = null;

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method Request::METHOD_XXX
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri ? rtrim($baseUri, '/') : null;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = rtrim($uri, '/');
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        if ($this->requestUri) {
            return $this->requestUri;
        }

        // GET/DELETE: move parameters into query
        if ($this->isGetOrDeleteMethod() && !empty($this->params)) {
            $query = http_build_query($this->params);
            if ($query !== '') {
                $this->uri .= strpos($this->uri, '?') === false ? '?' : '&';
                $this->uri .= $query;
            }
        }

        $url = $this->baseUri . $this->uri;
        $this->requestUri = substr($url, strpos($url, '/', 8));
        return $this->requestUri;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getBodyParams()
    {
        if ($this->bodyParams === null) {
            if ($this->isGetOrDeleteMethod()) {
                $this->bodyParams = '';
            } else {
                $this->bodyParams = empty($this->params) ? '' : json_encode($this->params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $this->bodyParams;
    }

    protected function isGetOrDeleteMethod()
    {
        return in_array($this->getMethod(), [self::METHOD_GET, self::METHOD_DELETE], true);
    }
}
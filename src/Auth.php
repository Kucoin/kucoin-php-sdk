<?php

namespace KuCoin\SDK;

class Auth implements IAuth
{
    private $key;

    private $secret;

    private $passphrase;

    public function __construct($key, $secret, $passphrase)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->passphrase = $passphrase;
    }

    public function signature($requestUri, $body, $timestamp, $method)
    {
        // Decode $requestUri
        $parts = parse_url($requestUri);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $queries);
            $queryString = '';
            foreach ($queries as $key => $value) {
                $queryString .= sprintf('%s=%s&', $key, $value);
            }
            $queryString = rtrim($queryString, '&');
            $requestUri = $parts['path'] . '?' . $queryString;
            if (isset($parts['fragment'])) {
                $requestUri .= '#' . $parts['fragment'];
            }
        }

        if (is_array($body)) {
            $body = empty($body) ? '' : json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $body = (string)$body;
        }
        $method = strtoupper($method);
        $plain = $timestamp . $method . $requestUri . $body;
        return base64_encode(hash_hmac('sha256', $plain, $this->secret, true));
    }

    public function getHeaders($method, $requestUri, $body)
    {
        $timestamp = floor(microtime(true) * 1000);
        $isV2ApiKeyVersion = KuCoinApi::getApiKeyVersion() === ApiKeyVersion::V2;
        $headers = [
            'KC-API-KEY'        => $this->key,
            'KC-API-TIMESTAMP'  => $timestamp,
            'KC-API-PASSPHRASE' => $isV2ApiKeyVersion ? $this->signaturePassphrase() : $this->passphrase,
            'KC-API-SIGN'       => $this->signature($requestUri, $body, $timestamp, $method),
        ];
        $isV2ApiKeyVersion && $headers['KC-API-KEY-VERSION'] = ApiKeyVersion::V2;
        return $headers;
    }

    private function signaturePassphrase()
    {
        return base64_encode(hash_hmac('sha256', $this->passphrase, $this->secret, true));
    }
}
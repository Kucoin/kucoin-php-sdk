<?php

namespace KuCoin\SDK;

class AuthVersion
{
    const V1 = 'V1';

    const V2 = 'V2';

    protected static $AuthApiKeyVersionMap = [
        self::V1 => '1',
        self::V2 => '2',
    ];

    /**
     * get authVersion corresponding api version
     *
     * @param $authVersion
     * @return string|null
     */
    public static function getAuthApiKeyVersion($authVersion)
    {
        return isset(self::$AuthApiKeyVersionMap[$authVersion]) ? self::$AuthApiKeyVersionMap[$authVersion] : null;
    }
}
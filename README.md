
# PHP SDK for KuCoin API
> The detailed document [https://docs.kucoin.com](https://docs.kucoin.com).

[![Latest Version](https://img.shields.io/github/release/Kucoin/kucoin-php-sdk.svg?style=flat-square)](https://github.com/Kucoin/kucoin-php-sdk/releases)
[![Build Status](https://travis-ci.org/Kucoin/kucoin-php-sdk.svg?branch=master)](https://travis-ci.org/Kucoin/kucoin-php-sdk)

## Requirements

| Dependency | Requirement |
| -------- | -------- |
| [PHP](https://secure.php.net/manual/en/install.php) | `>=5.5.0` `PHP7+ is better` |
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) | `~6.0` |

## Install
> Install package via [Composer](https://getcomposer.org/).

```shell
composer require "kucoin/kucoin-php-sdk:~1.0.0"
```

## Usage

- Choose environment

| Environment | BaseUri |
| -------- | -------- |
| *Production* `DEFAULT` | https://openapi-v2.kucoin.com |
| *Sandbox* | https://openapi-sandbox.kucoin.com |

```php
// Switch to the sandbox environment
KuCoinApi::setBaseUri('https://openapi-sandbox.kucoin.com');
```

- Example of API `without` authentication

```php
use KuCoin\SDK\PublicApi\Time;

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);
```

- Example of API `with` authentication

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Account;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\BusinessException;

$auth = new Auth('key', 'secret', 'passphrase');
$api = new Account($auth);

try {
    $result = $api->getList(['type' => 'main']);
    var_dump($result);
} catch (HttpException $e) {
    var_dump($e->getMessage());
} catch (BusinessException $e) {
    var_dump($e->getMessage());
}
```

- Example of WebSocket feed

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

$auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

$query = ['connectId' => uniqid('', true)];
$channel = [
    'topic' => '/market/ticker:KCS-BTC',
    //'response' => true,
];

$api->subscribePublicChannel($query, $channel, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    var_dump($message);
    
    // Stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
});
```

- API list

<details>
<summary>KuCoin\SDK\PrivateApi\Account</summary>

| API | Authentication | URL |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Account::create() | YES | https://docs.kucoin.com/#create-an-account |
| KuCoin\SDK\PrivateApi\Account::getList() | YES | https://docs.kucoin.com/#list-accounts |
| KuCoin\SDK\PrivateApi\Account::getDetail() | YES | https://docs.kucoin.com/#get-an-account |
| KuCoin\SDK\PrivateApi\Account::getList() | YES | https://docs.kucoin.com/#list-accounts |
| KuCoin\SDK\PrivateApi\Account::getLedgers() | YES | https://docs.kucoin.com/#get-account-ledgers |
| KuCoin\SDK\PrivateApi\Account::getHolds() | YES | https://docs.kucoin.com/#get-holds |
| KuCoin\SDK\PrivateApi\Account::innerTransfer() | YES | https://docs.kucoin.com/#inner-tranfer |

</details>

| API | Authentication |
| -------- | -------- |
| KuCoin\SDK\PrivateApi\Account | YES |
| KuCoin\SDK\PrivateApi\Deposit | YES |
| KuCoin\SDK\PrivateApi\Fill | YES |
| KuCoin\SDK\PrivateApi\Order | YES |
| KuCoin\SDK\PrivateApi\WebSocketFeed | YES |
| KuCoin\SDK\PrivateApi\Withdrawal | YES |
| KuCoin\SDK\PublicApi\Currency | NO |
| KuCoin\SDK\PublicApi\Symbol | NO |
| KuCoin\SDK\PublicApi\Time | NO |


## Run tests
> Modify your API key in `phpunit.xml` first.

```shell
composer test
```

## License

[MIT](LICENSE)


# PHP SDK for KuCoin API
> The detailed document [https://docs.kucoin.com](https://docs.kucoin.com).

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
| *SandBox* | https://openapi-sandbox.kucoin.com |

```php
// Switch to the SandBox environment
KuCoinApi::setBaseUri('https://openapi-sandbox.kucoin.com');
```

- API `without` authentication

```php
use KuCoin\SDK\PublicApi\Time;

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);
```

- API `with` authentication

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\Account;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\BusinessException;

$auth = new Auth('key', 'secret', 'passphrase');
$api = new Account($auth);

try {
    $result = $api->getList('main');
    var_dump($result);
} catch (HttpException $e) {
    var_dump($e->getMessage());
} catch (BusinessException $e) {
    var_dump($e->getMessage());
}
```

- WebSocket Feed

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;

$auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

$query = ['connectId' => uniqid('', true)];
$channel = [
    'type'  => 'subscribe',
    'topic' => '/market/snapshot:BTC-USDT',
];

$options = [
    'tls' => [
        'verify_peer' => false,
    ],
];
$api->subscribePublicChannel($query, $channel, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    // ping
    // $ws->send(json_encode($api->createPingMessage()));
    var_dump($message);

    // stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
}, $options);
```

- API list

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

```shell
phpunit
```

## License

[MIT](LICENSE)

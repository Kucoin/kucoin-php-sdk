# PHP SDK for KuCoin API

> The detailed document [https://docs.kucoin.com](https://docs.kucoin.com), in order to receive the latest API change notifications, please `Watch` this repository.

[![Latest Version](https://img.shields.io/github/release/Kucoin/kucoin-php-sdk.svg)](https://github.com/Kucoin/kucoin-php-sdk/releases)
[![PHP Version](https://img.shields.io/packagist/php-v/kucoin/kucoin-php-sdk.svg?color=green)](https://secure.php.net)
[![Build Status](https://travis-ci.org/Kucoin/kucoin-php-sdk.svg?branch=master)](https://travis-ci.org/Kucoin/kucoin-php-sdk)
[![Total Downloads](https://poser.pugx.org/kucoin/kucoin-php-sdk/downloads)](https://packagist.org/packages/kucoin/kucoin-php-sdk)
[![License](https://poser.pugx.org/kucoin/kucoin-php-sdk/license)](LICENSE)
<!-- [![Total Lines](https://tokei.rs/b1/github/Kucoin/kucoin-php-sdk)](https://github.com/Kucoin/kucoin-php-sdk) -->
<!-- [![Packagist](https://img.shields.io/packagist/dt/Kucoin/kucoin-php-sdk.svg)](https://packagist.org/packages/kucoin/kucoin-php-sdk) -->
<!-- [![License](https://img.shields.io/packagist/l/kucoin/kucoin-php-sdk.svg)](LICENSE) -->

## Requirements

| Dependency | Requirement |
| -------- | -------- |
| [PHP](https://secure.php.net/manual/en/install.php) | `>=5.5.0` `Recommend PHP7+` |
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) | `^6.0\|^7.0` |

## Install
> Install package via [Composer](https://getcomposer.org/).

```shell
composer require "kucoin/kucoin-php-sdk:~1.1.0"
```

## Usage

### Choose environment

| Environment | BaseUri |
| -------- | -------- |
| *Production* | `https://api.kucoin.com(DEFAULT)`|
| *Sandbox* | `https://openapi-sandbox.kucoin.com` |

```php
// Switch to the sandbox environment
KuCoinApi::setBaseUri('https://openapi-sandbox.kucoin.com');
```

### Debug mode & logging

```php
// Debug mode will record the logs of API and WebSocket to files in the directory "KuCoinApi::getLogPath()" according to the minimum log level "KuCoinApi::getLogLevel()".
KuCoinApi::setDebugMode(true);

// Logging in your code
// KuCoinApi::setLogPath('/tmp');
// KuCoinApi::setLogLevel(Monolog\Logger::DEBUG);
KuCoinApi::getLogger()->debug("I'm a debug message");
```

### Examples
> See the [test case](tests) for more examples.

#### Example of API `without` authentication

```php
use KuCoin\SDK\PublicApi\Time;

$api = new Time();
$timestamp = $api->timestamp();
var_dump($timestamp);
```

##### **Note**
To reinforce the security of the API, KuCoin upgraded the API key to version 2.0, the validation logic has also been changed. It is recommended to create(https://www.kucoin.com/account/api) and update your API key to version 2.0. The API key of version 1.0 will be still valid until May 1, 2021

#### Example of API `with` authentication

```php
use KuCoin\SDK\PrivateApi\Account;
use KuCoin\SDK\Exceptions\HttpException;
use KuCoin\SDK\Exceptions\BusinessException;
use KuCoin\SDK\Auth;

// Auth version v2 (recommend)
$auth = new Auth('key', 'secret', 'passphrase', Auth::API_KEY_VERSION_V2);
// Auth version v1
// $auth = new Auth('key', 'secret', 'passphrase');

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

#### Example of WebSocket feed

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\PrivateApi\WebSocketFeed;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

$auth = null;
// Need to pass the Auth parameter when subscribing to a private channel($api->subscribePrivateChannel()).
// $auth = new Auth('key', 'secret', 'passphrase');
$api = new WebSocketFeed($auth);

// Use a custom event loop instance if you like
//$loop = Factory::create();
//$loop->addPeriodicTimer(1, function () {
//    var_dump(date('Y-m-d H:i:s'));
//});
//$api->setLoop($loop);

$query = ['connectId' => uniqid('', true)];
$channels = [
    ['topic' => '/market/ticker:KCS-BTC'], // Subscribe multiple channels
    ['topic' => '/market/ticker:ETH-BTC'],
];

$api->subscribePublicChannels($query, $channels, function (array $message, WebSocket $ws, LoopInterface $loop) use ($api) {
    var_dump($message);

    // Subscribe another channel
    // $ws->send(json_encode($api->createSubscribeMessage('/market/ticker:LTC-BTC')));

    // Unsubscribe the channel
    // $ws->send(json_encode($api->createUnsubscribeMessage('/market/ticker:ETH-BTC')));

    // Stop loop
    // $loop->stop();
}, function ($code, $reason) {
    echo "OnClose: {$code} {$reason}\n";
});
```

#### ⚡️Coroutine HTTP client for asynchronous IO
> See the [benchmark](examples/BenchmarkCoroutine.php), almost `20x` faster than `curl`.

```bash
pecl install swoole
composer require swlib/saber
```

```php
use KuCoin\SDK\Auth;
use KuCoin\SDK\Http\SwooleHttp;
use KuCoin\SDK\KuCoinApi;
use KuCoin\SDK\PrivateApi\Order;
use KuCoin\SDK\PublicApi\Time;

// Require PHP 7.1+ and Swoole 2.1.2+
// Require running in cli mode

go(function () {
    $api = new Time(null, new SwooleHttp);
    $timestamp = $api->timestamp();
    var_dump($timestamp);
});

go(function () {
    // Auth version v2 (recommend)
    $auth = new Auth('key', 'secret', 'passphrase', Auth::API_KEY_VERSION_V2);
    // Auth version v1
    // $auth = new Auth('key', 'secret', 'passphrase');
    $api = new Order($auth, new SwooleHttp);
    // Create 50 orders CONCURRENTLY in 1 second
    for ($i = 0; $i < 50; $i++) {
        go(function () use ($api, $i) {
            $order = [
                'clientOid' => uniqid(),
                'price'     => '1',
                'size'      => '1',
                'symbol'    => 'BTC-USDT',
                'type'      => 'limit',
                'side'      => 'buy',
                'remark'    => 'ORDER#' . $i,
            ];
            try {
                $result = $api->create($order);
                var_dump($result);
            } catch (\Throwable $e) {
                var_dump($e->getMessage());
            }
        });
    }
});
```

### API list
<details>
<summary>KuCoin\SDK\PrivateApi\Account</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Account::create() | YES | https://docs.kucoin.com/#create-an-account |
| KuCoin\SDK\PrivateApi\Account::getList() | YES | https://docs.kucoin.com/#list-accounts |
| KuCoin\SDK\PrivateApi\Account::getDetail() | YES | https://docs.kucoin.com/#get-an-account |
| KuCoin\SDK\PrivateApi\Account::getLedgers() | YES | `DEPRECATED` https://docs.kucoin.com/#get-account-ledgers-deprecated |
| KuCoin\SDK\PrivateApi\Account::getHolds() | YES | https://docs.kucoin.com/#get-holds |
| KuCoin\SDK\PrivateApi\Account::innerTransfer() | YES | `DEPRECATED` https://docs.kucoin.com/#inner-transfer |
| KuCoin\SDK\PrivateApi\Account::innerTransferV2() | YES | https://docs.kucoin.com/#inner-transfer |
| KuCoin\SDK\PrivateApi\Account::getSubAccountUsers() | YES | https://docs.kucoin.com/#get-user-info-of-all-sub-accounts |
| KuCoin\SDK\PrivateApi\Account::getSubAccountDetail() | YES | https://docs.kucoin.com/#get-account-balance-of-a-sub-account |
| KuCoin\SDK\PrivateApi\Account::getSubAccountList() | YES | https://docs.kucoin.com/#get-the-aggregated-balance-of-all-sub-accounts-of-the-current-user |
| KuCoin\SDK\PrivateApi\Account::subTransfer() | YES | `DEPRECATED` https://docs.kucoin.com/#transfer-between-master-account-and-sub-account |
| KuCoin\SDK\PrivateApi\Account::subTransferV2() | YES | https://docs.kucoin.com/#transfer-between-master-user-and-sub-user |
| KuCoin\SDK\PrivateApi\Account::getLedgersV2() | YES | https://docs.kucoin.com/#get-account-ledgers |
| KuCoin\SDK\PrivateApi\Account::getLedgersV2() | YES | https://docs.kucoin.com/#get-account-ledgers |
| KuCoin\SDK\PrivateApi\Account::getSubUserV2() | YES | https://docs.kucoin.com/#get-paginated-list-of-sub-accounts |
| KuCoin\SDK\PrivateApi\Account::getUserinfoV2() | YES | https://docs.kucoin.com/#get-account-summary-info-v2 |
| KuCoin\SDK\PrivateApi\Account::createSubUserV2() | YES | https://docs.kucoin.com/#create-sub-account-v2 |
| KuCoin\SDK\PrivateApi\Account::createSubUserApiKey() | YES | https://docs.kucoin.com/#create-spot-apis-for-sub-account |
| KuCoin\SDK\PrivateApi\Account::getSubUserApiKey() | YES | https://docs.kucoin.com/#get-sub-account-spot-api-list |
| KuCoin\SDK\PrivateApi\Account::updateSubUserApiKey() | YES | https://docs.kucoin.com/#modify-sub-account-spot-apis |
| KuCoin\SDK\PrivateApi\Account::deleteSubUserApiKey() | YES | https://docs.kucoin.com/#delete-sub-account-spot-apis |
| KuCoin\SDK\PrivateApi\Account::getSubAccountListV2() | YES | https://docs.kucoin.com/#get-paginated-sub-account-information |
| KuCoin\SDK\PrivateApi\Account::getAccountTransferable() | YES | https://docs.kucoin.com/#get-the-transferable |
| KuCoin\SDK\PrivateApi\Account::getHfLedgersV2() | YES | https://docs.kucoin.com/spot-hf/#account-ledger-in-high-frequency-trading-accounts |
</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Deposit</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Deposit::createAddress() | YES | https://docs.kucoin.com/#create-deposit-address |
| KuCoin\SDK\PrivateApi\Deposit::getAddress() | YES | https://docs.kucoin.com/#get-deposit-address |
| KuCoin\SDK\PrivateApi\Deposit::getAddresses() | YES | https://docs.kucoin.com/#get-deposit-addresses-v2 |
| KuCoin\SDK\PrivateApi\Deposit::getDeposits() | YES | https://docs.kucoin.com/#get-deposit-list |
| KuCoin\SDK\PrivateApi\Deposit::getV1Deposits() | YES | https://docs.kucoin.com/#get-v1-historical-deposits-list |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\TradeFee</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\TradeFee::getBaseFee() | YES | https://docs.kucoin.com/#basic-user-fee |
| KuCoin\SDK\PrivateApi\TradeFee::getTradeFees() | YES | https://docs.kucoin.com/#actual-fee-rate-of-the-trading-pair |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Symbol</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Symbol::getAggregatedFullOrderBook() | NO | https://docs.kucoin.com/#get-full-order-book-aggregated |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Order</summary>

| API | Authentication | Description |
| -------- | -------- | ------ |
| KuCoin\SDK\PrivateApi\Order::create() | YES | https://docs.kucoin.com/#place-a-new-order |
| KuCoin\SDK\PrivateApi\Order::createMulti() | YES | https://docs.kucoin.com/#place-bulk-orders |
| KuCoin\SDK\PrivateApi\Order::cancel() | YES | https://docs.kucoin.com/#cancel-an-order |
| KuCoin\SDK\PrivateApi\Order::cancelAll() | YES | https://docs.kucoin.com/#cancel-all-orders |
| KuCoin\SDK\PrivateApi\Order::getList() | YES | https://docs.kucoin.com/#list-orders |
| KuCoin\SDK\PrivateApi\Order::getV1List() | YES | https://docs.kucoin.com/#get-v1-historical-orders-list |
| KuCoin\SDK\PrivateApi\Order::getDetail() | YES | https://docs.kucoin.com/#get-an-order |
| KuCoin\SDK\PrivateApi\Order::getRecentList() | YES | https://docs.kucoin.com/#recent-orders |
| KuCoin\SDK\PrivateApi\Order::createMarginOrder()| YES | https://docs.kucoin.com/#place-a-margin-order |
| KuCoin\SDK\PrivateApi\Order::cancelByClientOid() | YES | https://docs.kucoin.com/#cancel-single-order-by-clientoid |
| KuCoin\SDK\PrivateApi\Order::getDetailByClientOid() | YES | https://docs.kucoin.com/#get-single-active-order-by-clientoid|
| KuCoin\SDK\PrivateApi\Order::hfCreate() | YES | https://docs.kucoin.com/spot-hf/#place-hf-order|
| KuCoin\SDK\PrivateApi\Order::hfSyncCreate() | YES | https://docs.kucoin.com/spot-hf/#sync-place-hf-order|
| KuCoin\SDK\PrivateApi\Order::hfCreateMulti() | YES | https://docs.kucoin.com/spot-hf/#place-multiple-hf-orders|
| KuCoin\SDK\PrivateApi\Order::hfSyncCreateMulti() | YES | https://docs.kucoin.com/spot-hf/#sync-place-multiple-hf-orders|
| KuCoin\SDK\PrivateApi\Order::hfModify() | YES | https://docs.kucoin.com/spot-hf/#modify-order|
| KuCoin\SDK\PrivateApi\Order::hfCancel() | YES | https://docs.kucoin.com/spot-hf/#cancel-orders-by-orderid|
| KuCoin\SDK\PrivateApi\Order::hfSyncCancel() | YES | https://docs.kucoin.com/spot-hf/#sync-cancel-orders-by-orderid|
| KuCoin\SDK\PrivateApi\Order::hfCancelByClientOid() | YES | https://docs.kucoin.com/spot-hf/#cancel-order-by-clientoid|
| KuCoin\SDK\PrivateApi\Order::hfSyncCancelByClientOid() | YES | https://docs.kucoin.com/spot-hf/#sync-cancel-orders-by-clientoid|
| KuCoin\SDK\PrivateApi\Order::hfSyncCancelSize() | YES | https://docs.kucoin.com/spot-hf/#cancel-specified-number-of-orders-by-orderid|
| KuCoin\SDK\PrivateApi\Order::hfSyncCancelAll() | YES | https://docs.kucoin.com/spot-hf/#cancel-all-hf-orders-by-symbol|
| KuCoin\SDK\PrivateApi\Order::getActiveOrderList() | YES | https://docs.kucoin.com/spot-hf/#obtain-list-of-active-hf-orders|
| KuCoin\SDK\PrivateApi\Order::getActiveSymbols() | YES | https://docs.kucoin.com/spot-hf/#obtain-list-of-symbol-with-active-hf-orders|
| KuCoin\SDK\PrivateApi\Order::getDoneOrderList() | YES | https://docs.kucoin.com/spot-hf/#obtain-list-of-filled-hf-orders|
| KuCoin\SDK\PrivateApi\Order::getHfDetail() | YES | https://docs.kucoin.com/spot-hf/#details-of-a-single-hf-order|
| KuCoin\SDK\PrivateApi\Order::getHfDetailByClientOid() | YES | https://docs.kucoin.com/spot-hf/#obtain-details-of-a-single-hf-order-using-clientoid|
| KuCoin\SDK\PrivateApi\Order::hfAutoCancel() | YES | https://docs.kucoin.com/spot-hf/#hf-auto-cancel-setting|
| KuCoin\SDK\PrivateApi\Order::getHfAutoCancel() | YES | https://docs.kucoin.com/spot-hf/#hf-auto-cancel-order-setting-query|
| KuCoin\SDK\PrivateApi\Order::getHfFills() | YES | https://docs.kucoin.com/spot-hf/#hf-transaction-records|

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\StopOrder</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\StopOrder::create() | YES | https://docs.kucoin.com/#place-a-new-order-2 |
| KuCoin\SDK\PrivateApi\StopOrder::cancel() | YES | https://docs.kucoin.com/#cancel-an-order-2 |
| KuCoin\SDK\PrivateApi\StopOrder::cancelBatch() | YES | https://docs.kucoin.com/#cancel-orders |
| KuCoin\SDK\PrivateApi\StopOrder::getList() | YES | https://docs.kucoin.com/#list-stop-orders |
| KuCoin\SDK\PrivateApi\StopOrder::getDetail() | YES | https://docs.kucoin.com/#get-single-order-info |
| KuCoin\SDK\PrivateApi\StopOrder::getDetailByClientOid() | YES | https://docs.kucoin.com/#get-single-order-by-clientoid |
| KuCoin\SDK\PrivateApi\StopOrder::cancelByClientOid()| YES | https://docs.kucoin.com/#cancel-single-order-by-clientoid-2 |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Fill</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Fill::getList() | YES | https://docs.kucoin.com/#list-fills |
| KuCoin\SDK\PrivateApi\Fill::getRecentList() | YES | https://docs.kucoin.com/#recent-fills |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\WebSocketFeed</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\WebSocketFeed::getPublicServer() | NO | https://docs.kucoin.com/#apply-connect-token |
| KuCoin\SDK\PrivateApi\WebSocketFeed::getPrivateServer() | YES | https://docs.kucoin.com/#apply-connect-token |
| KuCoin\SDK\PrivateApi\WebSocketFeed::subscribePublicChannel() | NO | https://docs.kucoin.com/#public-channels |
| KuCoin\SDK\PrivateApi\WebSocketFeed::subscribePublicChannels() | NO | https://docs.kucoin.com/#public-channels |
| KuCoin\SDK\PrivateApi\WebSocketFeed::subscribePrivateChannel() | YES | https://docs.kucoin.com/#private-channels |
| KuCoin\SDK\PrivateApi\WebSocketFeed::subscribePrivateChannels() | YES | https://docs.kucoin.com/#private-channels |

</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Withdrawal</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Withdrawal::getQuotas() | YES | https://docs.kucoin.com/#get-withdrawal-quotas |
| KuCoin\SDK\PrivateApi\Withdrawal::getList() | YES | https://docs.kucoin.com/#get-withdrawals-list |
| KuCoin\SDK\PrivateApi\Withdrawal::getV1List() | YES | https://docs.kucoin.com/#get-v1-historical-withdrawals-list |
| KuCoin\SDK\PrivateApi\Withdrawal::apply() | YES | https://docs.kucoin.com/#apply-withdraw |
| KuCoin\SDK\PrivateApi\Withdrawal::cancel() | YES | https://docs.kucoin.com/#cancel-withdrawal |

</details>

<details>
<summary>KuCoin\SDK\PublicApi\Currency</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PublicApi\Currency::getList() | NO | https://docs.kucoin.com/#get-currencies |
| KuCoin\SDK\PublicApi\Currency::getDetail() | NO | https://docs.kucoin.com/#get-currency-detail |
| KuCoin\SDK\PublicApi\Currency::getPrices() | NO | https://docs.kucoin.com/#get-fiat-price |
| KuCoin\SDK\PublicApi\Currency::getV2Detail() | NO | https://docs.kucoin.com/#get-currency-detail-recommend |

</details>

<details>
<summary>KuCoin\SDK\PublicApi\Symbol</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PublicApi\Symbol::getList() | NO | `DEPRECATED` https://docs.kucoin.com/#get-symbols-list-deprecated |
| KuCoin\SDK\PublicApi\Symbol::getTicker() | NO | https://docs.kucoin.com/#get-ticker |
| KuCoin\SDK\PublicApi\Symbol::getAllTickers() | NO | https://docs.kucoin.com/#get-all-tickers |
| KuCoin\SDK\PublicApi\Symbol::getAggregatedPartOrderBook() | NO | https://docs.kucoin.com/#get-part-order-book-aggregated |
| KuCoin\SDK\PublicApi\Symbol::getTradeHistories() | NO | https://docs.kucoin.com/#get-trade-histories |
| KuCoin\SDK\PublicApi\Symbol::getKLines() | NO | https://docs.kucoin.com/#get-klines |
| KuCoin\SDK\PublicApi\Symbol::get24HStats() | NO | https://docs.kucoin.com/#get-24hr-stats |
| KuCoin\SDK\PublicApi\Symbol::getMarkets() | NO | https://docs.kucoin.com/#get-market-list |
| KuCoin\SDK\PublicApi\Symbol::getListV2() | NO | https://docs.kucoin.com/#get-symbols-list |
</details>

<details>
<summary>KuCoin\SDK\PrivateApi\Margin</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\Margin::getMarkPrice() | YES | https://docs.kucoin.com/#margin-info |
| KuCoin\SDK\PrivateApi\Margin::getConfig() | YES | https://docs.kucoin.com/#get-margin-configuration-info |
| KuCoin\SDK\PrivateApi\Margin::getAccount() | YES | https://docs.kucoin.com/#get-margin-account |
| KuCoin\SDK\PrivateApi\Margin::borrow() | YES | https://docs.kucoin.com/#post-borrow-order |
| KuCoin\SDK\PrivateApi\Margin::getBorrow() | YES | https://docs.kucoin.com/#get-borrow-order |
| KuCoin\SDK\PrivateApi\Margin::getOutstanding() | YES | https://docs.kucoin.com/#get-repay-record |
| KuCoin\SDK\PrivateApi\Margin::getRepayRecord() | YES | https://docs.kucoin.com/#get-repayment-record |
| KuCoin\SDK\PrivateApi\Margin::repayAll() | YES | https://docs.kucoin.com/#one-click-repayment |
| KuCoin\SDK\PrivateApi\Margin::repaySingle() | YES | https://docs.kucoin.com/#repay-a-single-order |
| KuCoin\SDK\PrivateApi\Margin::lend() | YES | https://docs.kucoin.com/#post-lend-order |
| KuCoin\SDK\PrivateApi\Margin::cancelLend() | YES | https://docs.kucoin.com/#cancel-lend-order |
| KuCoin\SDK\PrivateApi\Margin::setAutoLend() | YES | https://docs.kucoin.com/#set-auto-lend |
| KuCoin\SDK\PrivateApi\Margin::getLendActive() | YES | https://docs.kucoin.com/#get-active-order |
| KuCoin\SDK\PrivateApi\Margin::getLendDone() | YES | https://docs.kucoin.com/#get-lent-history |
| KuCoin\SDK\PrivateApi\Margin::getUnsettled() | YES | https://docs.kucoin.com/#get-active-lend-order-list |
| KuCoin\SDK\PrivateApi\Margin::getSettled() | YES | https://docs.kucoin.com/#get-settled-lend-order-history |
| KuCoin\SDK\PrivateApi\Margin::getLendAssets() | YES | https://docs.kucoin.com/#get-account-lend-record |
| KuCoin\SDK\PrivateApi\Margin::getMarket() | YES | https://docs.kucoin.com/#lending-market-data |
| KuCoin\SDK\PrivateApi\Margin::getTradeLast() | YES | https://docs.kucoin.com/#margin-trade-data |
</details>

<details>
<summary>KuCoin\SDK\PrivateApi\IsolatedMargin</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PrivateApi\IsolatedMargin::getSymbols() | YES | https://docs.kucoin.com/#query-isolated-margin-trading-pair-configuration |
| KuCoin\SDK\PrivateApi\IsolatedMargin::getAccountList() | YES | https://docs.kucoin.com/#query-isolated-margin-account-info |
| KuCoin\SDK\PrivateApi\IsolatedMargin::getAccountDetail() | YES | https://docs.kucoin.com/#query-single-isolated-margin-account-info |
| KuCoin\SDK\PrivateApi\IsolatedMargin::borrow() | YES | https://docs.kucoin.com/#isolated-margin-borrowing |
| KuCoin\SDK\PrivateApi\IsolatedMargin::getOutstanding() | YES | https://docs.kucoin.com/#query-outstanding-repayment-records |
| KuCoin\SDK\PrivateApi\IsolatedMargin::getRepaid() | YES | https://docs.kucoin.com/#query-repayment-records |
| KuCoin\SDK\PrivateApi\IsolatedMargin::repayAll() | YES | https://docs.kucoin.com/#quick-repayment |
| KuCoin\SDK\PrivateApi\IsolatedMargin::repaySingle() | YES | https://docs.kucoin.com/#single-repayment |
</details>

<details>
<summary>KuCoin\SDK\PublicApi\Time</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PublicApi\Time::timestamp() | NO | https://docs.kucoin.com/#server-time |

</details>

<details>
<summary>KuCoin\SDK\PublicApi\ServiceStatus</summary>

| API | Authentication | Description |
| -------- | -------- | -------- |
| KuCoin\SDK\PublicApi\ServiceStatus::getStatus() | NO | https://docs.kucoin.com/#service-status |

</details>

## Run tests
> Modify your API key in `phpunit.xml` first.

```shell
# Add your API configuration items into the environmental variable first
export API_BASE_URI=https://api.kucoin.com
export API_KEY=key
export API_SECRET=secret
export API_PASSPHRASE=passphrase
export API_KEY_VERSION=2

composer test
```

## License

[MIT](LICENSE)

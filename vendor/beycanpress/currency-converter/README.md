# Currency Converter

## About

It takes on the task of converting between fiat and cryptocurrencies.

## Installation

`composer require beycanpress/currency-converter`

## Usage

```php
use BeycanPress\CurrencyConverter;


$converter = new CurrencyConverter('CryptoCompare | CoinMarketCap | CoinGecko', 'api key for coinmarketcap');
$paymentPrice = $converter->convert('USD', 'BTC', 15 /* USD Price */);

$paymentPrice // BTC Price
```
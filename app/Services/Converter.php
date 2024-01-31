<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CurrencyConverter;
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Plugin;
// Types
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
// Exceptions
use BeycanPress\CryptoPayLite\Exceptions\ConverterException;

/**
 * Currency converter service
 * @since 2.1.0
 */
class Converter
{
    /**
     * @var array<string,array<string,float>>
     */
    public static array $customPrices = [];

    /**
     * @param PaymentDataType $data
     * @return float
     */
    public static function convert(PaymentDataType $data): float
    {
        /** @var float|null */
        $paymentAmount = null;
        $addon = $data->getAddon();
        $order = $data->getOrder();
        $network = $data->getNetwork();
        $amount = $order->getAmount();
        $orderCurrency = $order->getCurrency();
        $paymentCurrency = $order->getPaymentCurrency();
        $converter = new CurrencyConverter('CryptoCompare');

        if (
            $converter->isStableCoin($orderCurrency, $paymentCurrency->getSymbol()) ||
            $converter->isSameCurrency($orderCurrency, $paymentCurrency->getSymbol())
        ) {
            $paymentAmount = $amount;
        }

        $customPrices = self::getCustomPrices();
        if (isset($customPrices[$paymentCurrency->getSymbol()])) {
            $customPrices = $customPrices[$paymentCurrency->getSymbol()];
            if (isset($customPrices[$orderCurrency])) {
                $paymentAmount = ($amount / $customPrices[$orderCurrency]);
            }
        } elseif (isset($customPrices[$orderCurrency])) {
            $customPrices = $customPrices[$orderCurrency];
            if (isset($customPrices[$orderCurrency])) {
                $paymentAmount = ($amount / $customPrices[$orderCurrency]);
            }
        }

        if (is_null($paymentAmount)) {
            // if is not default, look another converters
            if (($converterKey = Helpers::getSetting('converter')) != 'CryptoCompare') {
                $paymentAmount = Hook::callFilter(
                    "currency_converter_" . $converterKey,
                    $paymentAmount,
                    $data
                );
            }

            if (is_null($paymentAmount)) {
                try {
                    $paymentAmount = $converter->convert($orderCurrency, $paymentCurrency->getSymbol(), $amount);
                } catch (\Exception $e) {
                    Helpers::debug($e->getMessage(), 'ERROR', $e);
                    $paymentAmount = null;
                }
            }

            if (is_null($paymentAmount)) {
                throw new ConverterException(
                    // @phpcs:ignore
                    esc_html__('There was a problem converting currency! Make sure your currency value is available in the relevant API or you define a custom value for your currency.', 'cryptopay_lite')
                );
            }
        }

        return Helpers::toFixed(Hook::callFilter('payment_amount', $paymentAmount, $data), 6);
    }

    /**
     * @return array<string,array<string,float>>
     */
    private static function getCustomPrices(): array
    {
        $customPrices = (array) Helpers::getSetting('customPrices');

        if (empty(self::$customPrices) && is_array($customPrices)) {
            foreach ($customPrices as $token) {
                if (!isset($token['symbol'])) {
                    continue;
                }
                $tokenSymbol = strtoupper($token['symbol']);
                self::$customPrices[$tokenSymbol] = [];
                foreach ($token['prices'] as $price) {
                    $symbol = strtoupper($price['symbol']);
                    self::$customPrices[$tokenSymbol][$symbol] = floatval($price['price']);
                }
            }
        }

        return Hook::callFilter('custom_prices', self::$customPrices);
    }
}

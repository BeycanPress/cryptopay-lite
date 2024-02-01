<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CurrencyConverter;
use BeycanPress\CryptoPayLite\Helpers;
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
        $order = $data->getOrder();
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

        return Helpers::toFixed($paymentAmount, 6);
    }
}

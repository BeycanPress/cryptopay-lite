<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
// Types
use BeycanPress\CryptoPayLite\Types\Data\ConfigDataType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;

/**
 * Class Discounts
 * @since 2.0.0
 */
class Discounts
{
    /**
     * @var array<string,float>
     */
    public static array $discountRates = [];

    /**
     * @return void
     */
    public function __construct()
    {
        Hook::addFilter('payment_amount', [$this, 'applyDiscount'], 10, 3);
        Hook::addFilter('edit_config_data', [$this, 'editConfigData'], 10);
        Hook::addFilter('edit_payment_data', [$this, 'editPaymentData'], 10);
    }

    /**
     * @param float $amount
     * @param PaymentDataType $data
     * @return float
     */
    public function applyDiscount(float $amount, PaymentDataType $data): float
    {
        $currency = $data->getOrder()->getPaymentCurrency()->getSymbol();

        $discountRates = self::getDiscountRates($data->getAddon(), $data);

        $discountRate = $discountRates->{$currency} ?? 0;

        $discountedAmount = $amount - ($amount * $discountRate / 100);

        return Hook::callFilter(
            'discounted_amount_' . $data->getAddon(),
            Hook::callFilter(
                'discounted_amount',
                $discountedAmount,
                $data
            ),
            $data
        );
    }

    /**
     * @param ConfigDataType $config
     * @return ConfigDataType
     */
    public function editConfigData(ConfigDataType $config): ConfigDataType
    {
        return $config->setDiscountRates(self::getDiscountRates($config->getAddon()));
    }

    /**
     * @param PaymentDataType $data
     * @return PaymentDataType
     */
    public function editPaymentData(PaymentDataType $data): PaymentDataType
    {
        $discountRates = self::getDiscountRates($data->getAddon(), $data);
        $paymentCurrency = $data->getOrder()->getPaymentCurrency();

        if ($paymentCurrency && isset($discountRates->{$paymentCurrency->getSymbol()})) {
            $discountRate = $discountRates->{$paymentCurrency->getSymbol()};
            $data->getOrder()->setDiscountRate(floatval($discountRate));
        }

        return $data;
    }

    /**
     * @param string $addon
     * @return bool
     */
    private static function getDiscountApplyStatus(string $addon): bool
    {
        return Hook::callFilter('apply_discount_' . $addon, true);
    }

    /**
     * @param string $addon
     * @param PaymentDataType|null $data
     * @return object
     */
    public static function getDiscountRates(string $addon, ?PaymentDataType $data = null): object
    {
        if (!self::getDiscountApplyStatus($addon)) {
            return (object) [];
        }

        $discountRates = (array) Helpers::getSetting('discountRates');

        if (empty(self::$discountRates) && is_array($discountRates)) {
            foreach ($discountRates as $token) {
                if (!isset($token['symbol'])) {
                    continue;
                };
                $tokenSymbol = strtoupper($token['symbol']);
                self::$discountRates[$tokenSymbol] = floatval($token['rate']);
            }
        }

        return (object) Hook::callFilter(
            'discount_rates_' . $addon,
            Hook::callFilter(
                'discount_rates',
                self::$discountRates,
                $data
            ),
            $data
        );
    }
}

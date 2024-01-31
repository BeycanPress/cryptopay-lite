<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Gateway;

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;

class Register
{
    /**
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_blocks_loaded', [$this, 'registerBlocksGateways']);
        add_filter('woocommerce_payment_gateways', [$this, 'registerClassicGateways']);
    }

    /**
     * @return void
     */
    public function registerBlocksGateways(): void
    {
        if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            add_action('woocommerce_blocks_payment_method_type_registration', [$this, 'gatewayRegistry']);
        }
    }

    /**
     * @param PaymentMethodRegistry $registry
     * @return void
     */
    public function gatewayRegistry(PaymentMethodRegistry $registry): void
    {
        $registry->register(new Blocks\CryptoPay());
    }

    /**
     * @param array<\WC_Payment_Gateway> $gateways
     * @return array<\WC_Payment_Gateway>
     */
    public function registerClassicGateways(array $gateways): array
    {
        $gateways[] = CryptoPay::class;
        return $gateways;
    }
}

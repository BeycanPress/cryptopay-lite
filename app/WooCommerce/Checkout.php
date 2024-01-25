<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce;

use BeycanPress\CryptoPayLite\Services;
use BeycanPress\CryptoPayLite\PluginHero\Helpers;

class Checkout
{
    use Helpers;

    /**
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_receipt_cryptopay_lite', array($this, 'init'), 1);
    }

    /**
     * @param int $orderId
     * @return void
     */
    // @phpcs:ignore
    public function init($orderId): void
    {
        $order = wc_get_order($orderId);

        if ($order->get_status() != 'pending') {
            wp_redirect($order->get_checkout_order_received_url());
        } else {
            echo Services::startPaymentProcess([
                'id' => (int) $order->get_id(),
                'amount' => (float) $order->get_total(),
                'currency' => strtoupper($order->get_currency()),
            ], 'woocommerce');
        }
    }
}

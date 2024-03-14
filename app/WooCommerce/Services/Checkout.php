<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Services;

// Classes
use BeycanPress\CryptoPayLite\Payment;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;

class Checkout extends \WC_Checkout
{
    /**
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_receipt_cryptopay_lite', [$this, 'init'], 1);
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function init(int $orderId): void
    {
        $order = wc_get_order($orderId);

        if ($order->get_status() != Status::PENDING->getValue()) {
            wp_redirect($order->get_checkout_order_received_url());
            exit();
        } else {
            $id = (int) $order->get_id();
            $amount = (float) $order->get_total();
            $currency = strtoupper($order->get_currency());

            echo (new Payment('woocommerce'))
            ->setOrder(OrderType::fromArray([
                'id' => $id,
                'amount' => $amount,
                'currency' => $currency,
            ]))->html(loading:true);
        }
    }
}

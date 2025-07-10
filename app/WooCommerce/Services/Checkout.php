<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Services;

// Classes
use BeycanPress\CryptoPayLite\Payment;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;

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

        if ('pending' != $order->get_status()) {
            admin_init($order->get_checkout_order_received_url());
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

    /**
     * @param int $userId
     * @param array<mixed> $data
     * @return \WC_Order
     */
    public function createOrder(int $userId, array $data): \WC_Order
    {
        $this->update_session($data);
        $this->process_customer($data);
        $orderId = $this->create_order($data);
        $order = wc_get_order($orderId);

        if (is_wp_error($orderId)) {
            throw new \Exception($orderId->get_error_message());
        }

        if (!$order) {
            throw new \Exception(__('Unable to create order.', 'woocommerce'));
        }

        do_action('woocommerce_checkout_order_processed', $orderId, $data, $order);

        $order->update_status('wc-on-hold');
        $order->calculate_totals();
        $order->update_meta_data(
            '_customer_user',
            $userId
        );
        $order->add_order_note(
            esc_html__(
                'Customer has chosen CryptoPay payment method, payment is pending.',
                'cryptopay'
            )
        );
        WC()->session->set(
            'cp_order_id',
            $orderId
        );
        WC()->session->set(
            'order_awaiting_payment',
            $orderId
        );
        wc_empty_cart();
        return $order;
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Http\Response;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;

class Payment
{
    /**
     * @return void
     */
    public function __construct()
    {
        new Checkout();

        // Redefine the order in the init process to avoid manipulation
        Hook::addFilter('init_woocommerce', [$this, 'init']);

        // WooCommerce hooks
        add_action('woocommerce_checkout_order_processed', [$this, 'orderProcessed'], 105, 3);

        // CryptoPay Payment Hooks
        Hook::addFilter('payment_redirect_urls_woocommerce', [$this, 'paymentRedirectUrls']);
        Hook::addFilter('before_payment_started_woocommerce', [$this, 'beforePaymentStarted']);
        Hook::addAction('payment_finished_woocommerce', [$this, 'paymentFinished']);
    }

    /**
     * @param PaymentDataType $data
     * @return PaymentDataType
     */
    public function init(PaymentDataType $data): PaymentDataType
    {
        $paymentCurrency = $data->getOrder()->getPaymentCurrency();

        if ($data->getOrder()->getId()) {
            $order = wc_get_order($data->getOrder()->getId());

            $data->setOrder(OrderType::fromArray([
                'id' => $order->get_id(),
                'amount' => $order->get_total(),
                'currency' => $order->get_currency(),
                'paymentCurrency' => $paymentCurrency?->toArray()
            ]));
        }

        return $data;
    }

    /**
     * @param integer $orderId
     * @param array<mixed> $data
     * @param object $order
     * @return void
     */
    public function orderProcessed(int $orderId, array $data, object $order): void
    {
        /** @var \WC_Order $order */
        if ('cryptopay' == $order->get_payment_method() && function_exists('wcs_get_subscriptions_for_order')) {
            /** @var \WC_Order_Item $item */
            foreach ($order->get_items() as $item) {
                $subs = wcs_get_subscriptions_for_order($item->get_order_id(), ['order_type' => 'any']);
                if (!empty($subs)) {
                    /** @var \WC_Subscription $sub */
                    foreach ($subs as $sub) {
                        $sub->set_requires_manual_renewal(true);
                        $sub->save();
                    }
                }
            }
        }
    }

    /**
     * @param PaymentDataType $data
     * @return array<string>
     */
    public function paymentRedirectUrls(PaymentDataType $data): array
    {
        $order = wc_get_order($data->getOrder()->getId());
        return [
            'success' => $order->get_checkout_order_received_url(),
            'failed' => $order->get_view_order_url()
        ];
    }

    /**
     * @param PaymentDataType $data
     * @return PaymentDataType
     */
    public function beforePaymentStarted(PaymentDataType $data): PaymentDataType
    {
        try {
            // Set posted data
            $order = wc_get_order($data->getOrder()->getId());
            $order->update_status('wc-on-hold');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 'ORDER_CREATION_ERROR', $e->getTrace());
        }

        return $data;
    }

    /**
     * @param PaymentDataType $data
     * @return void
     */
    public function paymentFinished(PaymentDataType $data): void
    {
        if ($order = wc_get_order($data->getOrder()->getId())) {
            $order->update_meta_data(
                '_transaction_hash',
                $data->getHash()
            );

            $order->save();

            if ($data->getStatus()) {
                if ('wc-completed' == Helpers::getSetting('paymentCompleteOrderStatus')) {
                    $note = esc_html__('Your order is complete.', 'cryptopay');
                } else {
                    $note = esc_html__('Your order is processing.', 'cryptopay');
                }

                $order->payment_complete();
                $order->update_status(Helpers::getSetting('paymentCompleteOrderStatus'), $note);
            } else {
                $order->update_status(
                    'wc-failed',
                    esc_html__('Payment not verified via Blockchain!', 'cryptopay')
                );
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Services;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Models\OrderTransaction;
use BeycanPress\CryptoPayLite\WooCommerce\Gateway\CryptoPay;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;

class Details
{
    /**
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_view_order', [$this, 'frontend'], 4);
        add_action('woocommerce_thankyou_cryptopay_lite', [$this, 'frontend'], 1);
        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'backend'], 10);
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function frontend(int $orderId): void
    {
        $order = wc_get_order($orderId);

        if (CryptoPay::ID == $order->get_payment_method()) {
            $transaction = (new OrderTransaction())->getTransactionByOrderId($orderId);

            if ('pending' == $order->get_status() && !$transaction) {
                Helpers::viewEcho('woocommerce/pending', ['payUrl' => $order->get_checkout_payment_url(true)]);
            } elseif (!is_null($transaction)) {
                echo Helpers::getPaymentHtmlDetails($transaction);
            }
        }
    }

    /**
     * @param \WC_Order $order
     * @return void
     */
    public function backend(\WC_Order $order): void
    {
        if (CryptoPay::ID == $order->get_payment_method()) {
            $tx = (new OrderTransaction())->getTransactionByOrderId($order->get_id());
            if (!$tx) {
                return;
            }

            $txOrder = $tx->getOrder();
            $amount = $txOrder->getPaymentAmount();
            $currency = $txOrder->getPaymentCurrency();

            if ($txOrder->getDiscountRate()) {
                $realAmount = Helpers::fromPercent($amount, $txOrder->getDiscountRate(), $currency->getDecimals());
            } else {
                $realAmount = null;
            }

            Helpers::viewEcho('woocommerce/details', [
                'order' => $txOrder,
                'realAmount' => $realAmount,
                'transactionHash' => $tx->getHash(),
                'paymentAmount' => $txOrder->getPaymentAmount(),
                'blockchainNetwork' => $tx->getNetwork()->getName(),
                'paymentCurrency' => $txOrder->getPaymentCurrency()->getSymbol(),
            ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Services;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Payment as CryptoPay;
use BeycanPress\CryptoPayLite\PluginHero\Http\Request;
use BeycanPress\CryptoPayLite\PluginHero\Http\Response;
use BeycanPress\CryptoPayLite\WooCommerce\Gateway\CryptoPay as Gateway;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Network\CurrencyType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Transaction\ParamsType;

class Payment
{
    /**
     * @var Checkout
     */
    private Checkout $checkout;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->checkout = new Checkout();

        // Redefine the order in the init process to avoid manipulation
        Hook::addFilter('init_woocommerce', [$this, 'init']);

        // WooCommerce hooks
        add_action('woocommerce_checkout_order_processed', [$this, 'orderProcessed'], 105, 3);
        add_action('woocommerce_after_checkout_validation', [$this, 'checkoutValidation'], 10, 2);

        // CryptoPay Payment Hooks
        Hook::addFilter('payment_redirect_urls_woocommerce', [$this, 'paymentRedirectUrls']);
        Hook::addFilter('before_payment_started_woocommerce', [$this, 'beforePaymentStarted']);
        Hook::addFilter('before_payment_finished_woocommerce', [$this, 'beforePaymentFinished']);
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
        if (Gateway::ID == $order->get_payment_method() && function_exists('wcs_get_subscriptions_for_order')) {
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
     * @param array<mixed> $data
     * @param object $errors
     * @return void
     */
    public function checkoutValidation(array $data, object $errors): void
    {
        $paymentMethod = WC()->session->get('chosen_payment_method');
        if (
            Gateway::ID == $paymentMethod && wp_doing_ajax() &&
            'checkout' == Helpers::getSetting('paymentReceivingArea')
        ) {
            WC()->session->set('cp_posted_data', array_merge($_POST, $data));

            // if has error
            if (!empty($errors->errors)) {
                foreach ($errors->errors as $code => $messages) {
                    $data = $errors->get_error_data($code);
                    foreach ($messages as $message) {
                        wc_add_notice($message, 'error', $data);
                    }
                }

                if (!isset(WC()->session->reload_checkout)) {
                    $messages = wc_print_notices(true);
                }

                $data = [
                    'refresh' => isset(WC()->session->refresh_totals),
                    'reload'  => isset(WC()->session->reload_checkout),
                ];

                unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

                Response::error((isset($messages) ? $messages : ''), null, $data);
            }

            // if needed to init data
            if ('true' === ($_POST['cp_init'] ?? 'false')) {
                // get init data
                $request = new Request();
                $params = json_decode($request->getParam('cp_params'));
                $network = json_decode($request->getParam('cp_network'));
                $currency = json_decode($request->getParam('cp_currency'));

                $order = OrderType::fromArray([
                    'amount' => (float) \WC()->cart->total,
                    'currency' => get_woocommerce_currency()
                ])->setPaymentCurrency(CurrencyType::fromObject($currency));

                $init = (new CryptoPay('woocommerce'))->setOrder($order)
                ->setParams(ParamsType::fromObject($params))
                ->init(NetworkType::fromObject($network));

                Response::success(esc_html__('Success', 'cryptopay'), [
                    'init' => $init->prepareForJsSide()
                ]);
            }

            Response::success();
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
            $_POST = (array) $data->getDynamicData()->get('wcForm');

            if (!$data->getOrder()->getId()) {
                $postedData = WC()->session->get('cp_posted_data');
                $order = $this->checkout->createOrder($data->getUserId(), $postedData);
                $data->getOrder()->setId($order->get_id());
                $data->getDynamicData()->set('order', [
                    'id' => $data->getOrder()->getId(),
                ]);
            } else {
                $order = wc_get_order($data->getOrder()->getId());
                $order->update_status('wc-on-hold');
            }
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 'ORDER_CREATION_ERROR', $e->getTrace());
        }

        return $data;
    }

    /**
     * @param PaymentDataType $data
     * @return PaymentDataType
     */
    public function beforePaymentFinished(PaymentDataType $data): PaymentDataType
    {
        if (!$data->getOrder()->getId()) {
            $data->getOrder()->setId($data->getDynamicData()->get('order.id'));
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

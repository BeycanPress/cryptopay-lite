<?php

declare(strict_types=1);

// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint

namespace BeycanPress\CryptoPayLite\WooCommerce\Gateway;

// Classes
use BeycanPress\CryptoPayLite\Helpers;

class CryptoPay extends \WC_Payment_Gateway
{
    /**
     * @var string
     */
    // @phpcs:ignore
    public $id = 'cryptopay_lite';

    /**
     * @var string
     */
    // @phpcs:ignore
    public $title;

    /**
     * @var string
     */
    // @phpcs:ignore
    public $description;

    /**
     * @var string
     */
    // @phpcs:ignore
    public $enabled;

    /**
     * @var string
     */
    // @phpcs:ignore
    public $method_title;

    /**
     * @var string
     */
    // @phpcs:ignore
    public $method_description;

    /**
     * @var string
     */
    // @phpcs:ignore
    public $order_button_text;

    /**
     * @var bool
     */
    // @phpcs:ignore
    public $has_fields;

    /**
     * @var array<string>
     */
    // @phpcs:ignore
    public $supports;

    /**
     * @var array<mixed>
     */
    // @phpcs:ignore
    public $form_fields;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->method_title = esc_html__('CryptoPay Lite', 'cryptopay_lite');
        $this->method_description = esc_html__(
            'With CryptoPay Lite, your customers can easily pay with their cryptocurrencies.',
            'cryptopay_lite'
        );

        // gateways can support subscriptions, saved payment methods,
        // but in this tutorial we begin with simple payments
        $this->supports = ['products'];

        // if subscription is activated, we need to add it to the supports
        if (Helpers::getSetting('acceptSubscriptionPayments')) {
            $this->supports[] = 'subscriptions';
        }

        // Method with all the options fields
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        $this->has_fields = false;
        $this->title = $this->get_option('title');
        $this->enabled = $this->get_option('enabled');
        $this->description = $this->get_option('description');
        $this->order_button_text = $this->get_option('order_button_text');

        // This action hook saves the settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * @param string $button
     * @return string
     */
    public function hidePlaceOrderButton(string $button): string
    {
        $paymentMethod = WC()->session->get('chosen_payment_method');

        if ($paymentMethod == $this->id) {
            $button = '';
        }

        return $button;
    }

    /**
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'       => esc_html__('Enable/Disable', 'cryptopay_lite'),
                'label'       => esc_html__('Enable', 'cryptopay_lite'),
                'type'        => 'checkbox',
                'default'     => 'no'
            ),
            'title' => array(
                'title'       => esc_html__('Title', 'cryptopay_lite'),
                'type'        => 'text',
                'description' => esc_html__(
                    'This controls the title which the user sees during checkout.',
                    'cryptopay_lite'
                ),
                'default'     => esc_html__('CryptoPay Lite', 'cryptopay_lite')
            ),
            'description' => array(
                'title'       => esc_html__('Description', 'cryptopay_lite'),
                'type'        => 'textarea',
                'description' => esc_html__(
                    'This controls the description which the user sees during checkout.',
                    'cryptopay_lite'
                ),
                'default'     => esc_html__(
                    'You can pay with supported networks and cryptocurrencies.',
                    'cryptopay_lite'
                ),
            ),
            'order_button_text' => array(
                'title'       => esc_html__('Order button text', 'cryptopay_lite'),
                'type'        => 'text',
                'description' => esc_html__('Pay button on the checkout page', 'cryptopay_lite'),
                'default'     => esc_html__('Proceed to CryptoPay Lite', 'cryptopay_lite'),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function get_icon(): string
    {
        $iconHtml = Helpers::view('woocommerce/icon', [
            'iconUrl' => Helpers::getImageUrl('icon.png')
        ]);

        return apply_filters('woocommerce_gateway_icon', $iconHtml, $this->id);
    }

    /**
     * @return void
     */
    public function payment_fields(): void
    {
        echo esc_html($this->description);
    }

    /**
     * @param int $orderId
     * @return array<mixed>
     */
    public function process_payment($orderId): array
    {
        global $woocommerce;
        $order = new \WC_Order($orderId);

        if ($order->get_total() == 0) {
            if (Helpers::getSetting('paymentCompleteOrderStatus') == 'wc-completed') {
                $note = esc_html__('Your order is complete.', 'cryptopay_lite');
            } else {
                $note = esc_html__('Your order is processing.', 'cryptopay_lite');
            }

            $order->payment_complete();

            $order->update_status(Helpers::getSetting('paymentCompleteOrderStatus'), $note);

            $order->add_order_note(esc_html__(
                'Was directly approved by CryptoPay Lite as the order amount was zero!',
                'cryptopay_lite'
            ));

            $url = $order->get_checkout_order_received_url();
        } else {
            $order->update_status('wc-pending', esc_html__('Payment is awaited.', 'cryptopay_lite'));

            $order->add_order_note(
                esc_html__('Customer has chosen CryptoPay payment method, payment is pending.', 'cryptopay_lite')
            );

            $url = $order->get_checkout_payment_url(true);
        }

        // Remove cart
        $woocommerce->cart->empty_cart();

        // Return thankyou redirect
        return array(
            'result' => 'success',
            'redirect' => $url
        );
    }
}

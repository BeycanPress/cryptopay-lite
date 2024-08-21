<?php

declare(strict_types=1);

// @phpcs:disable Squiz.NamingConventions.ValidVariableName
// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint

namespace BeycanPress\CryptoPayLite\WooCommerce\Gateway;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Payment;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;

class CryptoPay extends \WC_Payment_Gateway
{
    /**
     * @var string
     */
    public const ID = 'cryptopay_lite';

    /**
     * @var string
     */
    // @phpcs:ignore
    public $id;

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
        $this->id = self::ID;
        $this->method_title = esc_html__('CryptoPay Lite', 'cryptopay');
        $this->method_description = esc_html__(
            'With CryptoPay Lite, your customers can easily pay with their cryptocurrencies.',
            'cryptopay'
        );

        // gateways can support subscriptions, saved payment methods,
        // but in this tutorial we begin with simple payments
        $this->supports = ['products'];

        // if subscription is activated, we need to add it to the supports
        if (Helpers::getSetting('acceptSubscriptionPayments')) {
            $this->supports[] = 'subscriptions';
        }

        // If in checkout mode, we need to hide place order button and set has_fields to true
        $this->has_fields = 'checkout' == Helpers::getSetting('paymentReceivingArea');
        if ('checkout' == Helpers::getSetting('paymentReceivingArea') && !is_wc_endpoint_url()) {
            add_filter('woocommerce_order_button_html', [$this, 'hidePlaceOrderButton']);
        }

        // Method with all the options fields
        $this->init_form_fields();

        // Load the settings.
        /** @disregard */
        $this->init_settings();
        $this->has_fields = false;
        /** @disregard */
        $this->title = $this->get_option('title');
        /** @disregard */
        $this->enabled = $this->get_option('enabled');
        /** @disregard */
        $this->description = $this->get_option('description');
        /** @disregard */
        $this->order_button_text = $this->get_option('order_button_text');

        // If js not loaded yet, and user click on pay button, we need to show error if in checkout mode
        add_action('woocommerce_after_checkout_validation', [$this, 'checkPaymentReceivingArea'], 10, 2);
        // This action hook saves the settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
    }


    /**
     * @param string $button
     * @return string
     */
    public function hidePlaceOrderButton(string $button): string
    {
        $paymentMethod = WC()->session->get('chosen_payment_method');

        if ($paymentMethod == $this->id) {
            $button = $this->hideButtonOrShowOurButton($button);
        }

        return $button;
    }

    /**
     * @param string $button
     * @return string
     */
    public function hideButtonOrShowOurButton(string $button): string
    {
        if (boolval(Helpers::getSetting('checkoutProcessButtonOption'))) {
            $button = Helpers::view('button', [
                'buttonText' => $this->order_button_text
            ]);
        } else {
            $button = '';
        }

        return $button;
    }

    /**
     * @param array<mixed> $data
     * @param object $errors
     * @return void
     */
    public function checkPaymentReceivingArea(array $data, object $errors): void
    {
        $paymentMethod = WC()->session->get('chosen_payment_method');

        if ($paymentMethod == $this->id && 'checkout' == Helpers::getSetting('paymentReceivingArea')) {
            $errors->add('payment', esc_html__('Please wait for CryptoPay Lite to load!', 'cryptopay'));
        }
    }

    /**
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = [
            'enabled' => [
                'title'       => esc_html__('Enable/Disable', 'cryptopay'),
                'label'       => esc_html__('Enable', 'cryptopay'),
                'type'        => 'checkbox',
                'default'     => 'no'
            ],
            'title' => [
                'title'       => esc_html__('Title', 'cryptopay'),
                'type'        => 'text',
                'description' => esc_html__(
                    'This controls the title which the user sees during checkout.',
                    'cryptopay'
                ),
                'default'     => esc_html__('CryptoPay Lite', 'cryptopay')
            ],
            'description' => [
                'title'       => esc_html__('Description', 'cryptopay'),
                'type'        => 'textarea',
                'description' => esc_html__(
                    'This controls the description which the user sees during checkout.',
                    'cryptopay'
                ),
                'default'     => esc_html__(
                    'You can pay with supported networks and cryptocurrencies.',
                    'cryptopay'
                ),
            ],
            'order_button_text' => [
                'title'       => esc_html__('Order button text', 'cryptopay'),
                'type'        => 'text',
                'description' => esc_html__('Pay button on the checkout page', 'cryptopay'),
                'default'     => esc_html__('Proceed to CryptoPay Lite', 'cryptopay'),
            ],
        ];
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
     * @param bool $modal
     * @return string
     */
    private function startCryptoPay(bool $modal = false): string
    {
        $cp = (new Payment('woocommerce'))
        ->setOrder(OrderType::fromArray([
            'amount' => (float) \WC()->cart->total,
            'currency' => get_woocommerce_currency()
        ]))
        ->setAutoStart(false);

        if ($modal) {
            $cp = $cp->modal();
        } else {
            $cp = $cp->html(loading:true);
        }

        Helpers::addScript('checkout.min.js', [Helpers::getProp('mainJsKey', null)]);

        return $cp;
    }

    /**
     * @return void
     */
    private function cryptoPayButtonProcess(): void
    {
        Hook::addFilter('js_variables', function (array $vars): array {
            $vars['buttonProcess'] = true;
            return $vars;
        });
        add_action('wp_footer', function (): void {
            echo '<div style="z-index:99999999; position:relative;">'
                . $this->startCryptoPay(true) .
                '</div>';
        });
    }

    /**
     * @return void
     */
    public function payment_fields(): void
    {
        if ('checkout' == Helpers::getSetting('paymentReceivingArea') && !is_wc_endpoint_url()) {
            if (boolval(Helpers::getSetting('checkoutProcessButtonOption'))) {
                echo esc_html($this->description);
                $this->cryptoPayButtonProcess();
            } else {
                echo $this->startCryptoPay();
            }
        } else {
            echo esc_html($this->description);
        }
    }

    /**
     * @param int $orderId
     * @return array<mixed>
     */
    public function process_payment($orderId): array
    {
        global $woocommerce;
        $order = new \WC_Order($orderId);

        if (0 == $order->get_total()) {
            if ('wc-completed' == Helpers::getSetting('paymentCompleteOrderStatus')) {
                $note = esc_html__('Your order is complete.', 'cryptopay');
            } else {
                $note = esc_html__('Your order is processing.', 'cryptopay');
            }

            $order->payment_complete();

            $order->update_status(Helpers::getSetting('paymentCompleteOrderStatus'), $note);

            $order->add_order_note(esc_html__(
                'Was directly approved by CryptoPay Lite as the order amount was zero!',
                'cryptopay'
            ));

            $url = $order->get_checkout_order_received_url();
        } else {
            $order->update_status('wc-pending', esc_html__('Payment is awaited.', 'cryptopay'));

            $order->add_order_note(
                esc_html__('Customer has chosen CryptoPay payment method, payment is pending.', 'cryptopay')
            );

            $url = $order->get_checkout_payment_url(true);
        }

        // Remove cart
        $woocommerce->cart->empty_cart();

        // Return thankyou redirect
        return [
            'result' => 'success',
            'redirect' => $url
        ];
    }
}

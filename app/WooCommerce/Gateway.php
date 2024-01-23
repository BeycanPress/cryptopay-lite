<?php

namespace BeycanPress\CryptoPayLite\WooCommerce;

use \BeycanPress\CryptoPayLite\Settings;
use \BeycanPress\CryptoPayLite\PluginHero\Plugin;

class Gateway extends \WC_Payment_Gateway
{   
    /**
     * @var string
     */
    public const ID = 'cryptopay_lite';

    /**
     * @return void
     */
    public function __construct()
    {
        $this->id = self::ID;
        $this->method_title = esc_html__('CryptoPay Lite', 'cryptopay_lite');
        $this->method_description = esc_html__('With CryptoPay, your customers can easily pay with their cryptocurrencies.', 'cryptopay_lite');

        // gateways can support subscriptions, refunds, saved payment methods,
        // but in this tutorial we begin with simple payments
        $this->supports = ['products'];

        // Method with all the options fields
        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        $this->title = $this->get_option('title');
        $this->enabled = $this->get_option('enabled');
        $this->description = $this->get_option('description');
		$this->order_button_text = $this->get_option('order_button_text');

        // This action hook saves the settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * @return void
     */
    public function init_form_fields() : void
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
                'description' => esc_html__('This controls the title which the user sees during checkout.', 'cryptopay_lite'),
                'default'     => esc_html__('CryptoPay Lite', 'cryptopay_lite')
            ),
            'description' => array(
                'title'       => esc_html__('Description', 'cryptopay_lite'),
                'type'        => 'textarea',
                'description' => esc_html__('This controls the description which the user sees during checkout.', 'cryptopay_lite'),
                'default'     => esc_html__('You can pay with supported networks and cryptocurrencies.', 'cryptopay_lite'),
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
    public function get_icon() : string
    {
        $iconHtml = '<img src="'.esc_url(Plugin::$instance->getImageUrl('icon.png')).'" width="25" height="25">';
        return apply_filters('woocommerce_gateway_icon', $iconHtml, $this->id);
    }

    /**
     * @return void
     */
    public function payment_fields() : void
    {
        echo esc_html($this->description);
    }

    /**
     * @param int $orderId
     * @return array
     */
    public function process_payment($orderId) : array
    {
        global $woocommerce;
        $order = new \WC_Order($orderId);

        if ($order->get_total() == 0) {
            if (Settings::get('paymentCompleteOrderStatus') == 'wc-completed') {
                $note = esc_html__('Your order is complete.', 'cryptopay_lite');
            } else {
                $note = esc_html__('Your order is processing.', 'cryptopay_lite');
            }

            $order->payment_complete();

            $order->update_status(Settings::get('paymentCompleteOrderStatus'), $note);

            $order->add_order_note(esc_html__('Was directly approved by CryptoPay WooCommerce as the order amount was zero!', 'cryptopay_lite'));
            
            $url = $order->get_checkout_order_received_url();
        } else {
            $order->update_status('wc-pending', esc_html__( 'Payment is awaited.', 'cryptopay_lite'));

            $order->add_order_note(esc_html__('Customer has chosen CryptoPay Wallet payment method, payment is pending.', 'cryptopay_lite'));

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
<?php

namespace BeycanPress\CryptoPayLite\WooCommerce;

use \BeycanPress\Http\Response;
use \BeycanPress\CryptoPayLite\Services;
use \BeycanPress\CryptoPayLite\PluginHero\Hook;
use \BeycanPress\CryptoPayLite\PluginHero\Helpers;
use \BeycanPress\CryptoPayLite\Pages\TransactionPage;

class Register
{
    use Helpers;
    
    public function __construct()
    {   
        // Register WooCommerce
        if (function_exists('WC')) {
            Services::registerAddon('woocommerce');
            
            // Register gateways
            add_filter('woocommerce_payment_gateways', function($gateways) {
                $gateways[] = Gateway::class;
                return $gateways;
            });
            
            if (!is_admin()) {
                new Checkout();
            } else {
                new TransactionPage(
                    esc_html__('Order transactions', 'cryptopay_lite'),
                    'woocommerce',
                    2,
                    [
                        'orderId' => function($tx) {
                            return '<a href="'.get_edit_post_link($tx->orderId).'" target="_blank">'.$tx->orderId.'</a>';
                        }
                    ]
                );
            }

            Hook::addFilter('check_order_woocommerce', function(object $order) {
                if (!wc_get_order($order->id)) {
                    Response::error(esc_html__('The relevant order was not found!', 'cryptopay_lite'), 'ORDER_NOT_FOUND');
                }

                return $order;
            });

            Hook::addAction('payment_started_woocommerce', function(object $data) {
                $order = wc_get_order($data->order->id);
                $order->update_status('wc-on-hold');
            });

            Hook::addAction('payment_finished_woocommerce', function(object $data) {
                if ($order = wc_get_order($data->order->id)) {
                    $order->update_meta_data(
                        esc_html__('Blockchain network', 'cryptopay_lite'),
                        $data->network->name
                    );
            
                    $order->update_meta_data(
                        esc_html__('Transaction hash', 'cryptopay_lite'),
                        $data->hash
                    );
            
                    $order->update_meta_data(
                        esc_html__('Payment currency', 'cryptopay_lite'),
                        $data->order->paymentCurrency->symbol
                    );
            
                    $order->update_meta_data(
                        esc_html__('Payment price', 'cryptopay_lite'),
                        Services::toString($order->paymentPrice ?? $order->paymentAmount, $data->order->paymentCurrency->decimals)
                    );

                    $order->save();
                    
                    if ($data->status) {
                        if ($this->setting('paymentCompleteOrderStatus') == 'wc-completed') {
                            $note = esc_html__('Your order is complete.', 'cryptopay_lite');
                        } else {
                            $note = esc_html__('Your order is processing.', 'cryptopay_lite');
                        }
                        $order->payment_complete();
                        $order->update_status($this->setting('paymentCompleteOrderStatus'), $note);
                    } else {
                        $order->update_status('wc-failed', esc_html__('Payment not verified via Blockchain!', 'cryptopay_lite'));
                    }
                }
            });
    
            Hook::addFilter('payment_redirect_urls_woocommerce', function(object $data) {
                $order = wc_get_order($data->order->id);
                return [
                    'success' => $order->get_checkout_order_received_url(),
                    'failed' => $order->get_view_order_url()
                ];
            });
        }
    }
}
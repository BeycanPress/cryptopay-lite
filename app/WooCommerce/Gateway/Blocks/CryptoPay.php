<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\WooCommerce\Gateway\Blocks;

// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\WooCommerce\Gateway as WCCryptoPay;
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class CryptoPay extends AbstractPaymentMethodType
{
    /**
     * @var WCCryptoPay
     */
    // @phpcs:ignore
    private $gateway;

    /**
     * @var string
     */
    // @phpcs:ignore
    protected $name = 'cryptopay_lite';

    /**
     * @var array<string,mixed>
     */
    // @phpcs:ignore
    protected $settings = [];

    /**
     * @var string
     */
    private string $scriptId;

    /**
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_blocks_enqueue_checkout_block_scripts_after', [$this, 'anotherAssets']);
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->settings = get_option("woocommerce_{$this->name}_settings", []);
        $this->gateway = WC()->payment_gateways->payment_gateways()[$this->name];
    }

    /**
     * @return bool
     */
    public function is_active(): bool
    {
        return $this->gateway->is_available();
    }

    /**
     * @return array<string,mixed>
     */
    public function get_payment_method_data(): array
    {
        return [
            'name'     => $this->name,
            'label'    => $this->get_setting('title'),
            'icons'    => $this->get_payment_method_icons(),
            'content'  => $this->get_setting('description'),
            'button'   => $this->get_setting('order_button_text'),
            'supports' => array_filter($this->gateway->supports, [$this->gateway, 'supports'])
        ];
    }

    /**
     * @return array<array<string,string>>
     */
    public function get_payment_method_icons(): array
    {
        return [
            [
                'id'  => $this->name,
                'alt' => $this->get_setting('title'),
                'src' => Helpers::getImageUrl('icon.png')
            ]
        ];
    }

    /**
     * @return array<string>
     */
    public function get_payment_method_script_handles(): array
    {
        return [$this->scriptId = Helpers::registerScript('blocks.min.js')];
    }

    /**
     * @return void
     */
    public function anotherAssets(): void
    {
        if (wp_script_is($this->scriptId, 'registered')) {
            Helpers::addStyle('main.min.css');
        }
    }
}

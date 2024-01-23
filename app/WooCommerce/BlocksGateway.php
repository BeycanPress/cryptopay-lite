<?php

namespace BeycanPress\CryptoPayLite\WooCommerce;

use BeycanPress\CryptoPayLite\PluginHero\Plugin;
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class BlocksGateway extends AbstractPaymentMethodType
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array<string,mixed>
     */
    protected $settings = [];

    /**
     * @var string
     */
    private $scriptId = 'cryptopay-lite-blocks';

    /**
     * @return void
     */
    public function __construct()
    {
        $this->name = Gateway::ID;
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
            'content'   => $this->get_setting('description'),
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
                'src' => Plugin::$instance->pluginUrl . 'assets/images/icon.png'
            ]
        ];
    }

    /**
     * @return array<string>
     */
    public function get_payment_method_script_handles(): array
    {
        $this->scriptId = Plugin::$instance->registerScript('js/blocks.js');
        return [$this->scriptId];
    }

    /**
     * @return void
     */
    public function anotherAssets(): void
    {
        if (wp_script_is($this->scriptId, 'registered')) {
            Plugin::$instance->addStyle('main.css');
        }
    }
}

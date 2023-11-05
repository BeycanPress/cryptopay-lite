<?php

namespace BeycanPress\CryptoPayLite;

class Loader extends PluginHero\Plugin
{
    public function __construct($pluginFile)
    {
        parent::__construct([
            'pluginFile' => $pluginFile,
            'textDomain' => 'cryptopay_lite',
            'pluginKey' => 'cryptopay_lite',
            'settingKey' => 'cryptopay_lite_settings',
            'pluginVersion' => getCryptoLitePayVersion(),
        ]);
        
        $this->feedback(true, 'cryptopay-wc-lite');

        add_filter('plugin_action_links_' . plugin_basename($this->pluginFile), [$this, 'pluginActionLinks']);

        add_action('plugins_loaded', function() {
            $this->apiUrl = (new Api())->getUrl();
            new WooCommerce\Register();
        });
    }

    public function pluginActionLinks(array $links) : array
    {
        $links[] = '<a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=lite_version&utm_medium=plugins_list" style="color: #389e38;font-weight: bold;" target="_blank">' . __( 'Buy Premium', 'cryptopay_lite' ) . '</a>';
        $links[] = '<a href="' . admin_url( 'admin.php?page=cryptopay_lite_settings' ) . '">' . __( 'Settings', 'cryptopay_lite' ) . '</a>';
        $links[] = '<a href="https://beycanpress.gitbook.io/cryptopay-docs/" target="_blank">' . __( 'Documentation', 'cryptopay_lite' ) . '</a>';

        return $links;
    }

    public function adminProcess() : void
    {
        new Pages\HomePage();
        add_action('init', function(){
            new Settings();
        }, 9);
    }

    public static function activation() : void
    {
        (new Models\OrderTransaction())->createTable();
    }

    public static function uninstall() : void
    {
        $settings = get_option(self::$instance->settingKey);
        if (isset($settings['dds']) && $settings['dds']) {
            delete_option(self::$instance->settingKey);
            delete_option('woocommerce_cryptopay_lite_settings');
            (new Models\OrderTransaction())->drop();
        }
    }
}

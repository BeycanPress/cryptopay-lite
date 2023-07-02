<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\Http\Client;

class Loader extends PluginHero\Plugin
{
    public function __construct($pluginFile)
    {
        parent::__construct([
            'pluginFile' => $pluginFile,
            'textDomain' => 'cryptopay_lite',
            'pluginKey' => 'cryptopay_lite',
            'settingKey' => 'cryptopay_lite_settings',
            'pluginVersion' => '1.2.3',
        ]);
        
        $this->feedback();

        add_filter('plugin_row_meta', function(array $links, string $file) {
            if ($file === plugin_basename($this->pluginFile)) {
                $links[] = '<b><a href="https://bit.ly/cplitebuynow" target="_blank">' . __('Buy premium', 'cryptopay_lite') . '</a></b>';
            }
            return $links;
        }, 10, 2);

        add_action('plugins_loaded', function() {
            $this->apiUrl = (new Api())->getUrl();
            new WooCommerce\Register();
        });
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

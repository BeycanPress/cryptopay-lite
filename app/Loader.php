<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

class Loader extends PluginHero\Plugin
{
    /**
     * @param string $pluginFile
     */
    public function __construct(string $pluginFile)
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

        add_action('plugins_loaded', function (): void {
            $this->apiUrl = (new Api())->getUrl();
            new WooCommerce\Register();
        });
    }

    /**
     * @param array<string> $links
     * @return array<string>
     */
    public function pluginActionLinks(array $links): array
    {
        // @phpcs:disable
        $links[] = '<a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=lite_version&utm_medium=plugins_list" style="color: #389e38;font-weight: bold;" target="_blank">' . __('Buy Premium', 'cryptopay_lite') . '</a>';
        $links[] = '<a href="' . admin_url('admin.php?page=cryptopay_lite_settings') . '">' . __('Settings', 'cryptopay_lite') . '</a>';
        $links[] = '<a href="https://beycanpress.gitbook.io/cryptopay-docs/" target="_blank">' . __('Documentation', 'cryptopay_lite') . '</a>';
        // @phpcs:enable

        return $links;
    }

    /**
     * @return void
     */
    public function adminProcess(): void
    {
        new Pages\HomePage();
        add_action('init', function (): void {
            new Settings();
        }, 9);
    }

    /**
     * @return void
     */
    public static function activation(): void
    {
        (new Models\OrderTransaction())->createTable();
    }

    /**
     * @return void
     */
    public static function uninstall(): void
    {
        $settings = get_option(self::$instance->settingKey);
        if (isset($settings['dds']) && $settings['dds']) {
            delete_option(self::$instance->settingKey);
            delete_option('woocommerce_cryptopay_lite_settings');
            (new Models\OrderTransaction())->drop();
        }
    }
}

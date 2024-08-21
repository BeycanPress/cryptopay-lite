<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

class Loader extends PluginHero\Plugin
{
    /**
     * @param string $pluginFile
     * @return void
     */
    public function __construct(string $pluginFile)
    {
        parent::__construct([
            'pluginFile' => $pluginFile,
            'textDomain' => 'cryptopay',
            'pluginKey' => 'cryptopay_lite',
            'settingKey' => 'cryptopay_lite_settings',
        ]);

        Helpers::feedback(true, 'cryptopay-wc-lite');

        Helpers::setProp('debugging', boolval(Helpers::getSetting('debugging')));

        if (Helpers::getSetting('evmchainsWalletAddress')) {
            add_action('plugins_loaded', function (): void {
                new RestAPI();
                new Services\Initialize();
                new WooCommerce\Initialize();
            });
        } else {
            Helpers::adminNotice(
                __(
                    'CryptoPay Lite: Please enter your wallet address in the settings section for CryptoPay Lite run.',
                    'cryptopay'
                ),
                'error'
            );
        }

        add_filter(
            'plugin_action_links_' . plugin_basename(Helpers::getProp('pluginFile')),
            [$this, 'pluginActionLinks']
        );
    }

    /**
     * @param array<string,string> $links
     * @return array<string,string>
     */
    public function pluginActionLinks(array $links): array
    {
        // @phpcs:disable
        $links[] = '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugins_list" style="color: #389e38;font-weight: bold;" target="_blank">' . __('Buy Premium', 'cryptopay') . '</a>';
        $links[] = '<a href="' . admin_url('admin.php?page=cryptopay_lite_settings') . '">' . __('Settings', 'cryptopay') . '</a>';
        $links[] = '<a href="https://beycanpress.gitbook.io/cryptopay-docs/" target="_blank">' . __('Documentation', 'cryptopay') . '</a>';
        // @phpcs:enable

        return $links;
    }

    /**
     * @return void
     */
    public function adminProcess(): void
    {
        new Pages\HomePage();
        new Pages\Integrations();
        new Pages\PendingReminders();

        if (file_exists(Helpers::getProp('pluginDir') . '/debug.log')) {
            new Pages\DebugLogs();
        }

        add_action('init', function (): void {
            new Settings\Settings();
        }, 9);
    }

    /**
     * @return void
     */
    public static function activation(): void
    {
        try {
            (new Models\OrderTransaction())->createTable();
        } catch (\Throwable $th) {
            Helpers::debug($th->getMessage(), 'ERROR', $th);
        }
    }

    /**
     * @return void
     */
    public static function uninstall(): void
    {
        if (Helpers::getSetting('dds')) {
            delete_option(Helpers::getProp('settingKey'));
            delete_option('woocommerce_cryptopay_lite_settings');
            (new Models\OrderTransaction())->drop();
        }
    }
}

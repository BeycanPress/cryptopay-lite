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
        }

        if (is_admin()) {
            add_action('init', [$this, 'setupNotice'], 9);
        }

        add_filter(
            'plugin_action_links_' . plugin_basename(Helpers::getProp('pluginFile')),
            [$this, 'pluginActionLinks']
        );
    }

    /**
     * Tells the merchant when CryptoPay will not show up at checkout, and sends
     * them somewhere that fixes it rather than just naming the problem.
     * @return void
     */
    public function setupNotice(): void
    {
        // @phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';

        // No point nagging someone who is already standing in the wizard.
        if (Pages\SetupWizard::SLUG === $page || Services\SetupStatus::isReady()) {
            return;
        }

        $failing = Services\SetupStatus::failingLabels();

        // The labels come out of SetupStatus already escaped; escaping again here
        // would show a translator's apostrophe as a raw entity.
        Helpers::adminNotice(
            '<strong>' . esc_html__('CryptoPay Lite is not live at checkout yet.', 'cryptopay') . '</strong>'
            . CPL_BR .
            $failing[0]
            . CPL_BR2 .
            '<a href="' . esc_url(Pages\SetupWizard::wizardUrl()) . '" class="button button-primary">'
            . esc_html__('Finish setup', 'cryptopay') . '</a>',
            'error'
        );
    }

    /**
     * @param array<string,string> $links
     * @return array<string,string>
     */
    public function pluginActionLinks(array $links): array
    {
        // @phpcs:disable
        $links[] = '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugins_list" style="color: #389e38;font-weight: bold;" target="_blank">' . esc_html__('Buy Premium', 'cryptopay') . '</a>';
        $links[] = '<a href="' . admin_url('admin.php?page=cryptopay_lite_settings') . '">' . esc_html__('Settings', 'cryptopay') . '</a>';
        $links[] = '<a href="https://beycanpress.gitbook.io/cryptopay-docs/" target="_blank">' . esc_html__('Documentation', 'cryptopay') . '</a>';
        // @phpcs:enable

        return $links;
    }

    /**
     * @return void
     */
    public function adminProcess(): void
    {
        add_action('init', function (): void {
            new Pages\HomePage();
            new Pages\SetupWizard();
            new Pages\Integrations();
            new Pages\PendingReminders();

            if (file_exists(Helpers::getProp('pluginDir') . '/debug.log')) {
                new Pages\DebugLogs();
            }
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

        // Only walk first-time installs through setup; anyone who already finished
        // it once is just reactivating and does not need to be sent round again.
        if (!get_option(Pages\SetupWizard::COMPLETED_OPTION)) {
            update_option(Pages\SetupWizard::REDIRECT_OPTION, true);
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

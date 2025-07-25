<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable WordPress.Security.NonceVerification.Missing
// @phpcs:disable WordPress.Security.NonceVerification.Recommended

if (!function_exists('WP_Filesystem')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

WP_Filesystem();

abstract class Plugin
{
    /**
     * @var Plugin
     */
    public static Plugin $i;

    /**
     * @var Plugin
     */
    public static Plugin $instance;

    /**
     * @var object
     */
    public static object $properties;

    /**
     * @param array<mixed> $properties
     * @return mixed
     */
    public function __construct(array $properties)
    {
        self::$i = self::$instance = $this;

        $pluginData = Helpers::getPluginData($properties['pluginFile']);

        $phDir = trailingslashit(__DIR__);
        self::$properties = (object) array_merge($properties, [
            'phDir' => $phDir,
            'phVersion' => '0.6.0',
            'pluginData' => $pluginData,
            'pluginVersion' => $pluginData->Version, // phpcs:ignore
            'pluginUrl' => plugin_dir_url($properties['pluginFile']),
            'pluginDir' => plugin_dir_path($properties['pluginFile']),
        ]);

        if (file_exists(Helpers::getProp('pluginDir') . 'vendor/beycanpress/csf/csf.php')) {
            require_once Helpers::getProp('pluginDir') . 'vendor/beycanpress/csf/csf.php';
        } elseif (file_exists(__DIR__ . '/vendor/beycanpress/csf/csf.php')) {
            require_once __DIR__ . '/vendor/beycanpress/csf/csf.php';
        }

        $this->localization();

        if (is_admin()) {
            if (method_exists($this, 'adminProcess')) {
                /** @disregard */
                $this->adminProcess();
            }
            if (method_exists($this, 'adminScripts')) {
                add_action('admin_enqueue_scripts', [$this, 'adminScripts']);
            }
        } else {
            if (method_exists($this, 'frontendProcess')) {
                /** @disregard */
                $this->frontendProcess();
            }

            if (method_exists($this, 'frontendScripts')) {
                add_action('wp_enqueue_scripts', [$this, 'frontendScripts']);
            }
        }

        if (method_exists($this, 'activation')) {
            register_activation_hook(Helpers::getProp('pluginFile'), [get_called_class(), 'activation']);
        }

        if (method_exists($this, 'deactivation')) {
            register_deactivation_hook(Helpers::getProp('pluginFile'), [get_called_class(), 'deactivation']);
        }

        if (method_exists($this, 'uninstall')) {
            register_uninstall_hook(Helpers::getProp('pluginFile'), [get_called_class(), 'uninstall']);
        }

        Helpers::ajaxAction($this, 'bpDismissNotice');

        add_action('admin_init', function (): void {
            new Plugins();
        });
    }

    /**
     * @return void
     */
    public function bpDismissNotice(): void
    {
        if (isset($_POST['id'])) {
            $dismissed = get_option('bp_dismissed_notices', []);
            $dismissed[] = sanitize_text_field($_POST['id']);
            update_option('bp_dismissed_notices', array_unique($dismissed));
        }
        wp_die();
    }

    /**
     * @return Plugin
     */
    public static function getInstance(): Plugin
    {
        return self::$instance;
    }

    /**
     * @return string
     */
    public static function getKey(): string
    {
        return Helpers::getProp('pluginKey');
    }

    /**
     * @return void
     */
    private function localization(): void
    {
        global $wp_filesystem;

        $languagesFolder = Helpers::getProp('pluginDir') . 'languages';

        if ($textDomain = Helpers::getProp('textDomain')) {
            if (!is_dir($languagesFolder)) {
                $wp_filesystem->mkdir($languagesFolder);
            }
            add_action('init', function () use ($languagesFolder, $textDomain): void {
                load_plugin_textdomain($textDomain, false, $languagesFolder);
            }, 8);
        } else {
            if (is_dir($languagesFolder)) {
                $wp_filesystem->delete($languagesFolder, true);
            }
        }
    }
}

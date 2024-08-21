<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

// @phpcs:disable PSR1.Files.SideEffects

if (!function_exists('WP_Filesystem')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

WP_Filesystem();

class Plugins extends Page
{
    /**
     * @var string
     */
    private string $apiUrl = 'https://services.beycanpress.com/wp-json/general-data/';

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        global $wp_filesystem;

        if (!isset($GLOBALS['beycanpress-plugins'])) {
            $GLOBALS['beycanpress-plugins'] = true;
            if (!file_exists(Helpers::getProp('pluginDir') . 'assets/images/beycanpress.png')) {
                if (!is_dir(Helpers::getProp('pluginDir') . 'assets')) {
                    $wp_filesystem->mkdir(Helpers::getProp('pluginDir') . 'assets');
                }
                if (!is_dir(Helpers::getProp('pluginDir') . 'assets/images')) {
                    $wp_filesystem->mkdir(Helpers::getProp('pluginDir') . 'assets/images');
                }
                $wp_filesystem->put_contents(
                    Helpers::getProp('pluginDir') . 'assets/images/beycanpress.png',
                    $wp_filesystem->get_contents(
                        Helpers::getProp('pluginDir') . 'app/PluginHero/templates/beycanpress.png'
                    )
                );
            }

            parent::__construct([
                'pageName' => esc_html__('BeycanPress Plugins'),
                'subMenuPageName' => esc_html__('BeycanPress Plugins'),
                'slug' => 'beycanpress-plugins',
                'icon' => $this->iconUrl()
            ]);
        }
    }

    /**
     * @return void
     */
    public function page(): void
    {
        $res = wp_remote_retrieve_body(wp_remote_get($this->apiUrl . 'get-plugins'));
        $res = json_decode(str_replace(['<p>', '</p>'], '', $res));
        Helpers::echoTemplate('plugins', [
            'plugins' => $res->data->plugins
        ]);
    }

    /**
     * @return string
     */
    private function iconUrl(): string
    {
        return Helpers::getProp('pluginUrl') . 'assets/images/beycanpress.png';
    }
}

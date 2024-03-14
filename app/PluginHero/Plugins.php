<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

class Plugins extends Page
{
    /**
     * @var string
     */
    private string $apiUrl = 'https://beycanpress.com/wp-json/bp-api/';

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        if (!isset($GLOBALS['beycanpress-plugins'])) {
            $GLOBALS['beycanpress-plugins'] = true;
            if (!file_exists(Helpers::getProp('pluginDir') . 'assets/images/beycanpress.png')) {
                if (!is_dir(Helpers::getProp('pluginDir') . 'assets')) {
                    mkdir(Helpers::getProp('pluginDir') . 'assets');
                }
                if (!is_dir(Helpers::getProp('pluginDir') . 'assets/images')) {
                    mkdir(Helpers::getProp('pluginDir') . 'assets/images');
                }
                file_put_contents(
                    Helpers::getProp('pluginDir') . 'assets/images/beycanpress.png',
                    file_get_contents(Helpers::getProp('pluginDir') . 'app/PluginHero/templates/beycanpress.png')
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
        $res = wp_remote_retrieve_body(wp_remote_get($this->apiUrl . 'general-products'));
        $res = json_decode(str_replace(['<p>', '</p>'], '', $res));
        Helpers::echoTemplate('plugins', [
            'plugins' => $res->data->products
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

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

// @phpcs:disable WordPress.Security.NonceVerification.Missing
// @phpcs:disable WordPress.Security.NonceVerification.Recommended

use CSF;

abstract class Setting
{
    /**
     * @var array<mixed>
     */
    private static array $params;

    /**
     * @var string
     */
    public static string $prefix;

    /**
     * @var array<mixed>|null
     */
    private static ?array $data = null;

    /**
     * @param string $title
     * @param string|null $parent
     * @param array<mixed> $params
     * @return void
     */
    public function __construct(string $title, string $parent = null, array $params = [])
    {
        self::$prefix = self::getSettingKey();

        self::$params = array_merge([
            'framework_title'         => $title . ' <small>By BeycanPress</small>',

            // menu settings
            'menu_title'              => $title,
            'menu_slug'               => self::$prefix,
            'menu_capability'         => 'manage_options',
            'menu_position'           => 999,
            'menu_hidden'             => false,

            // menu extras
            'show_bar_menu'           => false,
            'show_sub_menu'           => false,
            'show_network_menu'       => true,
            'show_in_customizer'      => false,

            'show_search'             => true,
            'show_reset_all'          => true,
            'show_reset_section'      => true,
            'show_footer'             => true,
            'show_all_options'        => true,
            'sticky_header'           => true,
            'save_defaults'           => true,
            'ajax_save'               => true,

            // database model
            'transient_time'          => 0,

            // contextual help
            'contextual_help'         => [],

            // typography options
            'enqueue_webfont'         => false,
            'async_webfont'           => false,

            // others
            'output_css'              => false,

            // theme
            'theme'                   => 'dark',

            // external default values
            'defaults'                => [],
        ], $params);

        if (!is_null($parent)) {
            self::$params['menu_type'] = 'submenu';
            self::$params['menu_parent'] = $parent;
        }

        CSF::createOptions(self::$prefix, self::$params);
    }

    /**
     * @param array<mixed> $params
     * @return void
     */
    public static function createSection(array $params): void
    {
        CSF::createSection(self::$prefix, $params);
    }

    /**
     * @return string
     */
    public static function getSettingKey(): string
    {
        return Helpers::getProp('settingKey', null) ?? (Helpers::getProp('pluginKey') . '_settings');
    }

    /**
     * Easy use for get_option
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::getAll();
        $val = $settings[$key] ?? null;
        return $val ? $val : $default;
    }

    /**
     * @return array<mixed>
     */
    public static function getAll(): array
    {
        if (is_null(self::$data)) {
            $data = get_option(self::getSettingKey(), []);
            self::$data = is_array($data) ? $data : [];
        }

        return self::$data;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function update(string $key, mixed $value): void
    {
        self::getAll();
        self::$data[$key] = $value;
        update_option(self::getSettingKey(), self::$data);
    }
}

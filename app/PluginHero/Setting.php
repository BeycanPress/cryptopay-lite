<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

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
    private static string $prefix;

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
        self::$prefix = Helpers::getProp('settingKey');

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
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function live(string $key, mixed $default = null): mixed
    {
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            return null;
        }

        if (self::$params['ajax_save'] ?? true) {
            $action = 'csf_' . Helpers::getProp('settingKey') . '_ajax_save';

            if (!isset($_POST[$action]) && !isset($_POST['data'])) {
                return null;
            }

            $data = json_decode(stripslashes($_POST['data']), true);

            $settings = $data[Helpers::getProp('settingKey')];
        } else {
            if (!isset($_GET['page'])) {
                return null;
            }

            if (Helpers::getProp('settingKey') !== $_GET['page']) {
                return null;
            }

            $settings = $_POST[Helpers::getProp('settingKey')];
        }

        return $settings[$key] ?? $default;
    }

    /**
     * Easy use for get_option
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::getAll()[$key] ?? $default;
    }

    /**
     * @return array<mixed>
     */
    public static function getAll(): array
    {
        if (is_null(self::$data)) {
            $data = get_option(Helpers::getProp('settingKey'), []);
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
        update_option(Helpers::getProp('settingKey'), self::$data);
    }
}

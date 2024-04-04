<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

use BeycanPress\CryptoPayLite\PluginHero\Page;
use BeycanPress\CryptoPayLite\PluginHero\Addon;
use BeycanPress\CryptoPayLite\PluginHero\Plugin;
use BeycanPress\CryptoPayLite\PluginHero\BaseAPI;

trait Data
{
    /**
     * @var array<string,BaseAPI>
     */
    private static array $apis = [];

    /**
     * @var array<string,Page|array<string,Page>>
     */
    private static array $pages = [];

    /**
     * @var array<string,\Closure>
     */
    private static array $funcs = [];

    /**
     * @var array<string,Addon>
     */
    private static array $addons = [];

    /**
     * @return object
     */
    public static function showProps(): object
    {
        return Plugin::$properties;
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public static function getProp(string $property, mixed $default = 'no-default'): mixed
    {
        if (isset(Plugin::$properties->$property)) {
            return Plugin::$properties->$property;
        } elseif ('no-default' !== $default) {
            return $default;
        } else {
            throw new \Exception('Property not found: ' . $property);
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     * @param bool $force
     * @return void
     */
    public static function setProp(string $property, mixed $value, bool $force = false): void
    {
        if (!$force && isset(Plugin::$properties->{$property})) {
            throw new \Exception('Property already exists: ' . $property);
        }

        Plugin::$properties->{$property} = $value;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public static function updateProp(string $property, mixed $value): void
    {
        if (isset(Plugin::$properties->{$property})) {
            Plugin::$properties->{$property} = $value;
        } else {
            throw new \Exception('Property not found: ' . $property);
        }
    }

    /**
     * @param string $name
     * @param \Closure $closure
     * @return void
     */
    public static function addFunc(string $name, \Closure $closure): void
    {
        if (!isset(self::$funcs[$name])) {
            self::$funcs[$name] = $closure;
        } else {
            throw new \Exception('Function already exists: ' . $name);
        }
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return mixed
     */
    public static function runFunc(string $name, mixed ...$args): mixed
    {
        if (isset(self::$funcs[$name])) {
            $closure = self::$funcs[$name];
            return $closure(...$args);
        } else {
            throw new \Exception('Function not found: ' . $name);
        }
    }

    /**
     *
     * @param Page $page
     * @param string|null $slug
     * @return void
     */
    public static function addPage(Page $page, ?string $slug = null): void
    {
        $name = (new \ReflectionClass($page))->getShortName();
        if (!isset(self::$pages[$name]) || $slug) {
            if ($slug) {
                if (!isset(self::$pages[$name])) {
                    self::$pages[$name] = [];
                }

                if (!isset(self::$pages[$name][$slug])) {
                    self::$pages[$name][$slug] = $page;
                } else {
                    throw new \Exception('Page already exists: ' . $name . ' - ' . $slug);
                }
            } else {
                self::$pages[$name] = $page;
            }
        } else {
            throw new \Exception('Page already exists: ' . $name);
        }
    }

    /**
     * @param string $name
     * @param string|null $slug
     * @return Page
     */
    public static function getPage(string $name, ?string $slug = null): Page
    {
        if (isset(self::$pages[$name])) {
            if (is_array(self::$pages[$name])) {
                if (1 == count(self::$pages[$name])) {
                    return array_values(self::$pages[$name])[0];
                }

                if (isset(self::$pages[$name][$slug])) {
                    return self::$pages[$name][$slug];
                } else {
                    throw new \Exception('Page not found: ' . $name . ' - ' . $slug);
                }
            }
            return self::$pages[$name];
        } else {
            throw new \Exception('Page not found: ' . $name);
        }
    }

    /**
     * @param BaseAPI $api
     * @return void
     */
    public static function addAPI(BaseAPI $api): void
    {
        $name = (new \ReflectionClass($api))->getShortName();
        if (!isset(self::$apis[$name])) {
            self::$apis[$name] = $api;
        } else {
            throw new \Exception('Page already exists: ' . $name);
        }
    }

    /**
     * @param string $name
     * @return BaseAPI
     */
    public static function getAPI(string $name): BaseAPI
    {
        if (isset(self::$apis[$name])) {
            return self::$apis[$name];
        } else {
            throw new \Exception('API not found: ' . $name);
        }
    }

    /**
     * @param string $key
     * @param string $file
     * @return void
     */
    public static function registerAddon(string $key, string $file): void
    {
        if (!isset(self::$addons[$key])) {
            self::$addons[$key] = new Addon($key, $file);
        } else {
            throw new \Exception('Addon already registered:' . $key);
        }
    }

    /**
     * @param string $key
     * @return Addon
     */
    public static function getAddon(string $key): Addon
    {
        if (isset(self::$addons[$key])) {
            return self::$addons[$key];
        } else {
            throw new \Exception('Addon not found:' . $key);
        }
    }

    /**
     * @param string $file
     * @return object
     */
    public static function getPluginData(string $file): object
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $pluginData = (object) get_plugin_data($file);

        if (!isset($pluginData->Slug)) { // phpcs:ignore
            $pluginData->Slug = self::getPluginSlug($file); // phpcs:ignore
        }

        return $pluginData;
    }

    /**
     * @param string $file
     * @return string
     */
    public static function getPluginSlug(string $file): string
    {
        return plugin_basename($file);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return object|null
     */
    public static function getUserBy(string $field, mixed $value): ?object
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM $wpdb->users WHERE $field = '$value'");
    }
}

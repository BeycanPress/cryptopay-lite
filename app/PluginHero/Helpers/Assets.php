<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

trait Assets
{
        /**
     * @param string $path
     * @param string $baseUrl
     * @param string $subAsset
     * @return array<string>
     */
    public static function createAssetParams(string $path, string $baseUrl, string $subAsset): array
    {
        $f = substr($path, 0, 1);
        $key = str_replace('/', '-', $path);
        $ver = self::getProp('pluginVersion');
        $subAsset = 'assets/' . $subAsset . '/';
        $url = $baseUrl . ('/' === $f ? 'assets' : $subAsset) . $path;
        $key = self::getProp('pluginKey') . '-' . preg_replace('/\..*$/', '', $key);

        return [$key, $url, $ver];
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public static function registerScript(string $path, array $deps = []): string
    {
        [$key, $url, $ver] = self::createAssetParams($path, self::getProp('pluginUrl'), 'js');

        wp_register_script(
            $key,
            $url,
            $deps,
            $ver,
            true
        );

        return $key;
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public static function addScript(string $path, array $deps = []): string
    {
        [$key, $url, $ver] = self::createAssetParams($path, self::getProp('pluginUrl'), 'js');

        wp_enqueue_script(
            $key,
            $url,
            $deps,
            $ver,
            true
        );

        return $key;
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public static function registerStyle(string $path, array $deps = []): string
    {
        [$key, $url, $ver] = self::createAssetParams($path, self::getProp('pluginUrl'), 'css');

        wp_register_style(
            $key,
            $url,
            $deps,
            $ver
        );

        return $key;
    }

    /**
     * @param string $path
     * @param array<string> $deps
     * @return string
     */
    public static function addStyle(string $path, array $deps = []): string
    {
        [$key, $url, $ver] = self::createAssetParams($path, self::getProp('pluginUrl'), 'css');

        wp_enqueue_style(
            $key,
            $url,
            $deps,
            $ver
        );

        return $key;
    }
}

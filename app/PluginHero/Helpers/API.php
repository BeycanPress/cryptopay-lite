<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

trait API
{
    /**
     * Ajax action hooks
     * @param object $class class object
     * @param string $action ajax function name
     * @return void
     */
    public static function ajaxAction(object $class, string $action): void
    {
        add_action('wp_ajax_' . $action, [$class, $action]);
        add_action('wp_ajax_nopriv_' . $action, [$class, $action]);
    }

    /**
     * New nonce create method
     * @param string|null $externalKey
     * @return string
     */
    public static function createNewNonce(?string $externalKey = null): string
    {
        $key = self::getProp('pluginKey') . '_nonce' . $externalKey;
        return wp_create_nonce($key);
    }

    /**
     * Nonce control mehod
     * @param string|null $externalKey
     * @return void
     */
    public static function checkNonce(?string $externalKey = null): void
    {
        $key = self::getProp('pluginKey') . '_nonce' . $externalKey;
        check_ajax_referer($key, 'nonce');
    }

    /**
     * New nonce field create method
     * @param string|null $externalKey
     * @return void
     */
    public static function createNewNonceField(?string $externalKey = null): void
    {
        $key = self::getProp('pluginKey') . '_nonce' . $externalKey;
        wp_nonce_field($key, 'nonce');
    }

    /**
     * Nonce field control method
     * @param string|null $externalKey
     * @return bool
     */
    public static function checkNonceField(?string $externalKey = null): bool
    {
        if (!isset($_POST['nonce'])) {
            return false;
        }

        $key = self::getProp('pluginKey') . '_nonce' . $externalKey;

        return @wp_verify_nonce(sanitize_text_field($_POST['nonce']), $key) ? true : false;
    }
}

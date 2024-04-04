<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable PSR12.Files.FileHeader
// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable Generic.Files.InlineHTML

/**
 * Plugin Name: CryptoPay Lite
 * Version:     2.1.4
 * Plugin URI:  https://beycanpress.com/cryptopay/
 * Description: All In One Cryptocurrency Payments for WordPress
 * Author:      BeycanPress LLC
 * Author URI:  https://beycanpress.com
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: cryptopay_lite
 * Domain Path: /languages
 * Tags: Cryptopay, Cryptocurrency, WooCommerce, WordPress, MetaMask, Trust, Binance, Wallet, Ethereum, Bitcoin, Binance smart chain, Payment, Plugin, Gateway
 * Requires at least: 5.0
 * Tested up to: 6.4.3
 * Requires PHP: 8.1
*/

/**
 * Define constants
 */
define('CPL_NL', "\n");
define('CPL_BR', '<br>');
define('CPL_BR2', '<br><br>');

/**
 * @return void
 */
add_action('before_woocommerce_init', function (): void {
    if (class_exists(Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

if (!function_exists('json_validate')) {
    /**
     * @param string $string
     * @return bool
     */
    function json_validate(string $string): bool
    {
        json_decode($string);
        return JSON_ERROR_NONE === json_last_error();
    }
}

require __DIR__ . '/vendor/autoload.php';
use BeycanPress\CryptoPayLite\PluginHero\Helpers;

$args = [
    'phpVersions' => 8.1,
    'extensions' => [
        'bcmath',
        'curl',
        'file_get_contents',
    ]
];

if (Helpers::createRequirementRules($args, __FILE__)) {
    define('CRYPTOPAY_LITE_LOADED', true);
    new BeycanPress\CryptoPayLite\Loader(__FILE__);
}

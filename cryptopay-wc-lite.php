<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable PSR12.Files.FileHeader
// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable Generic.Files.InlineHTML

/**
 * Plugin Name: CryptoPay Lite
 * Version:     2.0.0
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
 * Tested up to: 6.4.2
 * Requires PHP: 8.1
*/

add_action('admin_footer', function (): void {
    $count = get_option('cryptopay_premium_version_promotion');
    $date = get_option('cryptopay_premium_version_promotion_date');
    $count = $count ? $count : 0;
    if ($count < 3 && $date != date('Y-m-d')) {
        $count++;
        update_option('cryptopay_premium_version_promotion', $count);
        update_option('cryptopay_premium_version_promotion_date', date('Y-m-d'));
        ?>
        <div class="cp-video-modal">
            <div class="modal-content">
                <div class="close-btn">
                    X
                </div>
                <div>
                    <div class="information">
                        You've earned a 50% discount code to get CryptoPay Premium using the lite version. You can buy CryptoPay Premium with 50% discount with the "Buy premium" button now. Remember, the discount code is only for the first 100 customers.
                    </div>

                    <div class="discount-code-wrapper">
                        <div class="discount-code">
                            Discount code: FIRST100
                        </div>
                    </div>
                    
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/3vaoFL4XG10" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    <div class="buttons">
                        <a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=lite_version&utm_medium=popup" target="_blank" class="button"><?php echo __('Buy premium', 'cryptopay_lite'); ?></a>
                        <a href="https://beycanpress.com/cryptopay/?utm_source=lite_version&utm_medium=popup" target="_blank" class="button"><?php echo __('Review now', 'cryptopay_lite'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .cp-video-modal {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .cp-video-modal .modal-content  {
                box-sizing: border-box;
                width: auto;
                max-width: 560px;
                padding: 40px;
                background-color: #fff;
                position: relative;
                border-radius: 5px;
            }
            .information {
                font-size: 16px;
                font-weight: 500;
                text-align: center;
                margin-bottom: 20px;
            }
            .discount-code {
                font-size: 20px;
                font-weight: 600;
                text-align: center;
                background-color: #eee;
                padding: 10px;
                border-radius: 5px;
                margin: 0 auto;
            }
            .discount-code-wrapper {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
            @media screen and (max-width: 640px) {
                .cp-video-modal {
                    padding : 20px 0px;
                    height: 100%;
                }
                .cp-video-modal .modal-content {
                    width: calc(100% - 40px);
                    padding: 20px;
                    height: 100%;
                    overflow-y: scroll;
                }
            }
            .cp-video-modal .modal-content .close-btn {
                position: absolute;
                right: 10px;
                top: 10px;
                cursor: pointer;
                font-weight: 600;
                font-size: 20px;
                color: #000;
            }
            .cp-video-modal .modal-content .buttons {
                position: relative;
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .cp-video-modal .modal-content iframe {
                max-width: 100%;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                $('.cp-video-modal .close-btn').click(function() {
                    $('.cp-video-modal').hide();
                });
            });
        </script>
        <?php
    }
});

/**
 * @return string
 */
function getCryptoLitePayVersion(): string
{
    if (!function_exists('get_plugin_data')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    return get_plugin_data(__FILE__)['Version'];
}

/**
 * @return string
 */
function cryptoPayLiteGetPHPMajorVersion(): string
{
    $version = explode('.', PHP_VERSION);
    return $version[0] . '.' . $version[1];
}

$cryptoPayLiteTestedPHPVersions = array(8.1, 8.2);
if (!in_array(cryptoPayLiteGetPHPMajorVersion(), $cryptoPayLiteTestedPHPVersions)) {
    add_action('admin_notices', function () use ($cryptoPayLiteTestedPHPVersions): void {
        $class = 'notice notice-error';
        $message = 'CryptoPay Lite: has not been tested with your current PHP version ' . cryptoPayLiteGetPHPMajorVersion() . '. This means that errors may occur due to incompatibility or other reasons. CryptoPay Lite has been tested with PHP ' . implode(' or ', $cryptoPayLiteTestedPHPVersions) . ' versions. Please ask your server service provider to update your PHP version.';
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    });
}

/**
 * @return bool
 */
function cryptoLitPayCheckRequirements(): bool
{
    $status = true;

    if (!extension_loaded('bcmath')) {
        $status = false;
        add_action('admin_notices', function (): void {
            $class = 'notice notice-error';
            $message = 'CryptoPay Lite: BCMath PHP extension is not installed. So CryptoPay Lite has been disabled BCMath is a mathematical library that CryptoPay Lite needs and uses to verify blockchain transactions. Please visit <a href="https://www.php.net/manual/en/book.bc.php">https://www.php.net/manual/en/book.bc.php</a> for install assistance. You can ask your server service provider to install BCMath.';
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        });
    }

    if (!extension_loaded('curl')) {
        $status = false;
        add_action('admin_notices', function (): void {
            $class = 'notice notice-error';
            $message = 'CryptoPay Lite: cURL PHP extension is not installed. So CryptoPay Lite has been disabled cURL is a HTTP request library that CryptoPay Lite needs and uses to verify blockchain transactions. Please visit "' . (php_sapi_name() == 'cli' ? 'https://www.php.net/manual/en/book.curl.php' : '<a href="https://www.php.net/manual/en/book.curl.php">https://www.php.net/manual/en/book.curl.php</a>') . '" for install assistance. You can ask your server service provider to install cURL.';
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        });
    }

    return $status;
}

add_action('before_woocommerce_init', function (): void {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

if (cryptoLitPayCheckRequirements()) {
    require __DIR__ . '/vendor/autoload.php';
    new \BeycanPress\CryptoPayLite\Loader(__FILE__);
}

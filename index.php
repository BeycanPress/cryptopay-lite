<?php defined('ABSPATH') || exit;

/**
 * Plugin Name: CryptoPay Lite
 * Version:     1.2.6
 * Plugin URI:  https://beycanpress.com/cryptopay
 * Description: All In One Cryptocurrency Payments for WordPress lite version
 * Author:      BeycanPress
 * Author URI:  https://beycanpress.com
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.tr.html
 * Text Domain: cryptopay_lite
 * Domain Path: /languages
 * Tags: Cryptopay, Cryptocurrency, WooCommerce, WordPress, MetaMask, Trust, Binance, Wallet, Ethereum, Bitcoin, Binance smart chain, Payment, Plugin, Gateway
 * Requires at least: 5.0
 * Tested up to: 6.2.2
 * Requires PHP: 7.4
 * WC requires at least: 4.4
 * WC tested up to: 7.5
*/

add_action('admin_footer', function() {
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
                        <a href="https://bit.ly/cplitebuynow" target="_blank" class="button"><?php echo __('Buy premium', 'cryptopay_lite'); ?></a>
                        <a href="https://bit.ly/3pOiY25" target="_blank" class="button"><?php echo __('Review now', 'cryptopay_lite'); ?></a>
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

require __DIR__ . '/vendor/autoload.php';
new \BeycanPress\CryptoPayLite\Loader(__FILE__);
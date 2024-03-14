<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Settings;

// @phpcs:disable Generic.Files.LineLength 

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\PluginHero\Setting;

class Settings extends Setting
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->createFeedbackPage($parent = Helpers::getPage('HomePage')->getSlug());
        parent::__construct(esc_html__('Settings', 'cryptopay_lite'), $parent);

        $proMsg = '<div style="display:flex;align-items:center">' . sprintf(esc_html__('This is a pro feature => %s', 'cryptopay_lite'), '<a href="https://beycanpress.com/cryptopay/?utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . __('Buy pro', 'cryptopay_lite') . '</a>') . '</div><br>';

        self::createSection([
            'id'     => 'generalSettings',
            'title'  => esc_html__('General settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-cog',
            'fields' => [
                [
                    'id'      => 'dds',
                    'title'   => esc_html__('Data deletion status', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('This setting is passive come by default. You enable this setting. All data created by the plug-in will be deleted while removing the plug-in.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'debugging',
                    'title'   => esc_html__('Debugging', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => false,
                    'desc'    => esc_html__('The Debug menu will appear when this setting is turned on and the log file is created.', 'cryptopay_lite'),
                    'help'    => esc_html__('This setting has been added for the developer team rather than the users. If you open a support ticket to us due to a bug, we will use this setting to check the plugin progress.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'testnet',
                    'title'   => esc_html__('Testnet', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('When you activate this setting, CryptoPay starts working on testnets.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'theme',
                    'title'   => esc_html__('Theme', 'cryptopay_lite'),
                    'type'    => 'select',
                    'help'    => esc_html__('Payment process theme', 'cryptopay_lite'),
                    'options' => [
                        'light' => esc_html__('Light', 'cryptopay_lite'),
                        'dark' => esc_html__('Dark', 'cryptopay_lite')
                    ],
                    'default' => 'light',
                ],
                [
                    'id'      => 'mode',
                    'title'   => esc_html__('Mode', 'cryptopay_lite'),
                    'type'    => 'select',
                    'options' => [
                        'network' => esc_html__('Network', 'cryptopay_lite'),
                        'currency' => esc_html__('Currency', 'cryptopay_lite'),
                    ],
                    'default' => 'network',
                    'desc'    => esc_html__('You can choose the mode you want to use in the payment process. If you choose the network mode, the user will first choose the network and then the currency and then the wallet. If you choose the currency mode, the user will first choose the currency and then the wallet.', 'cryptopay_lite')
                ]
            ]
        ]);

        self::createSection([
            'id'     => 'wooCommerceSettings',
            'title'  => esc_html__('WooCommerce settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-cog',
            'fields' => [
                [
                    'id'      => 'acceptSubscriptionPayments',
                    'title'   => esc_html__('Accept subscription payments (Via manual renewal)', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'desc'    => esc_html__('It is possible to receive automatic payments in cryptocurrencies in various ways, but automatic payments will not be introduced because malicious customers can use this situation to steal the customer\'s funds.', 'cryptopay_lite'),
                    'help'    => esc_html__('CryptoPay will work directly if manual payments are enabled in the subscription setting. You can enable this setting for CryptoPay to work when this setting is off.', 'cryptopay_lite'),
                    'default' => false,
                ],
                [
                    'id'      => 'acceptInstantPayments',
                    'title'   => esc_html__('Accept instant payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('As with PayPal, a Buy with Crypto Pay button appears directly on the product page, and users can instantly create an order by paying directly with CryptoPay. ', 'cryptopay_lite') . sprintf(esc_html__('If the %s setting is active in WooCommerce settings, non-registered users can use instant payments.', 'cryptopay_lite'), '<a href="' . admin_url('admin.php?page=wc-settings&tab=account') . '" target="_blank">' . esc_html__('"Allow customers to place orders without an account"', 'cryptopay_lite') . '</a>'),
                    'default' => false,
                ],
                [
                    'id'      => 'paymentReceivingArea',
                    'title'   => esc_html__('Payment receiving area', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('With this setting, you can choose from where the user receives the payment. With the checkout option, payment will be taken directly from the checkout page before the order is created, and then the order will be created. After the order is created with the Order Pay option, payment will be received on the Order Pay page.', 'cryptopay_lite'),
                    'default' => 'orderPay'
                ],
                [
                    'id'      => 'paymentCompleteOrderStatus',
                    'title'   => esc_html__('Payment complete order status', 'cryptopay_lite'),
                    'type'    => 'select',
                    'help'    => esc_html__('The status to apply for WooCommerce order after payment is complete.', 'cryptopay_lite'),
                    'options' => [
                        'wc-completed' => esc_html__('Completed', 'cryptopay_lite'),
                        'wc-processing' => esc_html__('Processing', 'cryptopay_lite')
                    ],
                    'default' => 'wc-completed',
                ],
                [
                    'id'      => 'activateWooCommercePaymentGateway',
                    'title'   => esc_html__('Activate WooCommerce payment gateway', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => Helpers::view('components/link', [
                        'text' => esc_html__('Click to activate', 'cryptopay_lite'),
                        'url' => admin_url('admin.php?page=wc-settings&tab=checkout&section=cryptopay')
                    ])
                ]
            ]
        ]);

        EvmChains::initSettings();

        Settings::createSection([
            'id'     => 'bitcoin',
            'title'  => esc_html__('Bitcoin settings', 'cryptopay_lite'),
            'icon'   => 'fab fa-bitcoin',
            'fields' => [
                [
                    'id'      => 'bitcoinPayments',
                    'title'   => esc_html__('Bitcoin payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => esc_html__('CryptoPay supports all EVM-based networks by default, but you can start accepting payments from other blockchain networks by purchasing extra network support.', 'cryptopay_lite') . CPL_BR2 . '<a href="https://beycanpress.com/our-plugins/?categoryId=88&utm_source=plugin_settings&utm_medium=bitcoin_payments&utm_campaign=buy_custom_networks" target="_blank">' . esc_html__('Buy custom network supports', 'cryptopay_lite') . '</a>'
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'currencyDiscountsRates',
            'title'  => esc_html__('Currency discounts', 'cryptopay_lite'),
            'icon'   => 'fa fa-percent',
            'fields' => [
                [
                    'id' => 'currencyDiscountsRatesInfo',
                    'type' => 'content',
                    'content' => 'Currency discounts is a feature where you can define special discounts for certain currencies. For example, you have a token specific to your project or you are sponsored by any token project. In this case, you can give percentage discounts to your paying users to encourage them to pay with this token.
                    ' . CPL_BR2 . '
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/currency-discounts" target="_blank">' . esc_html__('Click for more information', 'cryptopay_lite') . '</a>',
                    'title' => esc_html__('What is a currency discounts?', 'cryptopay_lite')
                ],
                [
                    'id'           => 'discountRates',
                    'type'         => 'content',
                    'title'        => esc_html__('Currency discounts', 'cryptopay_lite'),
                    'button_title' => esc_html__('Add new', 'cryptopay_lite'),
                    'content'      => $proMsg . esc_html__('You can define shopping-specific discounts for tokens with the symbols of the currency.', 'cryptopay_lite'),
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'currencyConverter',
            'title'  => esc_html__('Currency converter', 'cryptopay_lite'),
            'icon'   => 'fas fa-dollar-sign',
            'fields' => [
                [
                    'id' => 'otherConverterLinks',
                    'type' => 'content',
                    'content' => 'Currently, in crypto payments, most people list prices in FIAT currencies, i.e. currencies such as USD, EUR. With the currency converter, we convert these currencies into the currency chosen by the user. By default the CryptoCompare API is available. If your token is listed on Coin Market Cap, Coin Gecko or DEXs. You can get suitable currency converter add-ons to get the price value of your token.
                    ' . CPL_BR2 . '
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/currency-converter" target="_blank">' . esc_html__('Click for more information', 'cryptopay_lite') . '</a>
                    ' . CPL_BR2 . '<a href="https://beycanpress.com/our-plugins/?categoryId=167&utm_source=plugin_settings&utm_medium=currency_converter&utm_campaign=buy_custom_converters" target="_blank">' . esc_html__('Buy custom converters', 'cryptopay_lite') . '</a>',
                    'title' => esc_html__('What is a currency converter?', 'cryptopay_lite')
                ],
                [
                    'id' => 'autoPriceUpdateMin',
                    'type' => 'number',
                    'title' => esc_html__('Auto price update (Min)', 'cryptopay_lite'),
                    'help' => esc_html__('The setting where you specify how long the price will be updated after the network and cryptocurrency has been selected.', 'cryptopay_lite'),
                    'default' => 0.5,
                    'sanitize' => function ($val) {
                        return floatval($val);
                    }
                ],
                [
                    'id'           => 'customPrices',
                    'type'         => 'content',
                    'title'        => esc_html__('Custom prices', 'cryptopay_lite'),
                    'button_title' => esc_html__('Add new', 'cryptopay_lite'),
                    'content'      => $proMsg . esc_html__('If your currency is not available in the current API. You can define a special value for it.', 'cryptopay_lite') . ' <a href="https://beycanpress.gitbook.io/cryptopay-docs/how-custom-prices-work" target="_blank">' . esc_html__('Get more info', 'cryptopay_lite') . '</a>',
                ],
                [
                    'id' => 'converter',
                    'type'  => 'content',
                    'title' => esc_html__('Converter API', 'cryptopay_lite'),
                    'content' => $proMsg . esc_html__('You can choose the API you want to use for the currency converter.', 'cryptopay_lite'),
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'addressSanctions',
            'title'  => esc_html__('Address sanctions', 'cryptopay_lite'),
            'icon'   => 'fas fa-fingerprint',
            'fields' => [
                [
                    'id'      => 'sanctions',
                    'title'   => esc_html__('Address sanctions', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('With address restrictions, you can see in transactions whether paying addresses have been blacklisted by blockchain data services, or you can directly restrict them in wallet payments.', 'cryptopay_lite'),
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'cron',
            'title'  => esc_html__('Cron settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-clock',
            'fields' => [
                [
                    'id'      => 'reminderEmail',
                    'title'   => esc_html__('Reminder email', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('Users see a button called set reminder email during payment, and when they confirm this, the payment process is interrupted there and tells the user that they will receive a notification when the payment is completed. For this, please make sure you have adjusted the cron settings.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'backendConfirmation',
                    'title'   => esc_html__('Backend confirmation', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('If you open this break, let\'s assume that the user left the page during the payment, his internet was lost or his computer was shut down. When this setting is on, when the user comes back to the site and looks at their orders, the payment status of the order is checked while the order page is loaded, and if the transaction is successful, the order is confirmed. It also happens when an admin enters the Order transaction page.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'cronType',
                    'title'   => esc_html__('Cron type (Premium)', 'cryptopay_lite'),
                    'type'    => 'select',
                    'desc'    => esc_html__('CryptoPay uses cron jobs to check the status of transactions and to send reminder emails. If you have not set up cron jobs on your server, you can use the following settings.', 'cryptopay_lite'),
                    'options' => [
                        'wp' => esc_html__('WordPress Cron', 'cryptopay_lite'),
                        'server' => esc_html__('Server Cron (recommended)', 'cryptopay_lite')
                    ],
                    'default' => 'server',
                    'help'    => esc_html__('The difference between WordPress and Server cron is this. WordPress cron is triggered when someone enters the site. If the process is long, the user will have to wait for a certain amount of time while entering the site. Also, it will not work instantly because it is activated when a person enters the site. In contrast, Server cron is triggered by the server and will run just in time, and since it\'s not a user, it won\'t change anything no matter how long it takes. Users will be able to access your site quickly.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'cronTime',
                    'title'   => esc_html__('Cron time (in minutes)', 'cryptopay_lite'),
                    'type'    => 'number',
                    'default' => 5,
                    'dependency' => ['cronType', '==', 'wp'],
                    'desc'    => esc_html__('You can decide how many minutes apart it should run. However, we recommend 5 minutes.', 'cryptopay_lite')
                ],
                [
                    'type'  => 'content',
                    'dependency' => ['cronType', '==', 'server'],
                    'content' => '
                    <strong>API 1</strong>: ' . home_url('?rest_route=/cryptopay/verify-pending-transactions') . ' (GET, POST)
                    
                    ' . CPL_BR2 . '

                    This API checks pending transactions and confirms them based on status. If a user has set a reminder email, if a cron setting has not been added for this API but the backend confirmation setting is turned on, he will receive a reminder email when he comes to the site again or when you enter the transactions menu. 

                    ' . CPL_BR2 . '
                    
                    You can decide how many minutes apart it should run. However, we recommend 5 minutes.

                    ' . CPL_BR2 . '

                    If you have added a cron setting for this API, you can turn off backend confirmation. Because pending transactions will be checked regularly.

                    ' . CPL_BR2 . '

                    To find out whether the cron you added is working or not, you can turn on debugging and wait for the cron running time, then search for "Verify pending transactions process (API)" under the debug logs menu. If you see this message, cron is running.

                    ' . CPL_BR2 . '
                    <a href="https://www.hivelocity.net/kb/what-is-cron-job/" target="_blank">' . esc_html__('Click for more information', 'cryptopay_lite') . '</a>
                    '
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'backup',
            'title'  => esc_html__('Backup', 'cryptopay_lite'),
            'icon'   => 'fas fa-shield-alt',
            'fields' => [
                [
                    'type'  => 'backup',
                    'title' => esc_html__('Backup', 'cryptopay_lite')
                ],
            ]
        ]);

        add_action('admin_footer', function (): void {
            Helpers::viewEcho('css/settings-css');
        });
    }

    /**
     * @param string $parent
     * @return void
     */
    private function createFeedbackPage(string $parent): void
    {
        add_action('admin_menu', function () use ($parent): void {
            add_submenu_page(
                $parent,
                esc_html__('Feedback', 'cryptopay'),
                esc_html__('Feedback', 'cryptopay') . '<span class="awaiting-mod">NEW</span>',
                'manage_options',
                'cryptopay_feedback',
                function (): void {
                    Helpers::viewEcho('feedback');
                }
            );
        });
    }
}

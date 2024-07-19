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
        parent::__construct(esc_html__('Settings', 'cryptopay'), $parent);

        $proMsg = '<div style="display:flex;align-items:center">' . sprintf(esc_html__('This is a premium feature => %s', 'cryptopay'), '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . __('Buy premium', 'cryptopay') . '</a>') . '</div><br>';

        self::createSection([
            'id'     => 'generalSettings',
            'title'  => esc_html__('General settings', 'cryptopay'),
            'icon'   => 'fa fa-cog',
            'fields' => [
                [
                    'id'      => 'dds',
                    'title'   => esc_html__('Data deletion status', 'cryptopay'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('This setting is passive come by default. You enable this setting. All data created by the plug-in will be deleted while removing the plug-in.', 'cryptopay')
                ],
                [
                    'id'      => 'debugging',
                    'title'   => esc_html__('Debugging', 'cryptopay'),
                    'type'    => 'switcher',
                    'default' => false,
                    'desc'    => esc_html__('The Debug menu will appear when this setting is turned on and the log file is created.', 'cryptopay'),
                    'help'    => esc_html__('This setting has been added for the developer team rather than the users. If you open a support ticket to us due to a bug, we will use this setting to check the plugin progress.', 'cryptopay')
                ],
                [
                    'id'      => 'testnet',
                    'title'   => esc_html__('Testnet', 'cryptopay'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('When you activate this setting, CryptoPay starts working on testnets.', 'cryptopay')
                ],
                [
                    'id'      => 'mode',
                    'title'   => esc_html__('Mode', 'cryptopay'),
                    'type'    => 'select',
                    'options' => [
                        'network' => esc_html__('Network', 'cryptopay'),
                        'currency' => esc_html__('Currency', 'cryptopay'),
                    ],
                    'default' => 'network',
                    'desc'    => esc_html__('You can choose the mode you want to use in the payment process. If you choose the network mode, the user will first choose the network and then the currency and then the wallet. If you choose the currency mode, the user will first choose the currency and then the wallet.', 'cryptopay')
                ],
                [
                    'id'      => 'wcProjectId',
                    'title'   => esc_html__('WalletConnect Project ID', 'cryptopay'),
                    'type'    => 'text',
                    'desc'    => esc_html__('WalletConnect Project ID is required for WalletConnect, which are used to connect to mobile wallets on many networks. If you do not have a WalletConnect Project ID, WalletConnect will not work. You can get your project ID by registering for WalletConnect Cloud at the link below.', 'cryptopay')
                    . CPL_BR2 .
                    Helpers::view('components/link', [
                        'text' => esc_html__('WalletConnect Cloud', 'cryptopay'),
                        'url' => 'https://cloud.walletconnect.com/sign-in'
                    ])
                    ,
                ]
            ]
        ]);

        self::createSection([
            'id'     => 'wooCommerceSettings',
            'title'  => esc_html__('WooCommerce settings', 'cryptopay'),
            'icon'   => 'fa fa-cog',
            'fields' => [
                [
                    'id'      => 'acceptSubscriptionPayments',
                    'title'   => esc_html__('Accept subscription payments (Via manual renewal)', 'cryptopay'),
                    'type'    => 'switcher',
                    'desc'    => esc_html__('It is possible to receive automatic payments in cryptocurrencies in various ways, but automatic payments will not be introduced because malicious customers can use this situation to steal the customer\'s funds.', 'cryptopay'),
                    'help'    => esc_html__('CryptoPay will work directly if manual payments are enabled in the subscription setting. You can enable this setting for CryptoPay to work when this setting is off.', 'cryptopay'),
                    'default' => false,
                ],
                [
                    'id'      => 'acceptInstantPayments',
                    'title'   => esc_html__('Accept instant payments', 'cryptopay'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('As with PayPal, a Buy with Crypto Pay button appears directly on the product page, and users can instantly create an order by paying directly with CryptoPay. ', 'cryptopay') . sprintf(esc_html__('If the %s setting is active in WooCommerce settings, non-registered users can use instant payments.', 'cryptopay'), '<a href="' . admin_url('admin.php?page=wc-settings&tab=account') . '" target="_blank">' . esc_html__('"Allow customers to place orders without an account"', 'cryptopay') . '</a>'),
                    'default' => false,
                ],
                [
                    'id'      => 'paymentReceivingArea',
                    'title'   => esc_html__('Payment receiving area', 'cryptopay'),
                    'type'    => 'select',
                    'options' => [
                        'checkout' => esc_html__('Checkout', 'cryptopay'),
                        'orderPay' => esc_html__('Order pay', 'cryptopay')
                    ],
                    'help'    => esc_html__('With this setting, you can choose from where the user receives the payment. With the checkout option, payment will be taken directly from the checkout page before the order is created, and then the order will be created. After the order is created with the Order Pay option, payment will be received on the Order Pay page.', 'cryptopay'),
                    'default' => 'orderPay',
                    'desc' => esc_html__('This feature is currently only available in Classic Checkout. Newer versions of WooCommerce use Block Checkout. You can create your own Classic Checkout page with the shortcode [woocommerce_checkout].', 'cryptopay')
                ],
                [
                    'id'      => 'paymentCompleteOrderStatus',
                    'title'   => esc_html__('Payment complete order status', 'cryptopay'),
                    'type'    => 'select',
                    'help'    => esc_html__('The status to apply for WooCommerce order after payment is complete.', 'cryptopay'),
                    'options' => [
                        'wc-completed' => esc_html__('Completed', 'cryptopay'),
                        'wc-processing' => esc_html__('Processing', 'cryptopay')
                    ],
                    'default' => 'wc-completed',
                ],
                [
                    'id'      => 'activateWooCommercePaymentGateway',
                    'title'   => esc_html__('Activate WooCommerce payment gateway', 'cryptopay'),
                    'type'    => 'content',
                    'content' => Helpers::view('components/link', [
                        'text' => esc_html__('Click to activate', 'cryptopay'),
                        'url' => admin_url('admin.php?page=wc-settings&tab=checkout&section=cryptopay')
                    ])
                ]
            ]
        ]);

        EvmChains::initSettings();

        Settings::createSection([
            'id'     => 'bitcoin',
            'title'  => esc_html__('Bitcoin settings', 'cryptopay'),
            'icon'   => 'fab fa-bitcoin',
            'fields' => [
                [
                    'id'      => 'bitcoinPayments',
                    'title'   => esc_html__('Bitcoin payments', 'cryptopay'),
                    'type'    => 'content',
                    'content' => esc_html__('CryptoPay supports all EVM-based networks by default, but you can start accepting payments from other blockchain networks by purchasing extra network support.', 'cryptopay') . CPL_BR2 . '<a href="https://beycanpress.com/our-plugins/?categoryId=88&utm_source=plugin_settings&utm_medium=bitcoin_payments&utm_campaign=buy_custom_networks#categories" target="_blank">' . esc_html__('Buy custom network supports', 'cryptopay') . '</a>' . CPL_BR2 . 'Bitcoin and all other network support is only available for Premium.'
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'currencyDiscountsRates',
            'title'  => esc_html__('Currency discounts', 'cryptopay'),
            'icon'   => 'fa fa-percent',
            'fields' => [
                [
                    'id' => 'currencyDiscountsRatesInfo',
                    'type' => 'content',
                    'content' => 'Currency discounts is a feature where you can define special discounts for certain currencies. For example, you have a token specific to your project or you are sponsored by any token project. In this case, you can give percentage discounts to your paying users to encourage them to pay with this token.
                    ' . CPL_BR2 . '
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/currency-discounts" target="_blank">' . esc_html__('Click for more information', 'cryptopay') . '</a>',
                    'title' => esc_html__('What is a currency discounts?', 'cryptopay')
                ],
                [
                    'id'           => 'discountRates',
                    'type'         => 'group',
                    'title'        => esc_html__('Currency discounts', 'cryptopay'),
                    'button_title' => esc_html__('Add new', 'cryptopay'),
                    'help'         => esc_html__('You can define shopping-specific discounts for tokens with the symbols of the currency.', 'cryptopay'),
                    'sanitize' => function ($val) {
                        if (is_array($val)) {
                            foreach ($val as $key => &$value) {
                                $value['rate'] = floatval($value['rate']);
                                $value['symbol'] = strtoupper(sanitize_text_field($value['symbol']));
                            }
                        }

                        return $val;
                    },
                    'validate' => function ($val) {
                        if (is_array($val)) {
                            foreach ($val as $key => $value) {
                                if (empty($value['symbol'])) {
                                    return esc_html__('Symbol cannot be empty.', 'cryptopay');
                                } elseif (empty($value['rate'])) {
                                    return esc_html__('Discount rate cannot be empty.', 'cryptopay');
                                }
                            }
                        }
                    },
                    'fields'      => [
                        [
                            'title' => esc_html__('Symbol', 'cryptopay'),
                            'id'    => 'symbol',
                            'type'  => 'text'
                        ],
                        [
                            'title' => esc_html__('Discount rate (in %)', 'cryptopay'),
                            'id'    => 'rate',
                            'type'  => 'number'
                        ],
                    ],
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'currencyConverter',
            'title'  => esc_html__('Currency converter', 'cryptopay'),
            'icon'   => 'fas fa-dollar-sign',
            'fields' => [
                [
                    'id' => 'otherConverterLinks',
                    'type' => 'content',
                    'content' => 'Currently, in crypto payments, most people list prices in FIAT currencies, i.e. currencies such as USD, EUR. With the currency converter, we convert these currencies into the currency chosen by the user. By default the CryptoCompare API is available. If your token is listed on Coin Market Cap, Coin Gecko or DEXs. You can get suitable currency converter add-ons to get the price value of your token.
                    ' . CPL_BR2 . '
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/currency-converter" target="_blank">' . esc_html__('Click for more information', 'cryptopay') . '</a>
                    ' . CPL_BR2 . '<a href="https://beycanpress.com/our-plugins/?categoryId=167&utm_source=plugin_settings&utm_medium=currency_converter&utm_campaign=buy_custom_converters#categories" target="_blank">' . esc_html__('Buy custom converters', 'cryptopay') . '</a>
                    ' . CPL_BR2 . '
                    All custom converters are available for premium only.',
                    'title' => esc_html__('What is a currency converter?', 'cryptopay')
                ],
                [
                    'id' => 'autoPriceUpdateMin',
                    'type' => 'number',
                    'title' => esc_html__('Auto price update (Min)', 'cryptopay'),
                    'help' => esc_html__('The setting where you specify how long the price will be updated after the network and cryptocurrency has been selected.', 'cryptopay'),
                    'default' => 0.5,
                    'sanitize' => function ($val) {
                        return floatval($val);
                    }
                ],
                [
                    'id'           => 'customPrices',
                    'type'         => 'content',
                    'title'        => esc_html__('Custom prices', 'cryptopay'),
                    'button_title' => esc_html__('Add new', 'cryptopay'),
                    'content'      => $proMsg . esc_html__('If your currency is not available in the current API. You can define a special value for it.', 'cryptopay') . ' <a href="https://beycanpress.gitbook.io/cryptopay-docs/how-custom-prices-work" target="_blank">' . esc_html__('Get more info', 'cryptopay') . '</a>',
                ],
                [
                    'id' => 'converter',
                    'type'  => 'content',
                    'title' => esc_html__('Converter API', 'cryptopay'),
                    'content' => $proMsg . esc_html__('You can choose the API you want to use for the currency converter.', 'cryptopay'),
                ]
            ]
        ]);

        self::createSection([
            'id'     => 'addressSanctions',
            'title'  => esc_html__('Address sanctions', 'cryptopay'),
            'icon'   => 'fas fa-fingerprint',
            'fields' => [
                [
                    'id'      => 'sanctions',
                    'title'   => esc_html__('Address sanctions', 'cryptopay'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('With address restrictions, you can see in transactions whether paying addresses have been blacklisted by blockchain data services, or you can directly restrict them in wallet payments.', 'cryptopay'),
                ],
            ]
        ]);


        self::createSection([
            'id'     => 'cron',
            'title'  => esc_html__('Cron settings', 'cryptopay'),
            'icon'   => 'fa fa-clock',
            'fields' => [
                [
                    'id'      => 'reminderEmail',
                    'title'   => esc_html__('Reminder email', 'cryptopay'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('Users see a button called set reminder email during payment, and when they confirm this, the payment process is interrupted there and tells the user that they will receive a notification when the payment is completed. For this, please make sure you have adjusted the cron settings.', 'cryptopay')
                ],
                [
                    'id'      => 'backendConfirmation',
                    'title'   => esc_html__('Backend confirmation', 'cryptopay'),
                    'type'    => 'switcher',
                    'default' => true,
                    'help'    => esc_html__('If you open this break, let\'s assume that the user left the page during the payment, his internet was lost or his computer was shut down. When this setting is on, when the user comes back to the site and looks at their orders, the payment status of the order is checked while the order page is loaded, and if the transaction is successful, the order is confirmed. It also happens when an admin enters the Order transaction page.', 'cryptopay')
                ],
                [
                    'id'      => 'cronType',
                    'title'   => esc_html__('Cron type', 'cryptopay'),
                    'type'    => 'select',
                    'desc'    => esc_html__('CryptoPay uses cron jobs to check the status of transactions and to send reminder emails. If you have not set up cron jobs on your server, you can use the following settings.', 'cryptopay'),
                    'options' => [
                        'wp' => esc_html__('WordPress Cron', 'cryptopay'),
                        'server' => esc_html__('Server Cron (recommended)', 'cryptopay')
                    ],
                    'default' => 'server',
                    'help'    => esc_html__('The difference between WordPress and Server cron is this. WordPress cron is triggered when someone enters the site. If the process is long, the user will have to wait for a certain amount of time while entering the site. Also, it will not work instantly because it is activated when a person enters the site. In contrast, Server cron is triggered by the server and will run just in time, and since it\'s not a user, it won\'t change anything no matter how long it takes. Users will be able to access your site quickly.', 'cryptopay')
                ],
                [
                    'id'      => 'cronTime',
                    'title'   => esc_html__('Cron time (in minutes)', 'cryptopay'),
                    'type'    => 'number',
                    'default' => 5,
                    'dependency' => ['cronType', '==', 'wp'],
                    'desc'    => esc_html__('You can decide how many minutes apart it should run. However, we recommend 5 minutes.', 'cryptopay')
                ],
                [
                    'type'  => 'content',
                    'dependency' => ['cronType', '==', 'server'],
                    'content' => '
                    <strong>API 1</strong>: ' . home_url('?rest_route=/cryptopay-lite/verify-pending-transactions') . ' (GET, POST)
                    
                    ' . CPL_BR2 . '

                    This API checks pending transactions and confirms them based on status. If a user has set a reminder email, if a cron setting has not been added for this API but the backend confirmation setting is turned on, he will receive a reminder email when he comes to the site again or when you enter the transactions menu. 

                    ' . CPL_BR2 . '
                    
                    You can decide how many minutes apart it should run. However, we recommend 5 minutes.

                    ' . CPL_BR2 . '

                    If you have added a cron setting for this API, you can turn off backend confirmation. Because pending transactions will be checked regularly.

                    ' . CPL_BR2 . '

                    To find out whether the cron you added is working or not, you can turn on debugging and wait for the cron running time, then search for "Verify pending transactions process (API)" under the debug logs menu. If you see this message, cron is running.

                    ' . CPL_BR2 . '
                    <a href="https://www.hivelocity.net/kb/what-is-cron-job/" target="_blank">' . esc_html__('Click for more information', 'cryptopay') . '</a>
                    '
                ],
            ]
        ]);

        self::createSection([
            'id'     => 'themeOptions',
            'title'  => esc_html__('Theme options', 'cryptopay'),
            'icon'   => 'fa fa-palette',
            'fields' => [
                [
                    'id'      => 'themeMode',
                    'title'   => esc_html__('Theme mode', 'cryptopay'),
                    'type'    => 'select',
                    'options' => [
                        'light' => esc_html__('Light', 'cryptopay'),
                        'dark' => esc_html__('Dark', 'cryptopay')
                    ],
                    'default' => 'light',
                ],
                [
                    'id'        => 'themeLight',
                    'type'      => 'color_group',
                    'title'     => esc_html__('Light theme', 'cryptopay'),
                    'options'   => [
                        'text' => esc_html__('Text', 'cryptopay'),
                        'discount' => esc_html__('Discount', 'cryptopay'),
                        'border' => esc_html__('Border', 'cryptopay'),
                        'primary' => esc_html__('Primary', 'cryptopay'),
                        'secondary' => esc_html__('Secondary', 'cryptopay'),
                        'tertiary' => esc_html__('Tertiary', 'cryptopay'),
                        'background' => esc_html__('Background', 'cryptopay'),
                        'boxShadow' => esc_html__('Box shadow', 'cryptopay'),
                        'buttonColor' => esc_html__('Button color', 'cryptopay'),
                        'buttonDisabled' => esc_html__('Button disabled', 'cryptopay'),
                        'buttonBackground' => esc_html__('Button background', 'cryptopay'),
                        'buttonHoverBackground' => esc_html__('Button hover background', 'cryptopay'),
                    ],
                    'default'   => [
                        'text' => '#3d3d3d',
                        'discount' => 'red',
                        'border' => '#e2e4ec',
                        'primary' => '#f5f7f9',
                        'secondary' => '#888',
                        'tertiary' => '#555',
                        'background' => '#ffffff',
                        'boxShadow' => 'rgba(0, 0, 0, 0.2)',
                        'buttonColor' => '#fff',
                        'buttonDisabled' => '#cccccc',
                        'buttonBackground' => '#409eff',
                        'buttonHoverBackground' => '#79bbff',
                    ],
                ],
                [
                    'id'        => 'themeDark',
                    'type'      => 'color_group',
                    'title'     => esc_html__('Dark theme', 'cryptopay'),
                    'options'   => [
                        'text' => esc_html__('Text', 'cryptopay'),
                        'discount' => esc_html__('Discount', 'cryptopay'),
                        'border' => esc_html__('Border', 'cryptopay'),
                        'primary' => esc_html__('Primary', 'cryptopay'),
                        'secondary' => esc_html__('Secondary', 'cryptopay'),
                        'tertiary' => esc_html__('Tertiary', 'cryptopay'),
                        'background' => esc_html__('Background', 'cryptopay'),
                        'boxShadow' => esc_html__('Box shadow', 'cryptopay'),
                        'buttonColor' => esc_html__('Button color', 'cryptopay'),
                        'buttonDisabled' => esc_html__('Button disabled', 'cryptopay'),
                        'buttonBackground' => esc_html__('Button background', 'cryptopay'),
                        'buttonHoverBackground' => esc_html__('Button hover background', 'cryptopay'),
                    ],
                    'default'   => [
                        'text' => 'rgb(168, 174, 182)',
                        'discount' => 'rgb(210, 58, 58)',
                        'border' => 'rgb(5, 11, 19)',
                        'primary' => 'rgb(20, 29, 32)',
                        'secondary' => 'rgba(5, 11, 19, 0.8)',
                        'tertiary' => 'rgb(3, 6, 11)',
                        'background' => 'rgb(28, 34, 43)',
                        'boxShadow' => 'rgba(255, 255, 255, 0.1)',
                        'buttonColor' => '#fff',
                        'buttonDisabled' => '#cccccc',
                        'buttonBackground' => '#1da87c',
                        'buttonHoverBackground' => '#116c4f',
                    ],
                ]
            ]
        ]);

        self::createSection([
            'id'     => 'backup',
            'title'  => esc_html__('Backup', 'cryptopay'),
            'icon'   => 'fas fa-shield-alt',
            'fields' => [
                [
                    'type'  => 'backup',
                    'title' => esc_html__('Backup', 'cryptopay')
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

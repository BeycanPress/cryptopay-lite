<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\CryptoPayLite\PluginHero\Setting;
use \BeycanPress\CryptoPayLite\PluginHero\Helpers;

class Settings extends Setting
{
    use Helpers;

    public function __construct()
    {
        $parent = $this->pages->HomePage->slug;
        parent::__construct(esc_html__('Settings', 'cryptopay_lite'), $parent);

        $proMsg = '<div style="display:flex;align-items:center">'.sprintf(esc_html__('This is a pro feature => %s', 'cryptopay_lite'), '<a href="https://beycanpress.com/cryptopay/?utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . __('Buy pro', 'cryptopay_lite') . '</a>') .'</div><br>';

        self::createSection(array(
            'id'     => 'generalSettings', 
            'title'  => esc_html__('General settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-cog',
            'fields' => array(
                array(
                    'id'      => 'dds',
                    'title'   => esc_html__('Data deletion status', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('This setting is passive come by default. You enable this setting. All data created by the plug-in will be deleted while removing the plug-in.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'testnet',
                    'title'   => esc_html__('Testnet', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => false,
                    'help'    => esc_html__('When you activate this setting, CryptoPay starts working on testnets.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'backendConfirmation',
                    'title'   => esc_html__('Backend confirmation', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('If you open this break, let\'s assume that the user left the page during the payment, his internet was lost or his computer was shut down. When this setting is on, when the user comes back to the site and looks at their orders, the payment status of the order is checked while the order page is loaded, and if the transaction is successful, the order is confirmed. It also happens when an admin enters the Order transaction page.', 'cryptopay_lite')
                ),
            )
        ));

        self::createSection(array(
            'id'     => 'wooCommerceSettings', 
            'title'  => esc_html__('WooCommerce settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-cog',
            'fields' => array(
                array(
                    'id'      => 'acceptSubscritonPayments',
                    'title'   => esc_html__('Accept subscription payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('CryptoPay will work directly if manual payments are enabled in the subscription setting. You can enable this setting for CryptoPay to work when this setting is off.', 'cryptopay_lite'),
                ),
                array(
                    'id'      => 'paymentReceivingArea',
                    'title'   => esc_html__('Payment receiving area', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('With this setting, you can choose from where the user receives the payment. With the checkout option, payment will be taken directly from the checkout page before the order is created, and then the order will be created. After the order is created with the Order Pay option, payment will be received on the Order Pay page.', 'cryptopay_lite'),
                ),
                array(
                    'id'      => 'paymentCompleteOrderStatus',
                    'title'   => esc_html__('Payment complete order status', 'cryptopay_lite'),
                    'type'    => 'select',
                    'help'    => esc_html__('The status to apply for WooCommerce order after payment is complete.', 'cryptopay_lite'),
                    'options' => [
                        'wc-completed' => esc_html__('Completed', 'cryptopay_lite'),
                        'wc-processing' => esc_html__('Processing', 'cryptopay_lite')
                    ],
                    'default' => 'wc-completed',
                ),
            )
        ));

        self::createSection(array(
            'id'     => 'evmBased', 
            'title'  => esc_html__('EVM Based settings', 'cryptopay_lite'),
            'icon'   => 'fab fa-ethereum',
            'fields' => array(
                array(
                    'id'      => 'evmBasedWalletAddress',
                    'title'   => esc_html__('Wallet address', 'cryptopay_lite'),
                    'type'    => 'text',
                    'help'    => esc_html__('The account address to which the payments will be transferred. (BEP20, ERC20, MetaMask, Trust Wallet, Binance Wallet )', 'cryptopay_lite'),
                    'sanitize' => function($val) {
						return sanitize_text_field($val);
					},
                    'validate' => function($val) {
                        $val = sanitize_text_field($val);
                        if (empty($val)) {
                            return esc_html__('Wallet address cannot be empty.', 'cryptopay_lite');
                        } elseif (strlen($val) < 42 || strlen($val) > 42) {
                            return esc_html__('Wallet address must consist of 42 characters.', 'cryptopay_lite');
                        }
                    }
                ),
                array(
                    'id'      => 'evmBasedNetworksx',
                    'title'   => esc_html__('Accepted networks', 'cryptopay_lite'),
                    'help'   => esc_html__('Specify the networks you want to accept payments from.', 'cryptopay_lite'),
                    'type'    => 'fieldset',
                    'fields' => array(
                        array(
                            'id'      => 'id_1',
                            'title'   => esc_html('Ethereum'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id_56',
                            'title'   => esc_html('BNB Smart Chain'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id_43114',
                            'title'   => esc_html('Avalanche C-Chain'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id_137',
                            'title'   => esc_html('Polygon'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id_250',
                            'title'   => esc_html('Fantom'),
                            'type'    => 'switcher',
                            'default' => true
                        ),
                    )
                ),
                array(
                    'id'      => 'evmBasedWallets',
                    'title'   => esc_html__('Wallets', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('Specify the wallets you want to accept payments from.', 'cryptopay_lite')
                ),
            ) 
        ));

        Settings::createSection(array(
            'id'     => 'bitcoin', 
            'title'  => esc_html__('Bitcoin settings', 'cryptopay_lite'),
            'icon'   => 'fab fa-bitcoin',
            'fields' => array(
                array(
                    'id'      => 'bitcoinPayments',
                    'title'   => esc_html__('Bitcoin payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__(' With the Bitcoin Network Support add-on, you can receive Bitcoin payments in the CryptoPay. Network support add-ons only working with premium version.', 'cryptopay_lite')
                ),
            )
        ));

        Settings::createSection(array(
            'id'     => 'solana', 
            'title'  => esc_html__('Solana settings', 'cryptopay_lite'),
            'icon'   => 'fas fa-project-diagram',
            'fields' => array(
                array(
                    'id'      => 'solanaPayments',
                    'title'   => esc_html__('Solana payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__(' With the Solana Network Support add-on, you can receive Solana payments in the CryptoPay. Network support add-ons only working with premium version.', 'cryptopay_lite')
                ),
            )
        ));

        Settings::createSection(array(
            'id'     => 'tron', 
            'title'  => esc_html__('Tron settings', 'cryptopay_lite'),
            'icon'   => 'fas fa-project-diagram',
            'fields' => array(
                array(
                    'id'      => 'tronPayments',
                    'title'   => esc_html__('Tron payments', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__(' With the Tron Network Support add-on, you can receive Tron payments in the CryptoPay. Network support add-ons only working with premium version.', 'cryptopay_lite')
                ),
            )
        ));

        self::createSection(array(
            'id'     => 'currencyConverter', 
            'title'  => esc_html__('Currency converter', 'cryptopay_lite'),
            'icon'   => 'fas fa-dollar-sign',
            'fields' => array(
                array(
                    'id' => 'otherConverterLinks',
                    'type' => 'content',
                    'content' => 'Currently, in crypto payments, most people list prices in FIAT currencies, i.e. currencies such as USD, EUR. With the currency converter, we convert these currencies into the currency chosen by the user. By default the CryptoCompare API is available. If your token is listed on Coin Market Cap, Coin Gecko or DEXs. You can get suitable currency converter add-ons to get the price value of your token.
                    <br><br>
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/currency-converter" target="_blank">'.esc_html__('Click for more information', 'cryptopay_lite').'</a>
                    <br><br><a href="https://beycanpress.com/our-plugins/?categoryId=167&utm_source=lite_plugin_settings&utm_medium=currency_converter&utm_campaign=buy_custom_converters" target="_blank">'.esc_html__('Buy custom converters', 'cryptopay_lite').'</a>',
                    'title' => esc_html__('What is a currency converter?', 'cryptopay_lite')
                ),
                array(
                    'id' => 'autoPriceUpdateMin',
                    'type' => 'content',
                    'title' => esc_html__('Auto price update (Min)', 'cryptopay_lite'),
                    'content' =>  $proMsg . esc_html__(' The setting where you specify how long the price will be updated after the network and cryptocurrency has been selected.', 'cryptopay_lite'),
                    'default' => 0.5,
                    'sanitize' => function($val) {
                        return floatval($val);
                    }
                ),
                array(
                    'id'      => 'customPrices',
                    'title'   => esc_html__('Custom prices', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__('You can assign prices corresponding to fiat currencies to your own custom tokens.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'converter',
                    'title'   => esc_html__('Converter API', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__('CryptoCompare generally only lists popular cryptocurrencies. If you have a private token and you want to get paid with it. It is necessary to get the price value of your token from popular APIs such as CoinMarketCap, CoinGecko. Therefore, this setting and extra API support are required. So if you want to get paid with your own tokens, you will need it.', 'cryptopay_lite')
                ),
            )
        ));

        self::createSection(array(
            'id'     => 'backup', 
            'title'  => esc_html__('Backup', 'cryptopay_lite'),
            'icon'   => 'fas fa-shield-alt',
            'fields' => array(
                array(
                    'id'      => 'backup',
                    'title'   => esc_html__('Backup', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg 
                ),
            ) 
        ));
    }

}
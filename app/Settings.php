<?php

namespace BeycanPress\CryptoPayLite;

use \BeycanPress\CryptoPayLite\PluginHero\Setting;
use \BeycanPress\CryptoPayLite\PluginHero\Helpers;

class Settings extends Setting
{
    use Helpers;
    
    /**
     * @var array
     */
    public static $customPrices = [];

    /**
     * @var array
     */
    public static $tokenDiscounts = [];

    public function __construct()
    {
        $parent = $this->pages->HomePage->slug;
        parent::__construct(esc_html__('Settings', 'cryptopay_lite'), $parent);

        $proMsg = '<div style="display:flex;align-items:center">'.sprintf(esc_html__('This is a pro feature => %s', 'cryptopay_lite'), '<a href="https://bit.ly/3pOiY25" target="_blank" class="button" style="margin-left: 10px">' . __('Buy pro', 'cryptopay_lite') . '</a>') .'</div><br>';

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
                    'id'      => 'debugging',
                    'title'   => esc_html__('Debugging', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('This setting has been added for the developer team rather than the users. If you open a support ticket to us due to a bug, we will use this setting to check the plugin progress.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'backendConfirmation',
                    'title'   => esc_html__('Backend confirmation', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('If you open this break, let\'s assume that the user left the page during the payment, his internet was lost or his computer was shut down. When this setting is on, when the user comes back to the site and looks at their orders, the payment status of the order is checked while the order page is loaded, and if the transaction is successful, the order is confirmed. It also happens when an admin enters the Order transaction page.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'theme',
                    'title'   => esc_html__('Theme', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => '<div style="display:flex;align-items:center">'.sprintf(esc_html__('This is a pro feature => %s', 'cryptopay_lite'), '<a href="https://bit.ly/3pOiY25" target="_blank" class="button" style="margin-left: 10px">' . __('Buy pro', 'cryptopay_lite') . '</a>').'</div>',
                ),
                array(
                    'id'      => 'networkSorting',
                    'title'   => esc_html__('Network sorting', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('If you have multiple networks, you can sort them by dragging and dropping.', 'cryptopay_lite')
                ),
            )
        ));

        self::createSection(array(
            'id'     => 'wooCommerceSettings', 
            'title'  => esc_html__('WooCommerce settings', 'cryptopay_lite'),
            'icon'   => 'fa fa-cog',
            'fields' => array(
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
                array(
                    'id'      => 'onlyLoggedInUser',
                    'title'   => esc_html__('Only logged in users can pay', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('Even if a user enters the CryptoPay payment page, if they are not logged in, CryptoPay will not work at all.', 'cryptopay_lite'),
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
                    'id'      => 'evmBasedActivePassive',
                    'title'   => esc_html__('Active/Passive', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg
                ),
                array(
                    'id'      => 'evmBasedBlockConfirmationCount',
                    'title'   => esc_html__('Block confirmation count', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg
                ),
                array(
                    'id'      => 'evmBasedWallets',
                    'title'   => esc_html__('Wallets', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('Specify the wallets you want to accept payments from.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'evmBasedNetworks',
                    'title'   => esc_html__('Accepted networks', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__('Add the blockchain networks and crtyptocurrencies you accept to receive payments.', 'cryptopay_lite')
                ),
                array(
                    'id'      => 'infuraProjectId',
                    'title'   => esc_html__('Infura API Key', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__('Please enter an infura api key for WalletConnect to work.', 'cryptopay_lite')
                ),
            ) 
        ));

        self::createSection(array(
            'id'     => 'customPricesOptions', 
            'title'  => esc_html__('Custom token prices', 'cryptopay_lite'),
            'icon'   => 'fa fa-money',
            'fields' => array(
                array(
                    'id'      => 'customPrices',
                    'title'   => esc_html__('Custom prices', 'cryptopay_lite'),
                    'type'    => 'content',
                    'content' => $proMsg .  esc_html__('You can assign prices corresponding to fiat currencies to your own custom tokens.', 'cryptopay_lite')
                ),
            ) 
        ));

        self::createSection(array(
            'id'     => 'apis', 
            'title'  => esc_html__('API\'s', 'cryptopay_lite'),
            'icon'   => 'fas fa-project-diagram',
            'fields' => array(
                array(
                    'id' => 'otherConverterLinks',
                    'type' => 'content',
                    'content' => '<a href="https://bit.ly/41lD6Wl" target="_blank">'.esc_html__('Buy custom converter API\'s', 'cryptopay_lite').'</a>',
                    'title' => esc_html__('Buy custom converter API\'s', 'cryptopay_lite')
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
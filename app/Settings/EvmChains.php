<?php

declare(strict_types=1);

// @phpcs:disable Generic.Files.LineLength 

namespace BeycanPress\CryptoPayLite\Settings;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Settings\Settings;

class EvmChains
{
    /**
     * @var array<int>
     */
    private static array $networkMatch = [
        1 => 11155111,
        56 => 97,
        43114 => 43113,
        137 => 80002,
        250 => 4002
    ];

    /**
     * @return void
     */
    public static function initSettings(): void
    {
        $proMsg = '<div style="display:flex;align-items:center">' . sprintf(esc_html__('This is a premium feature => %s', 'cryptopay'), '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . __('Buy premium', 'cryptopay') . '</a>') . '</div><br>';

        if (Helpers::getSetting('evmchainsActivePassive') && '' == Helpers::getSetting('evmchainsWalletAddress')) {
            Helpers::networkWillNotWorkMessage('EVM Chains');
        }

        Settings::createSection([
            'id'     => 'evmchains',
            'title'  => esc_html__('EVM settings', 'cryptopay'),
            'icon'   => 'fab fa-ethereum',
            'fields' => [
                [
                    'id'      => 'evmchainsWalletAddress',
                    'title'   => esc_html__('General wallet address', 'cryptopay'),
                    'type'    => 'text',
                    'help'    => esc_html__('The account address to which the payments will be transferred. (BEP20, ERC20, MetaMask, Trust Wallet, Binance Wallet )', 'cryptopay'),
                    'desc'    => esc_html__('This field is the public EVM address field. As you know, when you create a MetaMask or Trust Wallet wallet, your wallet address is the same on all networks. Therefore, you only need to enter a single address here. However, you can define it at a different address for each network with premium version.', 'cryptopay'),
                    'sanitize' => function ($val) {
                        return sanitize_text_field($val);
                    },
                    'validate' => function ($val) {
                        $val = sanitize_text_field($val);
                        if (empty($val)) {
                            return esc_html__('Wallet address cannot be empty.', 'cryptopay');
                        } elseif (strlen($val) < 42 || strlen($val) > 42) {
                            return esc_html__('Wallet address must consist of 42 characters.', 'cryptopay');
                        }
                    }
                ],
                [
                    'id'      => 'evmchainsBlockConfirmationCount',
                    'title'   => esc_html__('Block confirmation count', 'cryptopay'),
                    'type'    => 'number',
                    'default' => 10,
                    'sanitize' => function ($val) {
                        return absint($val);
                    }
                ],

                [
                    'id'     => 'evmchainsWallets',
                    'type'   => 'fieldset',
                    'title'  => esc_html__('Wallets', 'cryptopay'),
                    'help'   => esc_html__('Specify the wallets you want to accept payments from.', 'cryptopay'),
                    'fields' => [
                        [
                            'id'      => 'metamask',
                            'title'   => esc_html('MetaMask'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'trustwallet',
                            'title'   => esc_html('Trust Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'bitgetwallet',
                            'title'   => esc_html('Bitget Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'okxwallet',
                            'title'   => esc_html('Okx Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'xdefiwallet',
                            'title'   => esc_html('Xdefi Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'walletconnect',
                            'title'   => esc_html('WalletConnect'),
                            'type'    => 'switcher',
                            'default' => true
                        ],
                        [
                            'id'      => 'web3modal',
                            'title'   => esc_html('Web3 Wallets (Web3Modal)'),
                            'type'    => 'switcher',
                            'default' => true,
                            'desc'    => esc_html__('It is a module within Web3Modal that supports hundreds of wallets with WalletConnect support. Since all the above wallets are already supported, you can deactivate all other wallets and allow users to make transactions only through Web3Modal.', 'cryptopay'),
                        ],
                    ]
                ],
                [
                    'id'      => 'buyPremiumForCustomNetworks',
                    'title'   => esc_html__('Unlimited network and currency', 'cryptopay'),
                    'type'    => 'content',
                    'content' => esc_html__('Get the premium to get paid with unlimited EVM blockchain network and any cryptocurrency (token) you want!', 'cryptopay') . CPL_BR2 . '<a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=plugin_settings&utm_medium=evm_settings&utm_campaign=unlimited_network" target="_blank">' . esc_html__('Buy premium now', 'cryptopay') . '</a>'
                ],
                [
                    'id'      => 'evmchainsNetworks',
                    'title'   => esc_html__('Networks', 'cryptopay'),
                    'help'    => esc_html__('Specify the networks you want to accept payments from.', 'cryptopay'),
                    'type'    => 'fieldset',
                    'desc'    => esc_html__('Unlimited and custom network support is only available in premium. As with MetaMask, you can add any EVM network you want with its information.', 'cryptopay'),
                    'fields' => [
                        [
                            'id'      => 'id_1',
                            'title'   => esc_html('Ethereum'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'id_56',
                            'title'   => esc_html('BNB Smart Chain'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'id_43114',
                            'title'   => esc_html('Avalanche C-Chain'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'id_137',
                            'title'   => esc_html('Polygon'),
                            'type'    => 'switcher',
                            'default' => true,
                        ],
                        [
                            'id'      => 'id_250',
                            'title'   => esc_html('Fantom'),
                            'type'    => 'switcher',
                            'default' => true
                        ],
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param boolean $keys
     * @return array<string,string>|array<string,bool>
     */
    public static function getWallets(bool $keys = true): array
    {
        $wallets = Helpers::getSetting('evmchainsWallets', []);

        $wallets = array_filter($wallets, function ($val) {
            return boolval($val);
        });

        return $keys ? array_keys($wallets) : $wallets;
    }

    /**
     * @return array<int>
     */
    public static function getNetworks(): array
    {
        $networks = Settings::get('evmchainsNetworks') ?? [];
        $networks = array_keys(array_filter($networks, function ($network) {
            return boolval($network);
        }));

        $networks = array_map(function ($network) {
            return (int) explode('_', $network)[1];
        }, $networks);

        if (Helpers::getTestnetStatus()) {
            return array_map(function ($network) {
                return self::$networkMatch[$network];
            }, $networks);
        }

        return $networks;
    }
}

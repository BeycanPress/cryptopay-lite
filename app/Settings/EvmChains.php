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
        137 => 80001,
        250 => 4002
    ];

    /**
     * @return void
     */
    public static function initSettings(): void
    {
        $proMsg = '<div style="display:flex;align-items:center">' . sprintf(esc_html__('This is a premium feature => %s', 'cryptopay_lite'), '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . __('Buy premium', 'cryptopay_lite') . '</a>') . '</div><br>';

        if (Helpers::getSetting('evmchainsActivePassive') && '' == Helpers::getSetting('evmchainsWalletAddress')) {
            Helpers::networkWillNotWorkMessage('EVM Chains');
        }

        Settings::createSection([
            'id'     => 'evmchains',
            'title'  => esc_html__('EVM settings', 'cryptopay_lite'),
            'icon'   => 'fab fa-ethereum',
            'fields' => [
                [
                    'id'      => 'evmchainsWalletAddress',
                    'title'   => esc_html__('General wallet address', 'cryptopay_lite'),
                    'type'    => 'text',
                    'help'    => esc_html__('The account address to which the payments will be transferred. (BEP20, ERC20, MetaMask, Trust Wallet, Binance Wallet )', 'cryptopay_lite'),
                    'desc'    => esc_html__('This field is the public EVM address field. As you know, when you create a MetaMask or Trust Wallet wallet, your wallet address is the same on all networks. Therefore, you only need to enter a single address here. However, you can define it at a different address for each network with premium version.', 'cryptopay_lite'),
                    'sanitize' => function ($val) {
                        return sanitize_text_field($val);
                    },
                    'validate' => function ($val) {
                        $val = sanitize_text_field($val);
                        if (empty($val)) {
                            return esc_html__('Wallet address cannot be empty.', 'cryptopay_lite');
                        } elseif (strlen($val) < 42 || strlen($val) > 42) {
                            return esc_html__('Wallet address must consist of 42 characters.', 'cryptopay_lite');
                        }
                    }
                ],
                [
                    'id'      => 'evmchainsBlockConfirmationCount',
                    'title'   => esc_html__('Block confirmation count', 'cryptopay_lite'),
                    'type'    => 'number',
                    'default' => 10,
                    'sanitize' => function ($val) {
                        return absint($val);
                    }
                ],
                [
                    'id'     => 'evmchainsWallets',
                    'type'   => 'content',
                    'title'  => esc_html__('Wallets', 'cryptopay_lite'),
                    'help'   => esc_html__('Specify the wallets you want to accept payments from.', 'cryptopay_lite'),
                    'content' => $proMsg . esc_html__('Specify the wallets you want to accept payments from. WalletConnect and mobile support.', 'cryptopay_lite')
                ],
                [
                    'id'      => 'evmchainsNetworks',
                    'title'   => esc_html__('Networks', 'cryptopay_lite'),
                    'help'    => esc_html__('Specify the networks you want to accept payments from.', 'cryptopay_lite'),
                    'type'    => 'fieldset',
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

<?php

declare(strict_types=1);

// @phpcs:disable Generic.Files.LineLength 

namespace BeycanPress\CryptoPayLite\Settings;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Settings\Settings;
use BeycanPress\CryptoPayLite\PluginHero\Plugin;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Network\NetworksType;
use BeycanPress\CryptoPayLite\Types\Network\CurrencyType;
use BeycanPress\CryptoPayLite\Types\Network\CurrenciesType;

class EvmChains
{
    /**
     * @var NetworksType
     */
    private static NetworksType $networks;

    /**
     * @var array<string,array<string,mixed>>
     */
    public static array $testnets = [
        // "ethereum" => [
        //     "id" => 5,
        //     "mainnetId" => 1,
        //     "name" => "Ethereum Goerli Testnet (QR)",
        //     "rpcUrl" => "https://goerli.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161",
        //     "wsUrl" => "wss://goerli.infura.io/ws/v3/9aa3d95b3bc440fa88ea12eaa4456161",
        //     "explorerUrl" => "https://goerli.etherscan.io/",
        //     "nativeCurrency" => [
        //         "symbol" => "ETH",
        //         "decimals" => 18
        //     ]
        // ],
        "ethereum" => [
            "id" => 11155111,
            "hexId" => "0xaa36a7",
            "mainnetId" => 1,
            "name" => "Ethereum Sepolia Testnet (QR)",
            "rpcUrl" => "https://sepolia.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161",
            "wsUrl" => "wss://sepolia.infura.io/ws/v3/9aa3d95b3bc440fa88ea12eaa4456161",
            "explorerUrl" => "https://sepolia.etherscan.io/",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ]
        ],
        'arbitrum' => [
            "id" => 421613,
            "mainnetId" => 42161,
            "name" => "Arbitrum Goerli Testnet",
            "rpcUrl" => "https://goerli-rollup.arbitrum.io/rpc",
            "explorerUrl" => "https://goerli.arbiscan.io/",
            "image" => "https://docs.arbitrum.io/img/logo.svg",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ],
        ],
        'optimism' => [
            "id" => 420,
            "mainnetId" => 10,
            "name" => "Optimism Goerli Testnet",
            "rpcUrl" => "https://goerli.optimism.io",
            "explorerUrl" => "https://goerli-optimism.etherscan.io/",
            "image" => "https://cryptologos.cc/logos/optimism-ethereum-op-logo.svg",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ],
        ],
        "bsc" => [
            "id" => 97,
            "mainnetId" => 56,
            "name" => "BNB Smart Chain Testnet",
            "rpcUrl" => "https://bsc-testnet.publicnode.com",
            "wsUrl" => "wss://compatible-palpable-arm.bsc-testnet.quiknode.pro/de9c63ea566b7efbd92ef3390e16ff23ff9bbf7e/",
            "explorerUrl" => "https://testnet.bscscan.com/",
            "nativeCurrency" => [
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ],
        "opbnb" => [
            "id" => 5611,
            "mainnetId" => 204,
            "name" => "opBNB Smart Chain Testnet",
            "rpcUrl" => "https://opbnb-testnet-rpc.bnbchain.org",
            "explorerUrl" => "https://testnet.opbnbscan.com/",
            "nativeCurrency" => [
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ],
        "avalanche" => [
            "id" => 43113,
            "mainnetId" => 43114,
            "name" => "Avalanche FUJI C-Chain Testnet",
            "wsUrl" => "wss://api.avax-test.network/ext/bc/C/ws",
            "rpcUrl" => "https://api.avax-test.network/ext/bc/C/rpc",
            "explorerUrl" => "https://cchain.explorer.avax-test.network",
            "nativeCurrency" => [
                "symbol" => "AVAX",
                "decimals" => 18
            ]
        ],
        "polygon" => [
            "id" => 80001,
            "mainnetId" => 137,
            "name" => "Polygon Mumbai Testnet",
            "rpcUrl" => "https://rpc-mumbai.maticvigil.com/",
            "explorerUrl" => "https://mumbai.polygonscan.com/",
            "nativeCurrency" => [
                "symbol" => "MATIC",
                "decimals" => 18
            ]
        ],
        "polygon-zkevm" => [
            "id" => 1442,
            "mainnetId" => 1101,
            "name" => "Polygon zkEVM Testnet",
            "rpcUrl" => "https://rpc.public.zkevm-test.net",
            "explorerUrl" => "https://testnet-zkevm.polygonscan.com/",
            "image" => "https://cryptologos.cc/logos/polygon-matic-logo.svg?v=026",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ]
        ],
        "zksync" => [
            "id" => 280,
            "mainnetId" => 324,
            "name" => "zkSync Era Testnet",
            "rpcUrl" => "https://testnet.era.zksync.dev",
            "wsUrl"  => "wss://testnet.era.zksync.dev/ws",
            "explorerUrl" => "https://goerli.explorer.zksync.io/",
            "image" => "https://i.ibb.co/60kYfMf/zksync.png",
            "nativeCurrency" => [
                "symbol" => "ETH",
                "decimals" => 18
            ]
        ],
        "fantom" => [
            "id" => 4002,
            "mainnetId" => 250,
            "name" => "Fantom Testnet",
            "rpcUrl" => "https://rpc.testnet.fantom.network/",
            "explorerUrl" => "https://testnet.ftmscan.com/",
            "nativeCurrency" => [
                "symbol" => "FTM",
                "decimals" => 18
            ]
        ]
    ];

    /**
     * polygon is coming: 137, 80001
     * Phantom goerli: 5
     * @var array<string,array<int>>
     */
    private static array $filteredWallets = [
        'binancewallet' => [56, 97, 1],
        'phantom' => [1],
    ];

    /**
     * @return NetworksType
     */
    public static function getNetworks(): NetworksType
    {
        $mainnets = self::getMainnetNetworks();

        if (
            Helpers::getSetting('evmchainsActivePassive') &&
            Helpers::getSetting('evmchainsWalletAddress')
        ) {
            if (Helpers::getTestnetStatus()) {
                $chainIds = $mainnets->column('id');
                $testnets = self::getTestnetNetworks()->all();

                $testnets = array_filter($testnets, function ($network) use ($chainIds) {
                    return in_array($network->getMainnetId(), $chainIds);
                });

                usort($testnets, function ($a, $b) use ($chainIds) {
                    $idA = array_search($a->getMainnetId(), $chainIds);
                    $idB = array_search($b->getMainnetId(), $chainIds);
                    return $idA - $idB;
                });

                return new NetworksType($testnets);
            } else {
                return $mainnets;
            }
        }

        return new NetworksType();
    }

    /**
     * @return NetworksType
     */
    private static function getMainnetNetworks(): NetworksType
    {
        $networks = new NetworksType();

        if (!empty(self::$networks)) {
            return self::$networks;
        }

        foreach (Helpers::getSetting('evmchainsNetworks') as $network) {
            // Active/Passive control
            if (isset($network['active']) && $network['active'] != '1') {
                continue;
            }

            $networkType = new NetworkType();

            $id = absint($network['id']);

            $networkType->setId($id);

            // currencies
            $currencies = new CurrenciesType();

            if (isset($network['nativeCurrency']['active']) && $network['nativeCurrency']['active'] == '1') {
                $symbol = trim($network['nativeCurrency']['symbol']);
                $decimals = absint($network['nativeCurrency']['decimals']);
                $currencies->addCurrency(CurrencyType::fromArray(compact('symbol', 'decimals')));
            }

            if (isset($network['currencies'])) {
                foreach ($network['currencies'] as $currency) {
                    if (isset($currency['active']) && $currency['active'] == '1') {
                        $address = trim($currency['address']);
                        $symbol = trim(strtoupper($currency['symbol']));
                        $imageUrl = sanitize_text_field($currency['image']);
                        $currencies->addCurrency(CurrencyType::fromArray(compact('symbol', 'address', 'imageUrl')));
                    }
                }
            }

            $networkType->setCode('evmchains');
            $networkType->setName($network['name']);
            $networkType->setRpcUrl($network['rpcUrl']);
            $networkType->setExplorerUrl($network['explorerUrl']);

            // dynamic
            $networkType->setWsUrl($network['wsUrl'] ?? null);
            $networkType->setImageUrl($network['image'] ?? null);
            $networkType->setWallets(self::filterSomeWallets($id));
            $networkType->setWeb3Domain($network['web3Domain'] ?? null);

            // currency
            $networkType->setCurrencies($currencies);
            $networkType->setNativeCurrency($currencies->first());

            $networks->addNetwork($networkType);
        }

        return self::$networks = $networks;
    }

    /**
     * @return NetworksType
     */
    private static function getTestnetNetworks(): NetworksType
    {
        return new NetworksType(array_map(function ($network) {
            $id = absint($network['id']);

            $symbol = trim($network['nativeCurrency']['symbol']);
            $decimals = absint($network['nativeCurrency']['decimals']);
            $nativeCurrency = CurrencyType::fromArray(compact('symbol', 'decimals'));
            $currencies = self::getTestnetsCurrencies($id, $nativeCurrency->getSymbol());

            $networkType = new NetworkType();
            $networkType->setId($id);
            $networkType->setCode('evmchains');
            $networkType->setName($network['name']);
            $networkType->setRpcUrl($network['rpcUrl']);
            $networkType->setMainnetId($network['mainnetId']);
            $networkType->setExplorerUrl($network['explorerUrl']);

            // dynamic
            $networkType->setWsUrl($network['wsUrl'] ?? null);
            $networkType->setImageUrl($network['image'] ?? null);
            $networkType->setWallets(self::filterSomeWallets($id));
            $networkType->setWeb3Domain($network['web3Domain'] ?? null);

            // currency
            $networkType->setCurrencies($currencies);
            $networkType->setNativeCurrency($nativeCurrency);

            return $networkType;
        }, array_values(self::$testnets)));
    }

    /**
     * @param int $networkId
     * @return array<string>
     */
    private static function filterSomeWallets(int $networkId): array
    {
        $wallets = Helpers::getWalletsByCode('evmchains');

        return array_values(array_filter($wallets, function ($wallet) use ($networkId) {
            if (isset(self::$filteredWallets[$wallet])) {
                return in_array($networkId, self::$filteredWallets[$wallet]);
            }

            return true;
        }));
    }
    /**
     * @param integer $id
     * @param string $nativeCurrency
     * @return CurrenciesType
     */
    public static function getTestnetsCurrencies(int $id, string $nativeCurrency): CurrenciesType
    {
        if ($id == 5) {
            return new CurrenciesType([
                new CurrencyType('ETH'),
                new CurrencyType('USDT', '0x5ab6f31b29fc2021436b3be57de83ead3286fdc7'),
                new CurrencyType('USDC', '0x466595626333c55fa7d7ad6265d46ba5fdbbdd99'),
            ]);
        } elseif ($id == 97) {
            return new CurrenciesType([
                new CurrencyType('BNB'),
                new CurrencyType('USDT', '0xba6670261a05b8504e8ab9c45d97a8ed42573822'),
            ]);
        } elseif ($id == 43113) {
            return new CurrenciesType([
                new CurrencyType('AVAX'),
                new CurrencyType('USDT', '0xFe143522938e253e5Feef14DB0732e9d96221D72'),
            ]);
        } elseif ($id == 80001) {
            return new CurrenciesType([
                new CurrencyType('MATIC'),
                new CurrencyType('USDT', '0xa02f6adc7926efebbd59fd43a84f4e0c0c91e832'),
            ]);
        } elseif ($id == 4002) {
            return new CurrenciesType([
                new CurrencyType('FTM'),
            ]);
        } elseif ($id == 421613) {
            return new CurrenciesType([
                new CurrencyType('ETH'),
                new CurrencyType('ARB', '0xF861378B543525ae0C47d33C90C954Dc774Ac1F9'),
            ]);
        } elseif ($id == 420) {
            return new CurrenciesType([
                new CurrencyType('ETH'),
                new CurrencyType('OP', '0x4200000000000000000000000000000000000042'),
            ]);
        } elseif ($id == 5611) {
            return new CurrenciesType([
                new CurrencyType('BNB'),
            ]);
        } elseif ($id == 280) {
            return new CurrenciesType([
                new CurrencyType('USDC'),
                new CurrencyType('OP', '0x0faF6df7054946141266420b43783387A78d82A9'),
            ]);
        } elseif ($id == 11155111) {
            return new CurrenciesType([
                new CurrencyType('ETH'),
            ]);
        } else {
            return new CurrenciesType([
                new CurrencyType($nativeCurrency),
            ]);
        }
    }

    /**
     * @return void
     */
    public static function initSettings(): void
    {
        if (Helpers::getSetting('evmchainsActivePassive') && Helpers::getSetting('evmchainsWalletAddress') == '') {
            Helpers::networkWillNotWorkMessage('EVM Chains');
        }

        Settings::createSection(array(
            'id'     => 'evmchains',
            'title'  => esc_html__('EVM settings', 'cryptopay_lite'),
            'icon'   => 'fab fa-ethereum',
            'fields' => array(
                array(
                    'id'      => 'evmchainsActivePassive',
                    'title'   => esc_html__('Active/Passive', 'cryptopay_lite'),
                    'type'    => 'switcher',
                    'default' => true,
                ),
                array(
                    'id'      => 'evmchainsWalletAddress',
                    'title'   => esc_html__('General wallet address', 'cryptopay_lite'),
                    'type'    => 'text',
                    'help'    => esc_html__('The account address to which the payments will be transferred. (BEP20, ERC20, MetaMask, Trust Wallet, Binance Wallet )', 'cryptopay_lite'),
                    'desc'    => esc_html__('This field is the public EVM address field. As you know, when you create a MetaMask or Trust Wallet wallet, your wallet address is the same on all networks. Therefore, you only need to enter a single address here. However, you can define it at a different address for each network.', 'cryptopay_lite'),
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
                ),
                array(
                    'id'      => 'evmchainsBlockConfirmationCount',
                    'title'   => esc_html__('Block confirmation count', 'cryptopay_lite'),
                    'type'    => 'number',
                    'default' => 10,
                    'sanitize' => function ($val) {
                        return absint($val);
                    }
                ),
                array(
                    'id'     => 'evmchainsWallets',
                    'type'   => 'fieldset',
                    'title'  => esc_html__('Wallets', 'cryptopay_lite'),
                    'help'   => esc_html__('Specify the wallets you want to accept payments from.', 'cryptopay_lite'),
                    'fields' => array(
                        array(
                            'id'      => 'metamask',
                            'title'   => esc_html('MetaMask'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'trustwallet',
                            'title'   => esc_html('Trust Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'bitget',
                            'title'   => esc_html('Bitget Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'okx',
                            'title'   => esc_html('Okx Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'xdefi',
                            'title'   => esc_html('Xdefi Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                        ),
                        array(
                            'id'      => 'binancewallet',
                            'title'   => esc_html('Binance Wallet'),
                            'type'    => 'switcher',
                            'default' => true,
                            'desc'    => esc_html__('Binance Wallet is only available on (BNB Smart Chain and Ethereum Mainnet).', 'cryptopay_lite') . CP_BR2 . esc_html__('DEPRECATED: Binance Wallet is no longer supported because merged with Trust Wallet. But you can still use if you want.', 'cryptopay_lite'),
                        ),
                        array(
                            'id'      => 'phantom',
                            'title'   => esc_html('Phantom'),
                            'type'    => 'switcher',
                            'default' => true,
                            'desc'    => esc_html__('Phantom is only available on Ethereum (Currently only supports mainnet).', 'cryptopay_lite'),
                        ),
                        array(
                            'id'      => 'walletconnect',
                            'title'   => esc_html('WalletConnect'),
                            'type'    => 'switcher',
                            'default' => true
                        ),
                        array(
                            'id'      => 'web3modal',
                            'title'   => esc_html('Web3 Wallets (Web3Modal)'),
                            'type'    => 'switcher',
                            'default' => true,
                            'desc'    => esc_html__('It is a module within Web3Modal that supports hundreds of wallets with WalletConnect support. Since all the above wallets are already supported, you can deactivate all other wallets and allow users to make transactions only through Web3Modal.', 'cryptopay_lite'),
                        ),
                    )
                ),
                array(
                    'id'     => 'evmchainsMainnetInfo',
                    'title'  => esc_html__('Informations', 'cryptopay_lite'),
                    'type'   => 'content',
                    'content' => esc_html__('To activate QR payments on EVM Chains you need to provide a Websocket address.') . CP_BR2 . esc_html__('You can use the links below regarding EVM-based network and adding tokens:') . CP_BR . "<a href='https://beycanpress.gitbook.io/cryptopay-docs/adding-new-network-for-evm-based-networks' target='_blank'>" . esc_html__('How do I add a new network', 'cryptopay_lite') . "</a>" . CP_BR . "<a href='https://beycanpress.gitbook.io/cryptopay-docs/how-do-i-add-a-new-token' target='_blank'>"  . esc_html__('How do I add a new token', 'cryptopay_lite') . "</a>",
                ),
                array(
                    'id'      => 'evmchainsNetworks',
                    'title'   => esc_html__('Networks', 'cryptopay_lite'),
                    'type'    => 'group',
                    'help'    => esc_html__('Add the blockchain networks you accept to receive payments.', 'cryptopay_lite'),
                    'button_title' => esc_html__('Add new', 'cryptopay_lite'),
                    'default' => [
                        [
                            'name' =>  'Ethereum',
                            'rpcUrl' =>  'https://mainnet.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161',
                            'wsUrl' => 'wss://mainnet.infura.io/ws/v3/9aa3d95b3bc440fa88ea12eaa4456161',
                            'id' =>  1,
                            'explorerUrl' =>  'https://etherscan.io/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/eth.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'ETH',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0xdac17f958d2ee523a2206206994597c13d831ec7',
                                    'image' =>  Helpers::getImageUrl('icons/usdt.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDC',
                                    'address' =>  '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48',
                                    'image' =>  Helpers::getImageUrl('icons/usdc.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'DAI',
                                    'address' =>  '0x6b175474e89094c44da98b954eedeac495271d0f',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'BNB',
                                    'address' =>  '0xB8c77482e45F1F44dE1745F52C74426C631bDD52',
                                    'image' =>  Helpers::getImageUrl('icons/bnb.svg'),
                                    'active' => false
                                ],
                            ]
                        ],
                        [
                            'name' =>  'Arbitrum One',
                            'rpcUrl' =>  'https://arb1.arbitrum.io/rpc',
                            'id' =>  42161,
                            'explorerUrl' =>  'https://arbiscan.io/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/arb.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'ETH',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'ARB',
                                    'address' =>  '0x912CE59144191C1204E64559FE8253a0e49E6548',
                                    'image' =>  Helpers::getImageUrl('icons/arb.svg'),
                                    'active' => true
                                ],
                            ]
                        ],
                        [
                            'name' =>  'Optimism',
                            'rpcUrl' =>  'https://mainnet.optimism.io',
                            'id' =>  10,
                            'explorerUrl' =>  'https://explorer.optimism.io/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/op.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'ETH',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'OP',
                                    'address' =>  '0x4200000000000000000000000000000000000042',
                                    'image' =>  Helpers::getImageUrl('icons/op.svg'),
                                    'active' => true
                                ],
                            ]
                        ],
                        [
                            'name' =>  'BNB Smart Chain',
                            'rpcUrl' =>  'https://bsc.publicnode.com/',
                            'id' =>  56,
                            'explorerUrl' =>  'https://bscscan.com/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/bnb.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'BNB',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0x55d398326f99059ff775485246999027b3197955',
                                    'image' =>  Helpers::getImageUrl('icons/usdt.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDC',
                                    'address' =>  '0x8ac76a51cc950d9822d68b83fe1ad97b32cd580d',
                                    'image' =>  Helpers::getImageUrl('icons/usdc.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'DAI',
                                    'address' =>  '0x1af3f329e8be154074d8769d1ffa4ee058b1dbc3',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'ETH',
                                    'address' =>  '0x2170ed0880ac9a755fd29b2688956bd959f933f8',
                                    'image' =>  Helpers::getImageUrl('icons/eth.svg'),
                                    'active' => false
                                ],
                                [
                                    'symbol' =>  'LTC',
                                    'address' =>  '0x4338665cbb7b2485a8855a139b75d5e34ab0db94',
                                    'image' =>  Helpers::getImageUrl('icons/ltc.svg'),
                                    'active' => false
                                ],
                                [
                                    'symbol' =>  'DOGE',
                                    'address' =>  '0xba2ae424d960c26247dd6c32edc70b295c744c43',
                                    'image' =>  Helpers::getImageUrl('icons/doge.svg'),
                                    'active' => false
                                ]
                            ]
                        ],
                        [
                            'name' =>  'opBNB Smart Chain',
                            'rpcUrl' =>  'https://opbnb-mainnet-rpc.bnbchain.org',
                            'id' =>  204,
                            'explorerUrl' =>  'https://opbnbscan.com/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/opbnb.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'BNB',
                                'decimals' =>  18,
                            ],
                            'currencies' => []
                        ],
                        [
                            'name' =>  'Avalanche',
                            'rpcUrl' =>  'https://api.avax.network/ext/bc/C/rpc',
                            'id' =>  43114,
                            'explorerUrl' =>  'https://cchain.explorer.avax.network/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/avax.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'AVAX',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0xde3a24028580884448a5397872046a019649b084',
                                    'image' =>  Helpers::getImageUrl('icons/usdt.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'DAI',
                                    'address' =>  '0xba7deebbfc5fa1100fb055a87773e1e99cd3507a',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'ETH',
                                    'address' =>  '0xf20d962a6c8f70c731bd838a3a388D7d48fA6e15',
                                    'image' =>  Helpers::getImageUrl('icons/eth.svg'),
                                    'active' => true
                                ],
                            ]
                        ],
                        [
                            'name' =>  'Polygon',
                            'rpcUrl' =>  'https://polygon-rpc.com/',
                            'id' =>  137,
                            'explorerUrl' =>  'https://polygonscan.com/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/matic.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'MATIC',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0xc2132d05d31c914a87c6611c10748aeb04b58e8f',
                                    'image' =>  Helpers::getImageUrl('icons/usdt.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDC',
                                    'address' =>  '0x2791bca1f2de4661ed88a30c99a7a9449aa84174',
                                    'image' =>  Helpers::getImageUrl('icons/usdc.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'DAI',
                                    'address' =>  '0x8f3Cf7ad23Cd3CaDbD9735AFf958023239c6A063',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                            ]
                            ],
                        [
                            'name' =>  'Polygon zkEVM',
                            'rpcUrl' =>  'https://zkevm.polygonscan.com/',
                            'id' =>  1101,
                            'explorerUrl' =>  'https://zkevm.polygonscan.com/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/matic.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'ETH',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'MATIC',
                                    'address' =>  '0xa2036f0538221a77a3937f1379699f44945018d0',
                                    'image' =>  Helpers::getImageUrl('icons/usdt.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0x1e4a5963abfd975d8c9021ce480b42188849d41d',
                                    'image' =>  Helpers::getImageUrl('icons/usdc.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDC',
                                    'address' =>  '0xa8ce8aee21bc2a48a5ef670afcc9274c7bbbc035',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                            ]
                        ],
                        [
                            'name' =>  'zkSync Era',
                            'rpcUrl' =>  'https://mainnet.era.zksync.io',
                            'wsUrl'  => 'wss://mainnet.era.zksync.io/ws',
                            'id' =>  324,
                            'explorerUrl' =>  'https://explorer.zksync.io/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/zksync.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'ETH',
                                'decimals' =>  18,
                            ],
                            'currencies' => [
                                [
                                    'symbol' =>  'USDT',
                                    'address' =>  '0x493257fD37EDB34451f62EDf8D2a0C418852bA4C',
                                    'image' =>  Helpers::getImageUrl('icons/usdc.svg'),
                                    'active' => true
                                ],
                                [
                                    'symbol' =>  'USDC',
                                    'address' =>  '0x3355df6D4c9C3035724Fd0e3914dE96A5a83aaf4',
                                    'image' =>  Helpers::getImageUrl('icons/dai.svg'),
                                    'active' => true
                                ],
                            ]
                        ],
                        [
                            'name' =>  'Fantom',
                            'rpcUrl' =>  'https://rpc.fantom.network',
                            'id' =>  250,
                            'explorerUrl' =>  'https://ftmscan.com/',
                            'active' => true,
                            'image' =>  Helpers::getImageUrl('icons/ftm.svg'),
                            'nativeCurrency' => [
                                'active' =>  true,
                                'symbol' =>  'FTM',
                                'decimals' =>  18,
                            ],
                            'currencies' => []
                        ]
                    ],
                    'sanitize' => function ($val) {
                        if (is_array($val)) {
                            foreach ($val as &$value) {
                                $value['id'] = absint($value['id']);
                                $value['name'] = sanitize_text_field($value['name']);
                                $value['rpcUrl'] = sanitize_text_field($value['rpcUrl']);
                                $value['wsUrl'] = sanitize_text_field($value['wsUrl']);
                                $value['explorerUrl'] = sanitize_text_field($value['explorerUrl']);
                                $value['web3Domain'] = sanitize_text_field($value['web3Domain']);
                                $value['walletAddress'] = sanitize_text_field($value['walletAddress']);
                                $value['nativeCurrency']['decimals'] = absint($value['nativeCurrency']['decimals']);
                                $value['nativeCurrency']['symbol'] = strtoupper(sanitize_text_field($value['nativeCurrency']['symbol']));

                                if (isset($value['image'])) {
                                    $value['image'] = sanitize_text_field($value['image']);
                                }

                                if (isset($value['currencies'])) {
                                    foreach ($value['currencies'] as &$currency) {
                                        $currency['symbol'] = strtoupper(sanitize_text_field($currency['symbol']));
                                        $currency['address'] = sanitize_text_field($currency['address']);
                                        if ($currency['image']) {
                                            $currency['image'] = sanitize_text_field($currency['image']);
                                        }
                                    }
                                }
                            }
                        }

                        return $val;
                    },
                    'validate' => function ($val) {
                        if (is_array($val)) {
                            foreach ($val as $key => $value) {
                                if (empty($value['name'])) {
                                    return esc_html__('Network name cannot be empty.', 'cryptopay_lite');
                                } elseif (empty($value['rpcUrl'])) {
                                    return esc_html__('Network RPC URL cannot be empty.', 'cryptopay_lite');
                                } elseif (empty($value['id'])) {
                                    return esc_html__('Chain ID cannot be empty.', 'cryptopay_lite');
                                } elseif (empty($value['explorerUrl'])) {
                                    return esc_html__('Explorer URL cannot be empty.', 'cryptopay_lite');
                                } elseif (empty($value['nativeCurrency']['symbol'])) {
                                    return esc_html__('Native currency symbol cannot be empty.', 'cryptopay_lite');
                                } elseif (empty($value['nativeCurrency']['decimals'])) {
                                    return esc_html__('Native currency Decimals cannot be empty.', 'cryptopay_lite');
                                } elseif (!empty($value['walletAddress']) && (strlen($value['walletAddress']) < 42 || strlen($value['walletAddress']) > 42)) {
                                    return esc_html__('Wallet address must consist of 42 characters.', 'cryptopay_lite');
                                } elseif (isset($value['currencies'])) {
                                    foreach ($value['currencies'] as $currency) {
                                        if (empty($currency['symbol'])) {
                                            return esc_html__('Currency symbol cannot be empty.', 'cryptopay_lite');
                                        } elseif (empty($currency['address'])) {
                                            return esc_html__('Currency contract address cannot be empty.', 'cryptopay_lite');
                                        } elseif (strlen($currency['address']) < 42 || strlen($currency['address']) > 42) {
                                            return esc_html__('Currency contract address must consist of 42 characters.', 'cryptopay_lite');
                                        }
                                    }
                                }
                            }
                        } else {
                            return esc_html__('You must add at least one blockchain network!', 'cryptopay_lite');
                        }
                    },
                    'fields'    => array(
                        array(
                            'title' => esc_html__('Network name', 'cryptopay_lite'),
                            'id'    => 'name',
                            'type'  => 'text'
                        ),
                        array(
                            'title' => esc_html__('Network RPC URL', 'cryptopay_lite'),
                            'id'    => 'rpcUrl',
                            'type'  => 'text',
                            'desc'   => esc_html__('A Node API address where we can control transactions on the blockchain. Sometimes public APIs can restrict you. Therefore you may need a custom API address.', 'cryptopay_lite') . ' <a href="https://beycanpress.gitbook.io/cryptopay-docs/network-support-add-ons/blockchain-node-providers#what-are-rpc-url" target="_blank">What are Custom RPC URL?</a>'
                        ),
                        array(
                            'title' => esc_html__('Websocket URL', 'cryptopay_lite'),
                            'id'    => 'wsUrl',
                            'type'  => 'text',
                            'desc' => esc_html__('If you want to enable QR payments on this network, please provide a Websocket URL.', 'cryptopay_lite') . ' <a href="https://beycanpress.gitbook.io/cryptopay-docs/network-support-add-ons/blockchain-node-providers#what-are-websocket-url" target="_blank">What are Websocket URL?</a>',
                        ),
                        array(
                            'title' => esc_html__('Chain ID', 'cryptopay_lite'),
                            'id'    => 'id',
                            'type'  => 'number'
                        ),
                        array(
                            'title' => esc_html__('Explorer URL', 'cryptopay_lite'),
                            'id'    => 'explorerUrl',
                            'type'  => 'text'
                        ),
                        array(
                            'title' => esc_html__('Web3 Domain', 'cryptopay_lite'),
                            'id'    => 'web3Domain',
                            'type'  => 'text',
                            'desc'  => esc_html__('There is no obligation to fill in this field. If you have a domain, for example, you can enter "beycanpress.eth" in this field. However, please make sure that this domain matches the general address or the address you will enter below.', 'cryptopay_lite')
                        ),
                        array(
                            'title' => esc_html__('Wallet Address', 'cryptopay_lite'),
                            'id'    => 'walletAddress',
                            'type'  => 'text',
                            'desc'  => esc_html__('This field is not required. If you have already entered a general wallet address, you do not need to enter anything here. However, if you want to receive payments to a different wallet address for this network, you can enter it.', 'cryptopay_lite')
                        ),
                        array(
                            'id'      => 'active',
                            'title'   => esc_html__('Active/Passive', 'cryptopay_lite'),
                            'type'    => 'switcher',
                            'help'    => esc_html__('Get paid in this network?', 'cryptopay_lite'),
                            'default' => true,
                        ),
                        array(
                            'title' => esc_html__('Image', 'cryptopay_lite'),
                            'id'    => 'image',
                            'type'  => 'upload',
                            'desc'    => esc_html__('You can upload an custom image for this network. If you\'r not choose any image app will use default image.', 'cryptopay_lite'),
                        ),
                        array(
                            'id'     => 'nativeCurrency',
                            'type'   => 'fieldset',
                            'title'  => esc_html__('Native Currency', 'cryptopay_lite'),
                            'fields' => array(
                                array(
                                    'id'      => 'active',
                                    'title'   => esc_html__('Active/Passive', 'cryptopay_lite'),
                                    'type'    => 'switcher',
                                    'help'    => esc_html__('Get paid in native currency?', 'cryptopay_lite'),
                                    'default' => true,
                                ),
                                array(
                                    'id'    => 'symbol',
                                    'type'  => 'text',
                                    'title' => esc_html__('Symbol', 'cryptopay_lite')
                                ),
                                array(
                                    'id'    => 'decimals',
                                    'type'  => 'number',
                                    'title' => esc_html__('Decimals', 'cryptopay_lite')
                                )
                            ),
                        ),
                        array(
                            'id'        => 'currencies',
                            'type'      => 'group',
                            'title'     => esc_html__('Currencies', 'cryptopay_lite'),
                            'button_title' => esc_html__('Add new', 'cryptopay_lite'),
                            'fields'    => array(
                                array(
                                    'title' => esc_html__('Symbol', 'cryptopay_lite'),
                                    'id'    => 'symbol',
                                    'type'  => 'text'
                                ),
                                array(
                                    'title' => esc_html__('Contract address', 'cryptopay_lite'),
                                    'id'    => 'address',
                                    'type'  => 'text'
                                ),
                                array(
                                    'title' => esc_html__('Image', 'cryptopay_lite'),
                                    'id'    => 'image',
                                    'type'  => 'upload',
                                    'desc'    => esc_html__('You can upload an custom image for this network. If you\'r not choose any image app will use default image.', 'cryptopay_lite'),
                                ),
                                array(
                                    'id'      => 'active',
                                    'title'   => esc_html__('Active/Passive', 'cryptopay_lite'),
                                    'type'    => 'switcher',
                                    'help'    => esc_html__('You can easily activate or deactivate Token without deleting it.', 'cryptopay_lite'),
                                    'default' => true,
                                ),
                            ),
                        ),
                    ),
                )
            )
        ));
    }
}

<?php 

namespace BeycanPress\CryptoPayLite;

use \MultipleChain\EvmBasedChains;
use \BeycanPress\CryptoPayLite\Lang;
use \BeycanPress\CurrencyConverter;
use \BeycanPress\CryptoPayLite\Settings;
use \MultipleChain\EvmChains\Provider;
use \BeycanPress\CryptoPayLite\PluginHero\Hook;
use \BeycanPress\CryptoPayLite\PluginHero\Plugin;
use \BeycanPress\CryptoPayLite\PluginHero\Helpers;

class Services 
{
    use Helpers;

    /**
     * @var array
     */
    private static $addons = [];

    public static function registerAddon(string $addon) : void
    {
        if (in_array($addon, self::$addons)) {
            throw new \Exception('This add-on is already registered, please choose another name!');
        }

        self::$addons[] = $addon;
    }

    /**
     * @param array $order
     * @param string $addon
     * @param boolean $confirmation
     * @param array $params
     * @return string
     */
    public static function startPaymentProcess(
        array $order, string $addon, bool $confirmation = true, array $params = []
    ) : string
    {
        if (!isset($order['id'])) {
            throw new \Exception('Order id parameter is required!');
        } elseif (!isset($order['amount'])) {
            throw new \Exception('Order amount parameter is required!');
        } elseif (!isset($order['currency'])) {
            throw new \Exception('Order currency parameter is required!');
        }

        return self::preparePaymentProcess($addon, $confirmation, [
            'order' => $order,
            'params' => $params,
            'autoInit' => true
        ]);
    }

    /**
     * @param string $addon
     * @param boolean $confirmation
     * @param array $data
     * @return string
     */
    public static function preparePaymentProcess(
        string $addon, bool $confirmation = true, array $data = []
    ) : string
    {
        $autoInit = isset($data['autoInit']) ? $data['autoInit'] : false;
        $pluginUrl = Plugin::$instance->pluginUrl;

        $walletImages = [];
        array_map(function($wallet) use ($pluginUrl, &$walletImages) {
            $walletImages[$wallet] = $pluginUrl . 'assets/images/wallets/' . $wallet . '.png';
        }, [
            'metamask',
            'binancewallet',
            'trustwallet'
        ]); 

        $networks = self::getNetworks();

        if (empty($networks)) {
            return esc_html__('No network is active, please activate at least one network!', 'cryptopay_lite');
        }

        $data = array_merge([
            'providers' => [],
            'callbacks' => [],
            'addon' => $addon,
            'autoInit'=> $autoInit,
            'networks' => $networks,
            'walletImages' => $walletImages,
            'confirmation' => $confirmation,
            'apiUrl' => Plugin::$instance->apiUrl,
            'imagesUrl' => $pluginUrl . 'assets/images/',
            'testnet' => boolval(Settings::get('testnet')),
            'lang' => Hook::callFilter('lang', Lang::get()),
        ], $data);

        Plugin::$instance->addScript('cryptopay/js/chunk-vendors.js');
        Plugin::$instance->addScript('cryptopay/js/app.js');
        Plugin::$instance->addStyle('cryptopay/css/chunk-vendors.css');
        Plugin::$instance->addStyle('cryptopay/css/app.css');
        
        $key = Plugin::$instance->addScript('js/main.js');
        wp_localize_script($key, 'CryptoPayLite', $data);

        return Plugin::$instance->view('checkout', compact('autoInit'));
    }


    /**
     * @return array
     */
    public static function getNetworks() : array
    {
        if (Settings::get('testnet') == '1') {
            return self::getTestnetNetworks();
        } else {
            return self::getMainnetNetworks();
        }
    }

    /**
     * @return array
     */
    private static function getMainnetNetworks() : array
    {
        $wallets = [
            'metamask' => true,
            'binancewallet' => true,
            'trustwallet' => true
        ];

        $prepareWallets = [];
        return array_map(function(&$network) use ($wallets) {
    
            $id = intval($network['id']);
            
            $prepareWallets = $wallets;

            if (isset($wallets['binancewallet']) && $id != 56) {
                unset($prepareWallets['binancewallet']);
            } 

            $network['code'] = 'evmBased';
            $network['paymentType'] = 'wallet';
            $network['wallets'] = array_keys($prepareWallets);
            $network['currencies'] = self::getMainnetCurrencies($id);
            
            return $network;
        }, array_values(EvmBasedChains::$mainnets));
    }

    /**
     * @return array
     */
    private static function getTestnetNetworks() : array
    {
        
        $wallets = [
            'metamask' => true,
            'binancewallet' => true,
            'trustwallet' => true
        ];

        $prepareWallets = [];
        return array_map(function($network) use ($wallets) {
    
            $id = intval($network['id']);
            
            $prepareWallets = $wallets;

            if (isset($wallets['binancewallet']) && $id != 97) {
                unset($prepareWallets['binancewallet']);
            } 

            $network['code'] = 'evmBased';
            $network['paymentType'] = 'wallet';
            $network['wallets'] = array_keys($prepareWallets);
            $network['currencies'] = self::getTestnetsCurrencies($id);
            
            return $network;
        }, array_values(EvmBasedChains::$testnets));
    }

    /**
     * @param string $code
     * @param integer|null $id
     * @return array
     */
    public static function getMainnetCurrencies(int $id = null) : array 
    {
        if ($id == 1) {
            return [
                [
                    'symbol' => "ETH",
                ],
                [ 
                    'symbol' =>  'USDT',
                    'address' =>  '0xdac17f958d2ee523a2206206994597c13d831ec7',
                ],
                [ 
                    'symbol' =>  'USDC',
                    'address' =>  '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48',
                ],
                [ 
                    'symbol' =>  'BUSD',
                    'address' =>  '0x4Fabb145d64652a948d72533023f6E7A623C7C53',
                ]
            ];
        } elseif ($id == 56) {
            return [
                [ 
                    'symbol' =>  'BNB',
                ],
                [ 
                    'symbol' =>  'BUSD',
                    'address' =>  '0xe9e7cea3dedca5984780bafc599bd69add087d56',
                ],
                [
                    'symbol' =>  'USDT',
                    'address' =>  '0x55d398326f99059ff775485246999027b3197955',
                ],
                [
                    'symbol' =>  'USDC',
                    'address' =>  '0x8ac76a51cc950d9822d68b83fe1ad97b32cd580d',
                ]
            ];
        } elseif ($id == 43114) {
            return [
                [
                    'symbol' => "AVAX",
                ],
                [ 
                    'symbol' =>  'USDT',
                    'address' =>  '0xde3a24028580884448a5397872046a019649b084',
                ]
            ];
        } elseif ($id == 137) {
            return [
                [
                    'symbol' => "MATIC",
                ],
                [ 
                    'symbol' =>  'USDT',
                    'address' =>  '0xc2132d05d31c914a87c6611c10748aeb04b58e8f',
                ]
            ];
        } elseif ($id == 250) {
            return [
                [
                    'symbol' => 'FTM',
                ]
            ];
        }
    }

    /**
     * @param string $code
     * @param integer|null $id
     * @return array
     */
    public static function getTestnetsCurrencies(int $id = null) : array 
    {
        if ($id == 5) {
            return [
                [
                    'symbol' => "ETH",
                ],
                [
                    'symbol' => "USDT",
                    'address' => "0x5ab6f31b29fc2021436b3be57de83ead3286fdc7"
                ],
                [
                    'symbol' => "USDC",
                    'address' => "0x466595626333c55fa7d7ad6265d46ba5fdbbdd99"
                ]
            ];
        } elseif ($id == 97) {
            return [
                [
                    'symbol' => "BNB",
                ],
                [
                    'symbol' => "BUSD",
                    'address' => "0xeD24FC36d5Ee211Ea25A80239Fb8C4Cfd80f12Ee"
                ],
                [
                    'symbol' => "USDT",
                    'address' => "0xba6670261a05b8504e8ab9c45d97a8ed42573822"
                ],
            ];
        } elseif ($id == 43113) {
            return [
                [
                    'symbol' => "AVAX",
                ],
                [
                    'symbol' =>  "USDT",
                    'address' =>  "0xFe143522938e253e5Feef14DB0732e9d96221D72"
                ]
            ];
        } elseif ($id == 80001) {
            return [
                [
                    'symbol' => "MATIC",
                ],
                [
                    'symbol' => "USDT",
                    'address' => "0xa02f6adc7926efebbd59fd43a84f4e0c0c91e832"
                ]
            ];
        } elseif ($id == 4002) {
            return [
                [
                    'symbol' => 'FTM',
                ]
            ];
        }
    }

    /**
     * @param string $addon
     * @return object|null
     */    
    public static function getModelByAddon(string $addon) : ?object
    {
        $models = Hook::callFilter('models', [
            'woocommerce' => new Models\OrderTransaction()
        ]);

        return isset($models[$addon]) ? $models[$addon] : null;
    }

    /**
     * @param object $transaction
     * @return object
     */
    public static function getProviderByTx(object $transaction) : object
    {
        return new Provider(json_decode($transaction->network), boolval($transaction->testnet), 20);
    }

    /**
     * @param string $fiatCurrency
     * @param object $cryptoCurrency
     * @param float $amount
     * @param object $network
     * @return float|null
     */
    public static function calculatePaymentPrice(
        string $fiatCurrency, object $cryptoCurrency, float $amount, object $network
    ) : ?float
    {
        $stableCoins = [
            'USDT',
            'USDC',
            'DAI',
            'BUSD',
            'UST',
            'TUSD'
        ];

        $from = $fiatCurrency;
        $to = $cryptoCurrency->symbol;

        if (strtoupper($from) == 'USD' || strtoupper($to) == 'USD') {
            if (in_array(strtoupper($from), $stableCoins) || in_array(strtoupper($to), $stableCoins)) {
                return floatval($amount);
            }
        }

        try {
            $apiUrl =  'https://min-api.cryptocompare.com/data/price?fsym=' . $from . '&tsyms=' . $to;
            $convertData = json_decode(file_get_contents($apiUrl));
            if (isset($convertData->$to)) {
                return Plugin::$instance->toFixed(($amount * $convertData->$to), 6);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            $paymentPrice = null;
        }

    }

    /**
     * @param string $amount
     * @param integer $decimals
     * @return string
     */
    public static function toString(string $amount, int $decimals) : string
    {
        $pos1 = stripos((string) $amount, 'E-');
        $pos2 = stripos((string) $amount, 'E+');
    
        if ($pos1 !== false) {
            $amount = number_format($amount, $decimals, '.', ',');
        }

        if ($pos2 !== false) {
            $amount = number_format($amount, $decimals, '.', '');
        }
    
        return $amount > 1 ? $amount : rtrim($amount, '0');
    }
}
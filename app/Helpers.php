<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

// Classes
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Settings\Settings;
use MultipleChain\EvmChains\Models\Transaction;
use MultipleChain\EvmChains\Models\CoinTransaction;
use MultipleChain\EvmChains\Models\TokenTransaction;
use BeycanPress\CryptoPayLite\Models\OrderTransaction;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
use MultipleChain\EvmChains\Provider as EvmChainsProvider;
use BeycanPress\CryptoPayLite\PluginHero\Helpers as PhHelpers;
// Types
use BeycanPress\CryptoPayLite\Types\Network\CurrencyType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Data\PaymentDataType;
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;
// Exceptions
use BeycanPress\CryptoPayLite\Exceptions\IntegrationRegistryException;

class Helpers extends PhHelpers
{
    /**
     * @var array<string>
     */
    private static array $integrations = [];

    /**
     * @param string $integration
     * @return void
     */
    public static function registerIntegration(string $integration): void
    {
        if (in_array($integration, self::$integrations)) {
            throw new IntegrationRegistryException(
                'This integration is already registered, please choose another name!'
            );
        }

        self::$integrations[] = $integration;
    }

    /**
     * @param string $integration
     * @return void
     */
    public static function checkIntegration(string $integration): void
    {
        if (!in_array($integration, self::$integrations)) {
            throw new IntegrationRegistryException(
                'This integration is not registered, please register first with the registerIntegration method!'
            );
        }
    }

    /**
     * @param string $modelClass
     * @return void
     */
    public static function registerModel(string $modelClass): void
    {
        if (class_exists($modelClass)) {
            $model = new $modelClass();
            if (!($model instanceof AbstractTransaction)) {
                throw new \Exception('Model must be an instance of AbstractTransaction');
            }

            Hook::addFilter('models', function (array $models) use ($model): array {
                /** @disregard */
                return array_merge($models, [
                    $model->addon => $model
                ]);
            });
        }
    }

    /**
     * @return int
     */
    public static function getCurrentUserId(): int
    {
        $user = wp_get_current_user();
        return $user->ID ?? 0;
    }

    /**
     * @return string|null
     */
    public static function getCurrentUserEmail(): ?string
    {
        $user = wp_get_current_user();
        return $user->user_email ?? null; // phpcs:ignore
    }

    /**
     * @param string $hash
     * @param string $addon
     * @return string
     */
    public static function getSingleTxLink(string $hash, string $addon): string
    {
        return sprintf(admin_url('admin.php?page=%s_%s_transactions&s=%s'), self::getProp('pluginKey'), $addon, $hash);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getSetting(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }

    /**
     * @return array<string,AbstractTransaction>
     */
    public static function getModels(): array
    {
        return Hook::callFilter('models', [
            'woocommerce' => new OrderTransaction()
        ]);
    }

    /**
     * @param string $addon
     * @return string
     */
    public static function getMode(string $addon): string
    {
        $mode = self::getSetting('mode');
        $mode = $mode ? $mode : 'network';
        $mode = Hook::callFilter('mode', $mode);
        return Hook::callFilter('mode_' . $addon, $mode);
    }

    /**
     * @param string $addon
     * @return array<mixed>
     */
    public static function getTheme(string $addon): array
    {
        $themeLight = Helpers::getSetting('themeLight', [
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
        ]);

        $themeDark = Helpers::getSetting('themeDark', [
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
        ]);

        $theme = [
            'mode' => Helpers::getSetting('themeMode', 'light'),
            'style' => [
                'light' => [
                    'text' => $themeLight['text'],
                    'discount' => $themeLight['discount'],
                    'border' => $themeLight['border'],
                    'primary' => $themeLight['primary'],
                    'secondary' => $themeLight['secondary'],
                    'tertiary' => $themeLight['tertiary'],
                    'background' => $themeLight['background'],
                    'boxShadow' => $themeLight['boxShadow'],
                    'button' => [
                        'color' => $themeLight['buttonColor'],
                        'disabled' => $themeLight['buttonDisabled'],
                        'background' => $themeLight['buttonBackground'],
                        'hoverBackground' => $themeLight['buttonHoverBackground']
                    ]
                ],
                'dark' => [
                    'text' => $themeDark['text'],
                    'discount' => $themeDark['discount'],
                    'border' => $themeDark['border'],
                    'primary' => $themeDark['primary'],
                    'secondary' => $themeDark['secondary'],
                    'tertiary' => $themeDark['tertiary'],
                    'background' => $themeDark['background'],
                    'boxShadow' => $themeDark['boxShadow'],
                    'button' => [
                        'color' => $themeDark['buttonColor'],
                        'disabled' => $themeDark['buttonDisabled'],
                        'background' => $themeDark['buttonBackground'],
                        'hoverBackground' => $themeDark['buttonHoverBackground']
                    ]
                ]
            ]
        ];

        return Hook::callFilter('theme_' . $addon, Hook::callFilter('theme', $theme));
    }

    /**
     * @param string $addon
     * @return AbstractTransaction|null
     */
    public static function getModelByAddon(string $addon): ?AbstractTransaction
    {
        return self::getModels()[$addon] ?? null;
    }

    /**
     * @param string $addon
     * @return string
     */
    public static function getTestnetStatus(): bool
    {
        return boolval(self::getSetting('testnet'));
    }

    /**
     * @param TransactionType $transaction
     * @return Provider
     */
    public static function getProvider(TransactionType $transaction): Provider
    {
        $provider = self::getProviders()[$transaction->getCode()] ?? null;

        if ($provider) {
            return new Provider($transaction, $provider);
        } else {
            throw new \Exception('Provider not found!');
        }
    }

    /**
     * @param string $code
     * @return boolean
     */
    public static function providerExists(string $code): bool
    {
        return isset(self::getProviders()[$code]);
    }

    /**
     * @return array<mixed>
     */
    public static function getProviders(): array
    {
        return Hook::callFilter('php_providers', [
            'evmchains' => [
                'transaction' => Transaction::class,
                'provider' => EvmChainsProvider::class,
                'coinTransaction' => CoinTransaction::class,
                'tokenTransaction' => TokenTransaction::class
            ]
        ]);
    }

    /**
     * @param NetworkType $network
     * @return string|null
     */
    public static function getWalletAddress(NetworkType $network): ?string
    {
        $walletAddress = self::getSetting($network->getCode() . 'WalletAddress') ?? null;
        return Hook::callFilter('wallet_address_' . $network->getCode(), $walletAddress);
    }

    /**
     * @param string $code
     * @return integer
     */
    public static function getBlockConfirmationCount(string $code): int
    {
        return self::getSetting($code . 'BlockConfirmationCount') ?? 0;
    }

    /**
     * @param PaymentDataType $data
     * @return string
     */
    public static function getReceiver(PaymentDataType $data): string
    {
        self::debug('Getting receiver address', 'INFO', $data->forDebug());

        $receiver = self::getWalletAddress($data->getNetwork());
        $receiver = Hook::callFilter('receiver', $receiver, $data);
        $receiver = Hook::callFilter('receiver_' . $data->getAddon(), $receiver, $data);
        return Hook::callFilter('receiver_' . $data->getNetwork()->getCode(), $receiver, $data);
    }

    /**
     * @param TransactionType $transaction
     * @return string
     */
    public static function getPaymentHtmlDetails(TransactionType $transaction): string
    {
        $order = $transaction->getOrder();
        $currency = $order->getPaymentCurrency();
        $amount = self::toString($order->getPaymentAmount(), $currency->getDecimals());

        if (Helpers::providerExists($transaction->getCode())) {
            $transactionUrl = (Helpers::getProvider($transaction))->transaction($transaction->getHash())->getUrl();
        } else {
            $transactionUrl = null;
        }

        // calculate real amount
        if ($order->getDiscountRate()) {
            $realAmount = self::fromPercent(
                $order->getPaymentAmount(),
                $order->getDiscountRate(),
                $currency->getDecimals()
            );
        } else {
            $realAmount = null;
        }

        // add styles
        self::addStyle('main.min.css');

        return self::view('details', compact(
            'order',
            'amount',
            'currency',
            'realAmount',
            'transaction',
            'transactionUrl',
        ));
    }

    /**
     * @param float $amount
     * @param float $percent
     * @param int $decimals
     * @return string
     */
    public static function fromPercent(float $amount, float $percent, int $decimals): string
    {
        return self::toString(self::toFixed($amount / (1 - ($percent / 100)), 6), $decimals);
    }

    /**
     * @param array<array<string,mixed>> $mainnetCurrencies
     * @param array<array<string,mixed>> $testnetCurrencies
     * @return array<CurrencyType>
     */
    public static function prepareCurrencies(array $mainnetCurrencies, array $testnetCurrencies = []): array
    {
        $currencies = [];

        if (self::getTestnetStatus()) {
            $currencies = array_merge($currencies, $testnetCurrencies);
        } else {
            foreach ($mainnetCurrencies as $currency) {
                if (isset($currency['active']) && '1' == $currency['active']) {
                    $address = trim($currency['address']);
                    $symbol = trim(strtoupper($currency['symbol']));
                    $imageUrl = sanitize_text_field($currency['image']);
                    $currencies[] = CurrencyType::fromArray(compact('address', 'symbol', 'imageUrl'));
                }
            }
        }

        return $currencies;
    }

    /**
     * @param string $networkName
     * @return void
     */
    public static function networkWillNotWorkMessage(string $networkName): void
    {
        // @phpcs:ignore
        self::adminNotice(str_replace('{networkName}', $networkName, esc_html__('You did not specify a wallet address in the "CryptoPay Lite {networkName} settings", {networkName} network will not work. Please specify a wallet address first.', 'cryptopay')), 'error');
    }
}

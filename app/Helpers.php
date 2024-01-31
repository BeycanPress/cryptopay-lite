<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

// Classes
use MultipleChain\EvmChains\Provider;
use BeycanPress\CryptoPayLite\PluginHero\Hook;
use BeycanPress\CryptoPayLite\Settings\Settings;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
use BeycanPress\CryptoPayLite\Models\OrderTransaction;
use BeycanPress\CryptoPayLite\Models\AbstractTransaction;
use BeycanPress\CryptoPayLite\PluginHero\Helpers as PhHelpers;
// Types
use BeycanPress\CryptoPayLite\Types\Network\CurrencyType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Network\NetworksType;
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
        return $user->user_email ?? null;
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
     * @param string $code
     * @param boolean $keys
     * @return array<string,boolean>
     */
    public static function getWalletsByCode(string $code, bool $keys = true): array
    {
        $wallets = self::getSetting($code . 'Wallets') ?? [];

        $wallets = array_filter($wallets, function ($val) {
            return boolval($val);
        });

        return $keys ? array_keys($wallets) : $wallets;
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
     * @param string $code
     * @return int
     */
    public static function getQrCodeWaitingTime(string $code): int
    {
        return self::getSetting($code . 'QrCodeWaitingTime') ?? 30;
    }

    /**
     * @param string $code
     * @return string|null
     */
    public static function getWeb3DomainByCode(string $code): ?string
    {
        return self::getSetting($code . 'Web3Domain') ?? null;
    }

    /**
     * @param TransactionType $transaction
     * @return object
     */
    public static function getProvider(TransactionType $transaction): object
    {
        $providers = Hook::callFilter('php_providers', [
            'evmchains' => Provider::class
        ]);

        $provider = $providers[$transaction->getCode()] ?? null;

        if ($provider) {
            $network = $transaction->getNetwork();
            return new $provider([
                'network' => $network->toObject(),
                'rpcUrl' => $network->getRpcUrl(),
                'testnet' => $transaction->getTestnet(),
            ]);
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
        $providers = Hook::callFilter('php_providers', [
            'evmchains' => Provider::class
        ]);

        return isset($providers[$code]);
    }

    /**
     * @param NetworkType $network
     * @return string|null
     */
    public static function getWalletAddress(NetworkType $network): ?string
    {
        $walletAddress = self::getSetting($network->getCode() . 'WalletAddress') ?? null;

        if ($network->getCode() == 'evmchains') {
            $evmNetworks = self::getSetting('evmchainsNetworks') ?? [];
            $evmNetworks = array_filter($evmNetworks, function ($val) use ($network) {
                return $val['id'] == $network->getMainnetId() ?? $network->getId();
            });

            if (count($evmNetworks) > 0) {
                $evmNetwork = array_values($evmNetworks)[0];
                if (!empty($evmNetwork['walletAddress'])) {
                    $walletAddress = $evmNetwork['walletAddress'];
                }
            }
        }

        return Hook::callFilter('wallet_address_' . $network->getCode(), $walletAddress);
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
            $transactionUrl = (Helpers::getProvider($transaction))->Transaction($transaction->getHash())->getUrl();
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
     * @param mixed|null $filter
     * @return NetworksType
     */
    public static function getNetworks(mixed $filter = null): NetworksType
    {
        $networkCodes = self::getNetworkCodes();
        /** @var NetworksType $networks */
        $networks = Hook::callFilter('networks', new NetworksType());

        $networksArray = [];
        /** @var NetworkType $network */
        foreach ($networks->all() as $network) {
            $networksArray[$network->getCode()] = $network;
        }

        $orderedNetworks = [];
        foreach ($networkCodes as $value) {
            if ($value == 'evmchains') {
                $evmNetworks = EvmChains::getNetworks()->all();
                $orderedNetworks = array_merge($orderedNetworks, $evmNetworks);
            } elseif (isset($networksArray[$value])) {
                $orderedNetworks[] = $networksArray[$value];
            }
        }

        if (!is_null($filter)) {
            if (is_numeric($filter)) {
                $orderedNetworks = array_values(array_filter($orderedNetworks, function ($val) use ($filter) {
                    return $val->getId() == $filter;
                }));
            } else {
                $orderedNetworks = array_values(array_filter($orderedNetworks, function ($val) use ($filter) {
                    return $val->getCode() == $filter;
                }));
            }
        }

        return new NetworksType($orderedNetworks);
    }

    /**
     * @return array<string>
     */
    public static function getNetworkCodes(): array
    {
        $networks = Hook::callFilter('networks', new NetworksType());
        $networksKeys = array_column($networks->toArray(), 'code');

        if (self::getSetting('evmchainsActivePassive')) {
            $networksKeys[] = 'evmchains';
        }

        $networkSorting = self::getSetting('networkSorting');
        $networkSorting = $networkSorting ? array_keys($networkSorting) : [];
        $networkSorting = array_unique(array_merge($networkSorting, $networksKeys));
        return array_filter($networkSorting, function ($val) use ($networksKeys) {
            return in_array($val, $networksKeys);
        });
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
                if (isset($currency['active']) && $currency['active'] == '1') {
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
        self::adminNotice(str_replace('{networkName}', $networkName, esc_html__('You did not specify a wallet address in the "CryptoPay {networkName} settings", {networkName} network will not work. Please specify a wallet address first.', 'cryptopay_lite')), 'error');
    }
}

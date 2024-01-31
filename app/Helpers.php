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
        return Hook::callFilter('wallet_address_' . $network->getCode(), $walletAddress);
    }

    /**
     * @return int
     */
    public static function getBlockConfirmationCount(): int
    {
        return 10;
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
        self::adminNotice(str_replace('{networkName}', $networkName, esc_html__('You did not specify a wallet address in the "CryptoPay Lite {networkName} settings", {networkName} network will not work. Please specify a wallet address first.', 'cryptopay_lite')), 'error');
    }
}

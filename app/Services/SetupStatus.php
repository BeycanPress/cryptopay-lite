<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

// @phpcs:disable Generic.Files.LineLength

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Constants;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
use BeycanPress\CryptoPayLite\Pages\SetupWizard;

/**
 * Single source of truth for "will CryptoPay actually show up at checkout?".
 *
 * Both the setup wizard and the admin status badge read from here, so the
 * two can never disagree about what is missing.
 *
 * @since 2.4.0
 */
class SetupStatus
{
    /**
     * @var string
     */
    public const OK = 'ok';

    /**
     * @var string
     */
    public const ERROR = 'error';

    /**
     * @var string
     */
    public const WARNING = 'warning';

    /**
     * WooCommerce stores gateway options under this key.
     * @var string
     */
    public const GATEWAY_OPTION = 'woocommerce_' . Constants::GATEWAY_ID . '_settings';

    /**
     * @return bool
     */
    public static function wooCommerceActive(): bool
    {
        return class_exists('WooCommerce');
    }

    /**
     * @return bool
     */
    public static function hasWalletAddress(): bool
    {
        return 42 === strlen((string) Helpers::getSetting('evmchainsWalletAddress'));
    }

    /**
     * Read straight from the option rather than through WC()->payment_gateways(),
     * because the gateway is not even registered until a wallet address exists.
     * @return bool
     */
    public static function gatewayEnabled(): bool
    {
        $settings = get_option(self::GATEWAY_OPTION, []);
        return is_array($settings) && 'yes' === ($settings['enabled'] ?? 'no');
    }

    /**
     * @return string
     */
    public static function gatewayUrl(): string
    {
        return admin_url('admin.php?page=wc-settings&tab=checkout&section=' . Constants::GATEWAY_ID);
    }

    /**
     * True when the shop's checkout page is built with the WooCommerce checkout
     * block, in which case the "checkout" payment receiving area does not work.
     * @return bool
     */
    public static function checkoutUsesBlocks(): bool
    {
        if (!function_exists('wc_get_page_id') || !function_exists('has_block')) {
            return false;
        }

        $pageId = wc_get_page_id('checkout');

        return $pageId > 0 && has_block('woocommerce/checkout', $pageId);
    }

    /**
     * Reown AppKit's connect() builds the modal on first use and throws when the
     * project ID is empty, so the option fails the moment a customer picks it.
     * The rest of the payment form is unaffected: the throw is caught and turned
     * into a rejected promise.
     *
     * Reads the raw setting on purpose. EvmChains::getWallets() already hides the
     * option in this exact case, so asking it would always answer "fine".
     * @return bool
     */
    public static function appKitBroken(): bool
    {
        if (EvmChains::hasProjectId()) {
            return false;
        }

        $wallets = Helpers::getSetting('evmchainsWallets', []);
        $wallets = is_array($wallets) ? $wallets : [];

        foreach (EvmChains::walletsRequiringProjectId() as $key) {
            if (!empty($wallets[$key])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public static function checks(): array
    {
        $checks = [];

        $checks['woocommerce'] = self::wooCommerceActive()
            ? self::pass(esc_html__('WooCommerce is active.', 'cryptopay'))
            : self::fail(
                esc_html__('WooCommerce is not active. CryptoPay Lite is a WooCommerce payment gateway, so nothing will appear until WooCommerce is installed and activated.', 'cryptopay'),
                esc_html__('Install WooCommerce', 'cryptopay'),
                admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')
            );

        $checks['walletAddress'] = self::hasWalletAddress()
            ? self::pass(esc_html__('A wallet address is set. Payments will be sent straight to it.', 'cryptopay'))
            : self::fail(
                esc_html__('No wallet address is set. Without one CryptoPay Lite does not load at all, and the payment gateway is never registered with WooCommerce.', 'cryptopay'),
                esc_html__('Add wallet address', 'cryptopay'),
                self::wizardUrl('address')
            );

        $checks['gateway'] = self::gatewayEnabled()
            ? self::pass(esc_html__('The payment gateway is enabled in WooCommerce.', 'cryptopay'))
            : self::fail(
                self::hasWalletAddress()
                    ? esc_html__('The payment gateway is switched off in WooCommerce. It ships off by default, and this is the single most common reason CryptoPay does not appear at checkout.', 'cryptopay')
                    : esc_html__('The payment gateway cannot be enabled yet. Add a wallet address first, otherwise the gateway does not exist in WooCommerce settings.', 'cryptopay'),
                esc_html__('Enable gateway', 'cryptopay'),
                self::wizardUrl('gateway')
            );

        $networks = EvmChains::getNetworks();
        $checks['networks'] = [] !== $networks
            ? self::pass(
                sprintf(
                    // translators: %d: number of active blockchain networks.
                    esc_html__('%d blockchain networks are active.', 'cryptopay'),
                    count($networks)
                )
            )
            : self::fail(
                esc_html__('No blockchain network is active. Customers would have nothing to pay with, and the payment form fails to load.', 'cryptopay'),
                esc_html__('Choose networks', 'cryptopay'),
                self::wizardUrl('networks')
            );

        $wallets = EvmChains::getWallets();
        $checks['wallets'] = [] !== $wallets
            ? self::pass(
                sprintf(
                    // translators: %d: number of enabled customer wallets.
                    esc_html__('%d wallets are available to customers.', 'cryptopay'),
                    count($wallets)
                )
            )
            : self::fail(
                esc_html__('No wallet is enabled, so customers have no way to connect and pay.', 'cryptopay'),
                esc_html__('Choose wallets', 'cryptopay'),
                self::wizardUrl('wallets')
            );

        if (self::appKitBroken()) {
            $checks['appKit'] = self::warn(
                esc_html__('A mobile wallet option is switched on but there is no Reown AppKit ID, so it cannot connect. It is being hidden from customers rather than failing when they click it. Add a Project ID to offer mobile wallets. The rest of checkout is unaffected.', 'cryptopay'),
                esc_html__('Add a Project ID', 'cryptopay'),
                self::wizardUrl('appkit')
            );
        }

        if ('checkout' === Helpers::getSetting('paymentReceivingArea') && self::checkoutUsesBlocks()) {
            $checks['blockCheckout'] = self::warn(
                esc_html__('Your payment receiving area is set to "Checkout", but your checkout page uses the WooCommerce checkout block. That combination only works on the classic checkout. Switch the setting to "Order pay", or rebuild the checkout page with the [woocommerce_checkout] shortcode.', 'cryptopay'),
                esc_html__('Open settings', 'cryptopay'),
                admin_url('admin.php?page=' . Helpers::getProp('settingKey'))
            );
        }

        return $checks;
    }

    /**
     * @return bool
     */
    public static function isReady(): bool
    {
        foreach (self::checks() as $check) {
            if (self::ERROR === $check['status']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string>
     */
    public static function failingLabels(): array
    {
        $labels = [];

        foreach (self::checks() as $check) {
            if (self::ERROR === $check['status']) {
                $labels[] = $check['message'];
            }
        }

        return $labels;
    }

    /**
     * @param string $step
     * @return string
     */
    private static function wizardUrl(string $step): string
    {
        return SetupWizard::wizardUrl($step);
    }

    /**
     * @param string $message
     * @return array<string,mixed>
     */
    private static function pass(string $message): array
    {
        return ['status' => self::OK, 'message' => $message, 'action' => null];
    }

    /**
     * @param string $message
     * @param string $actionText
     * @param string $actionUrl
     * @return array<string,mixed>
     */
    private static function fail(string $message, string $actionText, string $actionUrl): array
    {
        return [
            'status' => self::ERROR,
            'message' => $message,
            'action' => ['text' => $actionText, 'url' => $actionUrl],
        ];
    }

    /**
     * @param string $message
     * @param string $actionText
     * @param string $actionUrl
     * @return array<string,mixed>
     */
    private static function warn(string $message, string $actionText, string $actionUrl): array
    {
        return [
            'status' => self::WARNING,
            'message' => $message,
            'action' => ['text' => $actionText, 'url' => $actionUrl],
        ];
    }
}

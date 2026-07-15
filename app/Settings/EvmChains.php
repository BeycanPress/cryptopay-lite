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
        8453 => 84532,
        56 => 97,
        43114 => 43113,
        137 => 80002,
        42161 => 421614,
        10 => 11155420,
    ];

    /**
     * The single source of truth for the network switchers. The settings fields
     * and the setup wizard are both generated from this, so they cannot drift.
     * @return array<string,array<string,mixed>>
     */
    public static function networkList(): array
    {
        return [
            'id_1'     => ['title' => esc_html('Ethereum'), 'default' => true],
            'id_8453'  => ['title' => esc_html('Base'), 'default' => true],
            'id_56'    => ['title' => esc_html('BNB Smart Chain'), 'default' => true],
            'id_43114' => ['title' => esc_html('Avalanche C-Chain'), 'default' => true],
            'id_137'   => ['title' => esc_html('Polygon'), 'default' => true],
            'id_42161' => ['title' => esc_html('Arbitrum One'), 'default' => true],
            'id_10'    => ['title' => esc_html('Optimism'), 'default' => true],
        ];
    }

    /**
     * The single source of truth for the wallet switchers.
     *
     * WalletConnect (legacy) defaults to off because Reown AppKit already covers
     * it, and its own description tells users to disable it when AppKit is on.
     * @param string|null $group only return wallets in this group
     * @return array<string,array<string,mixed>>
     */
    public static function walletList(?string $group = null): array
    {
        $wallets = [
            'metamask'     => ['title' => esc_html('MetaMask'), 'default' => true, 'group' => 'browser'],
            'trustwallet'  => ['title' => esc_html('Trust Wallet'), 'default' => true, 'group' => 'browser'],
            'bitgetwallet' => ['title' => esc_html('Bitget Wallet'), 'default' => true, 'group' => 'browser'],
            'okxwallet'    => ['title' => esc_html('Okx Wallet'), 'default' => true, 'group' => 'browser'],
            'xdefiwallet'  => ['title' => esc_html('Xdefi Wallet'), 'default' => true, 'group' => 'browser'],
            'walletconnect' => [
                'title'   => esc_html('WalletConnect (legacy)'),
                'default' => false,
                'group'   => 'legacy',
                'requiresProjectId' => true,
                'desc'    => esc_html__('If you enabled Web3Wallets, please disable it.', 'cryptopay') // @phpcs:ignore
                . CPL_BR2 .
                esc_html__('Also needs a Reown AppKit ID: it opens a WalletConnect relay session, which will not start without one.', 'cryptopay'), // @phpcs:ignore
            ],
            'web3wallets' => [
                'title'   => esc_html('Web3 Wallets (Reown AppKit - formerly Web3Modal)'),
                'default' => false,
                'group'   => 'appkit',
                'requiresProjectId' => true,
                'desc'    => esc_html__('It is a module within Reown AppKit that supports hundreds of wallets with WalletConnect support. Since all the above wallets are already supported, you can deactivate all other wallets and allow users to make transactions only through Reown AppKit.', 'cryptopay') // @phpcs:ignore
                . CPL_BR2 .
                esc_html__('Requires a Reown AppKit ID in the general settings. Without one it cannot connect, so the option stays hidden from customers rather than failing when they click it.', 'cryptopay'), // @phpcs:ignore
            ],
        ];

        if (is_null($group)) {
            return $wallets;
        }

        return array_filter($wallets, fn($wallet) => $group === $wallet['group']);
    }

    /**
     * Wallets that cannot connect without a Reown AppKit project id. Both of them
     * reach Reown's relay: AppKit builds its modal with the id, and legacy
     * WalletConnect opens a relay session with it.
     * @return array<string>
     */
    public static function walletsRequiringProjectId(): array
    {
        return array_keys(array_filter(
            self::walletList(),
            fn($wallet) => !empty($wallet['requiresProjectId'])
        ));
    }

    /**
     * @return bool
     */
    public static function hasProjectId(): bool
    {
        return '' !== trim((string) Helpers::getSetting('wcProjectId'));
    }

    /**
     * Keeps the project-id-dependent wallets honest on every settings save.
     *
     * CSF calls a field's own validate() with only that field's value, so it
     * cannot see wcProjectId; this filter gets the whole posted payload just
     * before it is written, which is the only place the two can be compared.
     * @param array<string,mixed> $data
     * @param object $csf
     * @return array<string,mixed>
     */
    public static function enforceProjectIdRequirement(array $data, object $csf): array
    {
        if (!isset($data['evmchainsWallets']) || !is_array($data['evmchainsWallets'])) {
            return $data;
        }

        if ('' !== trim((string) ($data['wcProjectId'] ?? ''))) {
            return self::enableAppKitOnNewProjectId($data, $csf);
        }

        $blocked = [];
        $wallets = self::walletList();

        foreach (self::walletsRequiringProjectId() as $key) {
            if (empty($data['evmchainsWallets'][$key])) {
                continue;
            }

            $data['evmchainsWallets'][$key] = false;
            $blocked[] = $wallets[$key]['title'];
        }

        if ([] !== $blocked) {
            // Set after the fact on purpose: CSF hands these errors to the client
            // right after this filter, so the switch flipping back is explained
            // rather than looking like the save silently failed.
            $csf->errors['evmchainsWallets'] = sprintf(
                // translators: %s: comma separated list of wallet names.
                esc_html__('%s cannot be enabled without a Reown AppKit ID, because they cannot connect without one. Enter an ID in the general settings first.', 'cryptopay'), // @phpcs:ignore
                implode(', ', $blocked)
            );
        }

        return $data;
    }

    /**
     * Anyone who goes and fetches a Reown AppKit ID wants the mobile wallets it
     * unlocks, so turn AppKit on for them rather than leaving them to hunt for
     * the switch that only just appeared.
     *
     * Deliberately only on the transition from "no id" to "id": if they later
     * switch AppKit off on purpose, that decision stands. Legacy WalletConnect is
     * never touched, since having it on alongside AppKit is the conflict its own
     * description warns about.
     * @param array<string,mixed> $data
     * @param object $csf
     * @return array<string,mixed>
     */
    private static function enableAppKitOnNewProjectId(array $data, object $csf): array
    {
        // hasProjectId() still reports the stored value here: the filter runs
        // before CSF writes the option.
        if (self::hasProjectId() || !empty($data['evmchainsWallets']['web3wallets'])) {
            return $data;
        }

        $data['evmchainsWallets']['web3wallets'] = true;

        // CSF only falls back to "Settings saved." when the notice is still empty,
        // so this replaces it rather than being overwritten.
        $csf->notice = esc_html__('Settings saved. Reown AppKit was switched on, so your customers can now pay from mobile wallets.', 'cryptopay'); // @phpcs:ignore

        return $data;
    }

    /**
     * @param array<string,array<string,mixed>> $list
     * @return array<array<string,mixed>>
     */
    private static function buildSwitcherFields(array $list): array
    {
        $fields = [];

        foreach ($list as $id => $item) {
            $field = [
                'id'      => $id,
                'title'   => $item['title'],
                'type'    => 'switcher',
                'default' => $item['default'],
            ];

            if (isset($item['desc'])) {
                $field['desc'] = $item['desc'];
            }

            // Hide the switch outright until there is an id to give it, so the
            // only way to turn it on is to first make it work. The save filter
            // still enforces this server-side; this is just the polite half.
            //
            // The fourth element marks the rule global. Without it CSF resolves
            // the controller within the field's own container only, and
            // wcProjectId lives in another section, so the rule would never match
            // and the switch would stay hidden forever.
            if (!empty($item['requiresProjectId'])) {
                $field['dependency'] = ['wcProjectId', '!=', '', true];
            }

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * @param array<string,array<string,mixed>> $list
     * @return array<string,bool>
     */
    private static function defaultsOf(array $list): array
    {
        return array_map(fn($item) => (bool) $item['default'], $list);
    }

    /**
     * CSF only writes its defaults to the database when the whole settings option
     * is still empty. Anything that writes a setting before the settings page has
     * ever been opened (the setup wizard) would therefore leave the network and
     * wallet lists absent, which reads back as "nothing is active" and breaks
     * checkout. So seed them explicitly.
     * @return void
     */
    public static function ensureDefaults(): void
    {
        if (!is_array(Settings::get('evmchainsNetworks'))) {
            Settings::update('evmchainsNetworks', self::defaultsOf(self::networkList()));
        }

        if (!is_array(Settings::get('evmchainsWallets'))) {
            Settings::update('evmchainsWallets', self::defaultsOf(self::walletList()));
        }
    }

    /**
     * @return void
     */
    public static function initSettings(): void
    {
        add_filter(
            'csf_' . Settings::getSettingKey() . '_save',
            [self::class, 'enforceProjectIdRequirement'],
            10,
            2
        );

        $proMsg = '<div style="display:flex;align-items:center">' . sprintf(esc_html__('This is a premium feature => %s', 'cryptopay'), '<a href="https://beycanpress.com/chekcout/?add-to-cart=800&utm_source=lite_version&utm_medium=plugin_settings" target="_blank" class="button" style="margin-left: 10px">' . esc_html__('Buy premium', 'cryptopay') . '</a>') . '</div><br>';

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
                    'fields' => self::buildSwitcherFields(self::walletList()),
                ],
                [
                    'id'      => 'evmchainsNetworks',
                    'title'   => esc_html__('Networks', 'cryptopay'),
                    'help'    => esc_html__('Specify the networks you want to accept payments from.', 'cryptopay'),
                    'type'    => 'fieldset',
                    'desc'    => esc_html__('Unlimited and custom network support is only available in premium. As with MetaMask, you can add any EVM network you want with its information.', 'cryptopay'),
                    'fields' => self::buildSwitcherFields(self::networkList()),
                ],
                [
                    'id'      => 'buyPremiumForCustomNetworks',
                    'title'   => esc_html__('Unlimited network and currency', 'cryptopay'),
                    'type'    => 'content',
                    'content' => esc_html__('Get the premium to get paid with unlimited EVM blockchain network and any cryptocurrency (token) you want!', 'cryptopay') . CPL_BR2 . '<a href="https://beycanpress.com/product/cryptopay-all-in-one-cryptocurrency-payments-for-wordpress/?utm_source=plugin_settings&utm_medium=evm_settings&utm_campaign=unlimited_network" target="_blank">' . esc_html__('Buy premium now', 'cryptopay') . '</a>'
                ],
                [
                    'id'      => 'evmchainsQrPayments',
                    'title'   => esc_html__('QR payments', 'cryptopay'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('By defining a Websocket URL for your networks, you can allow your customers to pay by transferring to your address with a QR code, without connecting a wallet.', 'cryptopay'),
                ],
                [
                    'id'      => 'evmchainsNetworkWalletAddress',
                    'title'   => esc_html__('Network-specific wallet address', 'cryptopay'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('Instead of a single general address, you can define a different wallet address for each blockchain network and collect the payments of each network in a separate wallet.', 'cryptopay'),
                ],
                [
                    'id'      => 'evmchainsWeb3Domain',
                    'title'   => esc_html__('Web3 domain', 'cryptopay'),
                    'type'    => 'content',
                    'content' => $proMsg . esc_html__('You can define a Web3 domain such as "beycanpress.eth" for your networks and show your domain to your customers instead of your wallet address.', 'cryptopay'),
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
        $wallets = is_array($wallets) ? $wallets : [];

        $wallets = array_filter($wallets, function ($val) {
            return boolval($val);
        });

        // These throw on connect() without a project id, so they are dead the
        // moment a customer picks them. Never offer a wallet that cannot work.
        // This also quietly repairs sites that were shipped with AppKit on by
        // default and never had an id to give it.
        if (!self::hasProjectId()) {
            foreach (self::walletsRequiringProjectId() as $key) {
                unset($wallets[$key]);
            }
        }

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

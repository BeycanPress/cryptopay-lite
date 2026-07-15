<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

// @phpcs:disable Generic.Files.LineLength
// @phpcs:disable WordPress.Security.NonceVerification.Recommended

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Page;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
use BeycanPress\CryptoPayLite\Settings\Settings;
use BeycanPress\CryptoPayLite\Services\SetupStatus;

/**
 * Guided setup. Walks the merchant through the settings that decide whether
 * CryptoPay actually appears at checkout, in the only order that works:
 * the wallet address has to exist before WooCommerce even knows the gateway does.
 *
 * @since 2.4.0
 */
class SetupWizard extends Page
{
    /**
     * @var string
     */
    public const SLUG = 'cryptopay_lite_setup';

    /**
     * @var string
     */
    public const ACTION = 'cryptopay_lite_setup_save';

    /**
     * @var string
     */
    public const REDIRECT_OPTION = 'cryptopay_lite_do_setup_redirect';

    /**
     * @var string
     */
    public const COMPLETED_OPTION = 'cryptopay_lite_setup_completed';

    /**
     * @var string
     */
    private const INPUT_TRANSIENT = 'cryptopay_lite_setup_input_';

    /**
     * @var array<string>
     */
    private array $steps = ['address', 'gateway', 'networks', 'wallets', 'appkit'];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'pageName' => esc_html__('Setup', 'cryptopay'),
            'slug' => self::SLUG,
            'hidden' => true,
            'priority' => 1,
        ]);

        add_action('admin_post_' . self::ACTION, [$this, 'handleSubmit']);
        add_action('admin_init', [$this, 'maybeRedirectAfterActivation']);

        add_action('admin_footer', function (): void {
            if (get_current_screen() && str_contains((string) get_current_screen()->id, self::SLUG)) {
                Helpers::viewEcho('css/setup-wizard-css');
            }
        });
    }

    /**
     * @param string|null $step
     * @return string
     */
    public static function wizardUrl(?string $step = null): string
    {
        $args = ['page' => self::SLUG];

        if (!is_null($step)) {
            $args['step'] = $step;
        }

        return add_query_arg($args, admin_url('admin.php'));
    }

    /**
     * @return void
     */
    public function maybeRedirectAfterActivation(): void
    {
        if (!get_option(self::REDIRECT_OPTION)) {
            return;
        }

        delete_option(self::REDIRECT_OPTION);

        // Bulk activation sends the user to a summary screen of its own; hijacking
        // that would be hostile, and WordPress tells us it is happening.
        if (isset($_GET['activate-multi']) || !current_user_can('manage_options')) {
            return;
        }

        wp_safe_redirect($this->url);
        exit;
    }

    /**
     * @return string
     */
    private function currentStep(): string
    {
        $step = isset($_GET['step']) ? sanitize_key(wp_unslash($_GET['step'])) : '';

        if ('done' === $step || in_array($step, $this->steps, true)) {
            return $step;
        }

        return $this->firstUnfinishedStep();
    }

    /**
     * @return string
     */
    private function firstUnfinishedStep(): string
    {
        if (!SetupStatus::hasWalletAddress()) {
            return 'address';
        }

        if (!SetupStatus::gatewayEnabled()) {
            return 'gateway';
        }

        if ([] === EvmChains::getNetworks()) {
            return 'networks';
        }

        if ([] === EvmChains::getWallets()) {
            return 'wallets';
        }

        return 'appkit';
    }

    /**
     * @param string $step
     * @return string
     */
    private function nextStep(string $step): string
    {
        $index = array_search($step, $this->steps, true);

        if (false === $index || $index === count($this->steps) - 1) {
            return 'done';
        }

        return $this->steps[$index + 1];
    }

    /**
     * @param string $step
     * @param array<string> $errors
     * @return string
     */
    private function stepUrl(string $step, array $errors = []): string
    {
        $url = self::wizardUrl($step);

        if ([] !== $errors) {
            $url = add_query_arg('cpl_error', rawurlencode(implode('|', $errors)), $url);
        }

        return $url;
    }

    /**
     * @return void
     */
    public function handleSubmit(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to do this.', 'cryptopay'));
        }

        check_admin_referer(self::ACTION);

        $step = isset($_POST['step']) ? sanitize_key(wp_unslash($_POST['step'])) : '';

        if (!in_array($step, $this->steps, true)) {
            wp_safe_redirect($this->stepUrl($this->firstUnfinishedStep()));
            exit;
        }

        // Seed the switcher lists before writing anything else, otherwise the very
        // first write creates the settings option and CSF never persists its own
        // defaults, leaving every network and wallet switched off.
        EvmChains::ensureDefaults();

        if (isset($_POST['skip'])) {
            $this->finishIfLastStep($step);
            wp_safe_redirect($this->stepUrl($this->nextStep($step)));
            exit;
        }

        $errors = $this->save($step);

        if ([] !== $errors) {
            $this->rememberInput([
                'walletAddress' => isset($_POST['walletAddress'])
                    ? sanitize_text_field(wp_unslash($_POST['walletAddress']))
                    : '',
                'projectId' => isset($_POST['projectId'])
                    ? sanitize_text_field(wp_unslash($_POST['projectId']))
                    : '',
            ]);

            wp_safe_redirect($this->stepUrl($step, $errors));
            exit;
        }

        $this->finishIfLastStep($step);
        wp_safe_redirect($this->stepUrl($this->nextStep($step)));
        exit;
    }

    /**
     * @param string $step
     * @return void
     */
    private function finishIfLastStep(string $step): void
    {
        if ('done' === $this->nextStep($step)) {
            update_option(self::COMPLETED_OPTION, true);
        }
    }

    /**
     * Carries what the user typed across the redirect that follows a failed step,
     * so a rejected address does not have to be typed out again.
     *
     * Held in a transient rather than the query string: it keeps the wallet
     * address out of browser history and server access logs, and off the screen
     * of anyone reading over their shoulder.
     * @param array<string,string> $values
     * @return void
     */
    private function rememberInput(array $values): void
    {
        set_transient(self::INPUT_TRANSIENT . get_current_user_id(), $values, MINUTE_IN_SECONDS);
    }

    /**
     * Read once, then forget: a stale value must not reappear on a later visit.
     * @return array<string,string>
     */
    private function rememberedInput(): array
    {
        $key = self::INPUT_TRANSIENT . get_current_user_id();
        $values = get_transient($key);
        delete_transient($key);

        return is_array($values) ? $values : [];
    }

    /**
     * @param string $step
     * @return array<string> validation errors, empty when the step saved
     */
    private function save(string $step): array
    {
        switch ($step) {
            case 'address':
                return $this->saveAddress();
            case 'gateway':
                return $this->saveGateway();
            case 'networks':
                return $this->saveNetworks();
            case 'wallets':
                return $this->saveWallets();
            case 'appkit':
                return $this->saveAppKit();
        }

        return [];
    }

    /**
     * @return array<string>
     */
    private function saveAddress(): array
    {
        $address = isset($_POST['walletAddress']) ? sanitize_text_field(wp_unslash($_POST['walletAddress'])) : '';

        if ('' === $address) {
            return [esc_html__('Please enter your wallet address.', 'cryptopay')];
        }

        if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
            return [esc_html__('That does not look like an EVM wallet address. It must start with 0x and be 42 characters long.', 'cryptopay')];
        }

        Settings::update('evmchainsWalletAddress', $address);

        return [];
    }

    /**
     * @return array<string>
     */
    private function saveGateway(): array
    {
        if (!SetupStatus::wooCommerceActive()) {
            return [esc_html__('WooCommerce is not active, so the gateway cannot be enabled yet.', 'cryptopay')];
        }

        $settings = get_option(SetupStatus::GATEWAY_OPTION, []);
        $settings = is_array($settings) ? $settings : [];
        $settings['enabled'] = 'yes';

        update_option(SetupStatus::GATEWAY_OPTION, $settings);

        return [];
    }

    /**
     * @return array<string>
     */
    private function saveNetworks(): array
    {
        $selected = $this->postedKeys('networks', EvmChains::networkList());

        if ([] === array_filter($selected)) {
            return [esc_html__('Please choose at least one network, otherwise customers have nothing to pay with.', 'cryptopay')];
        }

        Settings::update('evmchainsNetworks', $selected);

        return [];
    }

    /**
     * @return array<string>
     */
    private function saveWallets(): array
    {
        $browser = $this->postedKeys('wallets', EvmChains::walletList('browser'));

        if ([] === array_filter($browser)) {
            return [esc_html__('Please choose at least one wallet, otherwise customers cannot connect and pay.', 'cryptopay')];
        }

        // Only the browser wallets are on this step; leave the AppKit and legacy
        // switches exactly as they are so the next step owns them outright.
        $wallets = array_merge($this->currentWallets(), $browser);

        Settings::update('evmchainsWallets', $wallets);

        return [];
    }

    /**
     * @return array<string>
     */
    private function saveAppKit(): array
    {
        $projectId = isset($_POST['projectId']) ? sanitize_text_field(wp_unslash($_POST['projectId'])) : '';

        Settings::update('wcProjectId', $projectId);

        $wallets = $this->currentWallets();

        if ('' === $projectId) {
            // Nothing that needs an id may stay on without one.
            foreach (EvmChains::walletsRequiringProjectId() as $key) {
                $wallets[$key] = false;
            }
        } else {
            // Turn on AppKit only. Legacy WalletConnect is superseded by it and
            // the two together are the conflict its own description warns about,
            // so leave that one to whoever deliberately wants it in the settings.
            $wallets['web3wallets'] = true;
        }

        Settings::update('evmchainsWallets', $wallets);

        return [];
    }

    /**
     * @return array<string,bool>
     */
    private function currentWallets(): array
    {
        $wallets = Settings::get('evmchainsWallets');
        $wallets = is_array($wallets) ? $wallets : [];

        return array_map(fn($val) => (bool) $val, $wallets);
    }

    /**
     * @param string $field
     * @param array<string,array<string,mixed>> $list
     * @return array<string,bool>
     */
    private function postedKeys(string $field, array $list): array
    {
        $posted = isset($_POST[$field]) && is_array($_POST[$field])
            ? array_map('sanitize_key', wp_unslash($_POST[$field]))
            : [];

        $result = [];

        foreach (array_keys($list) as $key) {
            $result[$key] = in_array($key, $posted, true);
        }

        return $result;
    }

    /**
     * @return void
     */
    public function page(): void
    {
        $step = $this->currentStep();

        $errors = isset($_GET['cpl_error'])
            ? array_filter(explode('|', sanitize_text_field(wp_unslash($_GET['cpl_error']))))
            : [];

        Helpers::viewEcho('pages/setup-wizard/index', [
            'step' => $step,
            'steps' => $this->steps,
            'errors' => $errors,
            'old' => $this->rememberedInput(),
            'action' => self::ACTION,
            'settingsUrl' => admin_url('admin.php?page=' . Helpers::getProp('settingKey')),
        ], $this->allowedHtml());
    }

    /**
     * The view helper derives its kses whitelist from the first occurrence of each
     * tag, so a `checked` checkbox or a named submit button further down the page
     * would quietly lose those attributes. Spell the whole form out instead.
     * @return array<string,array<string,bool>>
     */
    private function allowedHtml(): array
    {
        return [
            'form' => ['method' => true, 'action' => true, 'class' => true],
            'fieldset' => ['class' => true],
            'label' => ['class' => true, 'for' => true],
            'input' => [
                'type' => true,
                'name' => true,
                'value' => true,
                'checked' => true,
                'class' => true,
                'id' => true,
                'placeholder' => true,
                'autocomplete' => true,
                'spellcheck' => true,
            ],
            'button' => ['type' => true, 'name' => true, 'value' => true, 'class' => true],
            'a' => ['href' => true, 'class' => true, 'target' => true],
            'img' => ['src' => true, 'alt' => true, 'width' => true],
            'ol' => ['class' => true],
            'ul' => ['class' => true],
            'li' => ['class' => true],
            'div' => ['class' => true],
            'span' => ['class' => true],
            'p' => ['class' => true],
            'h1' => ['class' => true],
            'h2' => ['class' => true],
            'hr' => ['class' => true],
        ];
    }
}

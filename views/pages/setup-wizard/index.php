<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
use BeycanPress\CryptoPayLite\Services\SetupStatus;

/**
 * Every step lives in this one file on purpose. The view helper runs each
 * rendered view through wp_kses separately, and its allowed-attribute list is
 * derived from the first occurrence of each tag, so splitting the steps out
 * would silently drop things like `checked` on a checkbox.
 *
 * @var string $step
 * @var array<string> $steps
 * @var array<string> $errors
 * @var array<string,string> $old what was typed into a step that failed validation
 * @var string $action
 * @var string $settingsUrl
 */

$titles = [
    'address'  => esc_html__('Wallet address', 'cryptopay'),
    'gateway'  => esc_html__('Enable at checkout', 'cryptopay'),
    'networks' => esc_html__('Networks', 'cryptopay'),
    'wallets'  => esc_html__('Wallets', 'cryptopay'),
    'appkit'   => esc_html__('Mobile wallets', 'cryptopay'),
];

$currentIndex = (int) array_search($step, $steps, true);

// Read the raw switcher maps rather than the resolved getters: those drop the
// disabled entries, and getNetworks() hands back testnet ids while testnet mode
// is on. Either would tick the wrong boxes here.
$savedNetworks = Helpers::getSetting('evmchainsNetworks');
$savedNetworks = is_array($savedNetworks) ? $savedNetworks : [];
$savedWallets = Helpers::getSetting('evmchainsWallets');
$savedWallets = is_array($savedWallets) ? $savedWallets : [];
?>

<div class="wrap cpl-wizard">
    <hr class="wp-header-end">

    <div class="cpl-wizard-head">
        <img src="<?php echo esc_url_raw(Helpers::getImageUrl('menu.png')); ?>" alt="" width="28">
        <h1><?php echo esc_html__('CryptoPay Lite setup', 'cryptopay'); ?></h1>
    </div>

    <?php if ('done' !== $step) : ?>
        <ol class="cpl-steps">
            <?php foreach ($steps as $index => $key) : ?>
                <li class="<?php echo esc_attr($index < $currentIndex ? 'is-done' : ($index === $currentIndex ? 'is-current' : 'is-todo')); ?>">
                    <span class="cpl-step-num"><?php echo esc_html((string) ($index + 1)); ?></span>
                    <span class="cpl-step-label"><?php echo esc_html($titles[$key]); ?></span>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php foreach ($errors as $error) : ?>
        <div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div>
    <?php endforeach; ?>

    <div class="cpl-wizard-box">

    <?php if ('done' === $step) : ?>

        <?php $ready = SetupStatus::isReady(); ?>

        <h2><?php echo $ready
            ? esc_html__('You are ready to accept crypto.', 'cryptopay')
            : esc_html__('Almost there — some things still need fixing.', 'cryptopay'); ?></h2>

        <ul class="cpl-status">
            <?php foreach (SetupStatus::checks() as $check) : ?>
                <li class="cpl-status-<?php echo esc_attr($check['status']); ?>">
                    <span class="cpl-status-icon"></span>
                    <span class="cpl-status-text">
                        <?php // Already escaped in SetupStatus; re-escaping would mangle entities. ?>
                        <?php echo $check['message']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php if (is_array($check['action'])) : ?>
                            <a href="<?php echo esc_url($check['action']['url']); ?>"><?php echo esc_html($check['action']['text']); ?></a>
                        <?php endif; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="cpl-actions">
            <a href="<?php echo esc_url($settingsUrl); ?>" class="button button-primary"><?php echo esc_html__('Go to settings', 'cryptopay'); ?></a>
            <?php if ($ready) : ?>
                <a href="<?php echo esc_url(SetupStatus::gatewayUrl()); ?>" class="button"><?php echo esc_html__('Review gateway options', 'cryptopay'); ?></a>
            <?php endif; ?>
        </p>

    <?php else : ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="cpl-form">
            <?php wp_nonce_field($action); ?>
            <input type="hidden" name="action" value="<?php echo esc_attr($action); ?>">
            <input type="hidden" name="step" value="<?php echo esc_attr($step); ?>">

            <?php if ('address' === $step) : ?>

                <h2><?php echo esc_html__('Where should payments go?', 'cryptopay'); ?></h2>
                <p class="cpl-lead"><?php echo esc_html__('Your customers pay this address directly. No middleman holds the funds and we take no commission. The same address works on every EVM network, so you only need one.', 'cryptopay'); ?></p>

                <input
                    type="text"
                    name="walletAddress"
                    class="cpl-input"
                    placeholder="0x0000000000000000000000000000000000000000"
                    autocomplete="off"
                    spellcheck="false"
                    value="<?php echo esc_attr($old['walletAddress'] ?? (string) Helpers::getSetting('evmchainsWalletAddress')); ?>"
                >

                <p class="cpl-note"><?php echo esc_html__('Nothing else in this plugin loads until an address is set, so this step cannot be skipped.', 'cryptopay'); ?></p>

            <?php elseif ('gateway' === $step) : ?>

                <h2><?php echo esc_html__('Turn CryptoPay on at checkout', 'cryptopay'); ?></h2>

                <?php if (!SetupStatus::wooCommerceActive()) : ?>
                    <p class="cpl-lead"><?php echo esc_html__('WooCommerce is not active. CryptoPay Lite is a WooCommerce payment gateway, so there is no checkout to appear on until you install and activate WooCommerce.', 'cryptopay'); ?></p>
                    <p class="cpl-actions">
                        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="button"><?php echo esc_html__('Install WooCommerce', 'cryptopay'); ?></a>
                    </p>
                <?php elseif (SetupStatus::gatewayEnabled()) : ?>
                    <p class="cpl-lead"><?php echo esc_html__('Already enabled. CryptoPay is switched on as a WooCommerce payment method.', 'cryptopay'); ?></p>
                <?php else : ?>
                    <p class="cpl-lead"><?php echo esc_html__('WooCommerce ships every payment gateway switched off, including this one. This is the single most common reason people report that CryptoPay never shows up at checkout. Continue to switch it on now.', 'cryptopay'); ?></p>
                <?php endif; ?>

            <?php elseif ('networks' === $step) : ?>

                <h2><?php echo esc_html__('Which networks do you accept?', 'cryptopay'); ?></h2>
                <p class="cpl-lead"><?php echo esc_html__('Customers pick one of these at checkout, then pay with its coin or a token on it. Leave them all on unless you have a reason not to.', 'cryptopay'); ?></p>

                <fieldset class="cpl-choices">
                    <?php foreach (EvmChains::networkList() as $key => $network) : ?>
                        <?php $checked = array_key_exists($key, $savedNetworks) ? $savedNetworks[$key] : $network['default']; ?>
                        <label class="cpl-choice">
                            <input type="checkbox" name="networks[]" value="<?php echo esc_attr($key); ?>" <?php checked((bool) $checked); ?>>
                            <span><?php echo esc_html($network['title']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <p class="cpl-note"><?php echo esc_html__('Need a network that is not listed, or Bitcoin, Solana and Tron? Those come with premium.', 'cryptopay'); ?></p>

            <?php elseif ('wallets' === $step) : ?>

                <h2><?php echo esc_html__('Which wallets can customers use?', 'cryptopay'); ?></h2>
                <p class="cpl-lead"><?php echo esc_html__('These are the wallets your customers connect with on a desktop browser. Mobile wallets are the next step.', 'cryptopay'); ?></p>

                <fieldset class="cpl-choices">
                    <?php foreach (EvmChains::walletList('browser') as $key => $wallet) : ?>
                        <?php $checked = array_key_exists($key, $savedWallets) ? $savedWallets[$key] : $wallet['default']; ?>
                        <label class="cpl-choice">
                            <input type="checkbox" name="wallets[]" value="<?php echo esc_attr($key); ?>" <?php checked((bool) $checked); ?>>
                            <span><?php echo esc_html($wallet['title']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

            <?php elseif ('appkit' === $step) : ?>

                <h2><?php echo esc_html__('Do you want mobile wallet support?', 'cryptopay'); ?></h2>
                <p class="cpl-lead"><?php echo esc_html__('Reown AppKit (formerly WalletConnect) lets customers pay from hundreds of mobile wallets by scanning a QR code. It is free, but it needs a Project ID from Reown Cloud. Everything above already works without it on desktop.', 'cryptopay'); ?></p>

                <input
                    type="text"
                    name="projectId"
                    class="cpl-input"
                    placeholder="<?php echo esc_attr__('Paste your Reown AppKit Project ID', 'cryptopay'); ?>"
                    autocomplete="off"
                    spellcheck="false"
                    value="<?php echo esc_attr($old['projectId'] ?? (string) Helpers::getSetting('wcProjectId')); ?>"
                >

                <p class="cpl-note">
                    <a href="https://cloud.reown.com/sign-in" target="_blank"><?php echo esc_html__('Get a free Project ID from Reown Cloud', 'cryptopay'); ?></a>
                    &nbsp;·&nbsp;
                    <a href="https://beycanpress.gitbook.io/cryptopay-docs/overview/installation#id-10-reown-project-id-formerly-walletconnect" target="_blank"><?php echo esc_html__('Guide', 'cryptopay'); ?></a>
                </p>

                <p class="cpl-note"><?php echo esc_html__('Leave it empty to skip mobile wallets. AppKit stays switched off in that case, which is deliberate: without a Project ID it cannot connect, and an option that fails when clicked is worse than no option at all. Desktop wallets are unaffected either way.', 'cryptopay'); ?></p>

            <?php endif; ?>

            <p class="cpl-actions">
                <button type="submit" class="button button-primary button-hero"><?php echo esc_html__('Continue', 'cryptopay'); ?></button>
                <?php if (in_array($step, ['networks', 'wallets', 'appkit'], true)) : ?>
                    <button type="submit" name="skip" value="1" class="button cpl-skip"><?php echo esc_html__('Skip this step', 'cryptopay'); ?></button>
                <?php endif; ?>
            </p>
        </form>

    <?php endif; ?>

    </div>
</div>

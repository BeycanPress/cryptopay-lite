<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Data;

// Classes
use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\Constants;
use BeycanPress\CryptoPayLite\Settings\EvmChains;
// Types
use BeycanPress\CryptoPayLite\Types\AbstractType;

/**
 * The class where the data to be sent to CryptoPay JS is set
 * @since 2.1.0
 */
class ConfigDataType extends AbstractType
{
    #String types

    /**
     * Indicates in which plugin the payment process is processed. For example woocommerce, memberpress, donation etc.
     * @var string
     */
    private string $addon;

    /**
     * CryptoPay JS payment listing mode (network, currency)
     * @var string
     */
    private string $mode;

    /**
     * CryptoPay JS theme
     * @var array<mixed>
     */
    private array $theme;

    /**
     * URL of our Rest API that handles the payment process.
     * @var string
     */
    private string $apiUrl;

    /**
     * URL path with the images to be used on the CryptoPay JS side.
     * @var string
     */
    private string $imagesUrl;

    /**
     * CryptoPay version code
     * @var string
     */
    private string $version;

    /**
     * CryptoPay logo URL for using on the QR code.
     * @var string
     */
    // @phpstan-ignore-next-line
    private string $logo;

    /**
     * Reminder e-mail is the e-mail of the current user if the user is logged in during the e-mail.
     * This is used directly. If the user is not logged in,
     * this will be empty and the user will be prompted to enter an email.
     * @var ?string
     */
    // @phpstan-ignore-next-line
    private ?string $userEmail;

    #String types

    #Class types

    /**
     * Active networks to send to CryptoPay JS.
     * @var array<int>
     */
    private array $networks;

    /**
     * Wallets to send to CryptoPay JS.
     * @var array<string>
     */
    private array $wallets;

    #Class types

    #Object types

    /**
     * Wallet Adapter config
     * @var object
     */
    private object $adapterConfig;

    /**
     * The dynamic texts to be sent to CryptoPay JS.
     * @see \BeycanPress\CryptoPayLite\Constants
     * @var object
     */
    private object $lang;

    /**
     * This property contains the discount rates of the currencies.
     */
    private object $discountRates;

    #Object types

    #Boolean types

    /**
     * Testnet or mainnets
     * @var bool
     */
    private bool $testnet;

    /**
     * @var boolean
     */
    private bool $modal = false;

    /**
     * Users who do not want to wait for the verification process, which is a data from the settings,
     * can set up an email reminder using this.
     * @var bool
     */
    // @phpstan-ignore-next-line
    private bool $reminderEmail;

    /**
     * When debug mode is activated, it also activates debug mode in CryptoPay JS.
     * @var bool
     */
    private bool $debug;

    /**
     * CryptoPay JS also decides whether the payment goes to the confirmation step or not.
     * If False, the process is considered complete when the payment is detected.
     * This is usually set to false by us for payments made by the admin.
     * @var bool
     */
    private bool $confirmation;

    /**
     * After the payment is detected, it checks whether the relevant transaction record will be created.
     * This is automatically assigned if a model has been created for the related addon, if not it is false.
     * @var bool
     */
    private bool $createTransaction;

    #Boolean types

    #Integer types

    /**
     * In CryptoPay JS, the price is updated periodically, which indicates how often this transaction will occur.
     * @var float|int
     */
    private float $amountUpdateMin;

    #Integer types

    /**
     * @param string $addon
     */
    public function __construct(string $addon)
    {
        Helpers::checkIntegration($this->addon = $addon);

        // declare defaults
        $this->discountRates = (object) [];
        $this->logo = Constants::getLogoUrl();
        $this->apiUrl = Constants::getApiUrl();
        $this->lang = Constants::getLangParams();
        $this->imagesUrl = Constants::getImagesUrl();
        $this->userEmail = Helpers::getCurrentUserEmail();
        $this->version = Helpers::getProp('pluginVersion');
        $this->createTransaction = boolval(Helpers::getModelByAddon($addon));

        $this->testnet = Helpers::getTestnetStatus();

        // declare settings
        $this->mode = Helpers::getMode($addon);
        $this->theme = Helpers::getTheme($addon);
        $this->wallets = EvmChains::getWallets();
        $this->amountUpdateMin = $this->getUpdateMin();
        $this->debug = boolval(Helpers::getSetting('debugging'));
        $this->reminderEmail = boolval(Helpers::getSetting('reminderEmail'));

        // only for multiplechain providers
        $this->adapterConfig = (object) [
            'themeMode' => $this->theme['mode'],
            'projectId' => Helpers::getSetting('wcProjectId'),
        ];
    }

    /**
     * @return float
     */
    private function getUpdateMin(): float
    {
        $updateMin = Helpers::getSetting('amountUpdateMin');
        return $updateMin ? floatval($updateMin) : 0.5;
    }

    // Set methods for outcoming data

    /**
     * @param bool $modal
     * @return self
     */
    public function setModal(bool $modal): self
    {
        $this->modal = $modal;
        return $this;
    }

    /**
     * @param array<int> $networks
     * @return self
     */
    public function setNetworks(array $networks): self
    {
        $this->networks = $networks;
        return $this;
    }

    /**
     * @param bool $confirmation
     * @return self
     */
    public function setConfirmation(bool $confirmation): self
    {
        $this->confirmation = $confirmation;
        return $this;
    }

    /**
     * @param object $discountRates
     * @return self
     */
    public function setDiscountRates(object $discountRates): self
    {
        $this->discountRates = $discountRates;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddon(): string
    {
        return $this->addon;
    }

    /**
     * @return array<string,mixed>
     */
    public function prepareForJsSide(): array
    {
        return $this->toArray();
    }
}

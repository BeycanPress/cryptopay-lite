<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Network;

use BeycanPress\CryptoPayLite\Types\AbstractType;

/**
 * Network type
 * @since 2.1.0
 */
class NetworkType extends AbstractType
{
    /**
     * @var int
     */
    private ?int $id = null;

    /**
     * @var string
     */
    private ?string $hexId = null;

    /**
     * @var string|null
     */
    private ?string $wsUrl = null;

    /**
     * @var string
     */
    private ?string $rpcUrl = null;

    /**
     * @var int|null
     */
    private ?int $mainnetId = null;

    /**
     * @var string
     */
    private ?string $explorerUrl = null;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var array<string>
     */
    private array $wallets = [];

    /**
     * @var string|null
     */
    private ?string $web3Domain = null;

    /**
     * @var string|null
     */
    private ?string $imageUrl = null;

    /**
     * @var CurrencyType
     */
    private CurrencyType $nativeCurrency;

    /**
     * @var CurrenciesType
     */
    private CurrenciesType $currencies;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->currencies = new CurrenciesType();
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = absint($id);
        $this->hexId = '0x' . dechex($this->id);

        return $this;
    }


    /**
     * @param string|null $wsUrl
     * @return self
     */
    public function setWsUrl(?string $wsUrl): self
    {
        $this->wsUrl = $wsUrl;

        return $this;
    }

    /**
     * @param string|null $rpcUrl
     * @return self
     */
    public function setRpcUrl(?string $rpcUrl): self
    {
        $this->rpcUrl = $rpcUrl;

        return $this;
    }

    /**
     * @param int|null $mainnetId
     * @return self
     */
    public function setMainnetId(?int $mainnetId): self
    {
        $this->mainnetId = $mainnetId;

        return $this;
    }

    /**
     * @param string $explorerUrl
     * @return self
     */
    public function setExplorerUrl(?string $explorerUrl): self
    {
        $this->explorerUrl = $explorerUrl;

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param array<string> $wallets
     * @return self
     */
    public function setWallets(array $wallets): self
    {
        $this->wallets = $wallets;

        return $this;
    }

    /**
     * @param string|null $web3Domain
     * @return self
     */
    public function setWeb3Domain(?string $web3Domain): self
    {
        $this->web3Domain = $web3Domain;

        return $this;
    }

    /**
     * @param string|null $imageUrl
     * @return self
     */
    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @param CurrencyType $nativeCurrency
     * @return self
     */
    public function setNativeCurrency(CurrencyType $nativeCurrency): self
    {
        $this->nativeCurrency = $nativeCurrency;

        return $this;
    }

    /**
     * @param CurrenciesType $currencies
     * @return self
     */
    public function setCurrencies(CurrenciesType $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getHexId(): ?string
    {
        return $this->hexId;
    }

    /**
     * @return string|null
     */
    public function getWsUrl(): ?string
    {
        return $this->wsUrl;
    }

    /**
     * @return string|null
     */
    public function getRpcUrl(): ?string
    {
        return $this->rpcUrl;
    }

    /**
     * @return int|null
     */
    public function getMainnetId(): ?int
    {
        return $this->mainnetId;
    }

    /**
     * @return string|null
     */
    public function getExplorerUrl(): ?string
    {
        return $this->explorerUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array<string>
     */
    public function getWallets(): array
    {
        return $this->wallets;
    }

    /**
     * @return string|null
     */
    public function getWeb3Domain(): ?string
    {
        return $this->web3Domain;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return CurrencyType
     */
    public function getNativeCurrency(): CurrencyType
    {
        return $this->nativeCurrency;
    }

    /**
     * @return CurrenciesType
     */
    public function getCurrencies(): CurrenciesType
    {
        return $this->currencies;
    }

    /**
     * @return array<string,mixed>
     */
    public function forDebug(): array
    {
        return array_filter([
            'id' => $this->getId(),
            'hexId' => $this->getHexId(),
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'explorerUrl' => $this->getExplorerUrl(),
            'nativeCurrency' => $this->getNativeCurrency()->forDebug(),
        ]);
    }
}

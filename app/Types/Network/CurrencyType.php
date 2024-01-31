<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Network;

use BeycanPress\CryptoPayLite\Types\AbstractType;

/**
 * Currency type
 * @since 2.1.0
 */
class CurrencyType extends AbstractType
{
    /**
     * Currency symbol like USD, EUR, USDT, BTC, ETH etc.
     * @var string
     */
    private string $symbol;

    /**
     * Currency address if is it crypto currency token, because native currencies also don't have address.
     * @var string|null
     */
    private ?string $address = null;

    /**
     * Decimals of currency. For example 18 for ETH, 8 for BTC, 6 for USDT etc.
     * @var int|null
     */
    private ?int $decimals = null;

    /**
     * Currency image url.
     * @var string|null
     */
    private ?string $imageUrl = null;

    /**
     * @param string|null $symbol
     * @param string|null $address
     */
    public function __construct(?string $symbol = null, ?string $address = null)
    {
        if ($symbol) {
            $this->setSymbol($symbol);
        }

        if ($address) {
            $this->setAddress($address);
        }
    }

    /**
     * @param string $symbol
     * @return self
     */
    public function setSymbol(string $symbol): self
    {
        $this->symbol = strtoupper($symbol);
        return $this;
    }

    /**
     * @param string|null $address
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param int|null $decimals
     * @return self
     */
    public function setDecimals(?int $decimals): self
    {
        $this->decimals = absint($decimals);
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
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return int|null
     */
    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return array<string,mixed>
     */
    public function forDebug(): array
    {
        return array_filter([
            'symbol' => $this->getSymbol(),
            'address' => $this->getAddress(),
            'decimals' => $this->getDecimals(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Order;

use BeycanPress\CryptoPayLite\Types\AbstractType;
use BeycanPress\CryptoPayLite\Types\Network\CurrencyType;

/**
 * Order type
 * @since 2.1.0
 */
class OrderType extends AbstractType
{
    /**
     * @var int|null
     */
    private ?int $id = null;

    /**
     * @var float
     */
    private float $amount;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var float|null
     */
    private ?float $paymentAmount = null;

    /**
     * @var CurrencyType|null
     */
    private ?CurrencyType $paymentCurrency = null;

    /**
     * @var float|null
     */
    private ?float $discountRate = null;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->amount = 0;
        $this->currency = '';
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = abs($amount);
        return $this;
    }

    /**
     * @param string $currency
     * @return self
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = strtoupper($currency);
        return $this;
    }

    /**
     * @param float $paymentAmount
     * @return self
     */
    public function setPaymentAmount(float $paymentAmount): self
    {
        $this->paymentAmount = abs($paymentAmount);
        return $this;
    }

    /**
     * @param CurrencyType $paymentCurrency
     * @return self
     */
    public function setPaymentCurrency(CurrencyType $paymentCurrency): self
    {
        $this->paymentCurrency = $paymentCurrency;
        return $this;
    }

    /**
     * @param float|null $discountRate
     * @return self
     */
    public function setDiscountRate(?float $discountRate): self
    {
        $this->discountRate = $discountRate;
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
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float|null
     */
    public function getPaymentAmount(): ?float
    {
        return $this->paymentAmount;
    }

    /**
     * @return CurrencyType|null
     */
    public function getPaymentCurrency(): ?CurrencyType
    {
        return $this->paymentCurrency;
    }

    /**
     * @return float|null
     */
    public function getDiscountRate(): ?float
    {
        return $this->discountRate;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->amount > 0 && !empty($this->currency);
    }

    /**
     * @return array<string,mixed>
     */
    public function prepareForJsSide(): array
    {
        return $this->toArray(exclude:[
            'discountRate'
        ]);
    }

    /**
     * @return array<string,mixed>
     */
    public function forDebug(): array
    {
        return array_filter([
            'id' => $this->getId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'discountRate' => $this->getDiscountRate(),
            'paymentAmount' => $this->getPaymentAmount(),
            'paymentCurrency' => $this->paymentCurrency?->toArray(),
        ]);
    }
}

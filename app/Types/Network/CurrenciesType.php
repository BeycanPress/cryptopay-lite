<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Network;

use BeycanPress\CryptoPayLite\Types\AbstractListType;

/**
 * Currencies type
 * @since 2.1.0
 */
class CurrenciesType extends AbstractListType
{
    /**
     * @var string
     */
    protected static string $type = CurrencyType::class;

    /**
     * @param array<CurrencyType> $currencies
     */
    public function __construct(array $currencies = [])
    {
        $this->addCurrencies($currencies);
    }

    /**
     * @param CurrencyType $currency
     * @return self
     */
    public function addCurrency(CurrencyType $currency): self
    {
        $this->list[] = $currency;

        return $this;
    }

    /**
     * @param array<CurrencyType> $currencies
     * @return self
     */
    public function addCurrencies(array $currencies): self
    {
        foreach ($currencies as $currency) {
            $this->addCurrency($currency);
        }

        return $this;
    }
}

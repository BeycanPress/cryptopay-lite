<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Utils;
use MultipleChain\Utils\Number;
use MultipleChain\Enums\ErrorType;
use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\TransactionData;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\CoinInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class Coin implements CoinInterface
{
    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var array<string,mixed>
     */
    private array $currency;

    /**
     * @param Provider|null $provider
     */
    public function __construct(?ProviderInterface $provider = null)
    {
        $this->provider = $provider ?? Provider::instance();
        $this->currency = $this->provider->network->getNativeCurrency();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->currency['name'] ?? $this->currency['symbol'];
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->currency['symbol'];
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->currency['decimals'];
    }

    /**
     * @param string $owner
     * @return Number
     */
    public function getBalance(string $owner): Number
    {
        $balance = $this->provider->web3->getBalance($owner);
        return new Number($balance, $this->getDecimals());
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param float $amount
     * @return TransactionSigner
     */
    public function transfer(string $sender, string $receiver, float $amount): TransactionSigner
    {
        if ($amount < 0) {
            throw new \RuntimeException(ErrorType::INVALID_AMOUNT->value);
        }

        if ($amount > $this->getBalance($sender)->toFloat()) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        $amount = Utils::numberToHex($amount, $this->getDecimals());

        $txData = (new TransactionData())
            ->setData('')
            ->setFrom($sender)
            ->setTo($receiver)
            ->setValue($amount)
            ->setChainId($this->provider->network->getId())
            ->setGasPrice($this->provider->web3->getGasPrice())
            ->setNonce($this->provider->web3->getNonce($sender));

        $txData->setGas($this->provider->web3->getEstimateGas($txData->toArray()));

        return new TransactionSigner($txData);
    }
}

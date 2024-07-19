<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Utils;
use MultipleChain\Utils\Number;
use MultipleChain\Enums\ErrorType;
use MultipleChain\EvmChains\Provider;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\TokenInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class Token extends Contract implements TokenInterface
{
    /**
     * @param string $address
     * @param Provider|null $provider
     * @param array<object>|null $abi
     */
    public function __construct(string $address, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        parent::__construct(
            $address,
            $provider,
            $abi ? $abi : json_decode(file_get_contents(dirname(__DIR__, 2) . '/resources/ERC20.json') ?: '')
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->callMethodWithCache('name');
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->callMethodWithCache('symbol');
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return (int) hexdec($this->callMethodWithCache('decimals'));
    }

    /**
     * @param string $owner
     * @return Number
     */
    public function getBalance(string $owner): Number
    {
        $decimals = $this->getDecimals();
        $balance = $this->callMethod('balanceOf', $owner);
        return new Number($balance, $decimals);
    }

    /**
     * @return Number
     */
    public function getTotalSupply(): Number
    {
        $decimals = $this->getDecimals();
        $totalSupply = $this->callMethod('totalSupply');
        return new Number($totalSupply, $decimals);
    }

    /**
     * @param string $owner
     * @param string $spender
     * @return Number
     */
    public function getAllowance(string $owner, string $spender): Number
    {
        $decimals = $this->getDecimals();
        $allowance = $this->callMethod('allowance', $owner, $spender);
        return new Number($allowance, $decimals);
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

        return new TransactionSigner(
            $this->createTransactionData('transfer', $sender, $receiver, $amount)
        );
    }

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param float $amount
     * @return TransactionSigner
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        float $amount
    ): TransactionSigner {
        if ($amount < 0) {
            throw new \RuntimeException(ErrorType::INVALID_AMOUNT->value);
        }

        if ($amount > $this->getBalance($owner)->toFloat()) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        $allowance = $this->getAllowance($owner, $spender)->toFloat();

        if (0 == $allowance) {
            throw new \RuntimeException(ErrorType::UNAUTHORIZED_ADDRESS->value);
        }

        if ($amount > $allowance) {
            throw new \RuntimeException(ErrorType::INVALID_AMOUNT->value);
        }

        $amount = Utils::numberToHex($amount, $this->getDecimals());

        return new TransactionSigner(
            $this->createTransactionData('transferFrom', $spender, $owner, $receiver, $amount)
        );
    }

    /**
     * @param string $owner
     * @param string $spender
     * @param float $amount
     * @return TransactionSigner
     */
    public function approve(string $owner, string $spender, float $amount): TransactionSigner
    {
        if ($amount < 0) {
            throw new \RuntimeException(ErrorType::INVALID_AMOUNT->value);
        }

        if ($amount > $this->getBalance($owner)->toFloat()) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        $amount = Utils::numberToHex($amount, $this->getDecimals());

        return new TransactionSigner(
            $this->createTransactionData('approve', $owner, $spender, $amount)
        );
    }
}

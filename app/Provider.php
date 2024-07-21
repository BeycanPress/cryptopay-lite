<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite;

use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Models\TransactionInterface;
use MultipleChain\Interfaces\Models\CoinTransactionInterface;
use MultipleChain\Interfaces\Models\TokenTransactionInterface;
use BeycanPress\CryptoPayLite\Types\Transaction\TransactionType;

class Provider
{
    /**
     * @var ProviderInterface
     */
    public ProviderInterface $instance;

    /**
     * @var TransactionType
     */
    public TransactionType $transaction;

    /**
     * @var array<string>
     */
    private array $classes;

    /**
     * @param TransactionType $transaction
     * @param array<string> $classes
     */
    public function __construct(TransactionType $transaction, array $classes)
    {
        $this->classes = $classes;
        $this->transaction = $transaction;
        $this->instance = new $classes['provider']($transaction->getNetwork()->toArray());
    }

    /**
     * @param string $txId
     * @return TransactionInterface
     */
    public function transaction(string $txId): TransactionInterface
    {
        return new $this->classes['transaction']($txId);
    }

    /**
     * @param string $txId
     * @return CoinTransactionInterface
     */
    public function coinTransaction(string $txId): CoinTransactionInterface
    {
        return new $this->classes['coinTransaction']($txId);
    }

    /**
     * @param string $txId
     * @return TokenTransactionInterface
     */
    public function tokenTransaction(string $txId): TokenTransactionInterface
    {
        return new $this->classes['tokenTransaction']($txId);
    }
}

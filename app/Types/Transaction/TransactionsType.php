<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Transaction;

use BeycanPress\CryptoPayLite\Types\AbstractListType;

/**
 * Transactions type
 * @since 2.1.0
 */
class TransactionsType extends AbstractListType
{
    /**
     * @var string
     */
    protected static string $type = TransactionType::class;

    /**
     * @param array<TransactionType> $transactions
     */
    public function __construct(array $transactions = [])
    {
        $this->addTransactions($transactions);
    }

    /**
     * @param TransactionType $transaction
     * @return self
     */
    public function addTransaction(TransactionType $transaction): self
    {
        $this->list[] = $transaction;

        return $this;
    }

    /**
     * @param array<TransactionType> $transactions
     * @return self
     */
    public function addTransactions(array $transactions): self
    {
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
        }

        return $this;
    }
}

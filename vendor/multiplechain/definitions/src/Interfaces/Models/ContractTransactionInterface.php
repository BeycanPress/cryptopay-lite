<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Models;

interface ContractTransactionInterface extends TransactionInterface
{
    /**
     * @return string
     */
    public function getAddress(): string;
}

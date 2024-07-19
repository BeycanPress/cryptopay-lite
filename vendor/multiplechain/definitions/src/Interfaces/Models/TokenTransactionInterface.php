<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Models;

use MultipleChain\Utils\Number;
use MultipleChain\Enums\AssetDirection;
use MultipleChain\Enums\TransactionStatus;

interface TokenTransactionInterface extends AssetTransactionInterface, ContractTransactionInterface
{
    /**
     * @return Number
     */
    public function getAmount(): Number;

    /**
     * @param AssetDirection $direction
     * @param string $address
     * @param float $amount
     * @return TransactionStatus
     */
    public function verifyTransfer(AssetDirection $direction, string $address, float $amount): TransactionStatus;
}

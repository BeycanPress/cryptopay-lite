<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Models;

interface AssetTransactionInterface extends TransactionInterface
{
    /**
     * @return string
     */
    public function getReceiver(): string;

    /**
     * @return string
     */
    public function getSender(): string;
}

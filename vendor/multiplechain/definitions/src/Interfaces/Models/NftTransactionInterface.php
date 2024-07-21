<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Models;

use MultipleChain\Enums\AssetDirection;
use MultipleChain\Enums\TransactionStatus;

interface NftTransactionInterface extends AssetTransactionInterface, ContractTransactionInterface
{
    /**
     * @return int|string
     */
    public function getNftId(): int|string;

    /**
     * @param AssetDirection $direction
     * @param string $address
     * @param int|string $nftId
     * @return TransactionStatus
     */
    public function verifyTransfer(AssetDirection $direction, string $address, int|string $nftId): TransactionStatus;
}

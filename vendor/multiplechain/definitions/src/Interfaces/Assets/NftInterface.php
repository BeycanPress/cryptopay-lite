<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Assets;

use MultipleChain\Interfaces\Services\TransactionSignerInterface;

interface NftInterface extends AssetInterface, ContractInterface
{
    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getOwner(int|string $tokenId): string;

    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getTokenURI(int|string $tokenId): string;

    /**
     * @param int|string $tokenId
     * @return string|null
     */
    public function getApproved(int|string $tokenId): ?string;

    /**
     * @param string $sender
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSignerInterface
     */
    public function transfer(string $sender, string $receiver, int|string $tokenId): TransactionSignerInterface;

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSignerInterface
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        int|string $tokenId
    ): TransactionSignerInterface;

    /**
     * @param string $owner
     * @param string $spender
     * @param int|string $tokenId
     * @return TransactionSignerInterface
     */
    public function approve(string $owner, string $spender, int|string $tokenId): TransactionSignerInterface;
}

<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\Interfaces\Services\TransactionSignerInterface;

interface TokenInterface extends AssetInterface, ContractInterface
{
    /**
     * @return int
     */
    public function getDecimals(): int;

    /**
     * @return Number
     */
    public function getTotalSupply(): Number;

    /**
     * @param string $owner
     * @param string $spender
     * @return Number
     */
    public function getAllowance(string $owner, string $spender): Number;

    /**
     * @param string $sender
     * @param string $receiver
     * @param float $amount
     * @return TransactionSignerInterface
     */
    public function transfer(string $sender, string $receiver, float $amount): TransactionSignerInterface;

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param float $amount
     * @return TransactionSignerInterface
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        float $amount
    ): TransactionSignerInterface;

    /**
     * @param string $owner
     * @param string $spender
     * @param float $amount
     * @return TransactionSignerInterface
     */
    public function approve(string $owner, string $spender, float $amount): TransactionSignerInterface;
}

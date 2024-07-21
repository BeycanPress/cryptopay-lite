<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Assets;

use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Services\TransactionSignerInterface;

/**
 * @property ProviderInterface|null $provider
 */
interface CoinInterface extends AssetInterface
{
    /**
     * @param ProviderInterface|null $provider
     */
    public function __construct(?ProviderInterface $provider = null);

    /**
     * @return int
     */
    public function getDecimals(): int;

    /**
     * @param string $sender
     * @param string $receiver
     * @param float $amount
     * @return TransactionSignerInterface
     */
    public function transfer(string $sender, string $receiver, float $amount): TransactionSignerInterface;
}

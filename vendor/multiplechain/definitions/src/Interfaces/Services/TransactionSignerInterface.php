<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Services;

use MultipleChain\Interfaces\ProviderInterface;

/**
 * @property mixed $rawData
 * @property mixed $signedData
 * @property ProviderInterface $provider
 */
interface TransactionSignerInterface
{
    /**
     * @param mixed $rawData
     * @param ProviderInterface|null $provider
     */
    public function __construct(mixed $rawData, ?ProviderInterface $provider = null);

    /**
     * @param string $privateKey
     * @return TransactionSignerInterface
     */
    public function sign(string $privateKey): TransactionSignerInterface;

    /**
     * @return string Transaction id
     */
    public function send(): string;

    /**
     * @return mixed
     */
    public function getRawData(): mixed;

    /**
     * @return mixed
     */
    public function getSignedData(): mixed;
}

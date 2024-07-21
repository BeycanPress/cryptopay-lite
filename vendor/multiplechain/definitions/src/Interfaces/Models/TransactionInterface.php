<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Models;

use MultipleChain\Utils\Number;
use MultipleChain\Enums\TransactionType;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\Interfaces\ProviderInterface;

/**
 * @property string $id
 * @property mixed $data
 * @property ProviderInterface $provider
 */
interface TransactionInterface
{
    /**
     * @param string $id
     * @param ProviderInterface|null $provider
     */
    public function __construct(string $id, ?ProviderInterface $provider = null);

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return mixed
     */
    public function getData(): mixed;

    /**
     * @param int|null $ms
     * @return TransactionStatus
     */
    public function wait(?int $ms = 4000): TransactionStatus;

    /**
     * @return TransactionType
     */
    public function getType(): TransactionType;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return string
     */
    public function getSigner(): string;

    /**
     * @return Number
     */
    public function getFee(): Number;

    /**
     * @return int
     */
    public function getBlockNumber(): int;

    /**
     * @return int
     */
    public function getBlockTimestamp(): int;

    /**
     * @return int
     */
    public function getBlockConfirmationCount(): int;

    /**
     * @return TransactionStatus
     */
    public function getStatus(): TransactionStatus;
}

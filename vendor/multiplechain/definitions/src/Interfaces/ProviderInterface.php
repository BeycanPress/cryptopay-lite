<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces;

use MultipleChain\BaseNetworkConfig;

/**
 * @property BaseNetworkConfig $network
 * @property ProviderInterface|null $instance (static)
 */
interface ProviderInterface
{
    /**
     * @param array<string,mixed> $network
     */
    public function __construct(array $network);

    /**
     * @return ProviderInterface
     */
    public static function instance(): ProviderInterface;

    /**
     * @param array<string,mixed> $network
     * @return void
     */
    public static function initialize(array $network): void;

    /**
     * @param array<string,mixed> $network
     * @return void
     */
    public function update(array $network): void;

    /**
     * @return boolean
     */
    public function isTestnet(): bool;

    /**
     * @param string|null $url
     * @return boolean
     */
    public function checkRpcConnection(?string $url = null): bool;

    /**
     * @param string|null $url
     * @return boolean
     */
    public function checkWsConnection(?string $url = null): bool;
}

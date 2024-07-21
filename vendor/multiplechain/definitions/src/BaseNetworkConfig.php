<?php

declare(strict_types=1);

namespace MultipleChain;

class BaseNetworkConfig
{
    /**
     * @var string|null
     */
    private ?string $wsUrl;

    /**
     * @var string|null
     */
    private ?string $rpcUrl;

    /**
     * @var boolean
     */
    private bool $testnet;

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config)
    {
        $this->wsUrl = isset($config['wsUrl']) && is_string($config['wsUrl']) ? $config['wsUrl'] : null;
        $this->rpcUrl = isset($config['rpcUrl']) && is_string($config['rpcUrl']) ? $config['rpcUrl'] : null;
        $this->testnet = isset($config['testnet']) && is_bool($config['testnet']) ? $config['testnet'] : false;
    }

    /**
     * @return string|null
     */
    public function getWsUrl(): ?string
    {
        return $this->wsUrl;
    }

    /**
     * @return string|null
     */
    public function getRpcUrl(): ?string
    {
        return $this->rpcUrl;
    }

    /**
     * @return boolean
     */
    public function isTestnet(): bool
    {
        return $this->testnet;
    }
}

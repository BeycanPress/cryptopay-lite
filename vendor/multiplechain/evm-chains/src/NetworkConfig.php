<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains;

use MultipleChain\BaseNetworkConfig;

class NetworkConfig extends BaseNetworkConfig
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string|null
     */
    public ?string $hexId;

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var int|null
     */
    public ?int $mainnetId;

    /**
     * @var string
     */
    public string $rpcUrl;

    /**
     * @var string
     */
    public string $explorerUrl;

    /**
     * @var array<string,mixed>
     */
    public array $nativeCurrency;

    /**
     * @param array<string,mixed> $network
     */
    public function __construct(array $network)
    {
        if (!isset($network['id']) || !is_int($network['id'])) {
            throw new \RuntimeException('Network id is required and must be an integer');
        }

        if (!isset($network['rpcUrl']) || !is_string($network['rpcUrl'])) {
            throw new \RuntimeException('Network rpcUrl is required and must be a string');
        }

        if (!isset($network['explorerUrl']) || !is_string($network['explorerUrl'])) {
            throw new \RuntimeException('Network explorerUrl is required and must be a string');
        }

        if (!isset($network['nativeCurrency']) || !is_array($network['nativeCurrency'])) {
            throw new \RuntimeException('Network nativeCurrency is required and must be an array');
        }

        if (!isset($network['nativeCurrency']['symbol'])) {
            throw new \RuntimeException('Network nativeCurrency symbol is required');
        }

        if (!isset($network['nativeCurrency']['decimals'])) {
            throw new \RuntimeException('Network nativeCurrency decimals is required');
        }

        parent::__construct($network);

        $this->id = $network['id'];
        $this->rpcUrl = $network['rpcUrl'];
        $this->explorerUrl = $network['explorerUrl'];
        $this->nativeCurrency = $network['nativeCurrency'];
        $this->name = isset($network['name']) && is_string($network['name']) ? $network['name'] : null;
        $this->hexId = isset($network['hexId']) && is_string($network['hexId']) ? $network['hexId'] : null;
        $this->mainnetId = isset($network['mainnetId']) && is_int($network['mainnetId']) ? $network['mainnetId'] : null;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHexId(): string
    {
        return $this->hexId ?? '0x' . dechex($this->id);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getMainnetId(): ?int
    {
        return $this->mainnetId;
    }

    /**
     * @return string
     */
    public function getRpcUrl(): string
    {
        return $this->rpcUrl;
    }

    /**
     * @return string
     */
    public function getExplorerUrl(): string
    {
        return $this->explorerUrl;
    }

    /**
     * @return array<string,mixed>
     */
    public function getNativeCurrency(): array
    {
        return $this->nativeCurrency;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'hexId' => $this->hexId,
            'name' => $this->name,
            'mainnetId' => $this->mainnetId,
            'rpcUrl' => $this->rpcUrl,
            'explorerUrl' => $this->explorerUrl,
            'nativeCurrency' => $this->nativeCurrency,
            'testnet' => $this->isTestnet(),
            'wsUrl' => $this->getWsUrl(),
        ];
    }
}

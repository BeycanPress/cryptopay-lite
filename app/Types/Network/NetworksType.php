<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Network;

use BeycanPress\CryptoPayLite\Types\AbstractListType;

/**
 * Networks type
 * @since 2.1.0
 */
class NetworksType extends AbstractListType
{
    /**
     * @var string
     */
    protected static string $type = NetworkType::class;

    /**
     * @param array<NetworkType> $networks
     */
    public function __construct(array $networks = [])
    {
        $this->addNetworks($networks);
    }

    /**
     * @param NetworkType $network
     * @return self
     */
    public function addNetwork(NetworkType $network): self
    {
        $this->list[] = $network;

        return $this;
    }

    /**
     * @param array<NetworkType> $networks
     * @return self
     */
    public function addNetworks(array $networks): self
    {
        foreach ($networks as $network) {
            $this->addNetwork($network);
        }

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function forDebug(): array
    {
        $networks = [];

        /** @var NetworkType $network */
        foreach ($this->list as $network) {
            $networks[] = $network->forDebug();
        }

        return $networks;
    }
}

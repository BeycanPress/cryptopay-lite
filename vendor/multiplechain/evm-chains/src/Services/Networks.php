<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Services;

use MultipleChain\EvmChains\NetworkConfig as Network;

class Networks
{
    /**
     * @var array<Network>|null
     */
    private static ?array $testnets = null;

    /**
     * @var array<Network>|null
     */
    private static ?array $mainnets = null;

    /**
     * @var array<Network>
     */
    private static array $addedNetworks = [];

    /**
     * @param array<array<mixed>> $network
     * @return Network|null
     */
    private static function transform(array $network): ?Network
    {
        try {
            return new Network($network);
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * @return array<Network>
     */
    public static function getMainnets(): array
    {
        if (null !== self::$mainnets) {
            return self::$mainnets;
        }

        $mainnets = json_decode(file_get_contents(dirname(__DIR__, 2) . '/resources/mainnets.json') ?: '', true);

        return self::$mainnets = array_filter(array_map([self::class, 'transform'], $mainnets));
    }

    /**
     * @return array<Network>
     */
    public static function getTestnets(): array
    {
        if (null !== self::$testnets) {
            return self::$testnets;
        }

        $testnets = json_decode(file_get_contents(dirname(__DIR__, 2) . '/resources/testnets.json') ?: '', true);

        return self::$testnets = array_filter(array_map([self::class, 'transform'], $testnets));
    }

    /**
     * @return array<Network>
     */
    public static function getAll(): array
    {
        return array_values(array_merge(self::getMainnets(), self::getTestnets(), self::$addedNetworks));
    }

    /**
     * @param Network $network
     * @return void
     */
    public static function add(Network $network): void
    {
        if (in_array($network->id, array_column(self::getAll(), 'id'))) {
            throw new \RuntimeException('Network already exists');
        }

        self::$addedNetworks[] = $network;
    }

    /**
     * @param int $id
     * @return Network|null
     */
    public static function findById(int $id): ?Network
    {
        return array_reduce(self::getAll(), fn ($c, Network $n) => $n->id === $id ? $n : $c);
    }

    /**
     * @param string $name
     * @return Network|null
     */
    public static function findByName(string $name): ?Network
    {
        return array_reduce(self::getAll(), fn ($c, Network $n) => false !== strpos($n->name ?? '', $name) ? $n : $c);
    }

    /**
     * @param string $hexId
     * @return Network|null
     */
    public static function findByHexId(string $hexId): ?Network
    {
        return array_reduce(self::getAll(), fn ($c, Network $n) => $n->hexId === $hexId ? $n : $c);
    }

    /**
     * @param string $symbol
     * @return Network|null
     */
    public static function findBySymbol(string $symbol): ?Network
    {
        return array_reduce(self::getAll(), fn ($c, Network $n) => $n->nativeCurrency['symbol'] === $symbol ? $n : $c);
    }
}

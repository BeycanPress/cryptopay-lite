<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests;

use MultipleChain\EvmChains\Services\Networks;
use MultipleChain\EvmChains\NetworkConfig as Network;

class NetworksTest extends BaseTest
{
    /**
     * @return void
     */
    public function testAll(): void
    {
        $networks = Networks::getAll();
        $this->assertIsArray($networks);
        $this->assertNotEmpty($networks);
        $this->assertInstanceOf(Network::class, $networks[0]);
    }

    /**
     * @return void
     */
    public function testMainnets(): void
    {
        $mainnets = Networks::getMainnets();
        $this->assertIsArray($mainnets);
        $this->assertNotEmpty($mainnets);
        $this->assertInstanceOf(Network::class, $mainnets[0]);
    }

    /**
     * @return void
     */
    public function testTestnets(): void
    {
        $testnets = Networks::getTestnets();
        $this->assertIsArray($testnets);
        $this->assertNotEmpty($testnets);
        $this->assertInstanceOf(Network::class, $testnets[0]);
    }

    /**
     * @return void
     */
    public function testAddNetwork(): void
    {
        $network = new Network([
            'id' => 626566,
            'hexId' => '0xaa36a8',
            'mainnetId' => 1,
            'testnet' => true,
            'name' => 'Ethereum Sepolia Testnet (QR)',
            'explorerUrl' => 'https://sepolia.etherscan.io/',
            'rpcUrl' => 'https://sepolia.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161',
            'wsUrl' => 'wss://sepolia.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161',
            'nativeCurrency' => [
                'symbol' => 'ETH',
                'name' => 'Ethereum',
                'decimals' => 18
            ]
        ]);

        Networks::add($network);

        $testNetwork = Networks::findById(626566);

        $this->assertInstanceOf(Network::class, $testNetwork);

        $exception = null;
        try {
            Networks::add($network);
        } catch (\RuntimeException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    /**
     * @return void
     */
    public function testFindById(): void
    {
        $network = Networks::findById(1);
        $this->assertEquals(1, $network->id);
    }

    /**
     * @return void
     */
    public function testFindByHexId(): void
    {
        $network = Networks::findByHexId('0x1');
        $this->assertEquals('0x1', $network->hexId);
    }

    /**
     * @return void
     */
    public function testFindByName(): void
    {
        $network = Networks::findByName('Ethereum Sepolia Testnet (QR)');
        $this->assertEquals('Ethereum Sepolia Testnet (QR)', $network->name);
    }

    /**
     * @return void
     */
    public function testFindBySymbol(): void
    {
        $network = Networks::findBySymbol('ETH');
        $this->assertEquals('ETH', $network->nativeCurrency['symbol']);
    }
}

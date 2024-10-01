<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests;

use PHPUnit\Framework\TestCase;
use MultipleChain\EvmChains\Provider;

class BaseTest extends TestCase
{
    /**
     * @var Provider
     */
    protected Provider $provider;

    /**
     * @var object
     */
    protected object $data;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->data = json_decode(file_get_contents(__DIR__ . '/data.json'));

        $this->provider = new Provider([
            'id' => 11155111,
            'hexId' => '0xaa36a7',
            'mainnetId' => 1,
            'testnet' => true,
            'name' => 'Ethereum Sepolia Testnet (QR)',
            'explorerUrl' => 'https://sepolia.etherscan.io/',
            "wsUrl" => "wss://ethereum-sepolia-rpc.publicnode.com",
            "rpcUrl" => "https://ethereum-sepolia-rpc.publicnode.com",
            'nativeCurrency' => [
                'symbol' => 'ETH',
                'name' => 'Ethereum',
                'decimals' => 18
            ]
        ]);
    }

    /**
     * @return void
     */
    public function testExample(): void
    {
        $this->assertTrue(true);
    }
}

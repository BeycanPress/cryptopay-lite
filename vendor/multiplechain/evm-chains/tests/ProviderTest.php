<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests;

class ProviderTest extends BaseTest
{
    /**
     * @return void
     */
    public function testChainId(): void
    {
        $this->assertEquals(11155111, $this->provider->network->getId());
    }

    /**
     * @return void
     */
    public function testIsTestnet(): void
    {
        $this->assertTrue($this->provider->network->isTestnet());
    }

    /**
     * @return void
     */
    public function testRpcConnection(): void
    {
        $this->assertTrue($this->provider->checkRpcConnection(
            "https://sepolia.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161"
        ));
        $this->assertFalse($this->provider->checkRpcConnection(
            "https://sepolia.infura.io/v3/"
        ));
    }

    /**
     * @return void
     */
    public function testWsConnection(): void
    {
        $this->assertTrue($this->provider->checkWsConnection(
            "wss://sepolia.infura.io/ws/v3/9aa3d95b3bc440fa88ea12eaa4456161"
        ));
    }
}

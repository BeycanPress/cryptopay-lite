<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests;

use MultipleChain\EvmChains\Web3;
use PHPUnit\Framework\TestCase;

class Web3Test extends TestCase
{
    /**
     * @var Web3
     */
    private Web3 $web3;

    /**
     * @var string
     */
    private string $addr = '0x74dBE9cA4F93087A27f23164d4367b8ce66C33e2';

    /**
     * @var string
     */
    private string $tx = '0xb272d6fa3b87424da022a47ae0db47a56823e3364f8de5bfe84c670c00dde938';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->web3 = new Web3('https://ethereum-sepolia-rpc.publicnode.com');
    }

    /**
     * @return void
     */
    public function testBlockNumber(): void
    {
        $this->assertGreaterThan(6017525, $this->web3->getBlockNumber());
    }

    /**
     * @return void
     */
    public function testNonce(): void
    {
        $this->assertGreaterThan('0x1', $this->web3->getNonce($this->addr));
    }

    /**
     * @return void
     */
    public function testTransaction(): void
    {

        $this->assertArrayHasKey('from', $this->web3->getTransaction($this->tx));
    }

    /**
     * @return void
     */
    public function testTransactionReceipt(): void
    {
        $this->assertArrayHasKey('blockHash', $this->web3->getTransactionReceipt($this->tx));
    }
}

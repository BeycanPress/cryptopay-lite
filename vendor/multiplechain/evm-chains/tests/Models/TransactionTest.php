<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Models;

use MultipleChain\Enums\TransactionType;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\EvmChains\Models\Transaction;

class TransactionTest extends BaseTest
{
    /**
     * @var Transaction
     */
    private Transaction $tx;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tx = new Transaction($this->data->coinTransferTx);
    }

    /**
     * @return void
     */
    public function testId(): void
    {
        $this->assertEquals($this->data->coinTransferTx, $this->tx->getId());
    }

    /**
     * @return void
     */
    public function testData(): void
    {
        $this->assertIsObject($this->tx->getData());
    }

    /**
     * @return void
     */
    public function testType(): void
    {
        $this->assertEquals(TransactionType::COIN, $this->tx->getType());
    }

    /**
     * @return void
     */
    public function testWait(): void
    {
        $this->assertEquals(TransactionStatus::CONFIRMED, $this->tx->wait());
    }

    /**
     * @return void
     */
    public function testUrl(): void
    {
        $this->assertEquals(
            'https://sepolia.etherscan.io/tx/0x566002399664e92f82ed654c181095bdd7ff3d3f1921d963257585891f622251',
            $this->tx->getUrl()
        );
    }

    /**
     * @return void
     */
    public function testSender(): void
    {
        $this->assertEquals(strtolower($this->data->coinSender), strtolower($this->tx->getSigner()));
    }

    /**
     * @return void
     */
    public function testFee(): void
    {
        $this->assertEquals(0.000371822357865, $this->tx->getFee()->toFloat());
    }

    /**
     * @return void
     */
    public function testBlockNumber(): void
    {
        $this->assertEquals(5461884, $this->tx->getBlockNumber());
    }

    /**
     * @return void
     */
    public function testBlockTimestamp(): void
    {
        $this->assertEquals(1710141144, $this->tx->getBlockTimestamp());
    }

    /**
     * @return void
     */
    public function testBlockConfirmationCount(): void
    {
        $this->assertGreaterThan(129954, $this->tx->getBlockConfirmationCount());
    }

    /**
     * @return void
     */
    public function testStatus(): void
    {
        $this->assertEquals(TransactionStatus::CONFIRMED, $this->tx->getStatus());
    }
}

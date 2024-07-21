<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\EvmChains\Assets\Coin;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\EvmChains\Models\Transaction;

class CoinTest extends BaseTest
{
    /**
     * @var Coin
     */
    private Coin $coin;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->coin = new Coin();
    }

    /**
     * @return void
     */
    public function testName(): void
    {
        $this->assertEquals('Ethereum', $this->coin->getName());
    }

    /**
     * @return void
     */
    public function testSymbol(): void
    {
        $this->assertEquals('ETH', $this->coin->getSymbol());
    }

    /**
     * @return void
     */
    public function testDecimals(): void
    {
        $this->assertEquals(18, $this->coin->getDecimals());
    }

    /**
     * @return void
     */
    public function testBalance(): void
    {
        $this->assertEquals(
            $this->data->coinBalanceTestAmount,
            $this->coin->getBalance($this->data->balanceTestAddress)->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        $signer = $this->coin->transfer(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->transferTestAmount
        );

        $signer = $signer->sign($this->data->senderPrivateKey);

        if (!$this->data->coinTransferTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $beforeBalance = $this->coin->getBalance($this->data->receiverTestAddress);

        (new Transaction($signer->send()))->wait();

        $afterBalance = $this->coin->getBalance($this->data->receiverTestAddress);

        $transferNumber = new Number($this->data->transferTestAmount, $this->coin->getDecimals());

        $this->assertEquals(
            $afterBalance->toString(),
            $beforeBalance->add($transferNumber)->toString()
        );
    }
}

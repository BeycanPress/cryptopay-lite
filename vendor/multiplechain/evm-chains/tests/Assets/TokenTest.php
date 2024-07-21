<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\EvmChains\Assets\Token;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\EvmChains\Models\Transaction;

class TokenTest extends BaseTest
{
    /**
     * @var Token
     */
    private Token $token;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->token = new Token($this->data->tokenTestAddress);
    }

    /**
     * @return void
     */
    public function testName(): void
    {
        $this->assertEquals('MyToken', $this->token->getName());
    }

    /**
     * @return void
     */
    public function testSymbol(): void
    {
        $this->assertEquals('MTK', $this->token->getSymbol());
    }

    /**
     * @return void
     */
    public function testDecimals(): void
    {
        $this->assertEquals(18, $this->token->getDecimals());
    }

    /**
     * @return void
     */
    public function testBalance(): void
    {
        $this->assertEquals(
            $this->data->tokenBalanceTestAmount,
            $this->token->getBalance($this->data->balanceTestAddress)->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTotalSupply(): void
    {
        $this->assertEquals(
            1000000,
            $this->token->getTotalSupply()->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        $signer = $this->token->transfer(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->tokenTransferTestAmount
        );

        $signer = $signer->sign($this->data->senderPrivateKey);

        if (!$this->data->tokenTransferTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $beforeBalance = $this->token->getBalance($this->data->receiverTestAddress);

        (new Transaction($signer->send()))->wait();

        $afterBalance = $this->token->getBalance($this->data->receiverTestAddress);

        $transferNumber = new Number($this->data->tokenTransferTestAmount, $this->token->getDecimals());

        $this->assertEquals(
            $afterBalance->toString(),
            $beforeBalance->add($transferNumber)->toString()
        );
    }

    /**
     * @return void
     */
    public function testApproveAndAllowance(): void
    {
        $signer = $this->token->approve(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->tokenApproveTestAmount
        );

        $signer = $signer->sign($this->data->senderPrivateKey);

        if (!$this->data->tokenApproveTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        (new Transaction($signer->send()))->wait();

        $allowance = $this->token->getAllowance(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress
        );

        $this->assertEquals(
            $this->data->tokenApproveTestAmount,
            $allowance->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTransferFrom(): void
    {
        $signer = $this->token->transferFrom(
            $this->data->receiverTestAddress,
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            2
        );

        $signer = $signer->sign($this->data->receiverPrivateKey);

        if (!$this->data->tokenTransferFromTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $beforeBalance = $this->token->getBalance($this->data->receiverTestAddress);

        (new Transaction($signer->send()))->wait();

        $afterBalance = $this->token->getBalance($this->data->receiverTestAddress);

        $this->assertEquals(
            $afterBalance->toString(),
            $beforeBalance->add(new Number(2, $this->token->getDecimals()))->toString()
        );
    }
}

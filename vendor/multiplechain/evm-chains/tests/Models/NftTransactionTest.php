<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Models;

use MultipleChain\Enums\AssetDirection;
use MultipleChain\Enums\TransactionType;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\EvmChains\Models\NftTransaction;

class NftTransactionTest extends BaseTest
{
    /**
     * @var NftTransaction
     */
    private NftTransaction $tx;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tx = new NftTransaction($this->data->nftTransferTx);
    }

    /**
     * @return void
     */
    public function testReceiver(): void
    {
        $this->assertEquals(
            strtolower($this->tx->getReceiver()),
            strtolower($this->data->nftReceiver)
        );
    }

    /**
     * @return void
     */
    public function testSender(): void
    {
        $this->assertEquals(
            strtolower($this->tx->getSender()),
            strtolower($this->data->nftSender)
        );
    }

    /**
     * @return void
     */
    public function testSigner(): void
    {
        $this->assertEquals(
            strtolower($this->tx->getSigner()),
            strtolower($this->data->nftReceiver)
        );
    }

    /**
     * @return void
     */
    public function testNftId(): void
    {
        $this->assertEquals(
            $this->tx->getNftId(),
            $this->data->nftId
        );
    }

    /**
     * @return void
     */
    public function testType(): void
    {
        $this->assertEquals(
            $this->tx->getType(),
            TransactionType::NFT
        );
    }

    /**
     * @return void
     */
    public function testVerifyTransfer(): void
    {
        $this->assertEquals(
            $this->tx->verifyTransfer(
                AssetDirection::INCOMING,
                $this->data->nftReceiver,
                $this->data->nftId
            ),
            TransactionStatus::CONFIRMED
        );

        $this->assertEquals(
            $this->tx->verifyTransfer(
                AssetDirection::OUTGOING,
                $this->data->nftSender,
                $this->data->nftId
            ),
            TransactionStatus::CONFIRMED
        );

        $this->assertEquals(
            $this->tx->verifyTransfer(
                AssetDirection::INCOMING,
                $this->data->nftSender,
                $this->data->nftId
            ),
            TransactionStatus::FAILED
        );
    }
}

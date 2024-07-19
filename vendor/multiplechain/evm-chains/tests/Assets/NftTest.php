<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\EvmChains\Assets\NFT;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\EvmChains\Models\Transaction;

class NftTest extends BaseTest
{
    /**
     * @var NFT
     */
    private NFT $nft;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->nft = new NFT($this->data->nftTestAddress);
    }

    /**
     * @return void
     */
    public function testName(): void
    {
        $this->assertEquals('NFT2', $this->nft->getName());
    }

    /**
     * @return void
     */
    public function testSymbol(): void
    {
        $this->assertEquals('NFT2', $this->nft->getSymbol());
    }

    /**
     * @return void
     */
    public function testBalance(): void
    {
        $this->assertEquals(
            $this->data->nftBalanceTestAmount,
            $this->nft->getBalance($this->data->balanceTestAddress)->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testOwner(): void
    {
        $this->assertEquals(
            strtolower($this->data->balanceTestAddress),
            strtolower($this->nft->getOwner(600))
        );
    }

    /**
     * @return void
     */
    public function testTokenURI(): void
    {
        $this->assertEquals(
            '',
            $this->nft->getTokenURI(600)
        );
    }

    /**
     * @return void
     */
    public function testApproved(): void
    {
        $this->assertEquals(
            null,
            $this->nft->getApproved(600)
        );
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        $signer = $this->nft->transfer(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->nftTransferId
        );

        $signer = $signer->sign($this->data->senderPrivateKey);

        if (!$this->data->nftTransactionTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        (new Transaction($signer->send()))->wait();

        $this->assertEquals(
            strtolower($this->nft->getOwner($this->data->nftTransferId)),
            strtolower($this->data->receiverTestAddress)
        );
    }

    /**
     * @return void
     */
    public function testApprove(): void
    {
        $customOwner = $this->data->nftTransactionTestIsActive
            ? $this->data->receiverTestAddress
            : $this->data->senderTestAddress;
        $customSpender = $this->data->nftTransactionTestIsActive
            ? $this->data->senderTestAddress
            : $this->data->receiverTestAddress;
        $customPrivateKey = $this->data->nftTransactionTestIsActive
            ? $this->data->receiverPrivateKey
            : $this->data->senderPrivateKey;

        $signer = $this->nft->approve(
            $customOwner,
            $customSpender,
            $this->data->nftTransferId
        );

        $signer = $signer->sign($customPrivateKey);

        if (!$this->data->nftTransactionTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        (new Transaction($signer->send()))->wait();

        $this->assertEquals(
            strtolower($this->nft->getApproved($this->data->nftTransferId)),
            strtolower($this->data->senderTestAddress)
        );
    }

    /**
     * @return void
     */
    public function testTransferFrom(): void
    {
        if (!$this->data->nftTransactionTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $signer = $this->nft->transferFrom(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->senderTestAddress,
            $this->data->nftTransferId
        );

        (new Transaction($signer->sign($this->data->senderPrivateKey)->send()))->wait();

        $this->assertEquals(
            strtolower($this->nft->getOwner($this->data->nftTransferId)),
            strtolower($this->data->senderTestAddress)
        );
    }
}

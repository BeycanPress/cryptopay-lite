<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Models;

use MultipleChain\Utils\Number;
use MultipleChain\Enums\ErrorType;
use MultipleChain\EvmChains\Provider;
use MultipleChain\Enums\TransactionType;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\EvmChains\Assets\NFT;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Models\TransactionInterface;

class Transaction implements TransactionInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var object
     */
    private ?object $data = null;

    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var int
     */
    protected int $currencyDecimals;

    /**
     * @param string $id
     * @param Provider|null $provider
     */
    public function __construct(string $id, ?ProviderInterface $provider = null)
    {
        $this->id = $id;
        $this->provider = $provider ?? Provider::instance();
        $this->currencyDecimals = $this->provider->network->getNativeCurrency()['decimals'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return object|null
     */
    public function getData(): ?object
    {
        if (isset($this->data?->response, $this->data?->receipt)) {
            return $this->data;
        }

        try {
            if (empty($response = $this->provider->web3->getTransaction($this->id))) {
                return null;
            }

            if (empty($receipt = $this->provider->web3->getTransactionReceipt($this->id))) {
                return null;
            }

            return $this->data = (object) [
                'response' => (object) $response,
                'receipt' => (object) $receipt
            ];
        } catch (\Throwable $th) {
            if (false !== strpos($th->getMessage(), 'timeout')) {
                throw new \RuntimeException(ErrorType::RPC_TIMEOUT->value);
            }
            throw new \RuntimeException(ErrorType::RPC_REQUEST_ERROR->value);
        }
    }

    /**
     * @param int|null $ms
     * @return TransactionStatus
     */
    public function wait(?int $ms = 4000): TransactionStatus
    {
        try {
            $status = $this->getStatus();
            if (TransactionStatus::PENDING != $status) {
                return $status;
            }

            sleep($ms / 1000);

            return $this->wait($ms);
        } catch (\Throwable $th) {
            return TransactionStatus::FAILED;
        }
    }

    /**
     * @return TransactionType
     */
    public function getType(): TransactionType
    {
        $selectors = [
            // ERC20
            '0xa9059cbb', // transfer(address,uint256)
            '0x095ea7b3', // approve(address,uint256)
            '0x23b872dd', // transferFrom(address,address,uint256)
            // ERC721
            '0x42842e0e', // safeTransferFrom(address,address,uint256)
            '0xb88d4fde', // safeTransferFrom(address,address,uint256,bytes)
            // ERC1155
            '0xf242432a', // safeTransferFrom(address,address,uint256,uint256,bytes)
            '0x2eb2c2d6', // safeBatchTransferFrom(address,address,uint256[],uint256[],bytes)
            '0x29535c7e' // setApprovalForAll(address,bool)
        ];

        $data = $this->getData();

        if (null == $data) {
            return TransactionType::GENERAL;
        }

        $to = $data->response?->to ?? '';
        $input = $data->response?->input ?? '';

        $byteCode = $this->provider->web3->getByteCode($to);

        if ('0x' === $byteCode || '0x' === $input) {
            return TransactionType::COIN;
        }

        $selectorId = substr($input, 0, 10);

        if (in_array($selectorId, $selectors)) {
            try {
                $nft = new NFT($to);
                $nft->getApproved(1);
                return TransactionType::NFT;
            } catch (\Throwable $th) {
                return TransactionType::TOKEN;
            }
        }

        return TransactionType::CONTRACT;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $explorerUrl = $this->provider->network->getExplorerUrl();
        $explorerUrl .= '/' === substr($explorerUrl, -1) ? '' : '/';
        $explorerUrl .= 'tx/' . $this->id;
        return $explorerUrl;
    }

    /**
     * @return string
     */
    public function getSigner(): string
    {
        $data = $this->getData();
        return $data?->response?->from ?? '';
    }

    /**
     * @return Number
     */
    public function getFee(): Number
    {
        $data = $this->getData();
        $gasUsed = $data?->receipt?->gasUsed ?? null;
        $gasPrice = $data?->response?->gasPrice ?? null;

        if (null == $gasUsed || null == $gasUsed) {
            return new Number(0);
        }

        return new Number((hexdec($gasPrice) * hexdec($gasUsed)) / pow(10, $this->currencyDecimals));
    }

    /**
     * @return int
     */
    public function getBlockNumber(): int
    {
        $data = $this->getData();

        $blockNumber = $data?->response?->blockNumber ?? null;

        if (null == $blockNumber) {
            return 0;
        }

        return (int) hexdec($blockNumber);
    }

    /**
     * @return int
     */
    public function getBlockTimestamp(): int
    {
        $blockNumber = $this->getBlockNumber();
        $block = $this->provider->web3->getBlockByNumber($blockNumber);
        return (int) hexdec($block->timestamp ?? 0);
    }

    /**
     * @return int
     */
    public function getBlockConfirmationCount(): int
    {
        $blockNumber = $this->getBlockNumber();
        $blockCount = $this->provider->web3->getBlockNumber();
        $confirmations = $blockCount - $blockNumber;
        return (int) $confirmations < 0 ? 0 : $confirmations;
    }

    /**
     * @return TransactionStatus
     */
    public function getStatus(): TransactionStatus
    {
        $data = $this->getData();
        $receipt = $data?->receipt ?? null;
        $blockNumber = $data?->response?->blockNumber ?? null;

        if (null === $data) {
            return TransactionStatus::PENDING;
        } elseif (null !== $blockNumber && null !== $receipt) {
            if ('0x1' === $receipt->status) {
                return TransactionStatus::CONFIRMED;
            } else {
                return TransactionStatus::FAILED;
            }
        }

        return TransactionStatus::PENDING;
    }
}

<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Models;

use MultipleChain\EvmChains\Provider;
use MultipleChain\Enums\AssetDirection;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Models\NftTransactionInterface;

class NftTransaction extends ContractTransaction implements NftTransactionInterface
{
    /**
     * @param string $address
     * @param Provider|null $provider
     * @param array<object>|null $abi
     */
    public function __construct(string $address, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        parent::__construct(
            $address,
            $provider,
            $abi ? $abi : json_decode(file_get_contents(dirname(__DIR__, 2) . '/resources/ERC721.json') ?: '')
        );
    }

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        /**
         * @var null|object{'name': string, 'args': array<int,object>} $decoded
         */
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return '';
        }

        if ('transferFrom' === $decoded->name) {
            // @phpstan-ignore-next-line
            return $decoded->args[1]->value;
        }

        // @phpstan-ignore-next-line
        return $decoded->args[0]->value;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        /**
         * @var null|object{'name': string, 'args': array<int,object>} $decoded
         */
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return '';
        }

        if ('transferFrom' === $decoded->name) {
            // @phpstan-ignore-next-line
            return $decoded->args[0]->value;
        }

        return $this->getSigner();
    }

    /**
     * @return string
     */
    public function getNftId(): int|string
    {
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return '';
        }

        // @phpstan-ignore-next-line
        return (int) hexdec($decoded->args[2]->value);
    }

    /**
     * @param AssetDirection $direction
     * @param string $address
     * @param int|string $nftId
     * @return TransactionStatus
     */
    public function verifyTransfer(AssetDirection $direction, string $address, int|string $nftId): TransactionStatus
    {
        $status = $this->getStatus();

        if (TransactionStatus::PENDING === $status) {
            return TransactionStatus::PENDING;
        }

        if ($this->getNftId() !== $nftId) {
            return TransactionStatus::FAILED;
        }

        if (AssetDirection::INCOMING === $direction) {
            if (strtolower($this->getReceiver()) !== strtolower($address)) {
                return TransactionStatus::FAILED;
            }
        } else {
            if (strtolower($this->getSender()) !== strtolower($address)) {
                return TransactionStatus::FAILED;
            }
        }

        return TransactionStatus::CONFIRMED;
    }
}

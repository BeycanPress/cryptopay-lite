<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\Enums\ErrorType;
use MultipleChain\EvmChains\Provider;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\NftInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class NFT extends Contract implements NftInterface
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
    public function getName(): string
    {
        return $this->callMethodWithCache('name');
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->callMethodWithCache('symbol');
    }

    /**
     * @param string $owner
     * @return Number
     */
    public function getBalance(string $owner): Number
    {
        return new Number($this->callMethod('balanceOf', $owner), 0);
    }

    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getOwner(int|string $tokenId): string
    {
        return $this->callMethod('ownerOf', $tokenId);
    }

    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getTokenURI(int|string $tokenId): string
    {
        return $this->callMethodWithCache('tokenURI', $tokenId);
    }

    /**
     * @param int|string $tokenId
     * @return string|null
     */
    public function getApproved(int|string $tokenId): ?string
    {
        $address = $this->callMethod('getApproved', $tokenId);
        return '0x0000000000000000000000000000000000000000' === $address ? null : $address;
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function transfer(string $sender, string $receiver, int|string $tokenId): TransactionSigner
    {
        return $this->transferFrom($sender, $sender, $receiver, $tokenId);
    }

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        int|string $tokenId
    ): TransactionSigner {
        if ($this->getBalance($owner)->toFloat() <= 0) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        $originalOwner = $this->getOwner($tokenId);

        if (strtolower($originalOwner) !== strtolower($owner)) {
            throw new \RuntimeException(ErrorType::UNAUTHORIZED_ADDRESS->value);
        }

        if (strtolower($spender) !== strtolower($owner)) {
            $approved = $this->getApproved($tokenId) ?? '';
            if (strtolower($approved) !== strtolower($spender)) {
                throw new \RuntimeException(ErrorType::UNAUTHORIZED_ADDRESS->value);
            }
        }

        return new TransactionSigner(
            $this->createTransactionData('transferFrom', $spender, $owner, $receiver, $tokenId)
        );
    }

    /**
     * @param string $owner
     * @param string $spender
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function approve(string $owner, string $spender, int|string $tokenId): TransactionSigner
    {
        if ($this->getBalance($owner)->toFloat() <= 0) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        if (strtolower($this->getOwner($tokenId)) !== strtolower($owner)) {
            throw new \RuntimeException(ErrorType::UNAUTHORIZED_ADDRESS->value);
        }

        return new TransactionSigner(
            $this->createTransactionData('approve', $owner, $spender, $tokenId)
        );
    }
}

<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Services;

use Web3p\EthereumTx\Transaction;
use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\TransactionData;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Services\TransactionSignerInterface;

class TransactionSigner implements TransactionSignerInterface
{
    /**
     * @var TransactionData
     */
    private TransactionData $rawData;

    /**
     * @var string
     */
    private string $signedData;

    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @param mixed $rawData
     * @param Provider|null $provider
     * @return void
     */
    public function __construct(mixed $rawData, ?ProviderInterface $provider = null)
    {
        if (null === $rawData) {
            throw new \RuntimeException('Invalid transaction data');
        }

        if (!($rawData instanceof TransactionData)) {
            if (!is_array($rawData)) {
                throw new \RuntimeException('Raw data must be an array');
            }
            $rawData = new TransactionData($rawData);
        }

        $this->rawData = $rawData;
        $this->provider = $provider ?? Provider::instance();
    }

    /**
     * @param string $privateKey
     * @return TransactionSigner
     */
    public function sign(string $privateKey): TransactionSigner
    {
        $tx = new Transaction($this->rawData->toArray());
        $this->signedData = '0x' . $tx->sign($privateKey);
        return $this;
    }

    /**
     * @return string Transaction id
     */
    public function send(): string
    {
        return $this->provider->web3->sendRawTransaction($this->signedData);
    }

    /**
     * @return TransactionData
     */
    public function getRawData(): mixed
    {
        return $this->rawData;
    }

    /**
     * @return string
     */
    public function getSignedData(): string
    {
        return $this->signedData;
    }
}

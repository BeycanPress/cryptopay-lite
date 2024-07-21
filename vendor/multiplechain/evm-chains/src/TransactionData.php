<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains;

class TransactionData
{
    /**
     * @var string|null
     */
    private ?string $from = null;

    /**
     * @var string|null
     */
    private ?string $to = null;

    /**
     * @var string|null
     */
    private ?string $value = null;

    /**
     * @var string|null
     */
    private ?string $data = null;

    /**
     * @var string|null
     */
    private ?string $gas = null;

    /**
     * @var int|null
     */
    private ?int $chainId = null;

    /**
     * @var string|null
     */
    private ?string $gasPrice = null;

    /**
     * @var string|null
     */
    private ?string $gasLimit = null;

    /**
     * @var string|null
     */
    private ?string $nonce = null;

    /**
     * @param array<string,mixed>|null $data
     */
    public function __construct(?array $data = null)
    {
        if (null === $data) {
            return;
        }

        if (
            !isset(
                $data['from'],
                $data['to'],
                $data['value'],
                $data['data'],
                $data['gasPrice'],
                $data['nonce'],
                $data['chainId']
            )
        ) {
            throw new \InvalidArgumentException(
                'TransactionData must have from, to, value, data, gasPrice, nonce and chainId'
            );
        }

        if (
            !is_string($data['from']) ||
            !is_string($data['to']) ||
            !is_string($data['value']) ||
            !is_string($data['data']) ||
            !is_string($data['gasPrice']) ||
            !is_string($data['nonce'])
        ) {
            throw new \InvalidArgumentException(
                'TransactionData from, to, value, data, gasPrice and nonce must be strings'
            );
        }

        if (!is_int($data['chainId'])) {
            throw new \InvalidArgumentException('TransactionData chainId must be an integer');
        }

        if (isset($data['gas'])) {
            if (!is_string($data['gas'])) {
                throw new \InvalidArgumentException('TransactionData gas must be a string');
            }
            $this->setGas($data['gas']);
        }

        $this->setFrom($data['from'])
            ->setTo($data['to'])
            ->setValue($data['value'])
            ->setData($data['data'])
            ->setGasPrice($data['gasPrice'])
            ->setNonce($data['nonce'])
            ->setChainId($data['chainId']);
    }

    /**
     * @param string $from
     * @return TransactionData
     */
    public function setFrom(string $from): TransactionData
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param string $to
     * @return TransactionData
     */
    public function setTo(string $to): TransactionData
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param string $value
     * @return TransactionData
     */
    public function setValue(string $value): TransactionData
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $data
     * @return TransactionData
     */
    public function setData(string $data): TransactionData
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $gas
     * @return TransactionData
     */
    public function setGas(string $gas): TransactionData
    {
        $this->gas = $gas;
        return $this;
    }

    /**
     * @param string $gasPrice
     * @return TransactionData
     */
    public function setGasPrice(string $gasPrice): TransactionData
    {
        $this->gasPrice = $gasPrice;
        return $this;
    }

    /**
     * @param string $gasLimit
     * @return TransactionData
     */
    public function setGasLimit(string $gasLimit): TransactionData
    {
        $this->gasLimit = $gasLimit;
        return $this;
    }

    /**
     * @param string $nonce
     * @return TransactionData
     */
    public function setNonce(string $nonce): TransactionData
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @param int $chainId
     * @return TransactionData
     */
    public function setChainId(int $chainId): TransactionData
    {
        $this->chainId = $chainId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @return string|null
     */

    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getGas(): ?string
    {
        return $this->gas;
    }

    /**
     * @return string|null
     */
    public function getGasPrice(): ?string
    {
        return $this->gasPrice;
    }

    /**
     * @return string|null
     */
    public function getGasLimit(): ?string
    {
        return $this->gasLimit;
    }

    /**
     * @return string|null
     */
    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    /**
     * @return int|null
     */
    public function getChainId(): ?int
    {
        return $this->chainId;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
            'value' => $this->getValue(),
            'data' => $this->getData(),
            'gas' => $this->getGas(),
            'gasPrice' => $this->getGasPrice(),
            'nonce' => $this->getNonce(),
            'chainId' => $this->getChainId(),
        ]);
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray()) ?: '';
    }
}

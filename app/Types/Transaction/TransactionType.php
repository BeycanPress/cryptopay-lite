<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Transaction;

use BeycanPress\CryptoPayLite\Types\AbstractType;
use BeycanPress\CryptoPayLite\Types\Order\OrderType;
use BeycanPress\CryptoPayLite\Types\Network\NetworkType;
use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;

/**
 * Transaction type
 * @since 2.1.0
 */
class TransactionType extends AbstractType
{
    /**
     * @var int|null
     */
    private ?int $id;

    /**
     * @var string
     */
    private string $hash;

    /**
     * @var OrderType
     */
    private OrderType $order;

    /**
     * @var int|null
     */
    private ?int $orderId;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var NetworkType
     */
    private NetworkType $network;

    /**
     * @var ParamsType
     */
    private ParamsType $params;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var bool
     */
    private bool $testnet = false;

    /**
     * @var string|null
     */
    private ?string $reminderEmail;

    /**
     * @var AddressesType
     */
    private AddressesType $addresses;

    /**
     * @var Status
     */
    private Status $status;

    /**
     * @var \DateTime
     */
    private \DateTime $updatedAt;

    /**
     * @var \DateTime
     */
    private \DateTime $createdAt;

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $hash
     * @return self
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param OrderType $order
     * @return self
     */
    public function setOrder(OrderType $order): self
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param int|null $orderId
     * @return self
     */
    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param NetworkType $network
     * @return self
     */
    public function setNetwork(NetworkType $network): self
    {
        $this->network = $network;
        return $this;
    }

    /**
     * @param ParamsType $params
     * @return self
     */
    public function setParams(ParamsType $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param bool $testnet
     * @return self
     */
    public function setTestnet(bool $testnet): self
    {
        $this->testnet = $testnet;
        return $this;
    }

    /**
     * @param string|null $reminderEmail
     * @return self
     */
    public function setReminderEmail(?string $reminderEmail): self
    {
        $this->reminderEmail = $reminderEmail;
        return $this;
    }

    /**
     * @param AddressesType $addresses
     * @return self
     */
    public function setAddresses(AddressesType $addresses): self
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * @param Status $status
     * @return self
     */
    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param \DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return OrderType
     */
    public function getOrder(): OrderType
    {
        return $this->order;
    }

    /**
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return NetworkType
     */
    public function getNetwork(): NetworkType
    {
        return $this->network;
    }

    /**
     * @return ParamsType
     */
    public function getParams(): ParamsType
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function getTestnet(): bool
    {
        return $this->testnet;
    }

    /**
     * @return string|null
     */
    public function getReminderEmail(): ?string
    {
        return $this->reminderEmail;
    }

    /**
     * @return AddressesType
     */
    public function getAddresses(): AddressesType
    {
        return $this->addresses;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return array<string,mixed>
     */
    public function forDebug(): array
    {
        return array_filter([
            'id' => $this->getId(),
            'hash' => $this->getHash(),
            'code' => $this->getCode(),
            'userId' => $this->getUserId(),
            'orderId' => $this->getOrderId(),
            'testnet' => $this->getTestnet(),
            'order' => $this->getOrder()->forDebug(),
            'params' => $this->getParams()->toArray(),
            'status' => $this->getStatus()->getValue(),
            'network' => $this->getNetwork()->forDebug(),
            'reminderEmail' => $this->getReminderEmail(),
            'addresses' => $this->getAddresses()->toArray(),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }
}

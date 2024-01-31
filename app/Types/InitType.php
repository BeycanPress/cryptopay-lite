<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types;

// Types
use BeycanPress\CryptoPayLite\Types\Order\OrderType;

/**
 * CryptoPay JS init parameter type
 * @since 2.1.0
 */
class InitType extends AbstractType
{
    /**
     * @param float $order
     */
    private OrderType $order;

    /**
     * @param string $receiver
     */
    private string $receiver;

    /**
     * @param string $providerConfig
     */
    private object $providerConfig;

    /**
     * @param string $qrCodeWaitingTime
     */
    private int $qrCodeWaitingTime;

    /**
     * @param string $blockConfirmationCount
     */
    private int $blockConfirmationCount;

    /**
     * @param OrderType $order
     * @param string $receiver
     * @param object $providerConfig
     * @param int $qrCodeWaitingTime
     * @param int $blockConfirmationCount
     */
    public function __construct(
        OrderType $order,
        string $receiver,
        object $providerConfig,
        int $qrCodeWaitingTime,
        int $blockConfirmationCount
    ) {
        $this->setOrder($order);
        $this->setReceiver($receiver);
        $this->setProviderConfig($providerConfig);
        $this->setQrCodeWaitingTime($qrCodeWaitingTime);
        $this->setBlockConfirmationCount($blockConfirmationCount);
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
     * @param string $receiver
     * @return self
     */
    public function setReceiver(string $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @param object $providerConfig
     * @return self
     */
    public function setProviderConfig(object $providerConfig): self
    {
        $this->providerConfig = $providerConfig;
        return $this;
    }

    /**
     * @param int $qrCodeWaitingTime
     * @return self
     */
    public function setQrCodeWaitingTime(int $qrCodeWaitingTime): self
    {
        $this->qrCodeWaitingTime = $qrCodeWaitingTime;
        return $this;
    }

    /**
     * @param int $blockConfirmationCount
     * @return self
     */
    public function setBlockConfirmationCount(int $blockConfirmationCount): self
    {
        $this->blockConfirmationCount = $blockConfirmationCount;
        return $this;
    }

    /**
     * @return OrderType
     */
    public function getOrder(): OrderType
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * @return object
     */
    public function getProviderConfig(): object
    {
        return $this->providerConfig;
    }

    /**
     * @return int
     */
    public function getQrCodeWaitingTime(): int
    {
        return $this->qrCodeWaitingTime;
    }

    /**
     * @return int
     */
    public function getBlockConfirmationCount(): int
    {
        return $this->blockConfirmationCount;
    }

    /**
     * @return array<string,mixed>
     */
    public function prepareForJsSide(): array
    {
        return $this->toArray(exclude:[
            'order' => ['discountRate']
        ]);
    }
}

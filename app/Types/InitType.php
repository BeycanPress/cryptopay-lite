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
     * @param string $blockConfirmationCount
     */
    private int $blockConfirmationCount;

    /**
     * @param OrderType $order
     * @param string $receiver
     * @param int $blockConfirmationCount
     */
    public function __construct(
        OrderType $order,
        string $receiver,
        int $blockConfirmationCount
    ) {
        $this->setOrder($order);
        $this->setReceiver($receiver);
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
            'order' => ['refunds', 'discountRate']
        ]);
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Transaction;

use BeycanPress\CryptoPayLite\Types\AbstractType;

/**
 * Transaction addresses type
 * @since 2.1.0
 */
class AddressesType extends AbstractType
{
    /**
     * @var string|null
     */
    private ?string $receiver = null;

    /**
     * @var string|null
     */
    private ?string $sender = null;

    /**
     * @param string|null $receiver
     * @param string|null $sender
     */
    public function __construct(?string $receiver = null, ?string $sender = null)
    {
        $this->setSender($sender);
        $this->setReceiver($receiver);
    }

    /**
     * @param string|null $receiver
     * @return self
     */
    public function setReceiver(?string $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @param string|null $sender
     * @return self
     */
    public function setSender(?string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    /**
     * @return string|null
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }
}

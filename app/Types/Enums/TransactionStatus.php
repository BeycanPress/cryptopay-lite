<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Enums;

enum TransactionStatus: string
{
    use EnumHelperMethods;

    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case FAILED = 'failed';

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}

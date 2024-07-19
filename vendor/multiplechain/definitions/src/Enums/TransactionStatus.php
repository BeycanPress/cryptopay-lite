<?php

declare(strict_types=1);

namespace MultipleChain\Enums;

enum TransactionStatus: string
{
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
}

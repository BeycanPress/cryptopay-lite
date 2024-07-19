<?php

declare(strict_types=1);

namespace MultipleChain\Enums;

enum AssetDirection: string
{
    case INCOMING = 'INCOMING';
    case OUTGOING = 'OUTGOING';
}

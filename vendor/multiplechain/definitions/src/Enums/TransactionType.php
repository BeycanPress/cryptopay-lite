<?php

declare(strict_types=1);

namespace MultipleChain\Enums;

enum TransactionType: string
{
    case GENERAL = 'GENERAL';
    case CONTRACT = 'CONTRACT';
    case COIN = 'COIN';
    case TOKEN = 'TOKEN';
    case NFT = 'NFT';
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Exceptions;

use Exception;

class NoActiveCurrencyException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'No active currency found.', int $code = 4000)
    {
        parent::__construct($message, $code);
    }
}

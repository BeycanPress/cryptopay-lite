<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Exceptions;

use Exception;

class NoActiveNetworkException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'No active network found.', int $code = 5000)
    {
        parent::__construct($message, $code);
    }
}

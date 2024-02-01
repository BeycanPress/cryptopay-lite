<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Exceptions;

use Exception;

class InitializeException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 3000)
    {
        parent::__construct($message, $code);
    }
}

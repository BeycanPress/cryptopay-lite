<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Exceptions;

use Exception;

class IntegrationRegistryException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 1000)
    {
        parent::__construct($message, $code);
    }
}

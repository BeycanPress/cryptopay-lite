<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Exceptions;

use Exception;

class ConverterException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 2000)
    {
        parent::__construct($message, $code);
    }
}

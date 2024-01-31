<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Enums;

trait EnumHelperMethods
{
    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}

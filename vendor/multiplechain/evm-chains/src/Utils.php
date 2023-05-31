<?php

namespace MultipleChain\EvmChains;

use Web3\Validators\AddressValidator;
use MultipleChain\Utils as AbstractUtils;

final class Utils extends AbstractUtils
{
    /**
     * Validate parameters
     *
     * @param string $from
     * @param string $to
     * @param float $amount
     * @param string|null $tokenAddress
     * @return void
     * @throws Exception
     */
    public static function validate(string $from, string $to, float $amount, ?string $tokenAddress = null) : void
    {
        if ($amount <= 0) {
            throw new \Exception("The amount cannot be zero or less than zero!", 20000);
        } 

        if (AddressValidator::validate($from) === false) {
            throw new \Exception('Invalid sender address!', 21000);
        }

        if (AddressValidator::validate($to) === false) {
            throw new \Exception('Invalid receiver address!', 22000);
        }

        if (!is_null($tokenAddress) && AddressValidator::validate($tokenAddress) === false) {
            throw new \Exception('Invalid token address!', 23000);
        }
    }
}
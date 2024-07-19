<?php

declare(strict_types=1);

namespace MultipleChain\Interfaces\Assets;

use MultipleChain\Utils\Number;

interface AssetInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSymbol(): string;

    /**
     * @param string $owner
     * @return Number
     */
    public function getBalance(string $owner): Number;
}

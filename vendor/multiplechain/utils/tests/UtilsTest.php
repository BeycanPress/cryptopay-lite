<?php

declare(strict_types=1);

namespace MultipleChain\Tests;

use MultipleChain\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    /**
     * @return void
     */
    public function testNumberToHex(): void
    {
        $this->assertEquals('0x8ac7230489e80000', Utils::numberToHex(10, 18));
    }

    /**
     * @return void
     */
    public function testHexToNumber(): void
    {
        $this->assertEquals(10, Utils::hexToNumber('0x8ac7230489e80000', 18));
    }

    /**
     * @return void
     */
    public function testBase58Encode(): void
    {
        $this->assertEquals('Uad4z3ti', Utils::base58Encode('7bWpTW'));
    }

    /**
     * @return void
     */
    public function testBase58Encode2(): void
    {
        $this->assertEquals('Uad4z3ti', Utils::base58Encode([55, 98, 87, 112, 84, 87]));
    }

    /**
     * @return void
     */
    public function testBase58Decode(): void
    {
        $this->assertEquals([55, 98, 87, 112, 84, 87], Utils::base58Decode('Uad4z3ti'));
    }

    /**
     * @return void
     */
    public function testBufferToString(): void
    {
        $this->assertEquals('example', Utils::bufferToString([101, 120, 97, 109, 112, 108, 101]));
    }

    /**
     * @return void
     */
    public function testStringToBuffer(): void
    {
        $this->assertEquals([101, 120, 97, 109, 112, 108, 101], Utils::stringToBuffer('example'));
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->assertEquals('0.000000065', Utils::toString('6.5e-8'));
    }
}

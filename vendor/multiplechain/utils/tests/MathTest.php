<?php

declare(strict_types=1);

namespace MultipleChain\Tests;

use MultipleChain\Utils\Math;
use PHPUnit\Framework\TestCase;

class MathTest extends TestCase
{
    /**
     * @return void
     */
    public function testAdd(): void
    {
        $this->assertEquals(0.3, Math::add(0.2, 0.1));
    }

    /**
     * @return void
     */
    public function testSub(): void
    {
        $this->assertEquals(0.2, Math::sub(0.3, 0.1));
    }

    /**
     * @return void
     */
    public function testMul(): void
    {
        $this->assertEquals(200, Math::mul(10, 20));
    }

    /**
     * @return void
     */
    public function testDiv(): void
    {
        $this->assertEquals(0.5, Math::div(10, 20));
    }

    /**
     * @return void
     */
    public function testPow(): void
    {
        $this->assertEquals(8, Math::pow(2, 3));
    }

    /**
     * @return void
     */
    public function testSqrt(): void
    {
        $this->assertEquals(3, Math::sqrt(9));
    }

    /**
     * @return void
     */
    public function testAbs(): void
    {
        $this->assertEquals(10, Math::abs(-10));
    }

    /**
     * @return void
     */
    public function testCeil(): void
    {
        $this->assertEquals(1, Math::ceil(0.1));
    }

    /**
     * @return void
     */
    public function testFloor(): void
    {
        $this->assertEquals(0, Math::floor(0.9));
    }

    /**
     * @return void
     */
    public function testRound(): void
    {
        $this->assertEquals(1, Math::round(0.5));
    }
}

<?php

declare(strict_types=1);

namespace MultipleChain\Tests;

use MultipleChain\Utils\Number;
use phpseclib\Math\BigInteger;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    /**
     * @var Number
     */
    private Number $number0;

    /**
     * @var Number
     */
    private Number $number1;

    /**
     * @var Number
     */
    private Number $number2;

    /**
     * @var Number
     */
    private Number $number3;

    /**
     * @var Number
     */
    private Number $number4;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->number0 = new Number(0.31190793872462);
        $this->number1 = new Number('0.311907938724615903');
        $this->number2 = new Number('0x04541e9e224e16df');
        $this->number3 = new Number(new BigInteger('311907938724615903'));
        $this->number4 = new Number(6.5E-5, 9);
    }

    /**
     * @return void
     */
    public function testToHex(): void
    {
        $this->assertEquals('0x04541e9e224e26e0', $this->number0->toHex());
        $this->assertEquals('0x04541e9e224e16df', $this->number1->toHex());
        $this->assertEquals('0x04541e9e224e16df', $this->number2->toHex());
        $this->assertEquals('0x04541e9e224e16df', $this->number3->toHex());
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $this->assertEquals('0.31190793872462', $this->number0->toString());
        $this->assertEquals('0.311907938724615903', $this->number1->toString());
        $this->assertEquals('0.311907938724615903', $this->number2->toString());
        $this->assertEquals('0.311907938724615903', $this->number3->toString());
    }

    /**
     * @return void
     */
    public function testToFloat(): void
    {
        $this->assertEquals(0.31190793872462, $this->number0->toFloat());
        $this->assertEquals(0.311907938724615903, $this->number1->toFloat());
        $this->assertEquals(0.311907938724615903, $this->number2->toFloat());
        $this->assertEquals(0.311907938724615903, $this->number3->toFloat());
    }

    /**
     * @return void
     */
    public function testToBigNumber(): void
    {
        $this->assertEquals('311907938724620000', $this->number0->toBigNumber()->toString());
        $this->assertEquals('311907938724615903', $this->number1->toBigNumber()->toString());
        $this->assertEquals('311907938724615903', $this->number2->toBigNumber()->toString());
        $this->assertEquals('311907938724615903', $this->number3->toBigNumber()->toString());
    }

    /**
     * @return void
     */
    public function testToReadableString(): void
    {
        $this->assertEquals('0.31190793872462', $this->number0->toReadableString(), 'number0');
        $this->assertEquals('0.311907938724615903', $this->number1->toReadableString(), 'number1');
        $this->assertEquals('0.311907938724615903', $this->number2->toReadableString(), 'number2');
        $this->assertEquals('0.311907938724615903', $this->number3->toReadableString(), 'number3');
    }

    /**
     * @return void
     */
    public function testEquals(): void
    {
        $this->assertTrue($this->number1->equals($this->number2));
        $this->assertTrue($this->number1->equals($this->number3));
        $this->assertEquals(0.000065, $this->number4->toFloat());
    }

    /**
     * @return void
     */
    public function testCalc(): void
    {
        $this->assertEquals(
            '0.321907938724615903',
            $this->number1->add(new Number(0.01))->toString()
        );

        $this->assertEquals(
            '0.301907938724615903',
            $this->number1->sub(new Number(0.01))->toString()
        );

        $this->assertEquals(
            '0.623815877449231806',
            $this->number1->mul(new Number(2))->toString()
        );

        $this->assertEquals(
            '0.155953969362307951',
            $this->number1->div(new Number(2))->toString()
        );

        $this->assertEquals(
            '0.077976984681153975',
            $this->number1->div(new Number(4))->toString()
        );

        $this->assertEquals(
            $this->number1->toString(),
            (new Number('0.1559539693623079515', 19))->mul(new Number(2))->toString()
        );
    }

    /**
     * @return void
     */
    public function testCalc2(): void
    {
        $number1 = new Number(1123.36);
        $number2 = new Number(1);

        $this->assertEquals(
            '1124.36',
            $number1->add($number2)->toString()
        );
    }

    /**
     * @return void
     */
    public function testCalc3(): void
    {
        $number1 = new Number('0.311907938724615903');
        $this->assertEquals(
            '0.623815877449231806',
            $number1->mul(new Number(2))->toString()
        );
    }
}

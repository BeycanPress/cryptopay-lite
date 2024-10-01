<?php

declare(strict_types=1);

namespace MultipleChain\Utils;

use MultipleChain\Utils;
use phpseclib\Math\BigInteger as BigNumber;

class Number
{
    /**
     * @var int
     */
    private int $decimals;

    /**
     * @var string
     */
    private string $hexNumber;

    /**
     * @var string
     */
    private string $stringNumber;

    /**
     * @var float
     */
    private float $floatNumber;

    /**
     * @var BigNumber
     */
    private BigNumber $bigNumber;

    /**
     * @param string|float|BigNumber $number
     * @param integer $decimals
     */
    public function __construct(string|float|BigNumber $number, int $decimals = 18)
    {
        if (is_numeric($number) && $number < 0) {
            throw new \InvalidArgumentException('Number must be positive!');
        }

        if (is_string($number) && Utils::isHex($number)) {
            $number = $this->toStringInternal($number, $decimals);
        }

        if ($this->isScientificNotation($number)) {
            $number = Utils::toString($number, $decimals);
        }

        if (!($number instanceof BigNumber)) {
            $this->bigNumber = $this->toBigNumberInternal($number, $decimals);
        } else {
            $this->bigNumber = $number;
        }

        $this->decimals = $decimals;
        $hex = $this->bigNumber->toHex(true);
        $this->hexNumber = '0x' . ('' !== $hex ? $hex : '0');
        $this->floatNumber = Utils::hexToNumber($this->hexNumber, $decimals);
        $this->stringNumber = $this->toStringInternal($this->hexNumber, $decimals);
    }

    /**
     * @param mixed $number
     * @return bool
     */
    private function isScientificNotation(mixed $number): bool
    {
        return is_numeric($number) && preg_match('/^[+-]?\d+(\.\d+)?[eE][+-]?\d+$/', (string) $number);
    }

    /**
     * @return string
     */
    public function toHex(): string
    {
        return $this->hexNumber;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->stringNumber;
    }

    /**
     * @return float
     */
    public function toFloat(): float
    {
        return $this->floatNumber;
    }

    /**
     * @return BigNumber
     */
    public function toBigNumber(): BigNumber
    {
        return $this->bigNumber;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @param Number $number
     * @return bool
     */
    public function equals(Number $number): bool
    {
        return $this->bigNumber->equals($number->toBigNumber());
    }

    /**
     * @param Number $number
     * @return Number
     */
    public function add(Number $number): Number
    {
        return new Number(bcadd($this->toString(), $number->toString(), $this->decimals));
    }

    /**
     * @param Number $number
     * @return Number
     */
    public function sub(Number $number): Number
    {
        return new Number(bcsub($this->toString(), $number->toString(), $this->decimals));
    }

    /**
     * @param Number $number
     * @return Number
     */
    public function mul(Number $number): Number
    {
        return new Number(bcmul($this->toString(), $number->toString(), $this->decimals));
    }

    /**
     * @param Number $number
     * @return Number
     */
    public function div(Number $number): Number
    {
        return new Number(bcdiv($this->toString(), $number->toString(), $this->decimals));
    }

    /**
     * @param string|float|BigNumber $number
     * @return Number
     */
    public static function from(string|float|BigNumber $number): Number
    {
        return new Number($number);
    }

    /**
     * @return string
     */
    public function toReadableString(): string
    {
        if (true == $this->isSmall($this->floatNumber)) {
            return $this->stringNumber;
        } else {
            $length = $this->getDecimalPlaces($this->stringNumber);
            return number_format($this->floatNumber, $length, '.', ',');
        }
    }

    /**
     * @param string $number
     * @return int
     */
    private function getDecimalPlaces(string $number): int
    {
        $comps = explode('.', $number);
        return strlen($comps[1] ?: '0');
    }

    /**
     * @param string $value
     * @param integer $decimals
     * @return string
     */
    private function toStringInternal(string $value, int $decimals): string
    {
        $length = bcpow('10', strval($decimals));
        $newValue = $this->hexToDec($value);
        return rtrim(rtrim(bcdiv($newValue, $length, $decimals), '0'), '.');
    }

    /**
     * @param string $hex
     * @return string
     */
    private function hexToDec(string $hex): string
    {
        $dec = '0';
        $hex = strtolower(preg_replace('/[^0-9a-f]/', '', $hex));
        $len = strlen($hex);
        for ($i = 0; $i < $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i])), bcpow('16', strval($len - 1 - $i))));
        }
        return $dec;
    }

    /**
     * @param string|float|BigNumber $number
     * @param int $decimals
     * @return BigNumber
     */
    private function toBigNumberInternal(string|float|BigNumber $number, int $decimals): BigNumber
    {
        $bn = $this->toBigNumberComplex($number);
        $length = '1' . str_repeat('0', $decimals);

        $bnt = new BigNumber($length);

        if (is_array($bn)) {
            /**
             * @var BigNumber $whole
             * @var BigNumber $fraction
             * @var int $fractionLength
             * @var bool|BigNumber $negative1
             */
            list($whole, $fraction, $fractionLength, $negative1) = $bn;

            if ($fractionLength > strlen($length)) {
                throw new \InvalidArgumentException('toBigNumber fraction part is out of limit.');
            }

            $whole = $whole->multiply($bnt);

            // @phpstan-ignore-next-line
            switch (MATH_BIGINTEGER_MODE) {
                case $whole::MODE_GMP:
                case $whole::MODE_BCMATH:
                    $powerBase = bcpow('10', (string) $fractionLength, 0);
                    break;
                default:
                    $powerBase = pow(10, (int) $fractionLength);
                    break;
            }

            // @phpstan-ignore-next-line
            $base = new BigNumber($powerBase);
            $fraction = $fraction->multiply($bnt)->divide($base)[0];

            if (false !== $negative1 && $negative1 instanceof BigNumber) {
                return $whole->add($fraction)->multiply($negative1);
            }

            return $whole->add($fraction);
        }

        return $bn->multiply($bnt);
    }

    /**
     * @param mixed $number
     * @return array<mixed>|BigNumber
     */
    private function toBigNumberComplex(mixed $number): array|BigNumber
    {
        if ($number instanceof BigNumber) {
            $bn = $number;
        } elseif (is_int($number)) {
            $bn = new BigNumber($number);
        } elseif (is_numeric($number)) {
            $number = (string) $number;

            if ($this->isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }

            if (strpos($number, '.') > 0) {
                $comps = explode('.', $number);

                if (count($comps) > 2) {
                    throw new \InvalidArgumentException('toBn number must be a valid number.');
                }

                $whole = $comps[0];
                $fraction = $comps[1];

                return [
                    new BigNumber($whole),
                    new BigNumber($fraction),
                    strlen($comps[1]),
                    isset($negative1) ? $negative1 : false
                ];
            } else {
                $bn = new BigNumber($number);
            }

            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } elseif (is_string($number)) {
            $number = mb_strtolower($number);

            if ($this->isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }

            if ($this->isZeroPrefixed($number) || 1 === preg_match('/[a-f]+/', $number)) {
                $number = $this->stripZero($number);
                $bn = new BigNumber($number, 16);
            } elseif (empty($number)) {
                $bn = new BigNumber(0);
            } else {
                throw new \InvalidArgumentException('toBn number must be valid hex string.');
            }

            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } else {
            throw new \InvalidArgumentException('toBn number must be BigNumber, string or int.');
        }
        return $bn;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isNegative(string $value): bool
    {
        return (0 === strpos($value, '-'));
    }

    /**
     * @param float $value
     * @return bool
     */
    private function isSmall(float $value): bool
    {
        return ($value < 1);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    private function isZeroPrefixed(mixed $value): bool
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('The value to isZeroPrefixed function must be string.');
        }
        return (0 === strpos($value, '0x'));
    }

    /**
     * @param string $value
     * @return string
     */
    private function stripZero(string $value): string
    {
        if ($this->isZeroPrefixed($value)) {
            $count = 1;
            return str_replace('0x', '', $value, $count);
        }
        return $value;
    }
}

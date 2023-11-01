<?php

namespace MultipleChain;

use InvalidArgumentException;
use phpseclib\Math\BigInteger as BigNumber;

class Utils
{
    /**
     * @param string $value
     * @return string
     */
    public static function hex($value) : string
    {
        return '0x' . dechex($value);
    }

    /**
     * Converts the regular number into a format that blockchain networks will understand
     * Decimal number to hexadecimal number
     * @param float|int $amount
     * @param int $decimals
     * @return string
     */
    public static function toHex(float $amount, int $decimals) : string
    {
        $value = self::toBigNumber($amount, $decimals);
        if (is_numeric($value)) {
            // turn to hex number
            $bn = self::toBn($value);
            $hex = $bn->toHex(true);
            $hex = preg_replace('/^0+(?!$)/', '', $hex);
        } elseif (is_string($value)) {
            $value = self::stripZero($value);
            $hex = implode('', unpack('H*', $value));
        } elseif ($value instanceof BigNumber) {
            $hex = $value->toHex(true);
            $hex = preg_replace('/^0+(?!$)/', '', $hex);
        } else {
            throw new InvalidArgumentException('The value to toHex function is not support.');
        }

        return '0x' . $hex;
    }

    /**
     * Converts a hexadecimal number to a normal number
     * Hecadecimal number to decimal number
     * @param string|int|float $amount
     * @param int $decimals
     * @return float
     */
    public static function toDec(string $amount, int $decimals) : float
    {
        $bn = self::toBn($amount);
        $length = '1' . str_repeat('0', $decimals);
        $bnt = new BigNumber($length);

        $amount = $bn->divide($bnt)[1]->toString();
        $result = (float) bcdiv($amount, $length, $decimals);
        $result += (float) $bn->divide($bnt)[0]->toString();

        return $result;
    }

    /**
     * @param BigNumber|string|int $number
     * @param int $decimals
     * @return BigNumber
     */
    public static function toBigNumber($number, int $decimals) : BigNumber
    {
        $bn = self::toBn($number);
        $length = '1' . str_repeat('0', $decimals);

        $bnt = new BigNumber($length);

        if (is_array($bn)) {
            list($whole, $fraction, $fractionLength, $negative1) = $bn;

            if ($fractionLength > strlen($length)) {
                throw new InvalidArgumentException('toBigNumber fraction part is out of limit.');
            }
            $whole = $whole->multiply($bnt);

            switch (MATH_BIGINTEGER_MODE) {
                case $whole::MODE_GMP:
                    static $two;
                    $powerBase = gmp_pow(gmp_init(10), (int) $fractionLength);
                    break;
                case $whole::MODE_BCMATH:
                    $powerBase = bcpow('10', (string) $fractionLength, 0);
                    break;
                default:
                    $powerBase = pow(10, (int) $fractionLength);
                    break;
            }
            $base = new BigNumber($powerBase);
            $fraction = $fraction->multiply($bnt)->divide($base)[0];

            if ($negative1 !== false) {
                return $whole->add($fraction)->multiply($negative1);
            }
            return $whole->add($fraction);
        }

        return $bn->multiply($bnt);
    }

    /**
     * @param BigNumber|string|int $number
     * @return BigNumber|array
     */
    public static function toBn($number) 
    {
        if ($number instanceof BigNumber){
            $bn = $number;
        } elseif (is_int($number)) {
            $bn = new BigNumber($number);
        } elseif (is_numeric($number)) {
            $number = (string) $number;

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }
            if (strpos($number, '.') > 0) {
                $comps = explode('.', $number);

                if (count($comps) > 2) {
                    throw new InvalidArgumentException('toBn number must be a valid number.');
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

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigNumber(-1);
            }
            if (self::isZeroPrefixed($number) || preg_match('/[a-f]+/', $number) === 1) {
                $number = self::stripZero($number);
                $bn = new BigNumber($number, 16);
            } elseif (empty($number)) {
                $bn = new BigNumber(0);
            } else {
                throw new InvalidArgumentException('toBn number must be valid hex string.');
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } else {
            throw new InvalidArgumentException('toBn number must be BigNumber, string or int.');
        }
        return $bn;
    }

    /**
     * @param string $amount
     * @param integer $decimals
     * @return string
     */
    public static function toString(string $amount, int $decimals) : string
    {
        $pos1 = stripos((string) $amount, 'E-');
        $pos2 = stripos((string) $amount, 'E+');
    
        if ($pos1 !== false) {
            $amount = number_format($amount, $decimals, '.', ',');
        }

        if ($pos2 !== false) {
            $amount = number_format($amount, $decimals, '.', '');
        }
    
        return $amount > 1 ? $amount : rtrim($amount, '0');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isNegative(string $value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isNegative function must be string.');
        }
        return (strpos($value, '-') === 0);
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isZeroPrefixed(string $value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isZeroPrefixed function must be string.');
        }
        return (strpos($value, '0x') === 0);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function stripZero(string $value)
    {
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            return str_replace('0x', '', $value, $count);
        }
        return $value;
    }
}
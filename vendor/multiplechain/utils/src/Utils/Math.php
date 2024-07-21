<?php

declare(strict_types=1);

namespace MultipleChain\Utils;

class Math
{
    /**
     * @param float $a
     * @param float $b
     * @param integer $decimals
     * @return float
     */
    public static function add(float $a, float $b, int $decimals = 18): float
    {
        return self::format(bcadd((string)$a, (string)$b, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param float $b
     * @param integer $decimals
     * @return float
     */
    public static function sub(float $a, float $b, int $decimals = 18): float
    {
        return self::format(bcsub((string)$a, (string)$b, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param float $b
     * @param integer $decimals
     * @return float
     */
    public static function mul(float $a, float $b, int $decimals = 18): float
    {
        return self::format(bcmul((string)$a, (string)$b, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param float $b
     * @param integer $decimals
     * @return float
     */
    public static function div(float $a, float $b, int $decimals = 18): float
    {
        return self::format(bcdiv((string)$a, (string)$b, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param float $b
     * @param integer $decimals
     * @return float
     */
    public static function pow(float $a, float $b, int $decimals = 18): float
    {
        return self::format(bcpow((string)$a, (string)$b, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param integer $decimals
     * @return float
     */
    public static function sqrt(float $a, int $decimals = 18): float
    {
        return self::format(bcsqrt((string)$a, $decimals), $decimals);
    }

    /**
     * @param float $a
     * @param integer $decimals
     * @return float
     */
    public static function abs(float $a, int $decimals = 18): float
    {
        return self::format(bccomp((string)$a, '0') < 0 ? bcmul((string)$a, '-1', $decimals) : (string)$a, $decimals);
    }

    /**
     * @param float $a
     * @param integer $decimals
     * @return float
     */
    public static function ceil(float $a, int $decimals = 18): float
    {
        return self::format(strval(ceil($a)), $decimals);
    }

    /**
     * @param float $a
     * @param integer $decimals
     * @return float
     */
    public static function floor(float $a, int $decimals = 18): float
    {
        return self::format(strval(floor($a)), $decimals);
    }

    /**
     * @param float $a
     * @param integer $decimals
     * @return float
     */
    public static function round(float $a, int $decimals = 18): float
    {
        return self::format(strval(round($a)), $decimals);
    }

    /**
     * @param string $value
     * @param integer $decimals
     * @return float
     */
    private static function format(string $value, int $decimals): float
    {
        return (float)number_format((float)$value, $decimals, '.', '');
    }
}

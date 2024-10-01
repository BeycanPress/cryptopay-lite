<?php

declare(strict_types=1);

namespace MultipleChain;

class Utils
{
    private const BASE58_ALPHABET = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isHex(mixed $value): bool
    {
        return (is_string($value) && 1 === preg_match('/^(0x)[a-f0-9]*$/', $value));
    }

    /**
     * @param int|string $value
     * @return string
     */
    public static function toHex(int|string $value): string
    {
        return '0x' . dechex(intval($value));
    }

    /**
     * @param float $value
     * @param integer $decimals
     * @return string
     */
    public static function numberToHex(float $value, int $decimals): string
    {
        $length = bcpow('10', strval($decimals));
        $newValue = bcmul(strval($value), $length, 0);

        $hex = '';
        while (bccomp($newValue, '0') > 0) {
            $remainder = bcmod($newValue, '16');
            $hex = dechex(intval($remainder)) . $hex;
            $newValue = bcdiv($newValue, '16', 0);
        }

        return '0x' . ('' === $hex ? '0' : $hex);
    }

    /**
     * @param string $value
     * @param integer $decimals
     * @return float
     */
    public static function hexToNumber(string $value, int $decimals): float
    {
        $length = bcpow('10', strval($decimals));

        $decimal = '0';
        $value = ltrim($value, '0x');
        $len = strlen($value);
        for ($i = 0; $i < $len; $i++) {
            $decimal = bcadd($decimal, bcmul(strval(hexdec($value[$i])), bcpow('16', strval($len - 1 - $i))));
        }

        $result = bcdiv($decimal, $length, $decimals);
        return floatval($result);
    }

    /**
     * @param array<int>|string $input
     * @return string
     */
    public static function base58Encode(array|string $input): string
    {
        if (is_string($input)) {
            $unpack = unpack('C*', $input);
            $input = array_values($unpack ? $unpack : []);
        }

        $base58Array = [];
        $hex = bin2hex(implode(array_map('chr', $input)));

        $value = '0';
        $hexLength = strlen($hex);
        for ($i = 0; $i < $hexLength; $i++) {
            $value = bcadd(bcmul($value, '16'), base_convert($hex[$i], 16, 10));
        }

        while (bccomp($value, '0') > 0) {
            $remainder = bcmod($value, '58');
            $value = bcdiv($value, '58', 0);
            $base58Array[] = self::BASE58_ALPHABET[intval($remainder)];
        }

        foreach ($input as $byte) {
            if (0 !== $byte) {
                break;
            }
            $base58Array[] = self::BASE58_ALPHABET[0];
        }

        return implode('', array_reverse($base58Array));
    }

    /**
     * @param string $input
     * @return array<int>
     */
    public static function base58Decode(string $input): array
    {
        $value = '0';
        for ($i = 0; $i < strlen($input); $i++) {
            $value = bcadd(bcmul($value, '58'), strval(strpos(self::BASE58_ALPHABET, $input[$i])));
        }

        // Decimal to hexadecimal conversion
        $hex = '';
        while (bccomp($value, '0') > 0) {
            $remainder = bcmod($value, '16');
            $hex = dechex(intval($remainder)) . $hex;
            $value = bcdiv($value, '16', 0);
        }

        if (0 != strlen($hex) % 2) {
            $hex = '0' . $hex;
        }

        $unpack = unpack('C*', hex2bin($hex) ?: '');

        return array_values($unpack ?: []);
    }

    /**
     * @param array<int> $buffer
     * @return string
     */
    public static function bufferToString(array $buffer): string
    {
        return implode(array_map('chr', $buffer));
    }

    /**
     * @param string $string
     * @return array<int>
     */
    public static function stringToBuffer(string $string): array
    {
        $unpack = unpack('C*', $string);
        return array_values($unpack ? $unpack : []);
    }

    /**
     * @param string|float $amount
     * @param integer $decimals
     * @return string
     */
    public static function toString(string|float $amount, int $decimals = 18): string
    {
        $pos1 = stripos((string) $amount, 'E-');
        $pos2 = stripos((string) $amount, 'E+');

        if (false !== $pos1) {
            $amount = number_format(floatval($amount), $decimals, '.', ',');
        }

        if (false !== $pos2) {
            $amount = number_format(floatval($amount), $decimals, '.', '');
        }

        return strval(floatval($amount) > 1 ? $amount : rtrim(strval($amount), '0'));
    }
}

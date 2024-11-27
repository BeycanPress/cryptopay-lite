<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

// @phpcs:disable PSR1.Files.SideEffects

if (!function_exists('WP_Filesystem')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

WP_Filesystem();

trait General
{
    /**
     * @return string
     */
    public static function getCurrentUrl(): string
    {
        $siteURL = explode('/', get_site_url());
        $requestURL = explode('/', esc_url_raw($_SERVER['REQUEST_URI']));
        $currentURL = array_unique(array_merge($siteURL, $requestURL));
        return implode('/', $currentURL);
    }

    /**
     * @param string $datetime
     * @return \DateTime
     */
    public static function getTime(string $datetime = 'now'): \DateTime
    {
        return new \DateTime($datetime, new \DateTimeZone(wp_timezone_string()));
    }

    /**
     * @param string $datetime
     * @return \DateTime
     */
    public static function getUTCTime(string $datetime = 'now'): \DateTime
    {
        return new \DateTime($datetime, new \DateTimeZone('UTC'));
    }

    /**
     * @param string $date
     * @return string
     */
    public static function dateToTimeAgo(string $date): string
    {
        return human_time_diff(strtotime(wp_date('Y-m-d H:i:s')), strtotime($date)) . esc_html__(' ago');
    }

    /**
     * @param float|int $number
     * @param int $decimals
     * @return float
     */
    public static function toFixed(float|int $number, int $decimals): float
    {
        return floatval(number_format($number, $decimals, '.', ""));
    }

    /**
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    public static function numberFormat(float|int $number, int $decimals = 2): string
    {
        return preg_replace('/(\.0*|0+)$/', '', number_format($number, $decimals, '.', ','));
    }

    /**
     * @param float|int $amount
     * @param integer $decimals
     * @return string
     */
    public static function toString(float|int $amount, int $decimals): string
    {
        $pos1 = stripos((string) $amount, 'E-');
        $pos2 = stripos((string) $amount, 'E+');

        if (false !== $pos1) {
            $amount = number_format($amount, $decimals, '.', ',');
        }

        if (false !== $pos2) {
            $amount = number_format($amount, $decimals, '.', '');
        }

        return strval($amount > 1 ? $amount : rtrim(strval($amount), '0'));
    }


    /**
     * @return string|null
     */
    public static function getIp(): ?string
    {
        $ip = null;
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = wp_unslash(esc_url_raw($_SERVER['REMOTE_ADDR']));
            $ip = rest_is_ip_address($ip);
            if (false === $ip) {
                $ip = null;
            }
        }

        return $ip;
    }

    /**
     * @param callable $function
     * @param string $file
     * @param int $time
     * @return object
     */
    public static function cache(callable $function, string $file, int $time = 600): object
    {
        global $wp_filesystem;

        if (file_exists($file) && time() - $time < filemtime($file)) {
            $content = $wp_filesystem->get_contents($file);
        } else {
            if (file_exists($file)) {
                wp_delete_file($file);
            }

            $content = $function();

            $wp_filesystem->put_contents($file, $content, FS_CHMOD_FILE);
        }

        return (object) compact('file', 'content');
    }

    /**
     * @param string $name
     * @return boolean
     */
    public static function deleteCache(string $name): bool
    {
        $path = self::getProp('pluginDir') . 'cache/';
        $file = $path . $name . '.html';
        if (file_exists($file)) {
            return wp_delete_file($file);
        } else {
            return false;
        }
    }

    /**
     * @param string|null $address
     * @return string|null
     */
    public static function parseDomain(?string $address): ?string
    {
        if (is_null($address)) {
            return $address;
        }

        $parseUrl = wp_parse_url(trim($address));
        if (isset($parseUrl['host'])) {
            $domain = trim($parseUrl['host']);
        } else {
            $domain = explode('/', $parseUrl['path'], 2);
            $domain = array_shift($domain);
        }

        return str_replace(['www.'], '', $domain);
    }

    /**
     * @param string $domain
     * @return boolean
     */
    public static function isValidDomain(string $domain): bool
    {
        if (!preg_match('/^[a-zA-Z0-9\-]+(\.[a-zA-Z]{2,})+$/', $domain)) {
            return false;
        }

        return true;
    }
}

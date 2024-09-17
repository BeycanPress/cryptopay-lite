<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

// @phpcs:disable PSR1.Files.SideEffects

if (!function_exists('WP_Filesystem')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

WP_Filesystem();

trait Debug
{
    /**
     * @param string $message
     * @param string $level
     * @param array<mixed>|\Throwable $context
     * @return void
     */
    public static function debug(string $message, string $level = 'INFO', array|\Throwable $context = []): void
    {
        global $wp_filesystem;

        if (self::getProp('debugging', false)) {
            $debugLevel = self::getProp('debugLevel', 'ALL');
            if ('ALL' !== $debugLevel && $debugLevel !== $level) {
                return;
            }

            if ($context instanceof \Throwable) {
                $e = $context;
                $context = [];
                $context['line'] = $e->getLine();
                $context['file'] = $e->getFile();
                $context['code'] = $e->getCode();
                $context['message'] = $e->getMessage();
            }

            $trace = debug_backtrace();
            $content = self::getTemplate('log', [
                'level' => strtoupper($level),
                'message' => $message,
                'file' => $trace[0]['file'] . '(' . $trace[0]['line'] . ')',
                'class' => $trace[1]['class'] ?? null,
                'function' => $trace[1]['function'] ?? null,
                'date' => self::getTime()->format('Y-m-d H:i:s'),
                'context' => print_r(array_merge(self::getProp('debugDefaultContext', []) ?? [], $context), true)
            ]);

            $file = self::getProp('pluginDir') . 'debug.log';

            if ($wp_filesystem->exists($file)) {
                $content = $wp_filesystem->get_contents($file) . $content;
                $wp_filesystem->put_contents($file, $content, FS_CHMOD_FILE);
            } else {
                $wp_filesystem->put_contents($file, $content, FS_CHMOD_FILE);
            }
        }
    }

    /**
     * @return ?string
     */
    public static function getLogFile(): ?string
    {
        global $wp_filesystem;
        $file = self::getProp('pluginDir') . 'debug.log';
        if (file_exists($file)) {
            return $wp_filesystem->get_contents($file);
        } else {
            return null;
        }
    }

    /**
     * @return void
     */
    public static function deleteLogFile(): void
    {
        if (file_exists(self::getProp('pluginDir') . 'debug.log')) {
            wp_delete_file(self::getProp('pluginDir') . 'debug.log');
        }
    }
}

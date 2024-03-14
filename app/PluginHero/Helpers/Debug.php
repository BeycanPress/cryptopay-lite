<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

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

            $fp = fopen($file, 'a+');
            fwrite($fp, $content);
            fclose($fp);
        }
    }

    /**
     * @return ?string
     */
    public static function getLogFile(): ?string
    {
        $file = self::getProp('pluginDir') . 'debug.log';
        if (file_exists($file)) {
            return file_get_contents($file);
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
            unlink(self::getProp('pluginDir') . 'debug.log');
        }
    }
}

<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

class Hook
{
    /**
     * @return string
     */
    public static function getPrefix(): string
    {
        return Helpers::getProp('pluginKey') . '_';
    }

    /**
     * @param string $name
     * @param mixed $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return void
     */
    public static function addAction(string $name, mixed $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        add_action(self::getPrefix() . $name, $callback, $priority, $acceptedArgs);
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function callAction(string $name, mixed ...$args): void
    {
        do_action(self::getPrefix() . $name, ...$args);
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function removeAction(string $name, mixed ...$args): void
    {
        remove_action(self::getPrefix() . $name, ...$args);
    }

    /**
     * @param string $name
     * @param mixed $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return mixed
     */
    public static function addFilter(string $name, mixed $callback, int $priority = 10, int $acceptedArgs = 1): mixed
    {
        return add_filter(self::getPrefix() . $name, $callback, $priority, $acceptedArgs);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public static function callFilter(string $name, mixed $value, mixed ...$args): mixed
    {
        return apply_filters(self::getPrefix() . $name, $value, ...$args);
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function removeFilter(string $name, mixed ...$args): void
    {
        remove_filter(self::getPrefix() . $name, ...$args);
    }
}

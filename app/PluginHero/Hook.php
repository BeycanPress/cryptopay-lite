<?php 

namespace BeycanPress\CryptoPayLite\PluginHero;

class Hook
{
    /**
     * @return string
     */
    public static function getPrefix() : string 
    {
        return Plugin::$instance->pluginKey . '_';
    }

    /**
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function callAction(string $name, ...$args) : void
    {
        do_action(self::getPrefix() . $name, ...$args);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public static function callFilter(string $name, $value = null, ...$args)
    {
        return apply_filters(self::getPrefix() . $name, $value, ...$args);
    }

    /**
     * @param string $name
     * @param \Closure $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return void
     */
    public static function addAction(string $name, \Closure $callback, int $priority = 10, int $acceptedArgs = 1) : void
    {
        add_action(self::getPrefix() . $name, $callback, $priority, $acceptedArgs);
    }

    /**
     * @param string $name
     * @param \Closure $callback
     * @param integer $priority
     * @param integer $acceptedArgs
     * @return mixed
     */
    public static function addFilter(string $name, \Closure $callback, int $priority = 10, int $acceptedArgs = 1)
    {
        return add_filter(self::getPrefix() . $name, $callback, $priority, $acceptedArgs);
    }
    
}
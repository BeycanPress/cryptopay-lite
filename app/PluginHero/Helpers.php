<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

/**
 * Contains the commonly used ones for this plugin
 */
class Helpers
{
    use Helpers\API;
    use Helpers\Data;
    use Helpers\Debug;
    use Helpers\Assets;
    use Helpers\General;
    use Helpers\Template;
    use Helpers\Feedback;
    use Helpers\Redirect;

    /**
     * @param string $pluginFile
     * @param mixed $closureOrMethodName
     * @return void
     */
    public static function registerActivation(string $pluginFile, mixed $closureOrMethodName): void
    {
        register_activation_hook($pluginFile, $closureOrMethodName);
    }

    /**
     * @param string $pluginFile
     * @param mixed $closureOrMethodName
     * @return void
     */
    public static function registerDeactivation(string $pluginFile, mixed $closureOrMethodName): void
    {
        register_deactivation_hook($pluginFile, $closureOrMethodName);
    }

    /**
     * @param string $pluginFile
     * @param mixed $closureOrMethodName
     * @return void
     */
    public static function registerUninstall(string $pluginFile, mixed $closureOrMethodName): void
    {
        register_uninstall_hook($pluginFile, $closureOrMethodName);
    }
}

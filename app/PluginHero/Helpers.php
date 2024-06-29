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

    /**
     * @return float
     */
    public static function getPHPVersion(): float
    {
        $version = explode('.', PHP_VERSION);
        return floatval($version[0] . '.' . $version[1]);
    }

    /**
     * @return int|null
     */
    public static function getIoncubeVersion(): ?int
    {
        if (function_exists('ioncube_loader_iversion')) {
            $version = ioncube_loader_iversion();
            $version = sprintf('%d', $version / 10000);
            return intval($version);
        }
        return null;
    }

    /**
     * @param array<mixed> $rules
     * @param string $pluginFile
     * @return bool
     */
    public static function createRequirementRules(array $rules, string $pluginFile): bool
    {
        $status = true;
        $pluginName = self::getPluginData($pluginFile)->Name;

        if (isset($rules['phpVersions'])) {
            $phpVersions = $rules['phpVersions'];
            if (is_array($phpVersions)) {
                $condition = !in_array(self::getPHPVersion(), $phpVersions);
            } else {
                $condition = version_compare(strval(self::getPHPVersion()), strval($phpVersions), '<');
            }

            if ($condition) {
                $status = false;
                add_action('admin_notices', function () use ($phpVersions, $pluginName): void {
                    $versions = is_array($phpVersions) ? implode(', ', $phpVersions) : $phpVersions . ' or higher';
                    // @phpcs:ignore
                    $message = $pluginName . ': Your current PHP version does not support ' . self::getPHPVersion() . '. This means errors may occur due to incompatibility or other reasons. So ' . $pluginName . ' is disabled please use one of the supported versions ' . $versions . '. You can ask your server service provider to update your PHP version.';
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }
        }

        if (isset($rules['ioncubeVersion'])) {
            $ionCubeVersion = self::getIoncubeVersion();
            $requiredIonCubeVersion = $rules['ioncubeVersion'];
            if (!is_int($requiredIonCubeVersion)) {
                throw new \Exception('ioncubeVersion must be an integer!');
            }
            if (!$ionCubeVersion || $ionCubeVersion < $requiredIonCubeVersion) {
                $status = false;
                // @phpcs:ignore
                add_action('admin_notices', function () use ($requiredIonCubeVersion, $ionCubeVersion, $pluginName): void {
                    $message = $pluginName . ": Is disabled because " . ('cli' == php_sapi_name() ? 'ionCube ' . $requiredIonCubeVersion : '<a href="http://www.ioncube.com">ionCube ' . $requiredIonCubeVersion . '</a>') . " PHP Loader is not installed! In order for " . $pluginName . " to work, you must have ionCube " . $requiredIonCubeVersion . " and above. This is a widely used PHP extension for running ionCube protected PHP code, website security and malware blocking. Please visit " . ('cli' == php_sapi_name() ? 'ioncube.com/loaders.php' : '<a href="https://www.ioncube.com/loaders.php">ioncube.com/loaders.php</a>') . " for install assistance or you can ask your server service provider to install ionCube " . $requiredIonCubeVersion . " or above. Your current installed IonCube version is " . ($ionCubeVersion ? $ionCubeVersion : 'not installed') . "."; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }
            if (extension_loaded('xdebug') && $status) {
                $modes = xdebug_info('mode');
                $loaderFile = file_get_contents(dirname(__DIR__) . '/Loader.php', true);
                if (isset($modes[0]) && 'off' != $modes[0] && false !== strpos($loaderFile, 'HR+')) {
                    $status = false;
                    add_action('admin_notices', function () use ($pluginName): void {
                        $message = $pluginName . ': xDebug installation was detected and ' . $pluginName . ' was disabled because of it. This is because ' . $pluginName . ' uses IonCube for license protection and the IonCube Loader is incompatible with xDebug, causing the site to crash. xDebug helps developers with debug and profile, but it doesn\'t need to be on the production site. So to turn off xDebug, please set mode to off or uninstall it. If you are not familiar with this process, you can get help from your server service provider.'; // @phpcs:ignore
                        printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                    });
                }
            }
        }

        if (isset($rules['extensions'])) {
            $extensions = $rules['extensions'];
            if (!is_array($extensions)) {
                throw new \Exception('extensions must be an array!');
            }
            if (isset($extensions['curl']) && !extension_loaded('curl')) {
                $status = false;
                add_action('admin_notices', function () use ($pluginName): void {
                    $message = $pluginName . ': cURL PHP extension is not installed. So ' . $pluginName . ' has been disabled cURL is a HTTP request library that ' . $pluginName . ' needs and uses to verify blockchain transactions. Please visit "' . ('cli' == php_sapi_name() ? 'https://www.php.net/manual/en/book.curl.php' : '<a href="https://www.php.net/manual/en/book.curl.php">https://www.php.net/manual/en/book.curl.php</a>') . '" for install assistance. You can ask your server service provider to install cURL.'; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }

            if (isset($extensions['file_get_contents']) && !function_exists('file_get_contents')) {
                $status = false;
                add_action('admin_notices', function () use ($pluginName): void {
                    $message = $pluginName . ': file_get_contents PHP function is not available. So ' . $pluginName . ' has been disabled file_get_contents is a PHP function that ' . $pluginName . ' needs and uses for some process. Please visit "' . ('cli' == php_sapi_name() ? 'https://www.php.net/manual/en/function.file-get-contents.php' : '<a href="https://www.php.net/manual/en/function.file-get-contents.php">https://www.php.net/manual/en/function.file-get-contents.php</a>') . '" for install assistance. You can ask your server service provider to enable file_get_contents.'; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }

            if (isset($extensions['bcmath']) && !extension_loaded('bcmath')) {
                $status = false;
                add_action('admin_notices', function () use ($pluginName): void {
                    $message = $pluginName . ': bcmath PHP extension is not installed. So ' . $pluginName . ' has been disabled bcmath is a PHP extension that ' . $pluginName . ' needs and uses for some process. Please visit "' . ('cli' == php_sapi_name() ? 'https://www.php.net/manual/en/book.bc.php' : '<a href="https://www.php.net/manual/en/book.bc.php">https://www.php.net/manual/en/book.bc.php</a>') . '" for install assistance. You can ask your server service provider to install bcmath.'; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }

            if (isset($extensions['gmp']) && !extension_loaded('gmp')) {
                $status = false;
                add_action('admin_notices', function () use ($pluginName): void {
                    $message = $pluginName . ': gmp PHP extension is not installed. So ' . $pluginName . ' has been disabled gmp is a PHP extension that ' . $pluginName . ' needs and uses for some process. Please visit "' . ('cli' == php_sapi_name() ? 'https://www.php.net/manual/en/book.gmp.php' : '<a href="https://www.php.net/manual/en/book.gmp.php">https://www.php.net/manual/en/book.gmp.php</a>') . '" for install assistance. You can ask your server service provider to install gmp.'; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }

            if (isset($extensions['sodium']) && !extension_loaded('sodium')) {
                $status = false;
                add_action('admin_notices', function () use ($pluginName): void {
                    $message = $pluginName . ': sodium PHP extension is not installed. So ' . $pluginName . ' has been disabled sodium is a PHP extension that ' . $pluginName . ' needs and uses for some process. Please visit "' . ('cli' == php_sapi_name() ? 'https://www.php.net/manual/en/book.sodium.php' : '<a href="https://www.php.net/manual/en/book.sodium.php">https://www.php.net/manual/en/book.sodium.php</a>') . '" for install assistance. You can ask your server service provider to install sodium.'; // @phpcs:ignore
                    printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
                });
            }
        }

        if (!$status && isset($rules['documentation'])) {
            if (!filter_var($rules['documentation'], FILTER_VALIDATE_URL)) {
                throw new \Exception('documentation must be a valid URL!');
            }
            add_action('admin_notices', function () use ($pluginName, $rules): void {
                $message = sprintf($pluginName . ': Deficiencies in ' . $pluginName . ' requirements have been detected. You can check the <a href="%s" target="_blank">documentation</a> if you wish.', $rules['documentation']); // @phpcs:ignore
                printf('<div class="notice notice-error"><p>%1$s</p></div>', $message);
            });
        }

        return $status;
    }
}

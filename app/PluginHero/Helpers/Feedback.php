<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable WordPress.Security.NonceVerification.Missing
// @phpcs:disable WordPress.Security.NonceVerification.Recommended

if (!function_exists('WP_Filesystem')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

WP_Filesystem();

trait Feedback
{
    private static string $bpApiUrl = 'https://services.beycanpress.com/wp-json/plugin-statistics/';

        /**
     * @return string
     */
    public static function getAdminEmail(): string
    {
        try {
            try {
                return wp_get_current_user()->user_email;
            } catch (\Throwable $th) {
                global $wpdb;
                $key = 'bp_admin_email';
                $result = wp_cache_get($key);
                if (false === $result) {
                    // @phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
                    $result = ($wpdb->get_row("SELECT * FROM {$wpdb->users} WHERE ID = 1"))->user_email;
                    wp_cache_set($key, $result);
                }
                return $result;
            }
        } catch (\Throwable $th) {
            return get_option('admin_email');
        }
    }

    /**
     * @return array<mixed>
     */
    public static function getSiteInfos(): array
    {
        return [
            'siteUrl' => get_site_url(),
            'email' => self::getAdminEmail(),
            'siteName' => get_bloginfo('name'),
            'pluginKey' => self::getProp('pluginKey'),
            'pluginVersion' => self::getProp('pluginVersion'),
            'apiUrl' => home_url('?rest_route=/' . self::getProp('pluginKey') . '-deactivation/deactivate'),
        ];
    }

    /**
     * @param bool $form
     * @param string|null $wpOrgSlug
     * @return void
     */
    public static function feedback(bool $form = true, ?string $wpOrgSlug = null): void
    {
        self::registerActivation(self::getProp('pluginFile'), fn() => self::sendActivationInfo());

        add_action('init', function (): void {
            if (isset($_GET['bp-plugin-check-' . self::getProp('pluginKey')])) {
                wp_send_json_success();
            }
        });

        add_action('rest_api_init', function (): void {
            register_rest_route('bp-plugin-check', self::getProp('pluginKey'), [
                'callback' => fn() => wp_send_json_success(),
                'methods' => ['POST', 'GET'],
                'permission_callback' => '__return_true'
            ]);
        });

        if ($form) {
            global $pagenow, $wp_filesystem;
            if ('plugins.php' === $pagenow) {
                if (!file_exists(self::getProp('pluginDir') . 'assets/css/feedback.css')) {
                    if (!is_dir(self::getProp('pluginDir') . 'assets')) {
                        $wp_filesystem->mkdir(self::getProp('pluginDir') . 'assets');
                    }
                    if (!is_dir(self::getProp('pluginDir') . 'assets/css')) {
                        $wp_filesystem->mkdir(self::getProp('pluginDir') . 'assets/css');
                    }
                    $wp_filesystem->put_contents(
                        self::getProp('pluginDir') . 'assets/css/feedback.css',
                        $wp_filesystem->get_contents(
                            self::getProp('pluginDir') . 'app/PluginHero/templates/feedback.css'
                        )
                    );
                }
                if (!file_exists(self::getProp('pluginDir') . 'assets/js/feedback.js')) {
                    if (!is_dir(self::getProp('pluginDir') . 'assets')) {
                        $wp_filesystem->mkdir(self::getProp('pluginDir') . 'assets');
                    }
                    if (!is_dir(self::getProp('pluginDir') . 'assets/js')) {
                        $wp_filesystem->mkdir(self::getProp('pluginDir') . 'assets/js');
                    }
                    $wp_filesystem->put_contents(
                        self::getProp('pluginDir') . 'assets/js/feedback.js',
                        $wp_filesystem->get_contents(
                            self::getProp('pluginDir') . 'app/PluginHero/templates/feedback.js'
                        )
                    );
                }
                add_action('admin_enqueue_scripts', function (): void {
                    wp_enqueue_style(
                        self::getProp('pluginKey') . '-feedback',
                        self::getProp('pluginUrl') . 'assets/css/feedback.css',
                        [],
                        self::getProp('pluginVersion')
                    );
                    wp_enqueue_script(
                        self::getProp('pluginKey') . '-feedback',
                        self::getProp('pluginUrl') . 'assets/js/feedback.js',
                        [],
                        self::getProp('pluginVersion'),
                        true
                    );
                });
                add_action('admin_footer', function () use ($wpOrgSlug): void {
                    $slug = self::getPluginSlug(self::getProp('pluginFile'));
                    self::echoTemplate('feedback', array_merge([
                        'slug' => $slug,
                        'wpOrgSlug' => $wpOrgSlug,
                        'hidePremiumVersionReason' => self::getProp('hidePremiumVersionReason', false),
                    ], self::getSiteInfos()));
                });
            }

            self::sendDeactivationInfoApi();
        } else {
            self::registerDeactivation(self::getProp('pluginFile'), function (): void {
                self::sendDeactivationInfo([
                    'reason' => 'Without feedback form',
                    'email' => self::getAdminEmail(),
                ]);
            });
        }
    }

    /**
     * @return void
     */
    public static function sendDeactivationInfoApi(): void
    {
        add_action('rest_api_init', function (): void {
            register_rest_route(self::getProp('pluginKey') . '-deactivation', 'deactivate', [
                'callback' => fn() => self::sendDeactivationInfoApiReal(),
                'methods' => ['POST'],
                'permission_callback' => '__return_true'
            ]);
        });
    }

    /**
     * @return void
     */
    public static function sendDeactivationInfoApiReal(): void
    {
        delete_option(self::getProp('pluginKey') . '_feedback_activation');
        if (function_exists('curl_version')) {
            try {
                self::sendDeactivationInfo([
                    'reasonCode' => isset($_POST['reasonCode']) ? sanitize_text_field($_POST['reasonCode']) : null,
                    'reason' =>  isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : null,
                    'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : null,
                ]);
            } catch (\Exception $e) {
                wp_send_json_success($e->getMessage());
            }
        }

        wp_send_json_success();
    }

    /**
     * @return bool
     */
    public static function sendActivationInfo(): bool
    {
        if (function_exists('curl_version')) {
            try {
                wp_remote_post(self::$bpApiUrl . 'activation', [
                    'body' => self::getSiteInfos()
                ]);

                return true;
            } catch (\Exception $th) {
                return false;
            }
        }

        return false;
    }

    /**
     * @param array<mixed> $params
     * @return bool
     */
    public static function sendDeactivationInfo(array $params = []): bool
    {
        if (function_exists('curl_version')) {
            try {
                $data = array_merge(
                    self::getSiteInfos(),
                    $params
                );

                wp_remote_post(self::$bpApiUrl . 'deactivation', [
                    'body' => $data
                ]);

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $message
     * @return bool
     */
    public static function sendFeedbackMessage(string $message): bool
    {
        if (function_exists('curl_version')) {
            try {
                $data = array_merge([
                    'message' => $message,
                ], self::getSiteInfos());

                wp_remote_post(self::$bpApiUrl . 'feedbacks', [
                    'body' => $data
                ]);

                return true;
            } catch (\Exception $th) {
                return false;
            }
        }

        return false;
    }
}

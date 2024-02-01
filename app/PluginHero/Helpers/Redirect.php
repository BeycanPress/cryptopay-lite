<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Helpers;

trait Redirect
{
    /**
     * @param string $url
     * @return void
     */
    public static function redirect(string $url): void
    {
        wp_redirect($url);
        exit();
    }

    /**
     * @param string $url
     * @return void
     */
    protected function jsRedirect(string $url): void
    {
        $allowedHtml = array_merge_recursive(wp_kses_allowed_html('post'), ['script' => true]);
        echo wp_kses("<script>window.location.href = '" . esc_url_raw($url) . "'</script>", $allowedHtml);
        die();
    }

    /**
     * @param string $url
     * @return void
     */
    public static function adminRedirect(string $url): void
    {
        add_action('admin_init', function () use ($url): void {
            wp_redirect($url);
            exit();
        });
    }

    /**
     * @param string $url
     * @return void
     */
    public static function templateRedirect(string $url): void
    {
        add_action('template_redirect', function () use ($url): void {
            wp_redirect($url);
            exit();
        });
    }
}

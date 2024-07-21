<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Pages;

use BeycanPress\CryptoPayLite\Helpers;
use BeycanPress\CryptoPayLite\PluginHero\Page;

class DebugLogs extends Page
{
    /**
     * @return string
     */
    public function __construct()
    {
        parent::__construct([
            'priority' => 11,
            'pageName' => esc_html__('Debug logs', 'cryptopay'),
            'parent' => Helpers::getPage('HomePage')->getSlug(),
        ]);
    }

    /**
     * @return void
     */
    public function page(): void
    {
        if ($_POST['delete'] ?? 0) {
            Helpers::deleteLogFile();
            wp_redirect(admin_url('admin.php?page=cryptopay_lite_settings'));
        }

        Helpers::viewEcho('pages/debug-logs', [
            'logs' => Helpers::getLogFile(),
            'pageUrl' => Helpers::getCurrentUrl()
        ]);
    }
}

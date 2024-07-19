<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

use BeycanPress\CryptoPayLite\Helpers;

/**
 * Cron service
 *
 * @since 2.0.0
 */
class Cron
{
    /**
     * Cron constructor.
     */
    public function __construct()
    {
        if ('wp' == Helpers::getSetting('cronType')) {
            add_filter('cron_schedules', [$this, 'cron']);
            if (!wp_next_scheduled('cryptopay_lite_cron_job')) {
                wp_schedule_event(time(), 'cryptopay_lite_cron_job', 'cryptopay_lite_cron_job');
            }
        } else {
            wp_clear_scheduled_hook('cryptopay_lite_cron_job');
        }

        add_action('cryptopay_lite_cron_job', fn() => Helpers::debug('WP Cron works'));
    }

    /**
     * @param array<string,array<string,mixed>> $schedules
     * @return array<string,array<string,mixed>>
     */
    public function cron(array $schedules): array
    {
        $schedules['cryptopay_lite_cron_job'] = [
            'interval' => absint(Helpers::getSetting('cronTime')) * 60,
            'display'  => sprintf(esc_html__('Every %d minutes', 'cryptopay'), Helpers::getSetting('cronTime'))
        ];
        return $schedules;
    }
}

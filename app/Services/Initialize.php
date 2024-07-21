<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Services;

class Initialize
{
    /**
     * @return void
     */
    public function __construct()
    {
        new Cron();
        new Verifier();
        new Discounts();
        new ReminderEmail();
    }
}

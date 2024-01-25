<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Models;

/**
 * Order transaction table model
 */
class OrderTransaction extends AbstractTransaction
{
    /**
     * @var string
     */
    // @phpcs:ignore
    public $addon = 'woocommerce';

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct('order_transaction');
    }
}

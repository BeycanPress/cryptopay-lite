<div class="clear"></div>
<p>
    <h3>
        <?php echo __('CryptoPay Payment Details', 'cryptopay'); ?>
    </h3>
    <div>
        <p>
            <strong><?php echo __('Blockchain Network', 'cryptopay'); ?>:</strong>
            <?php echo esc_html($blockchainNetwork); ?>
        </p>
        <?php if ($order->getDiscountRate()) : ?>
            <p>
                <strong><?php echo __('Discounted Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($paymentAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
            <p>
                <strong><?php echo __('Real Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($realAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
            <p>
                <strong><?php echo __('Discount Rate', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($order->getDiscountRate()); ?>%
            </p>
        <?php else: ?>
            <p>
                <strong><?php echo __('Payment Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($paymentAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
        <?php endif; ?>
        <p>
            <strong><?php echo __('Transaction Hash', 'cryptopay'); ?>:</strong>
            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cryptopay_lite_woocommerce_transactions&s=' . $transactionHash)); ?>">
                <?php echo esc_html($transactionHash); ?>
            </a>
        </p>
    </div>
</p>
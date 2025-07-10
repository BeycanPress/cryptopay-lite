<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="clear"></div>
<p>
    <h3>
        <?php echo esc_html__('CryptoPay Payment Details', 'cryptopay'); ?>
    </h3>
    <div>
        <p>
            <strong><?php echo esc_html__('Blockchain Network', 'cryptopay'); ?>:</strong>
            <?php echo esc_html($blockchainNetwork); ?>
        </p>
        <?php if ($order->getDiscountRate()) : ?>
            <p>
                <strong><?php echo esc_html__('Discounted Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($paymentAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
            <p>
                <strong><?php echo esc_html__('Real Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($realAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
            <p>
                <strong><?php echo esc_html__('Discount Rate', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($order->getDiscountRate()); ?>%
            </p>
        <?php else: ?>
            <p>
                <strong><?php echo esc_html__('Payment Amount', 'cryptopay'); ?>:</strong>
                <?php echo esc_html($paymentAmount); ?> <?php echo esc_html($paymentCurrency); ?>
            </p>
        <?php endif; ?>
        <p>
            <strong><?php echo esc_html__('Transaction Hash', 'cryptopay'); ?>:</strong>
            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=cryptopay_lite_woocommerce_transactions&s=' . $transactionHash)); ?>">
                <?php echo esc_html($transactionHash); ?>
            </a>
        </p>
    </div>
</p>
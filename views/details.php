<section class="cryptopay-lite-payment-details">
    <h2 class="cryptopay-lite-payment-details-title">
        <?php echo esc_html__('CryptoPay Payment Details', 'cryptopay_lite'); ?>
    </h2>
    <table class="cryptopay-lite-payment-details-table">
        <tr>
            <th scope="row">
                <?php echo esc_html__('Name: ', 'cryptopay_lite'); ?>
            </th>
            <td>
                <?php echo esc_html($transaction->getNetwork()->getName()); ?>
            </td>
        </tr>    
        <?php 
        if ($order->getDiscountRate()) : ?>
            <tr>
                <th scope="row">
                    <?php echo esc_html__('Discounted Amount: ', 'cryptopay_lite'); ?>
                </th>
                <td>
                    <?php echo esc_html($amount); ?> <?php echo esc_html($currency->getSymbol()); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php echo esc_html__('Real Amount: ', 'cryptopay_lite'); ?>
                </th>
                <td>
                    <?php echo esc_html($realAmount); ?> <?php echo esc_html($currency->getSymbol()); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php echo esc_html__('Discount Rate: ', 'cryptopay_lite'); ?>
                </th>
                <td>
                    <?php echo esc_html($order->getDiscountRate()); ?>%
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <th scope="row">
                    <?php echo esc_html__('Amount: ', 'cryptopay_lite'); ?>
                </th>
                <td>
                    <?php echo esc_html($amount); ?> <?php echo esc_html($currency->getSymbol()); ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th scope="row">
                <?php echo esc_html__('Status: ', 'cryptopay_lite'); ?>
            </th>
            <td>
                <?php
                    echo ucfirst(esc_html__(str_replace('-', ' ', ($transaction->getStatus()->getValue())), 'cryptopay_lite'));
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <?php echo esc_html__('Transaction hash: ', 'cryptopay_lite'); ?>
            </th>
            <td>
                <?php if ($transactionUrl) : ?>
                    <a href="<?php echo esc_url($transactionUrl); ?>" target="_blank" style="word-break: break-word">
                        <?php echo esc_html($transaction->getHash()); ?>
                    </a>
                <?php else: ?>
                    <?php echo esc_html($transaction->getHash()); ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</section>
<?php use BeycanPress\CryptoPayLite\Helpers; ?>
<img src="<?php echo esc_url_raw(Helpers::getImageUrl('menu.png')); ?>" alt="<?php echo esc_attr(esc_html__('CryptoPay Lite', 'cryptopay_lite')); ?>" width="18"> <?php echo esc_html__('CryptoPay new products', 'cryptopay_lite'); ?>: <span class="cp-bubble-notifi"><?php echo esc_html($count); ?></span>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php /* translators: %s: Status */ ?>
<span class="cp-status <?php echo esc_attr($status); ?>"><?php echo esc_html__(sprintf('%s', ucfirst($status)), 'cryptopay'); ?></span>
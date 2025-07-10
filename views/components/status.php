<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span class="cp-status <?php echo esc_attr(esc_attr($status)); ?>"><?php echo esc_html(esc_html__(ucfirst($status), 'cryptopay')); ?></span>
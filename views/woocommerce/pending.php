<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php echo sprintf(esc_html__('Your order is waiting for payment, %s.', 'cryptopay'), '<a href="'.esc_url($payUrl).'" title="'.esc_html__('click to pay', 'cryptopay').'">'.esc_html__('click to pay', 'cryptopay').'</a>'); ?> 
<br><br>
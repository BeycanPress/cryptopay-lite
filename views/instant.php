
<?php if (!('no' == get_option('woocommerce_enable_guest_checkout') && !is_user_logged_in())) : ?>
    <button type="button" value="<?php echo esc_attr( $product->get_id() ); ?>" class="start-cryptopay-instant button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html__( 'Pay with CryptoPay', 'cryptopay' ); ?></button>
<?php endif; ?>

<?php echo wp_kses_post($cryptopay); ?>

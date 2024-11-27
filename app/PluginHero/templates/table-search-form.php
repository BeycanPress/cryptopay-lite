<?php
    if (!defined('ABSPATH')) exit;
    // @phpcs:disable WordPress.Security.NonceVerification.Missing
    // @phpcs:disable WordPress.Security.NonceVerification.Recommended
?>
<form>
    <?php if (!empty($_GET)) {
        foreach ($_GET as $key => $value) {
            $key = sanitize_text_field($key);
            $value = sanitize_text_field($value);
        ?>
            <input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"/>
        <?php }
    } ?>
    <?php $searchBox(
        $options['search']['title'], 
        $options['search']['id']
    ); ?>
</form>
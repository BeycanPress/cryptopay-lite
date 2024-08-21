<?php 
    // @phpcs:disable WordPress.Security.NonceVerification.Missing
    // @phpcs:disable WordPress.Security.NonceVerification.Recommended
?>
<form>
    <?php if (!empty($_GET)) {
        foreach ($_GET as $key => $value) { ?>
            <input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"/>
        <?php }
    } ?>
    <?php $searchBox(
        $options['search']['title'], 
        $options['search']['id']
    ); ?>
</form>
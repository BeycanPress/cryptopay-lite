<li>
    <?php if (isset($product->new)) : ?>
        <div class="new"><?php echo esc_html__('New', 'cryptopay') ?></div>
    <?php endif; ?>
    <a href="<?php echo esc_url($product->permalink) ?>" target="_blank">
        <img src="<?php echo esc_url($product->image) ?>" alt="<?php echo esc_attr($product->title) ?>">
        <span><?php echo esc_html($product->title) ?></span>
    </a>
</li>
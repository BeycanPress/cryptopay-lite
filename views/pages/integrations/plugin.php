<li>
    <?php if (isset($plugin->new)) : ?>
        <div class="new"><?php echo esc_html__('New', 'cryptopay') ?></div>
    <?php endif; ?>
    <a href="<?php echo esc_url($plugin->permalink) ?>" target="_blank">
        <img src="<?php echo esc_url($plugin->image) ?>" alt="<?php echo esc_attr($plugin->title) ?>">
        <span><?php echo esc_html($plugin->title) ?></span>
    </a>
</li>
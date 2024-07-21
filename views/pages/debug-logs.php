<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Debug logs', 'cryptopay'); ?>
    </h1>
    <hr class="wp-header-end">
    <br>
    <?php if ($logs) : ?>
    <form method="post" action="<?php echo esc_url($pageUrl) ?>">
        <button  type="submit" name="delete" value="1" class="button button-primary">
            <?php echo esc_html__('Delete old logs', 'cryptopay'); ?>
        </button>
    </form>
    <br>
    <?php endif; ?>
<pre>
<?php print_r($logs) ?? esc_html__('Logs not found!', 'cryptopay'); ?>
</pre>
</div>
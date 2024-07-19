<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Pending reminders', 'cryptopay'); ?>
    </h1>
    <hr class="wp-header-end">
    <br>
    <?php print($table->renderWpTable()); ?>
</div>
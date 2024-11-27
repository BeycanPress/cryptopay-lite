<?php
    if (!defined('ABSPATH')) exit; 

    $dismissible = $dismissible ? ' is-dismissible' : '';
?>
<div class="notice notice-<?php echo esc_attr($type); echo esc_attr($dismissible); ?>">
    <p><?php $ksesEcho($notice); ?></p>
</div>

<?php if ($dismissible && isset($id)) : ?>
    <script>
    jQuery(document).on('click', '.notice.is-dismissible', function () {
        window.onbeforeunload = function () {
            return 'There is an ongoing process, please do not close the browser.';
        }
        jQuery.ajax({
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            type: 'post',
            data: {
                action: 'bpDismissNotice',
                id: '<?php echo esc_attr($id); ?>'
            },
            success: function () {
                window.onbeforeunload = null;
            },
            error: function () {
                window.onbeforeunload = null;
                window.location.reload();
            }
        });
    });
</script>
<?php endif; ?>
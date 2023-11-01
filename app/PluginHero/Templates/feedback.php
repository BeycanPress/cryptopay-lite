<?php 
    if ( ! defined( 'ABSPATH' ) ) exit;
    $pattern = '/(?:plugins\\\\|plugins\/)(.*)/';
    preg_match($pattern, $this->pluginFile, $matches);
    $slug = str_replace('\\', '/', $matches[1]);
    $slug = explode('/', $slug);
?>

<div class="bp-feedback-modal" id="<?php echo esc_attr($pluginKey); ?>-feedback-modal">
    <div class="bp-feedback-modal-content">
        <div class="bp-feedback-modal-header">
            Quick Feedback
        </div>
        <div class="bp-feedback-modal-loading" id="<?php echo esc_attr($pluginKey); ?>-feedback-modal-loading">
            Please wait... Your feedback is being sending...
        </div>
        <div class="bp-feedback-modal-question" id="<?php echo esc_attr($pluginKey); ?>-feedback-modal-question">
            If you have a moment, please let us know why you are deactivating:
        </div>
        <ul class="bp-feedback-modal-body" id="<?php echo esc_attr($pluginKey); ?>-feedback-modal-body">
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_not_working">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_not_working" value="The plugin didn't work" data-reason-code="not-working"> The plugin didn't work
                </label>
            </li>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_better_plugin">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_better_plugin" value="I found a better plugin" data-reason-code="better-plugin"> I found a better plugin
                </label>
            </li>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_insufficient_feature">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_insufficient_feature" value="Insufficient add-on feature" data-reason-code="insufficient-feature"> Insufficient add-on feature
                </label>
            </li>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_premium_version">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_premium_version" value="I will buy the premium version" data-reason-code="premium-version"> I will buy the premium version
                </label>
            </li>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_temporary_deactivation">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_temporary_deactivation" value="It's a temporary deactivation - I'm troubleshooting an issu" data-reason-code="temporary-deactivation"> It's a temporary deactivation - I'm troubleshooting an issue
                </label>
            </li>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_other">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_other" value="Other" data-reason-code="other"> Other
                </label>
            </li>
        </ul>
        <div class="bp-feedback-modal-footer" id="<?php echo esc_attr($pluginKey); ?>-feedback-modal-footer"> 
            <?php if ($wpOrgSlug) : ?>
                <a class="button-primary" href="https://wordpress.org/support/plugin/<?php echo esc_attr($wpOrgSlug); ?>/" target="_blank">
                    <span class="dashicons dashicons-external" style="margin-top:3px;"></span>
                    Go to support
                </a>
            <?php endif; ?>
            <a href="#" class="button button-primary <?php echo $pluginKey; ?>-feedback-button-deactivate">Deactivate</a>
            <a href="#" class="button button-secondary <?php echo esc_attr($pluginKey); ?>-feedback-button-cancel">Cancel</a>		

            <a href="#" class="<?php echo esc_attr($pluginKey); ?>-feedback-skip" style="float: right;">I rather wouldn't say</a>
        </div>
    </div>
</div>

<script>
    (($) => {
        $(document).ready(() => {

            let pluginKey = '<?php echo esc_js($pluginKey); ?>';

            var deactivationUrl = '';
            window.onload = function(){
                $(document).on('click', 'tr[data-plugin="<?php echo esc_js($slug[0]) . '/' . esc_js($slug[1]); ?>"] .column-primary .deactivate a', function(e){
                    e.preventDefault()
                    deactivationUrl =  $(this).attr('href');
                    $('#'+pluginKey+'-feedback-modal').css('display', 'flex');
                });
            }

            $(document).on('click', '.'+pluginKey+'-feedback-button-cancel', function(e){
                e.preventDefault();
                $('#'+pluginKey+'-feedback-modal').css('display', 'none');
            });

            let exlcludedForReasonBox = [
                pluginKey+'_temporary_deactivation',
                pluginKey+'_premium_version',
            ];

            function deactivateProcess(reason, reasonCode) {
                if (!reasonCode) {
                    reasonCode = $('.'+pluginKey+'_deactivation_reason:checked').data('reason-code');
                }
                try {
                    $.ajax({
                        url: "<?php echo esc_url(home_url('wp-json/' . $pluginKey . '-deactivation/deactivate')); ?>",
                        type: 'POST',
                        data: {
                            reason,
                            reasonCode,
                            pluginKey,
                            email: '<?php echo esc_js($email); ?>',
                            pluginVersion: '<?php echo esc_js($pluginVersion); ?>',
                            siteUrl: '<?php echo esc_js($siteUrl); ?>',
                            siteName: '<?php echo esc_js($siteName); ?>',
                        },
                        beforeSend: function() {
                            $('#'+pluginKey+'-feedback-modal-loading').show();
                            $('#'+pluginKey+'-feedback-modal-question').hide();
                            $('#'+pluginKey+'-feedback-modal-body').hide();
                            $('#'+pluginKey+'-feedback-modal-footer').hide();
                        },
                        success: function() {
                            $('#'+pluginKey+'-feedback-modal').css('display', 'none');
                            window.location.href = deactivationUrl;
                        },
                        error: function(error) {
                            $('#'+pluginKey+'-feedback-modal').css('display', 'none');
                            window.location.href = deactivationUrl;
                        }
                    });
                } catch (error) {}
            }
            
            $(document).on('click', '.'+pluginKey+'-feedback-skip', function(e){
                e.preventDefault();
                deactivateProcess("I rather wouldn't say", 'rather-wouldnt-say');
            });

            $(document).on('click', '.'+pluginKey+'-feedback-button-deactivate', function(e){
                e.preventDefault();
                var reason = $('.'+pluginKey+'_deactivation_reason:checked').val();
                var detail = $('#'+pluginKey+'_deactivation_reason_detail').val();

                if (!reason) {
                    alert('Please select a reason!');
                    return false;
                }

                if (!detail && !exlcludedForReasonBox.includes($('.'+pluginKey+'_deactivation_reason:checked').attr('id'))) {
                    alert('Please provide more information!');
                    return false;
                }

                if (detail) {
                    reason = reason + ": " + detail;
                }

                deactivateProcess(reason);
            });
            
            $(document).on('change', '.'+pluginKey+'_deactivation_reason', function(e) {
                e.preventDefault()
                if (exlcludedForReasonBox.includes($(this).attr('id'))) {
                    $('#'+pluginKey+'-feedback-modal-reason-detail').remove();
                    return;
                }
                let html = `
                    <div class="bp-feedback-modal-reason-input" id="${pluginKey}-feedback-modal-reason-detail">
                        <span class="message error-message">Can you provide more information?</span>
                        <input type="text" id="${pluginKey}_deactivation_reason_detail" name="${pluginKey}_deactivation_reason_detail" maxlength="128" placeholder="">
                    </div>`;

                $('#'+pluginKey+'-feedback-modal-reason-detail').remove();

                $(this).parent().parent().append(html);
            });
        })
    })(jQuery);
</script>

<style>
    .bp-feedback-modal {
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        z-index: 999999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .bp-feedback-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 0;
        border: 1px solid #888;
        width: 650px;
        box-shadow: 0 0 10px 0 rgba(0,0,0,0.5);
        border-radius: 5px;
        box-sizing: border-box;
    }

    .bp-feedback-modal-header {
        border-bottom: #eee solid 1px;
        background: #fbfbfb;
        padding: 15px 20px;
        position: relative;
        font-weight: bold;
    }

    .bp-feedback-modal-loading {
        display: none;
    }

    .bp-feedback-modal-question, .bp-feedback-modal-loading {
        padding: 20px;
        margin: 0;
        list-style: none;
        border-bottom: #eee solid 1px;
    }

    .bp-feedback-modal-body {
        padding: 20px;
        margin: 0;
        list-style: none;
    }

    .bp-feedback-modal-reason-input {
        margin-left: 20px;
    }

    .bp-feedback-modal-reason-input input {
        width: 100%;
        border: #ccc solid 1px;
        border-radius: 3px;
        box-sizing: border-box;
    }

    .bp-feedback-modal-footer {
        border-top: #eee solid 1px;
        background: #fbfbfb;
        padding: 15px 20px;
        position: relative;
        text-align: left;
        position: relative;
    }

    .bp-feedback-modal-footer a:last-child {
        position: relative;
        right: 0;
        top: 9px;
        font-size: 12px;
    }
</style>
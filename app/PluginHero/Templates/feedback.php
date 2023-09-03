<div class="bp-feedback-modal" id="<?php echo $pluginKey; ?>-feedback-modal">
    <div class="bp-feedback-modal-content">
        <div class="bp-feedback-modal-header">
            Quick Feedback
        </div>
        <div class="bp-feedback-modal-loading" id="<?php echo $pluginKey; ?>-feedback-modal-loading">
            Please wait... Your feedback is being sending...
        </div>
        <div class="bp-feedback-modal-question" id="<?php echo $pluginKey; ?>-feedback-modal-question">
            If you have a moment, please let us know why you are deactivating:
        </div>
        <ul class="bp-feedback-modal-body" id="<?php echo $pluginKey; ?>-feedback-modal-body">
            <li>
                <label for="<?php echo $pluginKey; ?>_not_working">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_not_working" value="The plugin didn't work"> The plugin didn't work
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_better_plugin">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_better_plugin" value="I found a better plugin"> I found a better plugin
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_insufficient_feature">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_insufficient_feature" value="Insufficient add-on feature"> Insufficient add-on feature
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_premium_version">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_premium_version" value="I will buy the premium version"> I will buy the premium version
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_temporary_deactivation">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_temporary_deactivation" value="It's a temporary deactivation - I'm troubleshooting an issu"> It's a temporary deactivation - I'm troubleshooting an issue
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_other">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_other" value="Other"> Other
                </label>
            </li>
        </ul>
        <div class="bp-feedback-modal-footer" id="<?php echo $pluginKey; ?>-feedback-modal-footer"> 
            <a href="#" class="button button-secondary <?php echo $pluginKey; ?>-feedback-button-deactivate">Deactivate</a>
            <a href="#" class="button button-secondary <?php echo $pluginKey; ?>-feedback-button-cancel">Cancel</a>		
        </div>
    </div>
</div>

<script>
    (($) => {
        $(document).ready(() => {

            let pluginKey = '<?php echo $pluginKey; ?>';

            var deactivationUrl = '';
            window.onload = function(){
                $(document).on('click', '#deactivate-cryptopay-wc-lite', function(e){
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

            $(document).on('click', '.'+pluginKey+'-feedback-button-deactivate', function(e){
                try {
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
                    } else {
                        
                    }

                    if (detail) {
                        reason = reason + ": " + detail;
                    }

                    $.ajax({
                        url: "<?php echo home_url('wp-json/' . $pluginKey . '-deactivation/deactivate'); ?>",
                        type: 'POST',
                        data: {
                            reason,
                            pluginKey,
                            email: '<?php echo $email; ?>',
                            pluginVersion: '<?php echo $pluginVersion; ?>',
                            siteUrl: '<?php echo $siteUrl; ?>',
                            siteName: '<?php echo $siteName; ?>',
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
        width: 500px;
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
        text-align: right;
    }
</style>
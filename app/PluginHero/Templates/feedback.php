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
                <div class="bp-feedback-modal-reason-input" id="<?php echo $pluginKey; ?>-feedback-modal-reason-better-plugin">
                    <span class="message error-message">Kindly tell us the wich plugin so we can improve.</span>
                    <input type="text" id="<?php echo $pluginKey; ?>_deactivation_reason_better_plugin" name="<?php echo $pluginKey; ?>_deactivation_reason_better_plugin" maxlength="128" placeholder="">
                </div>
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
                <label for="<?php echo $pluginKey; ?>_dont_like_share_info">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_dont_like_share_info" value="I don't like to share my information with you"> I don't like to share my information with you
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_temporary_deactivation">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_temporary_deactivation" value="It's a temporary deactivation - I'm troubleshooting an issu"> It's a temporary deactivation - I'm troubleshooting an issue
                </label>
            </li>
            <li>
                <label for="<?php echo $pluginKey; ?>_other">
                    <input type="radio" class="<?php echo $pluginKey; ?>_deactivation_reason" name="<?php echo $pluginKey; ?>_deactivation_reason" id="<?php echo $pluginKey; ?>_other" value="other"> Other
                </label>
                <div class="bp-feedback-modal-reason-input" id="<?php echo $pluginKey; ?>-feedback-modal-reason-other">
                    <span class="message error-message">Kindly tell us the reason so we can improve.</span>
                    <input type="text" id="<?php echo $pluginKey; ?>_deactivation_reason_other" name="<?php echo $pluginKey; ?>_deactivation_reason_other" maxlength="128" placeholder="">
                </div>
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
            var deactivationUrl = '';
            window.onload = function(){
                $(document).on('click', '#deactivate-cryptopay-wc-lite', function(e){
                    e.preventDefault()
                    deactivationUrl =  $(this).attr('href');
                    $('#<?php echo $pluginKey; ?>-feedback-modal').css('display', 'flex');
                });
            }

            $(document).on('click', '.<?php echo $pluginKey; ?>-feedback-button-deactivate', function(e){
                try {
                    e.preventDefault();
                    var reason = $('.<?php echo $pluginKey; ?>_deactivation_reason:checked').val();

                    if (reason == 'other') {
                        reason = $('#<?php echo $pluginKey; ?>_deactivation_reason_other').val();
                    } else if (reason == 'I found a better plugin') {
                        reason = "I found a better plugin: " + $('#<?php echo $pluginKey; ?>_deactivation_reason_better_plugin').val();
                    }

                    if (!reason) {
                        alert('Please select or enter a reason!');
                        return false;
                    }

                    $.ajax({
                        url: "<?php echo home_url('wp-json/' . $pluginKey . '-deactivation/deactivate'); ?>",
                        type: 'POST',
                        data: {
                            reason: reason,
                            email: '<?php echo $email; ?>',
                            pluginKey: '<?php echo $pluginKey; ?>',
                            pluginVersion: '<?php echo $pluginVersion; ?>',
                            siteUrl: '<?php echo $siteUrl; ?>',
                            siteName: '<?php echo $siteName; ?>',
                        },
                        beforeSend: function() {
                            $('#<?php echo $pluginKey; ?>-feedback-modal-loading').show();
                            $('#<?php echo $pluginKey; ?>-feedback-modal-question').hide();
                            $('#<?php echo $pluginKey; ?>-feedback-modal-body').hide();
                            $('#<?php echo $pluginKey; ?>-feedback-modal-footer').hide();
                        },
                        success: function() {
                            $('#<?php echo $pluginKey; ?>-feedback-modal').css('display', 'none');
                            window.location.href = deactivationUrl;
                        },
                        error: function(error) {
                            $('#<?php echo $pluginKey; ?>-feedback-modal').css('display', 'none');
                            window.location.href = deactivationUrl;
                        }
                    });
                } catch (error) {}
            });

            $(document).on('click', '.<?php echo $pluginKey; ?>-feedback-button-cancel', function(e){
                e.preventDefault();
                $('#<?php echo $pluginKey; ?>-feedback-modal').css('display', 'none');
            });

            $(document).on('change', '.<?php echo $pluginKey; ?>_deactivation_reason', function(e){
                e.preventDefault()
                var value = $(this).attr('id');
                if (value == '<?php echo $pluginKey; ?>_other') {
                    $('#<?php echo $pluginKey; ?>-feedback-modal-reason-other').show();
                } else if (value == '<?php echo $pluginKey; ?>_better_plugin') {
                    $('#<?php echo $pluginKey; ?>-feedback-modal-reason-better-plugin').show();
                } else {
                    $('#<?php echo $pluginKey; ?>-feedback-modal-reason-other').hide();
                    $('#<?php echo $pluginKey; ?>-feedback-modal-reason-better-plugin').hide();
                }
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
        display: none;
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
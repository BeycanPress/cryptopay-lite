<?php if (!defined('ABSPATH')) exit; ?>
<div 
    class="bp-feedback-modal" 
    id="<?php echo esc_attr($pluginKey); ?>-feedback-modal" 
    data-slug="<?php echo esc_attr($slug); ?>"
    data-api-url="<?php echo esc_attr($apiUrl); ?>"
    data-plugin-key="<?php echo esc_attr($pluginKey); ?>"
    data-email="<?php echo esc_attr($email); ?>"
    data-site-url="<?php echo esc_attr($siteUrl); ?>"
    data-site-name="<?php echo esc_attr($siteName); ?>"
>
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
            <?php if (!$hidePremiumVersionReason) : ?>
            <li>
                <label for="<?php echo esc_attr($pluginKey); ?>_premium_version">
                    <input type="radio" class="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" name="<?php echo esc_attr($pluginKey); ?>_deactivation_reason" id="<?php echo esc_attr($pluginKey); ?>_premium_version" value="I will buy the premium version" data-reason-code="premium-version"> I will buy the premium version
                </label>
            </li>
            <?php endif; ?>
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
            <a href="#" class="button button-primary <?php echo esc_attr($pluginKey); ?>-feedback-button-deactivate">Deactivate</a>
            <a href="#" class="button button-secondary <?php echo esc_attr($pluginKey); ?>-feedback-button-cancel">Cancel</a>		

            <a href="#" class="<?php echo esc_attr($pluginKey); ?>-feedback-skip" style="float: right;">I rather wouldn't say</a>
        </div>
    </div>
</div>
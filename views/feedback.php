<div class="bp-feedback-modal">
    <div class="bp-feedback-modal-content">
        <div class="bp-feedback-modal-header">
            Quick Feedback
        </div>
        <div class="bp-feedback-modal-loading">
            Please wait... Your feedback is being sending...
        </div>
        <div class="bp-feedback-modal-question">
            If you have a moment, please let us know why you are deactivating:
        </div>
        <ul class="bp-feedback-modal-body">
            <li>
                <label for="bp_cp_lite_not_working">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_not_working" value="The plugin didn't work"> The plugin didn't work
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_better_plugin">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_better_plugin" value="I found a better plugin"> I found a better plugin
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_insufficient_feature">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_insufficient_feature" value="Insufficient add-on feature"> Insufficient add-on feature
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_premium_version">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_premium_version" value="I will buy the premium version"> I will buy the premium version
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_dont_like_share_info">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_dont_like_share_info" value="I don't like to share my information with you"> I don't like to share my information with you
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_temporary_deactivation">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_temporary_deactivation" value="It's a temporary deactivation - I'm troubleshooting an issu"> It's a temporary deactivation - I'm troubleshooting an issue
                </label>
            </li>
            <li>
                <label for="bp_cp_lite_other">
                    <input type="radio" class="bp_cp_lite_deactivation_reason" name="bp_cp_lite_deactivation_reason" id="bp_cp_lite_other" value="other"> Other
                </label>
                <div class="bp-feedback-modal-reason-input">
                    <span class="message error-message">Kindly tell us the reason so we can improve.</span>
                    <input type="text" id="bp_cp_lite_deactivation_reason_input" name="bp_cp_lite_deactivation_reason_input" maxlength="128" placeholder="">
                </div>
            </li>
        </ul>
        <div class="bp-feedback-modal-footer"> 
            <a href="#" class="button button-secondary bp-feedback-button-deactivate">Deactivate</a>
            <a href="#" class="button button-secondary bp-feedback-button-cancel">Cancel</a>		
        </div>
    </div>
</div>

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
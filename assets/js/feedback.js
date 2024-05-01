(($) => {
    if (!window.bpPhFeedbacks) {
        window.bpPhFeedbacks = [];
    }
    $(document).ready(() => {

        let feedbackModals = $(".bp-feedback-modal");

        feedbackModals.each(function() {
            let modal = $(this);
            let slug = modal.data('slug');

            if (window.bpPhFeedbacks.includes(slug)) {
                return;
            }

            window.bpPhFeedbacks.push(slug);
            let apiUrl = modal.data('api-url');
            let pluginKey = modal.data('plugin-key');
            let apiData = {
                email : modal.data('email'),
                siteUrl : modal.data('site-url'),
                siteName : modal.data('site-name'),
                pluginVersion : modal.data('plugin-version'),
            }

            var deactivationUrl = '';
            $(document).on('click', 'tr[data-plugin="'+slug+'"] .column-primary .deactivate a', function(e) {
                e.preventDefault()
                deactivationUrl =  $(this).attr('href');
                $('#'+pluginKey+'-feedback-modal').css('display', 'flex');
            });

            $(document).on('click', '.'+pluginKey+'-feedback-button-cancel', function(e) {
                e.preventDefault();
                $('#'+pluginKey+'-feedback-modal').css('display', 'none');
            });

            let excludedForReasonBox = [
                pluginKey+'_temporary_deactivation',
                pluginKey+'_premium_version',
            ];

            function deactivateProcess(reason, reasonCode) {
                if (!reasonCode) {
                    reasonCode = $('.'+pluginKey+'_deactivation_reason:checked').data('reason-code');
                }
                try {
                    $.ajax({
                        url: apiUrl,
                        type: 'POST',
                        data: Object.assign(apiData, {
                            reason,
                            reasonCode,
                            pluginKey,
                        }),
                        beforeSend: function() {
                            $('#'+pluginKey+'-feedback-modal-loading').show();
                            $('#'+pluginKey+'-feedback-modal-question').hide();
                            $('#'+pluginKey+'-feedback-modal-body').hide();
                            $('#'+pluginKey+'-feedback-modal-footer').hide();
                        },
                        success: function() {
                            window.location.href = deactivationUrl;
                        },
                        error: function(error) {
                            $('#'+pluginKey+'-feedback-modal').css('display', 'none');
                            window.location.href = deactivationUrl;
                        }
                    });
                } catch (error) {
                    console.error(error)
                }
            }
            
            $(document).on('click', '.'+pluginKey+'-feedback-skip', function(e) {
                e.preventDefault();
                deactivateProcess("I rather wouldn't say", 'rather-wouldnt-say');
            });

            $(document).on('click', '.'+pluginKey+'-feedback-button-deactivate', function(e) {
                e.preventDefault();
                var reason = $('.'+pluginKey+'_deactivation_reason:checked').val();
                var detail = $('#'+pluginKey+'_deactivation_reason_detail').val();

                if (!reason) {
                    alert('Please select a reason!');
                    return false;
                }

                if (!detail && !excludedForReasonBox.includes($('.'+pluginKey+'_deactivation_reason:checked').attr('id'))) {
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
                if (excludedForReasonBox.includes($(this).attr('id'))) {
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
        });
    })
})(jQuery);
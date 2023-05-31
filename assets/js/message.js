(($) => {
    $(document).ready(() => {
        var deactivationUrl = '';
        window.onload = function(){
            $(document).on('click', '#deactivate-cryptopay-wc-lite', function(e){
                e.preventDefault()
                deactivationUrl =  $(this).attr('href');
                $('.bp-feedback-modal').css('display', 'flex');
            });
        }

        $(document).on('click', '.bp-feedback-button-deactivate', function(e){
            e.preventDefault();
            var reason = $('.bp_cp_lite_deactivation_reason:checked').val();

            if (!reason) {
                alert('Please select a reason!');
                return false;
            }

            if (reason == 'other') {
                reason = $('#bp_cp_lite_deactivation_reason_input').val();
            }
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'bpFeedbackApi',
                    reason: reason,
                },
                beforeSend: function() {
                    $('.bp-feedback-modal-loading').show();
                    $('.bp-feedback-modal-question').hide();
                    $('.bp-feedback-modal-body').hide();
                    $('.bp-feedback-modal-footer').hide();
                },
                success: function() {
                    $('.bp-feedback-modal').css('display', 'none');
                    window.location.href = deactivationUrl;
                },
                error: function(error) {
                    alert(error);
                }
            });
        });

        $(document).on('click', '.bp-feedback-button-cancel', function(e){
            e.preventDefault();
            $('.bp-feedback-modal').css('display', 'none');
        });

        $(document).on('change', '.bp_cp_lite_deactivation_reason', function(e){
            e.preventDefault()
            var value = $(this).attr('id');
            if (value == 'bp_cp_lite_other') {
                $('.bp-feedback-modal-reason-input').show();
            } else {
                $('.bp-feedback-modal-reason-input').hide();
            }
        });
    })
})(jQuery)
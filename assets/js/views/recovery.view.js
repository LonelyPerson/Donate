$(document).ready(function() {
    loadPage('recovery', 'recovery', function() {
        if ($('#captcha').length) {
            var captchaKey = $('#captcha').attr('data-key');
            loadCaptcha(captchaKey);
        }

        $('#recovery-form input[name="save"]').on('click', function() {
            blockScreen();

            var data = $('#recovery-form').serialize();

            $.post(route('/recovery'), data, function(response) {
                console.log(response);

                if (response.hasOwnProperty('content')) {
                    if ($('#captcha').length) {
                        Recaptcha.reload();
                    }

                    $.post(route('/user/token/reload'), { form: 'recovery' }, function(response) {
                       $('input[name="token"]').val(response.token);
                    });
                    
                    $("#response").html(formatMessage(response.content, response.type));

                    if (response.type == 'success')
                        $('#recovery-form input[name="recovery_input"]').val('');

                    unblockScreen();
                }
            });
        });
    });
});

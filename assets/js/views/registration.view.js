$(document).ready(function() {
    loadPage('registration', 'registration', function() {
        if ($('#captcha').length) {
            var captchaKey = $('#captcha').attr('data-key');
            loadCaptcha(captchaKey);
        }

        $('#registration-form input[name="register"]').on('click', function() {
            blockScreen();

            var $this = $('#registration-form');

            var data = $this.serialize();
            $.post(route('/registration'), data, function(response) {
                if (response.hasOwnProperty('content')) {
                    if ($('#captcha').length) {
                        Recaptcha.reload();
                    }

                    $.post(route('/user/token/reload'), { form: 'registration' }, function(response) {
                       $('input[name="token"]').val(response.token);
                    });

                    $("#response").html(formatMessage(response.content, response.type));
                }

                if (response.hasOwnProperty('success')) {
                    $this.find('input[name="username"]').val('');
                    $this.find('input[name="password"]').val('');
                }

                unblockScreen();
            });
        });
    });
});

$(document).ready(function() {
    loadPage('login', 'login', function() {
        if ($('#captcha').length) {
            var captchaKey = $('#captcha').attr('data-key');
            loadCaptcha(captchaKey);
        }

        $('#login-form input[name="auth"]').on('click', function() {
            blockScreen();

            var $this = $('#login-form');

            var data = $this.serialize();

            $.post(route('/login'), data, function(response) {
                console.log(response);

                if (response.hasOwnProperty('view')) {
                    loadView(response.view);
                } else {
                    if ($('#captcha').length) {
                        Recaptcha.reload();
                    }

                    $this.find('input[name="username"]').val('');
                    $this.find('input[name="password"]').val('');

                    $("#response").html(formatMessage(response.content, response.type));

                    unblockScreen();
                }
            });
        });
    });
});

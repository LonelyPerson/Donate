$(document).ready(function() {
    loadPage('settings', 'config', function() {
        $('#settings-form input[name="save"]').on('click', function() {
            blockScreen();

            var data = $('#settings-form').serialize();

            $.post('index.php', data, function(response) {
                if (response.hasOwnProperty('content')) {
                    $("#response").html(formatMessage(response.content, response.type));

                    $('#settings-form input[name="new_password"]').val('');
                    $('#settings-form input[name="old_password"]').val('');

                    if (response.hasOwnProperty('verify-email')) {
                        $('#settings-form .email-group').html(response.email_form);

                        $('[data-toggle="tooltip"]').tooltip();
                    }

                    unblockScreen();
                }
            });
        });
    });
});

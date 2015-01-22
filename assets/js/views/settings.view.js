$(document).ready(function() {
    loadPage('settings', '_settings', function() {
        $('#settings-form input[name="save"]').on('click', function() {
            blockScreen();

            var data = $('#settings-form').serialize();

            $.post('ajax.php', data, function(response) {
                if (response.hasOwnProperty('content')) {
                    $("#response").html(formatMessage(response.content, response.type));

                    unblockScreen();
                }
            });
        });
    });
});
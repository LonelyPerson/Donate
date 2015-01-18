$(document).ready(function() {
    loadPage('balance', 'balance', function() {
        $('#jas-paypal-form input[name="jas_paypal_submit"]').on('click', function() {
            blockScreen();
            
            var data = $('#jas-paypal-form').serialize();
            
            $.post('ajax.php', data, function(response) {
                if (response.hasOwnProperty('redirect')) {
                    window.location = response.redirect;
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                    unblockScreen();
                }
            });
        });
        
        $('#jas-mokejimai-form input[name="jas_mokejimai_submit"]').on('click', function() {
            blockScreen();
            
            var data = $('#jas-mokejimai-form').serialize();

            $.post('ajax.php', data, function(response) {
                if (response.hasOwnProperty('submit')) {
                    $('#jas-mokejimai-form input[name="sign"]').val(response.sign);
                    $('#jas-mokejimai-form input[name="data"]').val(response.data);
                    
                    $('#jas-mokejimai-form').submit();
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                    
                    unblockScreen();
                }
            });
        });
    });
});
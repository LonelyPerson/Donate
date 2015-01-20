$(document).ready(function() {
    loadPage('balance', 'balance', function() {
        $('#jas-paypal-form input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('#jas-paypal-form .paypal-price').attr('data-min');
            var max = $('#jas-paypal-form .paypal-price').attr('data-max');
            var price = parseFloat($('#jas-paypal-form .paypal-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                $('#jas-paypal-form .paypal-price span').text(_price);
            } else {
                $('#jas-paypal-form .paypal-price span').text('0.00');
            }
        });

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

        $('#jas-mokejimai-form input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('#jas-mokejimai-form .mokejimai-price').attr('data-min');
            var max = $('#jas-mokejimai-form .mokejimai-price').attr('data-max');
            var price = parseFloat($('#jas-mokejimai-form .mokejimai-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                $('#jas-mokejimai-form .mokejimai-price span').text(_price);
            } else {
                $('#jas-mokejimai-form .mokejimai-price span').text('0.00');
            }
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
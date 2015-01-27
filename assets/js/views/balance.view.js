$(document).ready(function() {
    loadPage('balance', 'balance', function() {
        $('#jas-paypal-form input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('#jas-paypal-form .paypal-price').attr('data-min');
            var max = $('#jas-paypal-form .paypal-price').attr('data-max');
            var price = parseFloat($('#jas-paypal-form .paypal-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
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

        // mokejimai
        $('#jas-mokejimai-form input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('#jas-mokejimai-form .mokejimai-price').attr('data-min');
            var max = $('#jas-mokejimai-form .mokejimai-price').attr('data-max');
            var price = parseFloat($('#jas-mokejimai-form .mokejimai-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
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

        // paygol
        $('#jas-paygol-form input[name="pg_price"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('#jas-paygol-form .paygol-price').attr('data-min');
            var max = $('#jas-paygol-form .paygol-price').attr('data-max');
            var price = parseFloat($('#jas-paygol-form .paygol-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
                $('#jas-paygol-form .paygol-price span').text(_price);
            } else {
                $('#jas-paygol-form .paygol-price span').text('0.00');
            }
        });

        $('#jas-paygol-form input[name="jas_paygol_submit"]').on('click', function() {
            blockScreen();
            
            var data = $('#jas-paygol-form').serialize();

            $.post('ajax.php', data, function(response) {
                if (response.hasOwnProperty('submit')) {
                    $('#jas-paygol-form').submit();
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                    
                    unblockScreen();
                }
            });
        });

        // sms
        $('.sms-flags li').on('click', function() {
            blockScreen();

            $('.sms-flags li').removeClass('active');
            $(this).addClass('active');

            var code = $(this).attr('data-code');

            $.post('ajax.php', { get_sms_data: 'ok', 'code': code }, function(response) {
                if (response.hasOwnProperty('success')) {
                    $('#sms').html(response.table);

                    unblockScreen();
                }
            });
        });
    });
});
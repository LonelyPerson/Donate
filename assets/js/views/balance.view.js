$(document).ready(function() {
    loadPage('balance', 'balance', function() {
        $('.tab-content #paypal input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('.tab-content #paypal .paypal-price').attr('data-min');
            var max = $('.tab-content #paypal .paypal-price').attr('data-max');
            var price = parseFloat($('.tab-content #paypal .paypal-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
                $('.tab-content #paypal .paypal-price span').text(_price);
            } else {
                $('.tab-content #paypal .paypal-price span').text('0.00');
            }
        });

        $('#jas-paypal-form input[name="jas_paypal_submit"]').on('click', function() {
            blockScreen();

            var data = $('#jas-paypal-form').serialize();

            $.post('index.php', data, function(response) {
                if (response.hasOwnProperty('redirect')) {
                    window.location = response.redirect;
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                    unblockScreen();
                }
            });
        });

        // mokejimai
        $('.tab-content #paysera input[name="sum"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('.tab-content #paysera .mokejimai-price').attr('data-min');
            var max = $('.tab-content #paysera .mokejimai-price').attr('data-max');
            var price = parseFloat($('.tab-content #paysera .mokejimai-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
                $('.tab-content #paysera .mokejimai-price span').text(_price);
            } else {
                $('.tab-content #paysera .mokejimai-price span').text('0.00');
            }
        });

        $('#jas-mokejimai-form input[name="jas_mokejimai_submit"]').on('click', function() {
            blockScreen();

            var data = $('#jas-mokejimai-form').serialize();

            $.post('index.php', data, function(response) {
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
        $('.tab-content #paygol input[name="pg_price"]').on('keyup', function() {
            var value = parseFloat($(this).val());
            var min = $('.tab-content #paygol .paygol-price').attr('data-min');
            var max = $('.tab-content #paygol .paygol-price').attr('data-max');
            var price = parseFloat($('.tab-content #paygol .paygol-price').attr('data-price'));

            if ($.isNumeric(value) && value <= max && value >= min) {
                var _price = parseFloat(value * price);
                _price = _price.toFixed(2);
                $('.tab-content #paygol .paygol-price span').text(_price);
            } else {
                $('.tab-content #paygol .paygol-price span').text('0.00');
            }
        });

        $('#jas-paygol-form input[name="jas_paygol_submit"]').on('click', function() {
            blockScreen();

            var data = $('#jas-paygol-form').serialize();

            $.post('index.php', data, function(response) {
                if (response.hasOwnProperty('submit')) {
                    $('#jas-paygol-form').submit();
                } else {
                    $("#response").html(formatMessage(response.content, response.type));

                    unblockScreen();
                }
            });
        });

        // sms
        $('[data-toggle="tooltip"]').tooltip();
        $('.sms-flags li').on('click', function() {
            blockScreen();

            $('.sms-flags li').removeClass('active');
            $(this).addClass('active');

            var code = $(this).attr('data-code');

            $.post('index.php', { get_sms_data: 'ok', 'code': code }, function(response) {
                if (response.hasOwnProperty('success')) {
                    $('#sms').html(response.table);

                    unblockScreen();
                }
            });
        });
    });
});

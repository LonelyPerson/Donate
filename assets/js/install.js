$(document).ready(function() {
    var step = 0;

    $('#check').on('click', function() {
        $('.check').show();
        $(this).hide();

        var chmod_status = parseInt($('.check .chmod-status').text());
        var db_status = parseInt($('.check .db-status').text());

        if (db_status != 1) {
            $('.check .db-connection .no').hide();
            $('.check .db-connection .loader').show();
            $('.check .db-status').text('0');
            check_db();
        }

        if (chmod_status != 1) {
            $('.check .storage-chmod .no').hide();
            $('.check .storage-chmod .loader').show();
            $('.check .chmod-status').text('0');
            check_chmod();
        }

        var i = setInterval(function() {
            var chmod_status = parseInt($('.check .chmod-status').text());
            var db_status = parseInt($('.check .db-status').text());

            if (chmod_status != 0 && db_status != 0) {
                if (chmod_status == 1 && db_status == 1) {
                    $('.nav-buttons').show();
                } else {
                    $('#check').show();
                }

                clearInterval(i);
            }
        }, 500);
    });

    $('#install').on('click', function() {
        $(this).hide();
        $('.install-progress').show();

        $.post(route('/setup/start'), {}, function(response) {
            console.log(response);

            if (response.status == 'success') {
                $('#end').show();
                $('.install-progress .wait').hide();
                $('.install-progress .success').show();
            }
        });
    });
});

function check_db() {
    $.ajax({
        method: "POST",
        url: route('/setup/check/mysql'),
        data: { check_mysql_data: true },
        success: function(response) {
            if (response.status == 'error') {
                $('.check .db-connection .loader').fadeOut(function() {
                    $('.check .db-connection .no').fadeIn();
                    $('.check .db-status').text('2');
                });
            } else {
                $('.check .db-connection .loader').fadeOut(function() {
                    $('.check .db-connection .yes').fadeIn();
                    $('.check .db-status').text('1');
                });
            }
        }
    });
}

function check_chmod() {
    $.ajax({
        method: "POST",
        url: route('/setup/check/chmod'),
        data: { check_chmod: true },
        success: function(response) {
            if (response.status == 'error') {
                $('.check .storage-chmod .loader').fadeOut(function() {
                    $('.check .storage-chmod .no').fadeIn();
                    $('.check .chmod-status').text('2');
                });
            } else {
                $('.check .storage-chmod .loader').fadeOut(function() {
                    $('.check .storage-chmod .yes').fadeIn();
                    $('.check .chmod-status').text('1');
                });
            }
        }
    });
}

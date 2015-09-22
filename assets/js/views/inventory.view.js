$(document).ready(function() {
    loadPage('inventory', 'inventory', function() {
        checkIsOnline();

        $('.jas-delete').click(function() {
            blockScreen();

            var ownerId = $(this).attr('data-owner-id');
            var objectId = $(this).attr('data-object-id');
            var count = $('.item-modal input[name="count-' + objectId + '"]').val();

            $.post(route('/inventory/delete'), { owner_id: ownerId, object_id: objectId, count: count }, function(response) {
                console.log(response);

                location.reload(true);
            });
        });

        // Full featured example
        $("[data-toggle='confirmation']").popConfirm({
            placement: "top" // (top, right, bottom, left)
        });
        /*$('#login-form input[name="auth"]').on('click', function() {
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
        });*/

        $('.pagination li a').on('click', function() {
            if ($(this).parent().hasClass('active')) return;

            var page = parseInt($(this).attr('data-page'));
            var perPage = parseInt(gVar['inventory-pagination']);
            var start = 0;
            if (page != 1) {
                start = (page - 1) * perPage;
            }
            var end = parseInt(start + perPage - 1);

            $('.inventory .p').not(':hidden').stop(true, true).fadeOut('fast', function() {
                $(this).hide();
                for(i=start;i<=end;i++) {
                    $('.inventory .p#' + i).fadeIn('fast');
                }
            });

            $('.pagination li.active').removeClass('active');
            $(this).parent().addClass('active');
        });
    });
});

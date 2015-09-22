$(document).ready(function() {
    loadPage('shop', 'shop', function() {
        $('.item-modal .jas-buy').on('click', function() {
            var id = $(this).attr('data-id');

            $(".response-c").hide();

            if (gVar['buy-confirm'])
                $('#buy-confirm-modal').modal('hide');

            blockScreen();

            var $this = $(this);
            var item_id = $(this).attr('data-item-id');
            var quantity = $(this).attr('data-quantity');
            var price = $(this).attr('data-price');
            var title = $(this).attr('data-title');
            var stackable = $(this).attr('data-stackable');
            var is_group = $(this).attr('data-is-group');
            var group_id = $(this).attr('data-group-id');

            $.post(route('/shop/buy'), { buy: true, item_id: item_id, is_group: is_group, group_id: group_id, quantity: quantity, price: price, stackable: stackable, title: title }, function(response) {
                if (response.hasOwnProperty('content')) {
                    var new_balance = response.balance;
                    $('.nav li#balance span').html(new_balance.toFixed(2) + ' DC');
                    $("#response-" + id).html(formatMessage(response.content, response.type)).show();

                    $('#buy-confirm-modal-' + id).modal('hide');

                    unblockScreen();
                }
            });
        });

        $('.pagination li a').on('click', function() {
            if ($(this).parent().hasClass('active')) return;

            var page = parseInt($(this).attr('data-page'));
            var perPage = parseInt(gVar['shop-pagination']);
            var start = 0;
            if (page != 1) {
                start = (page - 1) * perPage;
            }
            var end = parseInt(start + perPage - 1);

            $('.shop .p').not(':hidden').stop(true, true).fadeOut('fast', function() {
                $(this).hide();
                for(i=start;i<=end;i++) {
                    $('.shop .p#' + i).fadeIn('fast');
                }
            });

            $('.pagination li.active').removeClass('active');
            $(this).parent().addClass('active');
        });
    });
});

$(document).ready(function() {
    loadPage('shop', 'shop', function() {
        $('.image-link').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300 // don't foget to change the duration also in CSS
            }
        });

        $('[data-toggle="tooltip"]').tooltip({
            html: true
        });

        if (gVar['buy-confirm']) {
            $('.item-box').on('click', function() {
                $('#buy-confirm-modal').modal('show');
            });
        }

        $(document).on('click', '.jas-buy', function() {
            if (gVar['buy-confirm'])
                $('#buy-confirm-modal').modal('hide');
            blockScreen();

            var $this = $(this);
            var data = $this.attr('data-item');

            $.post('ajax.php', { buy: true, item_data: data }, function(response) {
                if (response.hasOwnProperty('content')) {
                    $('.nav li#balance span').html(response.balance);
                    $("#response").html(formatMessage(response.content, response.type));

                    unblockScreen();
                }
            });
        });

        $('.overlay .title').center('.item-box');
        
        $('.item-box').on('mouseenter', function() {
            $(this).find('.overlay').stop(true).fadeIn('fast');
        }).on('mouseleave', function() {
            $(this).find('.overlay').stop(true).fadeOut('fast');
        });
    });
});

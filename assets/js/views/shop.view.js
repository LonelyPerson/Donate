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
        
        $('.jas-buy').on('click', function() {
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
    });
});
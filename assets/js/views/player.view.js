$(document).ready(function() {
    loadPage('player', 'player', function() {
        // change name
        $(document).on('click', 'input[name="jas_change_name_submit"]', function() {
            blockScreen();

            var index = $('.player .nav li.active').index();
            var data = $('#jas-name-change-form').serialize();

            $.post(route('/player/change-name'), data, function(response) {
                if (response.hasOwnProperty('success')) {
                    loadPage(response.view, response.view, function() {
                        $("#response").html(formatMessage(response.content, response.type));

                        setActiveTab('.player', index);
                    });
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                }

                unblockScreen();
            });
        });

        // unstuck
        $(document).on('click', 'input[name="jas_unstuck_submit"]', function() {
            blockScreen();

            var index = $('.player .nav li.active').index();
            var data = $('#jas-unstuck-form').serialize();

            $.post(route('/player/unstuck'), data, function(response) {
                if (response.hasOwnProperty('success')) {
                    loadPage(response.view, response.view, function() {
                        $("#response").html(formatMessage(response.content, response.type));

                        setActiveTab('.player', index);
                    });
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                }

                unblockScreen();
            });
        });

        // change level
        $('input[name="jas_level_submit"]').on('click', function() {
            blockScreen();

            var index = $('.player .nav li.active').index();
            var data = $('#jas-level-form').serialize();

            $.post(route('/player/level'), data, function(response) {
                if (response.hasOwnProperty('success')) {
                    loadPage(response.view, response.view, function() {
                        $("#response").html(formatMessage(response.content, response.type));

                        setActiveTab('.player', index);
                    });
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                }

                unblockScreen();
            });
        });
    });
});

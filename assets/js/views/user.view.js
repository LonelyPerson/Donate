$(document).ready(function() {
    loadPage('user', 'user', function() {
        $('.select-char').on('click', function() {
            var $this = $(this);
            var $parent = $this.parent();
            var name = $this.text();

            if ($this.hasClass('selected')) return;

            $.post(route('/user/character/select'), { select_character: true, character_name: name }, function(response) {
                if (response.success) {
                    loadView(response.view);
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                }
            });
        });
    });
});

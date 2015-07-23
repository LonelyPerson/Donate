$(document).ready(function() {
    loadPage('user', 'user', function() {
        $('.select-char').on('click', function() {
            var $this = $(this);
            var $parent = $this.parent();
            var name = $this.text();

            if ($this.hasClass('selected')) return;

            $.post('index.php', { select_character: true, character_name: name }, function(response) {
                if (response.success) {
                    $('#selected-char').html(name);

                    $('.select-char.selected').removeClass('selected');
                    $this.addClass('selected');

                    $("#response").html(formatMessage(response.content, response.type));
                } else {
                    $("#response").html(formatMessage(response.content, response.type));
                }
            });
        });
    });
});

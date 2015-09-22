$(document).ready(function() {
	// users
	$('.show-characters').on('click', function() {
		var account = $(this).attr('data-id');

		if ( ! $('#' + account).hasClass('active')) {
			$('#' + account).fadeIn('normal');
			$('#' + account).addClass('active');
			$(this).text('Hide characters');
		} else {
			$('#' + account).fadeOut('normal');
			$('#' + account).removeClass('active');
			$(this).text('Show characters');
		}
	});

	// translations
	$('.select-translation').on('click', function() {
		var language = $('select[name="language"] option:selected').val();
		
		if (language != 0)
			window.location.href = route('/admin/translation/' + language);
	});
	$('.delete-language').on('click', function() {
		var language = $('select[name="language"] option:selected').val();
		
		if (language != 0)
			window.location.href = route('/admin/translation/delete/' + language);
	});

	// shop
	// group items
	$(document).on('click', 'a.add', function() {
		var clone = $('.items-group-base').html();
		clone = "<div class='items-group'>" + clone + "</div>";
		
		$('.items-group').last().after(clone);
	});
	$(document).on('click', '.items-group .minus', function() {
		$(this).parent().remove();
	});
});
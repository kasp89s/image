$(document).ready( function(){
	$('.comment-reply-form').on(
        'submit',
        function (event) {
            event.preventDefault();
			if (ajaxProcessing === true)
				return;

            var form = $(this);
			ajaxProcessing = true;
            $.post(
                form.attr('action'),
                form.serialize(),
                function (response) {
					ajaxProcessing = false;
					if (typeof(response.message) !== 'undefined') {
						form.find('.error-txt').text(response.message).show();
					} else {
						form.find('.error-txt').empty().hide();
						location.reload();
					}
                },
                'json'
            ).fail(function() {
				ajaxProcessing = false;
			});
        }
    );
});
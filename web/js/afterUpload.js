var optional = $('.add-optional');
	function unsetTag(tag) {
		$(tag).remove();
	}
	function useTag(tag, type) {
		var tag = tag;
		if (type == 'select') {
			var tag = $(tag).find('h6').text();
		}

		$('.add-tag').find('p').find('[data-tag="'+tag+'"]').remove();
		$('.add-tag').find('p').append('<span data-tag="'+tag+'" onclick="unsetTag(this)">'+tag+'</span> ');
		optional.removeClass('open');
		optional.next('.selected-optional').slideUp(100);
		optional.find('.scroll-pane').find('ul').empty();
		$('#input-tags').val('');
	}
$(document).ready( function(){
	$('#input-tags').on(
		'keyup',
		function (event) {
			if (ajaxProcessing === true)
				return;
			
			if (event.keyCode == 32 || event.keyCode == 188 || event.keyCode == 190) {
				useTag($(this).val(), 'set');
			}
			if ($(this).val().length < 2) {
				if (optional.is('.open')){
					optional.removeClass('open');
					optional.next('.selected-optional').slideUp(100);
				}
				return;
			}

			ajaxProcessing = true;
		    $.post(
                '/ajax/find-tags',
                {
					title: $(this).val(),
					_csrf: $('[name="_csrf"]').val(),
				},
                function (response) {
					ajaxProcessing = false;
					var ul = optional.find('.scroll-pane').find('ul');
					ul.empty();
					for (var i in response) {
						ul.append(
							'<li onclick="useTag(this, \'select\')"><h6>'+response[i].title+'</h6><p>'+response[i].count+' images</p></li>'
						);
					}
					optional.addClass('open');
					optional.next('.selected-optional').slideDown(100);
					$(document).bind('click', function(e) {
						var $clicked = $(e.target);
						if (! $clicked.parents().hasClass("add-optional")){
							$(".add-optional").removeClass("open").next('.selected-optional').slideUp(100);
						}
					});
                },
                'json'
            ).fail(function() {
				ajaxProcessing = false;
				if(optional.is('.open')){
					optional.removeClass('open');
					optional.next('.selected-optional').slideUp(100);
				}
			});
		}
	);
	$('.form-change-album-poop').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                form.attr('action'),
                form.serialize(),
                function (response) {
					$('#cboxClose').trigger('click');
                },
                'json'
            ).fail(function() {
				alert('Error!');
			});
        }
    );
    $('.form-edit-album').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                form.attr('action'),
                form.serialize(),
                function (response) {
                    location.href='/' + response.url;
                },
                'json'
            ).fail(function() {
				alert('Error!');
			});
        }
    );
    $('.form-share-album').on(
        'submit',
        function (event) {
            event.preventDefault();
			if (ajaxProcessing === true)
				return;
			
            var form = $(this);
			var tags = $('.add-tag').find('p').find('span');
			var formData = new FormData;

			$.each(form.serializeArray(), function (key, value){
				formData.append(value.name, value.value);
			});

			$.each(tags, function (key, value){
				formData.append('UserPost[tags][]', $(value).text());
			});

			$.ajax({
				url: form.attr('action'),
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (response)
				{
					ajaxProcessing = false;
					if (typeof (response.post) !== 'undefined') {
						location.href = '/' + response.post
					}

					$('.error-txt').hide();
					$.each(response, function (key, value){
						success = false;
						$('.'+key).text(value).show();
					});
				}
			});
        }
    );
    $('.rearrange-save').on(
        'click',
        function () {
			var ul_sortable = $('#sortable');
			var sortable_data = ul_sortable.sortable('serialize');
			
			$.post(
				'/rearrange',
				sortable_data + '&_csrf=' + $('[name="_csrf"]').val(),
				function (response) {
					//location.reload();
				},
				'json'
			).fail(function() {
					alert('Error!');
			});	
        }
    );
    $('.yes-remove').on(
        'click',
        function () {
			$('.form-remove-album').submit();
        }
    );
});

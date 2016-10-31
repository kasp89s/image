var selectedElements = [];

var obj = $(".upload-right");
obj.on('dragenter', function (e)
{
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '2px solid #626262;');
});
obj.on('dragover', function (e)
{
    e.stopPropagation();
    e.preventDefault();
});
obj.on('drop', function (e)
{

    $(this).css('border', '2px dotted #626262');
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;

    //We need to send dropped files to Server
    dragAndDropUploadProfile(files);
});

function dragAndDropUploadProfile(files) {

    for (var i = 0; i < files.length; i++)
    {
        var file = files[i];
        uploadItemsCount++;
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (evt) {
        };
        reader.onerror = function (evt) {
        };
        // Добавляет значение файла.
        uploadQueue.push(file);
    }

    startUploadProfile();
}

function toDataUrlProfile(url) {
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function () {
        var reader = new FileReader(),
            dataURLtoBlob = function (dataurl) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], {type: mime});
            };
        reader.readAsDataURL(xhr.response);
        reader.onload = function (evt) {
            formData = [];
            uploadItemsCount = 1;
            var file = dataURLtoBlob(evt.target.result);
            file.name = url.substring(url.lastIndexOf('/') + 1, url.length);
            uploadQueue.push(file);

            startUploadProfile();
        };
//        reader.onerror = function (evt) {
//            $('.error-image').show();
//        };
    };
    xhr.open('GET', url);
    xhr.send();
}

function profileUpload(name)
{
    $.each($('#' + name)[0].files, function (i, file) {
        uploadItemsCount++;
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (evt) {

//            $('.upload-image-list').append('<li>' +
//                '<figure><img src="'+evt.target.result+'" alt="" /></figure>' +
//                '<div class="txt-image">'+file.name+'</div><a href="javascript:void(0)" onclick="removeUploadPhoto(\''+ name +'\', \''+file.name+'\', this)" class="off"></a>' +
//                '</li>');
        }
        reader.onerror = function (evt) {
            alert(evt)
        }
        // Добавляет значение файла.
        uploadQueue.push(file);
    });

    startUploadProfile();
}

function startUploadProfile()
{
    uploadInterval = setInterval(
        function () {
            console.log(uploadQueue);
            if (uploadQueue.length == 0) {
                $('.load-all').hide();
                clearInterval(uploadInterval);
                $('.load-image-main').text('');
                  window.location.href = '/profile/image';
            }

            if (uploaded === true)
                return;

            uploaded = true;
            $('.load-all').show();
            for (var i in uploadQueue) {
                uploadFileProfile(i, uploadQueue[i]);
                console.log(i + ' => ' + uploadQueue[i].name);
                break;
            }
        },
        1000
    );
}

function uploadFileProfile(key, file)
{
    var data = new FormData;
    data.append('_csrf', $('[name="_csrf"]').val());
    data.append('resize', $('[name="resize"]').val());
    data.append('albumId', $('#s3 > a > h4').data('id'));
    data.append('image', file, file.name);
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (evt) {
//        $('.load-image-main').find('p').text(file.name);
        $('.load-image-main').html(
            '<p>' + (file.name.length > 35) ? file.name.slice(0, 32) + '.' + file.name.split(".").pop() : file.name + '</p>'+
            '<span class="load-all"><i style="width:0%" data-percent="0"></i></span>'
        );
        var indicator = $('.load-all').find('i');
        indicator.data('percent', 0);
        var percentInterval = setInterval(
            function () {
                var percent = parseInt(indicator.data('percent'));

                indicator.attr('style', 'width:'+ parseInt(percent + 1) +'%');
                indicator.data('percent', parseInt(percent + 1));
                if (percent >= 100)
                    clearInterval(percentInterval);
            },
            5
        );
        $.ajax({
            url: '/ajax/upload',
            type: 'post',
            data: data,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response)
            {
                uploadQueue.shift();
                uploaded =  false;
            }
        });
    }
}

$(document).ready( function(){
	$('.about-message').on(
		'change',
		function(){
			$.post(
                '/ajax/change-about',
                {
					about: $(this).val(),
					_csrf: $('[name="_csrf"]').val()
				},
                function (response) {
                },
                'json'
            );
		}
	);

    $('.image-link').click(
        function () {
            updateLinkSpace();
            $('.link-space').show();
            $('.remove-elements').hide();
        }
    );
    $('.image-remove').click(
        function () {
            $('.link-space').hide();
            updateRemoveSpace();
        }
    );
    $('.apply-remove').click(
        function () {
            removeElements();
        }
    );
    $('.copy-link-btn').click(
        function () {
            setTimeout(function (){
                $('.link-span').empty();
                $('.link-space').hide();
            }, 500)
        }
    );

    $('#s1 .all-images-select ul li').click(function () {
        var element = $(this).find('h6');
        $('#s1 h4').html(element.html()).data('id', element.data('id'));
        $('#album-id').val(element.data('id'));
        $('.sorting-form').submit();
        //reloadImages();
    });

    $('#s2 .all-images-select ul li').click(function(){
        var element = $(this).find('h6');
        $('#s2 h4').html(element.html()).data('id', element.data('id'));
        moveImages();
    });

    $('#s3 .all-images-select ul li').click(function(){
        var element = $(this).find('h6');
        $('#s3 h4').html(element.html()).data('id', element.data('id'));
    });
    $('[name="order_value"]').on('change', function () {
        $('.sorting-form').submit();
    });
    $('[name="order_type"]').on('change', function () {
        $('.sorting-form').submit();
    });

    /*
     * jQuery UI Sortable setup
     */
    $('.sortable-album').sortable({
        update: function( ) {
            var sortable_data = $('.sortable-album').sortable('serialize');

            $.post(
                '/ajax/sort-albums',
                sortable_data + '&_csrf=' + $('[name="_csrf"]').val(),
                function (response) {
                    //location.reload();
                },
                'json'
            );
        }
    });

    $('.sortable-album').disableSelection();

    $('.main-section-form').sortable({
        update: function( ) {
            var sortable_data = $(this).sortable('serialize');

            $.post(
                '/ajax/sort-images',
                sortable_data + '&_csrf=' + $('[name="_csrf"]').val(),
                function (response) {
                    //location.reload();
                },
                'json'
            );
        }
    });
    $(".main-section-form").disableSelection();

    $('.radio-sorting > label > input').on(
        'click',
        function () {
            var element = $(this),
                form = element.closest('.radio-sorting').closest('form');

            form.find('.box').removeClass('visible').hide();
            form.find('.' + element.val()).addClass('visible').show();
            console.log(element.val());
            console.log(form.find('.box'));
        }
    )
});

$('.album-main-photo').on('click', 'i', function () {
    if ($(this).closest('figure').hasClass('active')) {
        selectPhoto($(this).closest('figure'), true);
    } else {
        selectPhoto($(this).closest('figure'), false);
    }
});

$('.form-new-album, .form-change-album').on(
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
                $('.error-txt').empty();

                if (typeof (response[0]) !== 'undefined' && typeof (response[0].title) !== 'undefined') {
                    $('.error-txt.title').text(response[0].title).show();
                } else {
                    location.reload();
                }

                //$('#cboxClose').trigger('click');
            },
            'json'
        ).fail(function() {});
    }
);

$('.change-album').on(
    'click',
    function () {
        var albumData = $(this).closest('.reg-sorting-image'),
            modal = $('#change-album-modal'),
            href = albumData.find('a').attr('href');
        modal.find('[name="UserAlbum[id]"]').val(albumData.data('id'));
        modal.find('[name="UserAlbum[title]"]').val(albumData.data('title'));
        modal.find('[name="UserAlbum[description]"]').val(albumData.data('description'));
        modal.find('[value="'+albumData.data('type')+'"]').prop("checked", true);
        modal.find('.album-type-link').attr('href', href).text(href);
        modal.find('.box').hide();
        modal.find('.box.' + albumData.data('type')).show();
        $('[href="#change-album-modal"]').trigger('click');
//        if (ajaxProcessing === true)
//            return;
//
//        ajaxProcessing = true;
//        $.post(
//            'ajax/get-album-info',
//            {_csrf: $('[name="_csrf"]').val()},
//            function (response) {
//                ajaxProcessing = false;
//
//                //$('#cboxClose').trigger('click');
//            },
//            'json'
//        ).fail(function() {});
    }
);

function reloadImages()
{
    var element = $('#s1 h4');
    $.post(
        '/ajax/render-album-images',
        {
            _csrf: $('[name="_csrf"]').val(),
            albumId: element.data('id'),
            order_value: $('[name="order_value"]').val(),
            order_type: $('[name="order_type"]').val()
        },
        function (response) {
            if (response.html != null) {
                $('.album-main-photo').html(response.html);
            }
            selectedElements = [];
            updateElementWorkSpace();
        },
        'json'
    );
}

function moveImages()
{
    var selected = $('#s1 h4'),
        destination = $('#s2 h4'),
        data = new FormData;

//    console.log(selected.data('id'));
//    console.log(destination.data('id'));
//    console.log(selectedElements);
    if (ajaxProcessing === true)
        return;

    data.append('_csrf', $('[name="_csrf"]').val());
    data.append('albumId', destination.data('id'));
    for (var i in selectedElements) {
        data.append('images[]', selectedElements[i].data('id'));
    }

    ajaxProcessing = true;
    $.ajax({
        url: '/ajax/move-images',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response)
        {
            ajaxProcessing = false;
            if (response.error != null) {
                alert(response.error);
            } else {
                location.reload();
            }

        },
        error: function ()
        {
            ajaxProcessing = false;
            location.reload();
        }
    });
}

function selectPhoto(element, remove)
{
    if (remove == false) {
        selectedElements.push(element);
        element.closest('figure').addClass('active');
        $('.carousel.carousel-navigation > ul').append(
            '<li>' +
            '<img src="' + element.find('a > img').attr('src') + '" data-original="'+ element.data('href') +'" class="sl-prev" />' +
            '</li>'
        );
        $('.carousel.carousel-stage > ul').append(
            '<li style="width: 998px">' +
            '<img src="'+ element.data('href') +'" width="100%" class="sl-original"/>' +
            '</li>'
        );
    } else {
        var tmp = [];
        for (var i in selectedElements) {
            if (selectedElements[i].data('id') != element.data('id')) {
                tmp.push(selectedElements[i]);
            }
        }
        selectedElements = tmp;
        element.closest('figure').removeClass('active');
        console.log($('.sl-prev[src="' + element.find('a > img').attr('src') + '"]'));
        console.log($('.sl-original[src="' + element.data('href') + '"]'));
        $('.sl-prev[src="' + element.find('a > img').attr('src') + '"]').closest('li').remove();
        $('.sl-original[src="' + element.data('href') + '"]').closest('li').remove();
    }

    updateElementWorkSpace();
    updateLinkSpace();
}

function updateRemoveSpace()
{
    $('.remove-elements').show();
}

function updateLinkSpace()
{
    $('.link-span').empty();
    for (var i in selectedElements) {
        $('.link.link-span').append(selectedElements[i].data('url') + '\n');
        $('.direct.link-span').append(selectedElements[i].data('href') + '\n');
        $('.html-image.link-span').text($('.html-image.link-span').text() + '<img src="' + selectedElements[i].data('href') + '" /> \n');
        $('.bbcode.link-span').append('[img]' + selectedElements[i].data('href') + '[/img] \n');
        $('.linked-bbcode.link-span').append('[url=' + selectedElements[i].data('href') + '][img]' + selectedElements[i].data('href') + '[/img][/url] \n');
    }
}

function updateElementWorkSpace() {
    if (selectedElements.length > 0) {
        $('.work-space').removeClass('kick-out');
    } else {
        $('.work-space').addClass('kick-out');
        $('.link-space').hide();
        $('.remove-elements').hide();

    }

    $('.elements-count').text(selectedElements.length);
}

function removeElements()
{
    if (ajaxProcessing === true)
        return;

    var data = new FormData;
    data.append('_csrf', $('[name="_csrf"]').val());
    for (var i in selectedElements) {
        data.append('images[]', selectedElements[i].data('id'));
    }

    ajaxProcessing = true;
    $.ajax({
        url: '/ajax/remove-images',
        type: 'post',
        data: data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response)
        {
            ajaxProcessing = false;
            // Закрываем окно удаления
            $('.remove-elements').hide();
            // Удаляем картинки из поля.
            $('.album-main-photo').find('figure').each(function () {
                var element = $(this);
                if (element.hasClass('active')) {
                    selectedElements.splice(selectedElements.indexOf(element.data('id')), 1);
                    element.remove();
                }
            });
            updateElementWorkSpace();
        },
        error: function ()
        {
            ajaxProcessing = false;
            //location.reload();
        }
    });
}

function like(id, type, a) {
    var element = $(a);
	$.post(
        '/ajax/like',
        {
			id: id,
			type: type,
			_csrf: $('[name="_csrf"]').val(),
		},
        function (response) {
            if (typeof(response.guest) !== 'undefined') {
                $('[href="#sign-in"]').trigger('click');
            }
			if (typeof(response.likeCount) !== 'undefined') {
                var parent = element.closest('div.over');
                if (typeof(parent.data('count')) !== 'undefined') {
                    console.log(parent.data('count'));
                    if (parseInt(response.likeCount) > parseInt(parent.data('count'))) {
                        // Стрелка вниз горит - тушим ее.
                        if (parent.find('.link-bot').hasClass('active')) {
                            parent.find('.link-bot').removeClass('active');
                            parent.closest('.figure').removeClass('no-vote');
                            parent.closest('.figure').removeClass('active');
                        } else {
                            // Иначе врубаем вверх.
                            parent.closest('.figure').addClass('es-vote');
                            parent.closest('.figure').addClass('active');
                            parent.find('.link-top').addClass('active');
                        }
                    }

                    if (parseInt(response.likeCount) < parseInt(parent.data('count'))) {
                        // Стрелка вверх горит - тушим ее.
                        if (parent.find('.link-top').hasClass('active')) {
                            parent.find('.link-top').removeClass('active');
                            parent.closest('.figure').removeClass('es-vote');
                            parent.closest('.figure').removeClass('active');
                        } else {
                            // Иначе врубаем вниз.
                            parent.closest('.figure').addClass('no-vote');
                            parent.closest('.figure').addClass('active');
                            parent.find('.link-bot').addClass('active');
                        }
                    }

                    parent.data('count', response.likeCount);
                    parent.find('.view').text(response.likeCount);
                } else {
                    // Новое значение больше старого.
                    if (parseInt(response.likeCount) > parseInt($('.like-image-count').text())) {
                        // Стрелка вниз горит - тушим ее.
                        if ($('.bot-up').hasClass('active')) {
                            $('.bot-up').removeClass('active');
                        } else {
                            // Иначе врубаем вверх.
                            $('.top-up').addClass('active');
                        }
                    }
                    // Новое значение меньше старого.
                    if (parseInt(response.likeCount) < parseInt($('.like-image-count').text())) {
                        // Стрелка вверх горит - тушим ее.
                        if ($('.top-up').hasClass('active')) {
                            $('.top-up').removeClass('active');
                        } else {
                            // Иначе врубаем вниз.
                            $('.bot-up').addClass('active');
                        }
                    }
                    $('.like-image-count').text(response.likeCount);
                }
			}
        },
        'json'
    );
}
function favorite(id, element) {
	var element = $(element);
	$.post(
        '/ajax/favoryte',
        {
			id: id,
			_csrf: $('[name="_csrf"]').val(),
		},
        function (response) {
			element.addClass('active');
		},
        'json'
    );
}

function reloadScripts()
{
    var iObj = document.createElement('SCRIPT');
    iObj.type = 'text/javascript';
    iObj.src = '/web/js/jquery-1.11.2.min.js' + '?' + Math.random();
    $('body').append(iObj);

    iObj = document.createElement('SCRIPT');
    iObj.type = 'text/javascript';
    iObj.src = '/web/js/jquery.jcarousel.min.js' + '?' + Math.random();
    $('body').append(iObj);

    iObj = document.createElement('SCRIPT');
    iObj.type = 'text/javascript';
    iObj.src = '/web/js/cropper.min.js' + '?' + Math.random();
    $('body').append(iObj);

    iObj = document.createElement('SCRIPT');
    iObj.type = 'text/javascript';
    iObj.src = '/web/js/imageEditor.js' + '?' + Math.random();
    $('body').append(iObj);
}

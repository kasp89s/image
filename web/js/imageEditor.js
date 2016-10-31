(function($) {
    // This is the connector function.
    // It connects one item from the navigation carousel to one item from the
    // stage carousel.
    // The default behaviour is, to connect items with the same index from both
    // carousels. This might _not_ work with circular carousels!
    var connector = function(itemNavigation, carouselStage) {
        return carouselStage.jcarousel('items').eq(itemNavigation.index());
    };

    $(function() {
        // Setup the carousels. Adjust the options for both carousels here.
        var carouselStage      = $('.carousel-stage').jcarousel();
        var carouselNavigation = $('.carousel-navigation').jcarousel();

        // We loop through the items of the navigation carousel and set it up
        // as a control for an item from the stage carousel.
        carouselNavigation.jcarousel('items').each(function() {
            var item = $(this);

            // This is where we actually connect to items.
            var target = connector(item, carouselStage);

            item
                .on('jcarouselcontrol:active', function() {
                    carouselNavigation.jcarousel('scrollIntoView', this);
                    item.addClass('active');
                })
                .on('jcarouselcontrol:inactive', function() {
                    item.removeClass('active');
                })
                .jcarouselControl({
                    target: target,
                    carousel: carouselStage
                });
        });

        // Setup controls for the stage carousel
        $('.prev-stage')
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        $('.next-stage')
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
    });
})(jQuery);

$(function () {

    'use strict';

    var $src = $('.carousel-navigation > ul > li.active > img').data('original'),
        $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]'),
        $cropBlock = $('.image-editor-box.crop'),
        $rotateBlock = $('.image-editor-box.rotate'),
    //$image = $('#cropped-image'),
        $width = $('#width'),
        $height = $('#height'),
        $rotateImages = $('.rotate-image > ul > li > img'),
        options = {
            aspectRatio: NaN,
            autoCropArea: 1,
            movable: false,
            zoomable: false,
            crop: function(e) {
                $width.val(parseInt(e.width));
                $height.val(parseInt(e.height));
//                console.log($image.cropper('getCropBoxData'));
            }
        },
        optionsResize = {
            aspectRatio: NaN,
            autoCropArea: 1,
            movable: false,
            zoomable: false,
            cropBoxMovable: false,
            dragMode: 'none',
            crop: function(e) {
                var data = $image.cropper('getCropBoxData');
                $width.val(parseInt(data.width));
                $height.val(parseInt(data.height));
            },
            cropend: function(e) {
                $image.cropper("setCanvasData", $image.cropper('getCropBoxData'));
                $image.cropper("setCropBoxData", $image.cropper('getCanvasData'));
            }
        },
        resizeImage = function (src) {
            var data = new FormData;
            data.append('_csrf', $('[name="_csrf"]').val());
            data.append('src', src);
            data.append('width', $width.val());
            data.append('height', $height.val());

            if (ajaxProcessing === true)
                return;

            ajaxProcessing = true;
            $.ajax({
                url: '/ajax/resize-image',
                type: 'post',
                data: data,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response)
                {
                    ajaxProcessing = false;
                    var albumImage = $('.c-photo > a > img[src="' + $src + '"]');
                    $src = response.src + '?' + Math.random();
                    albumImage.attr("src", $src);

                    $src = response.src + '?' + Math.random();
                    $('.carousel-navigation > ul > li.active > img').data('original', $src);
                    $image.cropper('destroy');
                    $image.replaceWith('<img src="'+$src+'" width="100%"/>');
                    $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]');
                    $image.removeAttr('width');
                    $image.cropper(optionsResize);

                    $('.click-changes-saved').trigger('click');
                },
                error: function () {
                    ajaxProcessing = false;
                }
            });
        },
        saveImage = function (file, src)
            {
//                console.log(file);
//                console.log(src);
                var data = new FormData;
                data.append('_csrf', $('[name="_csrf"]').val());
                data.append('image', file);
                data.append('src', src);

                if (ajaxProcessing === true)
                    return;

                ajaxProcessing = true;
                $.ajax({
                    url: '/ajax/save-image',
                    type: 'post',
                    data: data,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response)
                    {
                        ajaxProcessing = false;
                        var albumImage = $('.c-photo > a > img[src="' + $src + '"]');
                        $src = response.src + '?' + Math.random();
                        albumImage.attr("src", $src);

                        $src = response.src + '?' + Math.random();
                        $('.carousel-navigation > ul > li.active > img').data('original', $src);
                        $image.cropper('destroy');
                        $image.replaceWith('<img src="'+$src+'" width="100%"/>');
                        $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]');
                        $image.removeAttr('width');
                        $image.cropper(options);

                        $('.click-changes-saved').trigger('click');
                    },
                    error: function () {
                        ajaxProcessing = false;
                    }
                });
            },
            rotateImage = function (file, src)
            {
                var data = new FormData;
                data.append('_csrf', $('[name="_csrf"]').val());
                data.append('image', file);
                data.append('src', src);

                if (ajaxProcessing === true)
                    return;

                ajaxProcessing = true;
                $.ajax({
                    url: '/ajax/save-image',
                    type: 'post',
                    data: data,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response)
                    {
                        ajaxProcessing = false;

                        var albumImage = $('.c-photo > a > img[src="' + $src + '"]');
                        $src = response.src + '?' + Math.random();
                        albumImage.attr("src", $src);

                        $('.carousel-navigation > ul > li.active > img').data('original', $src);
                        $image.cropper('destroy');
                        $image.replaceWith('<img src="'+$src+'" width="100%"/>');
                        $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]');
                        $image.removeAttr('width');
                        $image.cropper(options);

                        $('.icon-main.res-ed').closest('li').trigger('click');
                        $('.rotate-image > ul > li').removeClass('active');
                        $('.rotate-image > ul > li:first').addClass('active');
                        $('.click-changes-saved').trigger('click');
                    },
                    error: function () {
                        ajaxProcessing = false;
                    }
                });
            },
            dataURLtoBlob = function (dataurl) {
                var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while(n--){
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new Blob([u8arr], {type:mime});
             };
    /*
        $("#aspect-ratio").change(function() {
            options.aspectRatio = NaN;
            if(this.checked) {
                options.aspectRatio = 1;
            }
            $image.cropper('destroy').cropper(options);
        });
    */
        $('span.prev').on(
            'click',
            function () {
                $cropBlock.addClass('visible');
                $rotateBlock.removeClass('visible');
                $('.nav-ul').find('li').removeClass('active');
                $('.icon-main.res-ed').closest('li').addClass('active');
                $('.prev-stage').trigger('click');
                $src = $('.carousel-navigation > ul > li.active > img').data('original');
                $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]');
                $image.removeAttr('width');
                $image.cropper('destroy').cropper(options);
            }
        );

        $('span.next').on(
            'click',
            function () {
                $cropBlock.addClass('visible');
                $rotateBlock.removeClass('visible');
                $('.nav-ul').find('li').removeClass('active');
                $('.icon-main.res-ed').closest('li').addClass('active');
                $('.next-stage').trigger('click');
                $src = $('.carousel-navigation > ul > li.active > img').data('original');
                $image = $('.carousel-stage > ul > li > img[src="'+$src+'"]');
                console.log($image);
                console.log($src);
                $image.removeAttr('width');
                $image.cropper('destroy').cropper(options);
            }
        );

    $image.removeAttr('width');
    $image.cropper(options);

/*
    $rotateImages.cropper({
        aspectRatio: NaN,
        autoCropArea: false,
        autoCrop: false,
        movable: false,
        zoomable: false,
        dragMode: 'none',
        built: function () {
//                console.log(this);
                $(this).cropper('rotate', $(this).data('rotate'));
        }
    });
*/
// Methods
    $('.nav-ul > li, .es-edit').on('click', function() {
        var $this = $(this);
        var data = $this.data();
        var $target;
        var result;
        var _image;

        if ($this.prop('disabled') || $this.hasClass('disabled')) {
            return;
        }
//        console.log($(this));
        if (($image.data('cropper') || $('.rotate-image > ul > li.active > img').data('cropper')) && data.method) {

            if (!$image.data('cropper')) {
                _image = $('.rotate-image > ul > li.active > img');
            } else {
                _image = $image;
            }
            data = $.extend({}, data); // Clone a new one

            if (typeof data.target !== 'undefined') {
                $target = $(data.target);

                if (typeof data.option === 'undefined') {
                    try {
                        data.option = JSON.parse($target.val());
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }

            result = _image.cropper(data.method, data.option);
//            console.log(result);
//            console.log($('.icon-main.rot-ed').closest('li').hasClass('active'));
            switch (data.method) {
                case 'getCroppedCanvas':
                    if (result && $('.icon-main.res-ed').closest('li').hasClass('active')) {
                        saveImage(dataURLtoBlob(result.toDataURL('image/jpeg')), $src);
                    } else if(result && $('.icon-main.rt-ed').closest('li').hasClass('active')) {
                        resizeImage($src);
                    } else if(result && $('.icon-main.rot-ed').closest('li').hasClass('active')) {
                        rotateImage(dataURLtoBlob(result.toDataURL('image/jpeg')), $src);
                    }

                    break;
            }
        } else {
            $(this).closest('ul').find('li').removeClass('active');
            $(this).addClass('active');

            $image.cropper('destroy');
            $rotateImages.cropper('destroy');
            if ($(this).find('i').hasClass('rt-ed')) {
                $image.cropper(optionsResize);
                $cropBlock.addClass('visible');
                $rotateBlock.removeClass('visible');
                $('.nav-editor-bot').show();
            } else if($(this).find('i').hasClass('res-ed')) {
                $image.cropper(options);
                $cropBlock.addClass('visible');
                $rotateBlock.removeClass('visible');
                $('.nav-editor-bot').hide();
            } else if($(this).find('i').hasClass('rot-ed')) {
                $rotateImages.attr('src', $src);
                $rotateImages.cropper({
                    aspectRatio: NaN,
                    autoCropArea: false,
                    autoCrop: false,
                    movable: false,
                    zoomable: false,
                    dragMode: 'none',
                    built: function () {
                        $(this).cropper('rotate', $(this).data('rotate'));
                    }
                });
                $cropBlock.removeClass('visible');
                $rotateBlock.addClass('visible');
                $('.nav-editor-bot').hide();
            }
        }
    });
});

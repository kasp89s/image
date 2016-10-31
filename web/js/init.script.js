var currentImageData = false,
    currentImageSrc = false,
    loadGalleria = false,
    loadIndex = false;
// Load the classic theme
Galleria.loadTheme('/web/galleria/galleria.classic.min.js');
// Initialize Galleria

function loadCheckedImage(index) {
    Galleria.run('#galleria');
    loadIndex = index;
}

Galleria.ready(function() {
    this.bind("loadfinish", function(e) {
        // console.log("loadfinish");
        loadGalleria = true;
        if (loadIndex !== false) {
            $('#galleria').data('galleria').show(loadIndex);
            loadIndex = false;
        }
    });
});
Galleria.on('image', function(e) {
    //Galleria.log(e.imageTarget); // the currently active IMG element
    currentImageSrc = $(e.imageTarget).attr('src');
    currentImageData = SliderImages[currentImageSrc];
    // console.log(e.imageTarget);
    // console.log(currentImageData);

    if (currentImageData.active == 1) {
        $('.share-slider-button').hide();
    } else {
        $('.share-slider-button').show();
    }
    var imageName = (currentImageData.name.length > 28) ? currentImageData.name.slice(0, 20) + '.' + currentImageData.name.split(".").pop() : currentImageData.name;
    $('#image-slider-name').text(imageName);
    $('#image-slider-date').text(currentImageData.submited);
    $('#image-slider-views').text(currentImageData.views);

    $('#share-url-slider').val(currentImageData.links.share);
    $('#direct-url-slider').val(currentImageData.links.direct);
    $('#html-url-slider').val(currentImageData.links.html);
    $('#bbcode-url-slider').val(currentImageData.links.bbcode);

    $('.social-left.slider-social > ul > li > a.ff').attr('href', currentImageData.share.ff);
    $('.social-left.slider-social > ul > li > a.gl').attr('href', currentImageData.share.gl);
    $('.social-left.slider-social > ul > li > a.sl').attr('href', currentImageData.share.sl);
    $('.social-left.slider-social > ul > li > a.tvt').attr('href', currentImageData.share.tvt);
    $('.social-left.slider-social > ul > li > a.vk').attr('href', currentImageData.share.vk);

    $('.slider-change-image-form > [name="image[UserImage][id]"]').val(currentImageData.id);
    $('.slider-change-image-form > [name="image[UserImage][title]"]').val(currentImageData.title);
    $('.slider-change-image-form > [name="image[UserImage][description]"]').val(currentImageData.description);

    $('.slider-share-modal').find('.main-section-form').html(
            '<li class="clearfix">' +
                '<figure><a href="' + currentImageSrc + '" class="ajax"><img src="' + currentImageData.thumb160 + '" alt="" /></a></figure>' +
                    '<div class="r-section-form">' +
                        '<input type="hidden" name="image[UserImage][id]" value="' + currentImageData.id + '"/>' +
                        '<input type="text" name="image[UserImage][title]" placeholder="Title" value="' + currentImageData.title + '">' +
                        '<textarea name="image[UserImage][description]" placeholder="Description" class="is-maxlength" value="' + currentImageData.description + '">' + currentImageData.description + '</textarea>' +
                    '</div>' +
                '<i class="up"></i>' +
                '</li>' +
            '</li>'
    );
});

$('.slider-change-image-form').on('submit', function () {
    event.preventDefault();
    var form = $(this);

    $.post(
        form.attr('action'),
        form.serialize(),
        function (response) {
            SliderImages[currentImageSrc].title = form.find('[name="image[UserImage][title]"]').val();
            SliderImages[currentImageSrc].description = form.find('[name="image[UserImage][description]"]').val();
        },
        'json'
    );
});

/*
description: ""
id: "234"
links: Object
bbcode: "[img]http://qruto.com/uploads/original/573e431963b50.png[/img] "
direct: "http://qruto.com/uploads/original/573e431963b50.png"
html: "<img src="http://qruto.com/uploads/original/573e431963b50.png" /> "
    share: "http://qruto.com/i/573e431963b50"
__proto__: Object
name: "kolorirovanie-na-svetlie-volosy-2.png"
share: Object
ff: "https://www.facebook.com/sharer/sharer.php?sdk=joey&u=qruto.com/i/573e431963b50"
gl: "https://plus.google.com/share?url=qruto.com/i/573e431963b50"
sl: "http://service.weibo.com/share/share.php?url=http://qruto.com/i/573e431963b50&language=en"
tvt: "https://twitter.com/home?status=qruto.com/i/573e431963b50"
vk: "http://vk.com/share.php?url=http://qruto.com/i/573e431963b50"
__proto__: Object
submited: "20 May in 01:50"
title: "WTF?"
views: "0"
__proto__: Object
*/

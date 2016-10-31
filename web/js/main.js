// JavaScript Document
var uploaded = false;
var uploadQueue = [];
var uploadInterval;
var imageCount;
var uploadItemsCount = 0;
var formData = [];
var initialData = new FormData;
var mainInLoad = false;
var uploadType = 'image';
var uploadUrl = false;

function toDataUrl(url) {
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
//        reader.onloadend = function () {
//            callback(reader.result);
//        }
        reader.readAsDataURL(xhr.response);
        reader.onload = function (evt) {
            formData = [];
            $('.upload-image-list').html('<li>' +
                '<figure><img src="' + evt.target.result + '" alt="" /></figure>' +
                '<div class="txt-image">' + url.substring(url.lastIndexOf('/') + 1, url.length) + '</div>' +
                '</li>');
            uploadItemsCount = 1;
            var file = dataURLtoBlob(evt.target.result);
            file.name = url.substring(url.lastIndexOf('/') + 1, url.length);
            formData.push(file);
            updateUploaderByItemsCount();
        };
        reader.onerror = function (evt) {
            $('.error-image').show();
        };
    };
    xhr.open('GET', url);
    xhr.send();
}

function fileUpload(name) {
    $.each($('#' + name)[0].files, function (i, file) {
        uploadItemsCount++;
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (evt) {
            var fileName = (file.name.length > 35) ? file.name.slice(0, 32) + '.' + file.name.split(".").pop() : file.name;
            $('.upload-image-list').append('<li>' +
                '<figure><img src="' + evt.target.result + '" alt="" /></figure>' +
                '<div class="txt-image">' + fileName + '</div>' +
                '<a href="javascript:void(0)" onclick="removeUploadPhoto(\'' + name + '\', \'' + file.name + '\', this)" class="off"></a>' +
                '</li>');
        };
        reader.onerror = function (evt) {
            $('.error-image').show();
        };
        // Добавляет значение файла.
        formData.push(file);
    });

    updateUploaderByItemsCount();
}

function dragAndDropUpload(files) {

    for (var i = 0; i < files.length; i++)
    {
        var file = files[i];
        uploadItemsCount++;
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (evt) {
            var fileName = (file.name.length > 35) ? file.name.slice(0, 32) + '.' + file.name.split(".").pop() : file.name;
            $('.upload-image-list').append('<li>' +
                '<figure><img src="' + evt.target.result + '" alt="" /></figure>' +
                '<div class="txt-image">' + fileName + '</div>' +
                '<a href="javascript:void(0)" onclick="removeUploadPhoto(\'' + name + '\', \'' + file.name + '\', this)" class="off"></a>' +
                '</li>');
        };
        reader.onerror = function (evt) {
            $('.error-image').show();
        };
        // Добавляет значение файла.
        formData.push(file);
    }

    updateUploaderByItemsCount();
}

function removeUploadPhoto(name, fileName, object) {
    $(object).closest('li').remove();
    var tmpData = [];
    for (var i in formData) {
        var value = formData[i];
        if (value.name == fileName) {
            uploadItemsCount--;
        } else {
            tmpData.push(value);
        }
    }
    formData = tmpData;

    updateUploaderByItemsCount();
}

function updateUploaderByItemsCount() {
    $('.upload-image').addClass('active');
    $('.es-pfoto').removeAttr('style');
    $('.up-image').trigger('click');
    setTimeout(function () {
        $('.scroll-pane').jScrollPane();
    }, 500);

    $('.upload-image-count').text(uploadItemsCount);

    if (uploadItemsCount > 1) {
        $('.album-ready').show();
        $('.create').trigger('click').addClass('blocked');
        $('.upload-album').show();
    }

    $('.start-upload').removeClass("blocked");
}

function startUpload() {
    uploadInterval = setInterval(
        function () {
            if (uploadQueue.length == 0) {
                clearInterval(uploadInterval);
                if (uploadType == 'image') {
                    window.location.href = '/' + uploadUrl;
                } else if (uploadType == 'album') {
                    window.location.href = '/a/' + uploadUrl;
                }

            }

            if (uploaded === true)
                return;

            uploaded = true;
            for (var i in uploadQueue) {
                uploadFile(i, uploadQueue[i]);
                console.log(i + ' => ' + uploadQueue[i].name);
                break;
            }
        },
        1000
    );
}

function uploadFile(key, file) {
    var data = new FormData;
    data.append('_csrf', $('[name="_csrf"]').val());
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function (evt) {
        var fileName = (file.name.length > 35) ? file.name.slice(0, 32) + '.' + file.name.split(".").pop() : file.name;
        $('.upload-images').html(
            '<li>' +
                '<figure><img src="' + evt.target.result + '" width="62" height="62" alt="" /></figure>' +
                '<div class="txt-image">' + fileName +
                '<span class="load-im"><i style="width:0%" data-percent="0"></i></span>' +
                '</div>' +
                '</li>'
        );
        data.append('image', file, file.name);
        var percentInterval = setInterval(
            function () {
                var indicator = $('.load-im').find('i');
                var percent = parseInt(indicator.data('percent'));

                indicator.attr('style', 'width:' + parseInt(percent + 1) + '%');
                indicator.data('percent', parseInt(percent + 1));
                if (percent >= 100)
                    clearInterval(percentInterval);
            },
            5
        );
        $.ajax({
            url: '/upload',
            type: 'post',
            data: data,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (uploadType == 'image') {
                    uploadUrl = response.imageUrl;
                }
                var indicator = $('.load-all').find('i');
                var percent = parseInt(indicator.data('percent'));
                console.log(percent);
                indicator.attr('style', 'width:' + parseInt(percent + parseInt(100 / imageCount)) + '%');
                indicator.data('percent', percent + parseInt(100 / imageCount));
                //clearInterval(percentInterval);
                uploadQueue.shift();
                uploaded = false;
            }
        });
    }
}
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            if (mainInLoad === true || typeof(enable_scroll_loading) === 'undefined')
                return;

            console.log(432423);
            var currentPage = $('#currentPage').val();
            var lastPage = $('#lastPage').val();
            if (parseInt(currentPage) >= parseInt(lastPage)) {
                return;
            }
            var loadPage = parseInt(currentPage) + 1;
            $('#currentPage').val(loadPage);
            mainInLoad = true;
            $('.ajax-loader').show();
            $.get(
                '/site/load-image',
                {
                    page: loadPage,
                    sort_topic: $('#sort_topic').val(),
                    sort: $('#sort').val(),
                    _csrf: $('[name="_csrf"]').val()
                },
                function (response) {
                    mainInLoad = false;
                    $('.ajax-loader').hide();
                    $('.galery-main').append(response);
                }
            ).fail(function () {
                    mainInLoad = false;
                    $('.ajax-loader').hide();
                });

        }
    });
    initialData.append('_csrf', $('[name="_csrf"]').val());
    initialData.append('initial', 1);

    var obj = $(".gr-add-dr");
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
        dragAndDropUpload(files);
    });

    $('.start-upload').on('click', function () {
        if ($(this).hasClass("blocked")) {
            return;
        }
        for (var i in formData) {
            var value = formData[i];
            console.log(value);
            uploadQueue.push(value);
        }
        imageCount = uploadQueue.length;
        $.ajax({
            url: '/upload',
            type: 'post',
            data: initialData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                uploadType = response.type;
                if (response.albumUrl != null) {
                    uploadUrl = response.albumUrl;
                }
                startUpload();
            }
        });

    });
    $('#captcha-input').on(
        'keyup',
        function () {
            $('#captcha-value').val($(this).val());
        }
    );
    $('#register-button').on(
        'click',
        function () {
            $('form[action="register"]').submit();
        }
    );

    function showErrors(data) {
        if (data.username != null) {
            $('#username').closest("p").addClass('error').find('span.error-txt').text(data.username);
        }
        if (data.email != null) {
            $('#email').closest("p").addClass('error').find('span.error-txt').text(data.email);
        }
        if (data.password != null) {
            $('#password').closest("p").addClass('error').find('span.error-txt').text(data.password);
        }
        if (data.password_retype != null) {
            $('#password_retype').closest("p").addClass('error').find('span.error-txt').text(data.password_retype);
        }
        if (data.captcha != null) {
            $('#captcha-input').closest("p").addClass('error').find('span.error-txt').text(data.captcha);
        }
    }

    $('#check-register').on(
        'click',
        function () {
            var form = $('form[action="register"]');
            $.post(
                '/check',
                form.serialize(),
                function (response) {
                    $('.center-sign-up').find('p.error').removeClass('error');
                    if (response != null) {
                        showErrors(response);
                    }
                },
                'json'
            ).fail(function () {
                    $('.center-sign-up').find('p.error').removeClass('error');
                    $('.go-captcha').trigger('click');
                });
        }
    );
    $('#changepassword').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                form.attr('action'),
                form.serialize(),
                function (response) {
                    $('.passw-form').find('p.error').removeClass('error');
                    if (response.success != null) {
                        $('.middle').replaceWith(response.success);
                    }
                    showErrors(response);
                },
                'json'
            )
        }
    );
    $('form[action="recovery"]').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                '/recovery',
                form.serialize(),
                function (response) {
                    if (response.success != null) {
                        $('.middle').replaceWith(response.success);
                    }
                    showErrors(response);
                },
                'json'
            )
            //.fail(function() {
            //	location.reload();
            //});
        }
    );
    $('form[action="login"]').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                '/login',
                form.serialize(),
                function (response) {
                    if (response.password != null) {
                        $('.l-p-e').text(response.password).show();
                    }
                },
                'json'
            ).fail(function () {
                    location.reload();
                });
        }
    );

    $('form[action="register"]').on(
        'submit',
        function (event) {
            event.preventDefault();
            var form = $(this);
            $.post(
                '/register',
                form.serialize(),
                function (response) {
                    if (response.success != null) {
                        location.reload();
                    }
                    showErrors(response);
                },
                'json'
            );
        }
    );

    //Для кроссбраузернго placeholder
    $(function () {
        $('input[placeholder], textarea[placeholder]').placeholder();
    });

    // search
    new UISearch(document.getElementById('search'));

    // main-calc
    $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;

        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });


    $(".create").click(function () {
        if ($(this).hasClass("blocked")) {
            return;
        }
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
            initialData.append('album', 'Unnamed');
            $(".title-album").find('input').val('Unnamed');
        } else {
            initialData.delete('album');
        }
        $(".title-album").toggleClass("active");
    })
    $(".publish").click(function () {
        if ($(this).hasClass("blocked")) {
            return;
        }
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
            initialData.append('publish', 1);
        } else {
            initialData.delete('publish');
        }
    })

    // link-top
    // $(".link-top").click(function () {
    //     $(this).toggleClass("active");
    // })
    // link-bot
    // $(".link-bot").click(function () {
    //     $(this).toggleClass("active");
    // })

    $(".head-list ul > li input[type=submit]").click(function () {
        $(this).toggleClass("active");
        $(".txt-es").toggleClass("active");
        $(".txt-no").toggleClass("active");
    });


// lang
	 $('.lang > a').click(function(){
		if($(this).is('.open')){
   		$(this).removeClass('open');
     	$(this).next('ul').slideUp(200);
   	}else{
   		$(this).addClass('open');
     	$(this).next('ul').slideDown(200);	
     	$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("lang")){
				$(".lang > a").removeClass("open").next('ul').slideUp(400);
			}
		});
   	}
   	return false;
	});


// user
	 $('.user-es > a').click(function(){
		if($(this).is('.open')){
   		$(this).removeClass('open');
     	$(this).next('ul').slideUp(200);
   	}else{
   		$(this).addClass('open');
     	$(this).next('ul').slideDown(200);	
     	$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("user-es")){
				$(".user-es > a").removeClass("open").next('ul').slideUp(400);
			}
		});
   	}
   	return false;
	})


// link-menu
	 $('.link-menu > a').click(function(){
		if($(this).is('.open')){
   		$(this).removeClass('open');
     	$(this).next('ul').slideUp(200);
   	}else{
   		$(this).addClass('open');
     	$(this).next('ul').slideDown(200);	
     	$(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("link-menu")){
				$(".link-menu > a").removeClass("open").next('ul').slideUp(400);
			}
		});
   	}
   	return false;
	})


    // comment-main ul
    $('.add-comm').click(function () {
        if ($(this).is('.open')) {
            $(this).removeClass('open');
            $(this).next('ul').slideUp(200);
        } else {
            $(this).addClass('open');
            $(this).next('ul').slideDown(200);
        }
        return false;
    })


    // submit-comment
    $(".realy").on('click', function () {
        $(this).parent().parent().parent().parent().find('.submit-comment').toggleClass("active");
        $(this).toggleClass("active");
    });


    // tabs
    /*
     $('.menu-right-main').delegate('li:not(.active)', 'click', function() {
     $(this).addClass('active').siblings().removeClass('active')
     .parents('.middle').find('.box').hide().eq($(this).index()).fadeIn(150);
     });
     */
    $('.head-poop-image ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
            .parents('.poop-image-title').find('.box').hide().eq($(this).index()).fadeIn(150);
    });

    $('.change-list').delegate('li:not(.active)', 'click', function () {
        $('#album-type').val($(this).data('value'));
        $(this).addClass('active').siblings().removeClass('active')
            .parents('.c-change-album-poop').find('.box').hide().eq($(this).index()).fadeIn(150);
    });

//	$('.nav-ul').delegate('li:not(.active)', 'click', function() {
//		$(this).addClass('active').siblings().removeClass('active')
//			.parents('.editor-image').find('.image-editor-box').hide().eq($(this).index()).fadeIn(150);
//	});

    $('.radio-sorting').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
            .parents('.main-form-sorting').find('.box-texter').hide().eq($(this).index()).fadeIn(0);
    });

    $('.txt-block').jTruncate({
        length: 54,
        minTrail: 0,
        moreText: "",
        lessText: "",
        ellipsisText: " ...",
        moreAni: "fast",
        lessAni: 2000
    });
    $('.txt-image').jTruncate({
        length: 70,
        minTrail: 0,
        moreText: "",
        lessText: "",
        ellipsisText: " ...",
        moreAni: "fast",
        lessAni: 2000
    });

    $('textarea').maxlength(140);
    $('.title-form input').maxlength(240);

    /*
     * jQuery UI Sortable setup
     */
    $('#sortable').sortable({
        update: function( ) {
            var sortable_data = $('#sortable').sortable('serialize');

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
    $("#sortable").disableSelection();


    $('input:checkbox:not([safari])').checkbox();
    $('input[safari]:checkbox').checkbox({cls: 'jquery-safari-checkbox'});
    $('input:radio').checkbox();


    displayForm = function (elementId) {
        var content = [];
        $('#' + elementId + ' input').each(function () {
            var el = $(this);
            if ((el.attr('type').toLowerCase() == 'radio')) {
                if (this.checked)
                    content.push([
                        '"', el.attr('name'), '": ',
                        'value="', ( this.value ), '"',
                        ( this.disabled ? ', disabled' : '' )
                    ].join(''));
            }
            else
                content.push([
                    '"', el.attr('name'), '": ',
                    ( this.checked ? 'checked' : 'not checked' ),
                    ( this.disabled ? ', disabled' : '' )
                ].join(''));
        });
        alert(content.join('\n'));
    }

    // c-selected-all
    $('.select-form > h4').click(function () {
        if ($(this).is('.open')) {
            $(this).removeClass('open');
            $(this).next('.selected-all').slideUp(100);
        } else {
            $(this).addClass('open');
            $(this).next('.selected-all').slideDown(100);
            $(document).bind('click', function (e) {
                var $clicked = $(e.target);
                if (!$clicked.parents().hasClass("select-form")) {
                    $(".select-form > h4").removeClass("open").next('.selected-all').slideUp(100);
                }
            });
        }
        return false;
    })

    $('.c-selected-all ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active');
    });

    // c-selected-all
    $('.add-optional').click(function () {
        /*
         if($(this).is('.open')){
         $(this).removeClass('open');
         $(this).next('.selected-optional').slideUp(100);
         }else{
         $(this).addClass('open');
         $(this).next('.selected-optional').slideDown(100);
         $(document).bind('click', function(e) {
         var $clicked = $(e.target);
         if (! $clicked.parents().hasClass("add-optional")){
         $(".add-optional").removeClass("open").next('.selected-optional').slideUp(100);
         }
         });
         }
         */
        return false;
    })

    $('.add-optional ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
    });

    $('.c-selected-all ul li').click(function () {
        $('.select-form > h4').html($(this).find('h4').html());
        $(this).closest('form').find('#topic-id').val($(this).find('h4').data('id'));
        $(".select-form > h4").addClass("active");
        $(".select-form > h4").removeClass("open").next('.selected-all').slideUp(0);
    });

    // up-image all-click
    $('.all-click').click(function () {
        if ($(this).is('.open')) {
            $(this).removeClass('open');
            $(this).next('ul').slideUp(40);
        } else {
            $(this).addClass('open');
            $(this).next('ul').slideDown(40);
            $(document).bind('click', function(e) {
			var $clicked = $(e.target);
			if (! $clicked.parents().hasClass("all-click")){
				$(".all-click").removeClass("open").next('ul').slideUp(400);
			}
		});
        }
        return false;
    })
    $('.btn-image ul li').click(function () {
        $(".all-click").removeClass("open").next('ul').slideUp(0);
    });

    $('.image-sorting').click(function () {
        if ($(this).is('.open')) {
            $(this).removeClass('open');
            $(this).next('.all-images-select').slideUp(100);
        } else {
            $(this).addClass('open');
            $(this).next('.all-images-select').slideDown(100);
            $(document).bind('click', function (e) {
                var $clicked = $(e.target);
                if (!$clicked.parents().hasClass("image-sorting")) {
                    $(".image-sorting").removeClass("open").next('.all-images-select').slideUp(100);
                }
            });
        }
        return false;
    })

    $('select:not(.notsel), .select').selectric();

    $(".r-change-album figure").click(function () {
        $(".b-change-album, .change-album-poop").toggleClass("active");
    })

    $('.coveralbum ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
    });
    $('.coveralbum ul li').click(function () {
        $('.r-change-album figure').html($(this).find('figure').html());
    });

    $('.all-images-select .scroll-pane ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
    });

    $('.rotate-image ul').delegate('li:not(.active)', 'click', function () {
        $(this).addClass('active').siblings().removeClass('active')
    });


    $(".edit-image, .off-editor").click(function () {
        $(this).toggleClass("active");
        $(".poop-editor").toggleClass("active");
    })
});//end ready


//Plugin placeholder
(function (b) {
    function d(a) {
        this.input = a;
        a.attr("type") == "password" && this.handlePassword();
        b(a[0].form).submit(function () {
            if (a.hasClass("placeholder") && a[0].value == a.attr("placeholder"))a[0].value = ""
        })
    }

    d.prototype = {show: function (a) {
        if (this.input[0].value === "" || a && this.valueIsPlaceholder()) {
            if (this.isPassword)try {
                this.input[0].setAttribute("type", "text")
            } catch (b) {
                this.input.before(this.fakePassword.show()).hide()
            }
            this.input.addClass("placeholder");
            this.input[0].value = this.input.attr("placeholder")
        }
    },
        hide: function () {
            if (this.valueIsPlaceholder() && this.input.hasClass("placeholder") && (this.input.removeClass("placeholder"), this.input[0].value = "", this.isPassword)) {
                try {
                    this.input[0].setAttribute("type", "password")
                } catch (a) {
                }
                this.input.show();
                this.input[0].focus()
            }
        }, valueIsPlaceholder: function () {
            return this.input[0].value == this.input.attr("placeholder")
        }, handlePassword: function () {
            var a = this.input;
            a.attr("realType", "password");
            this.isPassword = !0;
            if (b.browser.msie && a[0].outerHTML) {
                var c = b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
                    "type=$1text$1"));
                this.fakePassword = c.val(a.attr("placeholder")).addClass("placeholder").focus(function () {
                    a.trigger("focus");
                    b(this).hide()
                });
                b(a[0].form).submit(function () {
                    c.remove();
                    a.show()
                })
            }
        }};
    var e = !!("placeholder"in document.createElement("input"));
    b.fn.placeholder = function () {
        return e ? this : this.each(function () {
            var a = b(this), c = new d(a);
            c.show(!0);
            a.focus(function () {
                c.hide()
            });
            a.blur(function () {
                c.show(!1)
            });
            b.browser.msie && (b(window).load(function () {
                a.val() && a.removeClass("placeholder");
                c.show(!0)
            }),
                a.focus(function () {
                    if (this.value == "") {
                        var a = this.createTextRange();
                        a.collapse(!0);
                        a.moveStart("character", 0);
                        a.select()
                    }
                }))
        })
    }
})(jQuery);

(function ($) {
    var i = function (e) {
        if (!e)var e = window.event;
        e.cancelBubble = true;
        if (e.stopPropagation)e.stopPropagation()
    };
    $.fn.checkbox = function (f) {
        try {
            document.execCommand('BackgroundImageCache', false, true)
        } catch (e) {
        }
        var g = {cls: 'jquery-checkbox', empty: 'empty.png'};
        g = $.extend(g, f || {});
        var h = function (a) {
            var b = a.checked;
            var c = a.disabled;
            var d = $(a);
            if (a.stateInterval)clearInterval(a.stateInterval);
            a.stateInterval = setInterval(function () {
                if (a.disabled != c)d.trigger((c = !!a.disabled) ? 'disable' : 'enable');
                if (a.checked != b)d.trigger((b = !!a.checked) ? 'check' : 'uncheck')
            }, 10);
            return d
        };
        return this.each(function () {
            var a = this;
            var b = h(a);
            if (a.wrapper)a.wrapper.remove();
            a.wrapper = $('<span class="' + g.cls + '"><span class="mark"></span></span>');
            a.wrapperInner = a.wrapper.children('span:eq(0)');
            a.wrapper.hover(function (e) {
                a.wrapperInner.addClass(g.cls + '-hover');
                i(e)
            }, function (e) {
                a.wrapperInner.removeClass(g.cls + '-hover');
                i(e)
            });
            b.css({position: 'absolute', zIndex: -1, visibility: 'hidden'}).after(a.wrapper);
            var c = false;
            if (b.attr('id')) {
                c = $('label[for=' + b.attr('id') + ']');
                if (!c.length)c = false
            }
            if (!c) {
                c = b.closest ? b.closest('label') : b.parents('label:eq(0)');
                if (!c.length)c = false
            }
            if (c) {
                c.hover(function (e) {
                    a.wrapper.trigger('mouseover', [e])
                }, function (e) {
                    a.wrapper.trigger('mouseout', [e])
                });
                c.click(function (e) {
                    b.trigger('click', [e]);
                    i(e);
                    return false
                })
            }
            a.wrapper.click(function (e) {
                b.trigger('click', [e]);
                i(e);
                return false
            });
            b.click(function (e) {
                i(e)
            });
            b.bind('disable',function () {
                a.wrapperInner.addClass(g.cls + '-disabled')
            }).bind('enable', function () {
                a.wrapperInner.removeClass(g.cls + '-disabled')
            });
            b.bind('check',function () {
                a.wrapper.addClass(g.cls + '-checked')
            }).bind('uncheck', function () {
                a.wrapper.removeClass(g.cls + '-checked')
            });
            $('img', a.wrapper).bind('dragstart',function () {
                return false
            }).bind('mousedown', function () {
                return false
            });
            if (window.getSelection)a.wrapper.css('MozUserSelect', 'none');
            if (a.checked)a.wrapper.addClass(g.cls + '-checked');
            if (a.disabled)a.wrapperInner.addClass(g.cls + '-disabled')
        })
    }
})(jQuery);

(function (window) {

    'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

    function classReg(className) {
        return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
    }

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
    var hasClass, addClass, removeClass;

    if ('classList' in document.documentElement) {
        hasClass = function (elem, c) {
            return elem.classList.contains(c);
        };
        addClass = function (elem, c) {
            elem.classList.add(c);
        };
        removeClass = function (elem, c) {
            elem.classList.remove(c);
        };
    }
    else {
        hasClass = function (elem, c) {
            return classReg(c).test(elem.className);
        };
        addClass = function (elem, c) {
            if (!hasClass(elem, c)) {
                elem.className = elem.className + ' ' + c;
            }
        };
        removeClass = function (elem, c) {
            elem.className = elem.className.replace(classReg(c), ' ');
        };
    }

    function toggleClass(elem, c) {
        var fn = hasClass(elem, c) ? removeClass : addClass;
        fn(elem, c);
    }

    var classie = {
        // full names
        hasClass: hasClass,
        addClass: addClass,
        removeClass: removeClass,
        toggleClass: toggleClass,
        // short names
        has: hasClass,
        add: addClass,
        remove: removeClass,
        toggle: toggleClass
    };

// transport
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(classie);
    } else {
        // browser global
        window.classie = classie;
    }

})(window);

// colorbox
$(document).ready(function () {


    //Examples of how to assign the Colorbox event to elements
    $(".group1").colorbox({rel: 'group1'});
    $(".group2").colorbox({rel: 'group2', transition: "fade"});
    $(".group3").colorbox({rel: 'group3', transition: "none", width: "75%", height: "75%"});
    $(".group4").colorbox({rel: 'group4', slideshow: true});
    $(".ajax").colorbox();
    $(".youtube").colorbox({iframe: true, innerWidth: 640, innerHeight: 390});
    $(".vimeo").colorbox({iframe: true, innerWidth: 500, innerHeight: 409});
    $(".iframe").colorbox({iframe: true, width: "80%", height: "80%"});
    $(".inline").colorbox({inline: true});
    $(".callbacks").colorbox({
        onOpen: function () {
            alert('onOpen: colorbox is about to open');
        },
        onLoad: function () {
            alert('onLoad: colorbox has started to load the targeted content');
        },
        onComplete: function () {
            alert('onComplete: colorbox has displayed the loaded content');
        },
        onCleanup: function () {
            alert('onCleanup: colorbox has begun the close process');
        },
        onClosed: function () {
            alert('onClosed: colorbox has completely closed');
        }
    });

    $('.non-retina').colorbox({rel: 'group5', transition: 'none'})
    $('.retina').colorbox({rel: 'group5', transition: 'none', retinaImage: true, retinaUrl: true});
    $('.scroll-pane').jScrollPane();
});

// text ....
(function ($) {
    $.fn.jTruncate = function (options) {

        var defaults = {
            length: 300,
            minTrail: 20,
            moreText: "more",
            lessText: "less",
            ellipsisText: "...",
            moreAni: "",
            lessAni: ""
        };

        var options = $.extend(defaults, options);

        return this.each(function () {
            obj = $(this);
            var body = obj.html();

            if (body.length > options.length + options.minTrail) {
                var splitLocation = body.indexOf(' ', options.length);
                if (splitLocation != -1) {
                    // truncate tip
                    var splitLocation = body.indexOf(' ', options.length);
                    var str1 = body.substring(0, splitLocation);
                    var str2 = body.substring(splitLocation, body.length - 1);
                    obj.html(str1 + '<span class="truncate_ellipsis">' + options.ellipsisText +
                        '</span>' + '<span class="truncate_more">' + str2 + '</span>');
                    obj.find('.truncate_more').css("display", "none");

                    // insert more link
                    obj.append(
                        '<div class="clearboth">' +
                            '<a href="#" class="truncate_more_link">' + options.moreText + '</a>' +
                            '</div>'
                    );

                    // set onclick event for more/less link
                    var moreLink = $('.truncate_more_link', obj);
                    var moreContent = $('.truncate_more', obj);
                    var ellipsis = $('.truncate_ellipsis', obj);
                    moreLink.click(function () {
                        if (moreLink.text() == options.moreText) {
                            moreContent.show(options.moreAni);
                            moreLink.text(options.lessText);
                            ellipsis.css("display", "none");
                        } else {
                            moreContent.hide(options.lessAni);
                            moreLink.text(options.moreText);
                            ellipsis.css("display", "inline");
                        }
                        return false;
                    });
                }
            } // end if

        });
    };
})(jQuery);

$(function () {

    $(window).scroll(function () {
        var top = $(document).scrollTop();
        if (top > 80) $('.head-photo').addClass('fixed');
        else $('.head-photo').removeClass('fixed');
    });

    $(window).scroll(function () {
        var top = $(document).scrollTop();
        if (top > 80) $('.social-left').addClass('fix');
        else $('.social-left').removeClass('fix');
    });

});
$(document).ready(function () {
    jQuery(function ($) {
        'use strict';

        var $example = $('#example');
        var $frame = $example.find('.frame');
        window.frr = $frame;
        var sly = new Sly($frame, {
            horizontal: 1,
            itemNav: 'forceCentered',
            activateMiddle: 1,
            smart: 1,
            activateOn: 'click',
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            startAt: false,
            scrollBar: $example.find('.scrollbar'),
            scrollBy: 1,
            pagesBar: $example.find('.pages'),
            activatePageOn: 'click',
            speed: 200,
            moveBy: 600,
            elasticBounds: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,

            // Buttons
            forward: $example.find('.forward'),
            backward: $example.find('.backward'),
            prev: $example.find('.prev'),
            next: $example.find('.next'),
            prevPage: $example.find('.prevPage'),
            nextPage: $example.find('.nextPage')
        }).init();

        // Method calling buttons
        $example.on('click', 'button[data-action]', function () {
            var action = $(this).data('action');

            switch (action) {
                case 'add':
                    sly.add('<li>' + sly.items.length + '</li>');
                    break;
                case 'remove':
                    sly.remove(-1);
                    break;
                default:
                    sly[action]();
            }
        });
    });
});

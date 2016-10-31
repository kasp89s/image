$(function() {

    $('#side-menu').metisMenu();
	tinymce.init({
  selector: "textarea",  // change this value according to your HTML
  plugins: "code",
  toolbar: "code",
  menubar: ""
  });
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});
$(document).ready(function() {
    $('#dataTables-example').DataTable({
        responsive: true
    });

    $('.remove-photo').on(
        'click',
        function () {
            var element = $(this);
            if(!confirm('Удалить фото')) {
                return;
            }

            $.post(
                '/admin/hotel/photoremove',
                {
                    id: $('.current-id').val(),
                    photo: element.data('value'),
                    _csrf: $('[name="_csrf"]').val()
                },
                function (response) {
                    element.parent().parent().remove();
                },
                'json'
            );
        }
    );

    $('.datepicker').datepicker({
        format: "dd.mm.yyyy"
    });

    $('.rate-submit').on(
        'click',
        function () {
            var form = $('#rate-form');

            $.post(
                '/admin/room/rate',
                form.serialize(),
                function (response) {
                    if (response.errors != null) {
                        for(var i in response.errors) {
                            $('.alert-dismissable .content').empty().append(response.errors[i] + '<br />');
                            $('.alert-dismissable').show();
                        }
                    } else {
                        $('.close-model').trigger('click');
                        location.reload();
                    }
                },
                'json'
            );
        }
    );

    var ul_sortable = $('.sortable'); //setup one variable for sortable holder that will be used in few places


    /*
     * jQuery UI Sortable setup
     */
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder',
        update: function( ) {
            var sortable_data = ul_sortable.sortable('serialize');

            $.post(
                '/admin/slider/order',
                sortable_data + '&_csrf=' + $('[name="_csrf"]').val(),
                function (response) {
                    //location.reload();
                },
                'json'
            );
        }
    });
    ul_sortable.disableSelection();



    /*
     * Saving and displaying serialized data
     */
    var btn_save = $('button.save'); // select save button



    btn_save.on('click', function(e){ // trigger function on save button click
        e.preventDefault();

        var sortable_data = ul_sortable.sortable('serialize'); // serialize data from ul#sortable

        $.post(
            '/admin/slider/order',
            sortable_data + '&_csrf=' + $('[name="_csrf"]').val(),
            function (response) {
                //location.reload();
            },
            'json'
        );
    });

    $('.upload').on('click', function () {$('#photos').trigger('click')});

    $('#photos').on('change',
        function () {
            fileUpload('photos')
        }
    );

    function fileUpload(name)
    {
        var formData = new FormData;
        $.each($('#' + name)[0].files, function (i, file){
            formData.append(name + '[]', file);
        });
        formData.append('_csrf', $('[name="_csrf"]').val());

        $.ajax({
            url: '/admin/slider/create',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response)
            {
                location.reload();
            }
        });
    }
/*
    $('.fa-file-excel-o').on('click', function () {
        $('#hotelId').val($(this).data('id'));
    })

    $('.commission').on('click', function () {
        console.log($(this));
        $('#hotelcommission-hotelid').val($(this).data('id'));
    })

    $('.deals').on('click', function () {
        $('#hoteldeals-hotelid').val($(this).data('id'));
    })*/
});

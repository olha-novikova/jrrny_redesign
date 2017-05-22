var loader = '<div id="loader"><div class="sk-circle"> <div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div></div>';
var $ = jQuery.noConflict();
function refreshHeight() {
    $('.slick-slider').each(function () {
        $(this).slick("getSlick").refresh();
    });
}

function get_modal(type) {
    $.ajax({
        type: 'post',
        url: defineURL('ajaxurl'),
        data: {
            action: 'get_modal',
            modal: type + '_modal',
            current_url: defineURL('current_url')
        },
        success: function (response) {
            if ($('#' + type + '_wrapper').length) {
                $('#' + type + '_wrapper').remove();
            }
            $('body').append(response.msg);
            $('#' + type + '_wrapper').modal('show');
            //window.location = '#login-form';
        }
    });
}
jQuery(function ($) {
    $(function () {
        $('#full_search_btn').on('click', function (event) {
            event.preventDefault();
            $('#full_search').addClass('open');
            $('#full_search > form > input[type="search"]').focus();
        });

        $('#full_search, #full_search button.close').on('click keyup', function (event) {
            if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                $(this).removeClass('open');
            }
        });
    });

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refreshHeight();
    });
    if ($('#top-wrap.header_holder').length) {
        Date.prototype.days = function (to) {
            return  Math.abs(Math.floor(to.getTime() / (3600 * 24 * 1000)) - Math.floor(this.getTime() / (3600 * 24 * 1000)));
        };

        var currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0)

        var startDate = new Date(header.plc_header_start_date);
        var lastDate = new Date(header.plc_header_last_change);

        var change = header.plc_header_rotating;
        var images = header.plc_header_images.split(',');
        var next = header.plc_header_images_next;
        var count = header.plc_header_images_count;

        var dt = startDate.days(currentDate);
        var dl = lastDate.days(currentDate);

        if ((dt % change === 0 || next === 0) && dl > 0) {
            $('#top-wrap.header_holder').css('background-image', 'url(' + images[next] + ')');
            $('#top-wrap .blur').css('background-image', 'url(' + images[next] + ')');

            jQuery.ajax({
                url: header.ajaxurl,
                type: 'post',
                data: {
                    action: 'upd_next'
                },
                success: function (response) {
                    next = response;
                }
            });

        } else if (dl === 0 || dt % change === 1) {
            $('#top-wrap.header_holder').css('background-image', 'url(' + images[next] + ')');
            $('#top-wrap .blur').css('background-image', 'url(' + images[next] + ')');
        }

    }

    $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true}
        );
    });


    $(document).on("click", ".top-tagline .button", function (e) {
        //$('.takeTour').trigger( "click" );
        if (!$('.tour_container').length) {
            $('body').append(tour);
        }
        $('#tour_1').modal('show');
    });
    $(document).on("click", ".takeTour", function (e) {
        if (!$('.tour_container').length) {
            $('body').append(tour);
        }
        $('#beta-now').modal('hide');
        $('#tour_0').modal('show');
    });
    $(document).on("click", ".nextTour", function (e) {
        id = $(this).attr('data-terget');
        $('#' + id).modal('show');
    });
    $('.plc-modal').on('hidden.bs.modal', function (e) {
        if ($('.modal.in')) {
            //$('body').addClass('modal-open');
        }
    });
    $(document).on('shown.bs.modal', function (event) {
        if ($('body').hasClass('modal-open') == false) {
            $('body').addClass('modal-open');
        } else {
            $('body').css('padding-right', '0px');
        }
    });
    $(document).on('click', '.disabled', function (event) {
        event.preventDefault();
    });
    //google autofill
    if ($('#place-jrrny').length) {
        var input = document.getElementById('place-jrrny');
        var autocomplete = new google.maps.places.Autocomplete(input, {types: ['(cities)']});
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
        });
    }
    if ($('.place-jrrny').length) {
        var input = document.getElementById('place-jrrny-2');
        var autocomplete = new google.maps.places.Autocomplete(input, {types: ['(cities)']});
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
        });
    }
});
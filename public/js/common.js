var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');
var current_refinement="Homes";
const csrf_token = $("input[name='_token']").val();

$('.header_refinement').click(function() {
    current_refinement = $(this).attr('data');
    $(".header_refinement_input").val(current_refinement);
    $(".header_refinement").removeClass("active");
    $(this).addClass("active");
    guests_select_option("#modal_guests", current_refinement);
    guests_select_option("#header-search-guests", current_refinement);
});

guests_select_option("#modal_guests", current_refinement);
guests_select_option("#header-search-guests", current_refinement);

function guests_select_option (select, refinement) {
    if(refinement == 'Homes') {
        $(select+" option:gt(9)").removeAttr('disabled').show();
    } 
    else {
        value = $(select).val();
        if(value-0 > 10) {
            $(select+' option').removeAttr('selected')
        }
        $(select+" option:gt(9)").attr('disabled', true).hide();
    }
}

$(document).ready(function() {
   $(document).on('click','.disable_after_click',function(event) {
    setTimeout( () => $(this).attr('disabled','disabled') ,1);
});

   $('.dropdown.keep-open').on({
    "shown.bs.dropdown": () => this.closable = false,
    "click":             () => this.closable = true,
    "hide.bs.dropdown":  () => this.closable
});

   $('#header-search-checkin').attr('placeholder',datedisplay_format);
   $('#header-search-checkout').attr('placeholder',datedisplay_format);
   $('.explore_check').click(function(e){
    e.stopPropagation();
});
});

$(document).mouseup(function(e) {
    var container = $(".header-dropdown");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
    }
});

$(function() {
    $('#my-element').textfill({
        maxFontPixels: 36
    });
});

function countChar(val) {
    var len = val.value.length;
    if (len > 500) {
      val.value = val.value.substring(0, 500);
      $('#charNum').text(0);
  } else {
      $('#charNum').text(500 - len);
  }
};

function datepicker_hide_on_scroll() {
    $(document).on("click", ".hasDatepicker.ui-datepicker-target", function() {
        if($(this).hasClass('hasDatepicker')){
            $('#ui-datepicker-div').show();
            $(this).datepicker('show');
        }
    });
    if ($(window).width() > 760) {
        datepicker_on_descktop_scroll();
    }
    else {
        datepicker_on_mobile_scroll();
    }
}

function datepicker_hide() {
    $('#ui-datepicker-div').hide();
    $('.hasDatepicker').datepicker("hide");
    $('.hasDatepicker, .ui-datepicker-target').blur();
    $('.tooltip.fade.top.in').hide();
}

function datepicker_on_mobile_scroll() {
    $(window).on("touchmove", function(e){
        datepicker_hide();
    });
    $('.manage-listing-row-container,.manage-listing-content-wrapper,.modal-content,.contact-modal,.sidebar').on("touchmove", function(){
        datepicker_hide();
        $(".ui-datepicker-target").trigger('blur');
    })
}

function datepicker_on_descktop_scroll() {
 $(window).scroll(function(e){
    datepicker_hide();
    // $("body").trigger('mousedown');
});
 $('.manage-listing-row-container,.manage-listing-content-wrapper,.modal-content,.contact-modal,.sidebar').scroll(function(){
    datepicker_hide();
    $("body").trigger('mousedown');
});
}

datepicker_hide_on_scroll();
$(window).resize(function(){
    datepicker_hide_on_scroll();
});

$('#host-profile-contact-btn').click(function() {
    $("body").addClass("pos-fix");
});

$('.modal-close').click(function() {
    $("body").removeClass("pos-fix");
    $('#ui-datepicker-div').hide();
});

//document ready function
$('#accept_submit').attr('disabled', 'disabled');

$(document).ready(function() {
    // user request message check box validation uses for host side
    $(document).on('click', '#tos_confirm', function() {
        if ($('#tos_confirm').val() == 0) {
            $('#accept_submit').removeAttr('disabled');
            $('#tos_confirm').val('1');
        } else {
            $('#accept_submit').attr('disabled', 'disabled');
            $('#tos_confirm').val('0');
        }
    });

    //used for pre approve sucess message remove
    $(document).on('click', '#pre_approve_button', function() {
        $("div").remove(".alert-success");
    });
});

$(function() {
    var targets = $('[rel~=tooltip]'),
    target = false,
    tooltip = false,
    title = false;

    targets.bind('mouseenter', function() {
        target = $(this);
        tip = target.attr('title');
        tooltip = $('<div id="tooltip1"></div>');

        if (!tip || tip == '')
            return false;

        target.removeAttr('title');
        tooltip.css('opacity', 0).html(tip).appendTo('body');

        var init_tooltip = function() {
            if ($(window).width() < tooltip.outerWidth() * 1.5)
                tooltip.css('max-width', $(window).width() / 2);
            else
                tooltip.css('max-width', 340);

            var pos_left = target.offset().left + (target.outerWidth() / 2) - (tooltip.outerWidth() / 2),
            pos_top = target.offset().top - tooltip.outerHeight() - 20;

            if (pos_left < 0) {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass('left');
            } else
            tooltip.removeClass('left');

            if (pos_left + tooltip.outerWidth() > $(window).width()) {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass('right');
            } else
            tooltip.removeClass('right');

            if (pos_top < 0) {
                var pos_top = target.offset().top + target.outerHeight();
                tooltip.addClass('top');
            } else
            tooltip.removeClass('top');

            tooltip.css({
                left: pos_left,
                top: pos_top
            })
            .animate({
                top: '+=10',
                opacity: 1
            }, 50);
        };

        init_tooltip();
        $(window).resize(init_tooltip);

        var remove_tooltip = function() {
            tooltip.animate({
                top: '-=10',
                opacity: 0
            }, 50, function() {
                $(this).remove();
            });

            target.attr('title', tip);
        };

        target.bind('mouseleave', remove_tooltip);
        tooltip.bind('click', remove_tooltip);
    });
});

$(function() {
    $('.host_banner_content_slider_item').hide();
    $('#host_banner_content_slider_item_0').show();
    $("#host_banner_slider").responsiveSlides({
        auto: true,
        pager: false,
        nav: false,
        speed: 2000,
        timeout: 5000,
        namespace: "host_banner_slider_item",
        before: function(index) {
            items_count = $("#host_banners_count").val();
            current_index = $('.' + this.namespace + '2_on').index();
            next_index = current_index + 1;
            if (next_index > items_count) {
                next_index = 0;
            }
            $("#host_banner_content_slider_item_" + current_index).hide();
            $("#host_banner_content_slider_item_" + next_index).fadeIn(1000);
        },
    });
});

if ($('.manage-listing-row-container').hasClass('has-collapsed-nav') === true) {
    $('#js-manage-listing-nav').addClass('manage-listing-nav');
}

$('#room-type-tooltip').mouseover(function() {
    $('.tooltip-room').show();
});

$('#room-type-tooltip').mouseout(function() {
    $('.tooltip-room').hide();
});

$('[id^="amenity-tooltip"]').on("mouseover", function() {
    var id = $(this).data('id');
    $('#ame-tooltip-' + id).show();
});

$('[id^="amenity-tooltip"]').on("mouseout", function() {
    $('[id^="ame-tooltip"]').hide();
});

$('.tool-amenity1').mouseover(function() {
    $('.tooltip-amenity1').show();
});

$('.tool-amenity1').mouseout(function() {
    $('.tooltip-amenity1').hide();
});

$('.tool-amenity2').mouseover(function() {
    $('.tooltip-amenity2').show();
});
$('.tool-amenity2').mouseout(function() {
    $('.tooltip-amenity2').hide();
});

$('a.become').mouseover(function() {
    $('.drop-down-menu-host').show();
});

$('a.become').mouseout(function() {
    $('.drop-down-menu-host').hide();
});

$('.trip-drop').mouseout(function() {
    $('.drop-down-menu-trip').hide();
});

$('.trip-drop').mouseover(function() {
    $('.drop-down-menu-trip').show();
});

$('.inbox-icon').mouseout(function() {
    $('.drop-down-menu-msg').hide();
});

$('.inbox-icon').mouseover(function() {
    $('.drop-down-menu-msg').show();
});

$('.drop-down-menu-host').mouseover(function() {
    $(this).show();
});

$('.drop-down-menu-host').mouseout(function() {
    $(this).hide();
});

$('.drop-down-menu-trip').mouseover(function() {
    $(this).show();
});

$('.drop-down-menu-trip').mouseout(function() {
    $(this).hide();
});

$('.drop-down-menu-msg').mouseover(function() {
    $(this).show();
});

$('.drop-down-menu-msg').mouseout(function() {
    $(this).hide();
});

$('.burger--sm').click(function() {
    $('.header--sm .nav--sm').css('visibility', 'visible');
    $("body").addClass("remove-pos-fix pos-fix");
    $('.makent-header .header--sm .nav-content--sm').addClass('right-content');
});

$('.nav-mask--sm').click(function() {
    $('.header--sm .nav--sm').css('visibility', 'hidden');
    $("body").removeClass("remove-pos-fix pos-fix");
    $('.makent-header .header--sm .nav-content--sm').removeClass('right-content');
});

$('.search-modal-trigger').click(function(e) {
    e.preventDefault();
});

$('.list-nav-link a').click(function() {
    $('.listing-nav-sm').removeClass('collapsed');
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
});

$('#header-avatar-trigger').click(function(e) {
    e.preventDefault();
    $('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').toggle();
    $('.become_dropdown').hide();
});

$('.header-become-host').click(function(e) {
    e.preventDefault();
    $('.become_dropdown').toggle();
});

$('.login_popup_open').click(function(e) {
    $('.become_dropdown').css('display','none');
});

if (typeof(google) == 'undefined') {
    window.location.href = APP_URL + '/in_secure';
}

homeAutocomplete();
var home_autocomplete;
var home_mob_autocomplete;

function homeAutocomplete() {
    if (document.getElementById('location')) {
        home_autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));
        home_autocomplete.addListener('place_changed', trigger_checkin);
    }
    if (document.getElementById('mob-search-location')) {
        home_mob_autocomplete = new google.maps.places.Autocomplete(document.getElementById('mob-search-location'));
        google.maps.event.addListener(home_mob_autocomplete, 'place_changed', function() {
            var location = $('#mob-search-location').val();
            var locations = location.replace(" ", "+");
            window.location.href = APP_URL + '/s?location=' + locations;
        });
    }
}

var current_url = window.location.href.split('?')[0];
var last_part = current_url.substr(current_url.lastIndexOf('/'));
var last_part1 = current_url.substr(current_url.lastIndexOf('/') + 1);
if (last_part != '/s') {
    headerAutocomplete();
} 
else {
    $("#header-search-form-mob").keypress(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });
}

$('#header-search-form').keydown(function (e) {
    if (e.which == 13 && $('.pac-container:visible').length) {
        e.preventDefault();
    }else if (e.which == 13){
        e.preventDefault();
        if (last_part != '/s') {
            $('.search-form').submit();
        }else{
            $('.search-settings').addClass('shown');
        }
    };
});

var header_autocomplete;
var sm_autocomplete;

function headerAutocomplete() {
    if (document.getElementById('header-search-form')) {
        header_autocomplete = new google.maps.places.Autocomplete(document.getElementById('header-search-form'));
        google.maps.event.addListener(header_autocomplete, 'place_changed', function() {
            var searchPlace = header_autocomplete.getPlace();
            if(!searchPlace.geometry) {
                return false;
            }
            $('.home_latitude').val(searchPlace.geometry.location.lat());
            $('.home_longitude').val(searchPlace.geometry.location.lng());
            $('#header-search-settings').addClass('shown');
            $("#header-search-checkin").trigger('click');
            $(".webcot-lg-datepicker button").trigger("click");
        });
    }

    if (document.getElementById('header-search-form-mob')) {
        sm_autocomplete = new google.maps.places.Autocomplete(document.getElementById('header-search-form-mob'));
        google.maps.event.addListener(sm_autocomplete, 'place_changed', function() {
            var searchPlace = sm_autocomplete.getPlace();
            if(!searchPlace.geometry) {
                return false;
            }
            $('.home_latitude').val(searchPlace.geometry.location.lat());
            $('.home_longitude').val(searchPlace.geometry.location.lng());

            $("#header-search-form").val($("#header-search-form-mob").val());
            $("#modal_checkin").trigger('click');
        });
    }
}

start = moment();
$('#header-search-checkin').daterangepicker({
    minDate: start,
    dateLimitMin:{
     "days": 1
 },
 autoApply: true,
 parentEl: '#header-search-settings',
 autoUpdateInput: false,
 locale: {
    format: daterangepicker_format
},
});

$('#header-search-checkin').on('apply.daterangepicker', function(ev, picker) {
    startDateInput = $('input[name="checkin"]');
    endDateInput = $('input[name="checkout"]');
    startDate = picker.startDate;
    endDate = picker.endDate;

    $('#header-search-checkout').data('daterangepicker').setStartDate(startDate);
    $('#header-search-checkout').data('daterangepicker').setEndDate(endDate);

    startDateInput.val(startDate.format(daterangepicker_format));
    endDateInput.val(endDate.format(daterangepicker_format));
    $('#header-search-checkin').val(startDate.format(daterangepicker_format));
    $('#header-search-checkout').val(endDate.format(daterangepicker_format)); 
});

$('#header-search-checkout').daterangepicker({
    minDate: start,
    dateLimitMin:{
     "days": 1
 },
 autoApply: true,
 autoUpdateInput: false,
 parentEl: '#header-search-settings',
 locale: {
    format: daterangepicker_format
},
});

$('#header-search-checkout').on('apply.daterangepicker', function(ev, picker) {
    startDateInput = $('input[name="checkin"]');
    endDateInput = $('input[name="checkout"]');

    startDate = picker.startDate;
    endDate = picker.endDate;

    $('#header-search-checkin').data('daterangepicker').setStartDate(startDate);
    $('#header-search-checkin').data('daterangepicker').setEndDate(endDate);

    startDateInput.val(startDate.format(daterangepicker_format));
    endDateInput.val(endDate.format(daterangepicker_format));
    $('#header-search-checkin').val(startDate.format(daterangepicker_format));
    $('#header-search-checkout').val(endDate.format(daterangepicker_format)); 
});

start = moment();
$('#modal_checkin').daterangepicker({
    minDate: start,
    dateLimitMin:{
     "days": 1,
 },
 autoApply: true,
 parentEl: '.checkin_div',
 autoUpdateInput: false,
    // singleDatePicker: true,
    linkedCalendars:false,
    locale: {
        format: daterangepicker_format
    },
});

$('#modal_checkin').on('apply.daterangepicker', function(ev, picker) {
    startDateInput = $('#modal_checkin');
    endDateInput = $('#modal_checkout');

    startDate = picker.startDate;
    endDate = picker.endDate;

    $('#modal_checkout').data('daterangepicker').setStartDate(startDate);
    $('#modal_checkout').data('daterangepicker').setEndDate(endDate);

    startDateInput.val(startDate.format(daterangepicker_format));
    endDateInput.val(endDate.format(daterangepicker_format));
});

$('#modal_checkout').daterangepicker({
    minDate: start,
    dateLimitMin:{
     "days": 1
 },
 autoApply: true,
 parentEl: '.checkout_div',
 autoUpdateInput: false,
    // singleDatePicker: true,
    linkedCalendars:false,
    locale: {
        format: daterangepicker_format
    },
});

$('#modal_checkout').on('apply.daterangepicker', function(ev, picker) {
    startDateInput = $('#modal_checkin');
    endDateInput = $('#modal_checkout');

    startDate = picker.startDate;
    endDate = picker.endDate;

    $('#modal_checkin').data('daterangepicker').setStartDate(startDate);
    $('#modal_checkin').data('daterangepicker').setEndDate(endDate);

    startDateInput.val(startDate.format(daterangepicker_format));
    endDateInput.val(endDate.format(daterangepicker_format));
});

$('#location, #header-location-sm').keyup(function() {
    $('.search_location_error').addClass('d-none');
});

$(document).ready(function() {
    $('#submit_location').click(function(event) {
        if (typeof $(this).attr('search_type') === 'undefined') {
            $('.search_location_error').addClass('d-none');
            if($('#header-search-form').val() == '') {
                $('.set_location').removeClass('d-none');
                event.preventDefault();
            }
            else if($('.home_latitude').val() == '' || $('.home_longitude').val() == '') {
                $('.invalid_location').removeClass('d-none');
                event.preventDefault();
            }
        }
    });

    $('#header-search-form').keyup(function(e) {
        if (e.keyCode != 13) {
            $('.home_latitude').val('');
            $('.home_longitude').val('');
            $('.search_location_error').addClass('d-none');
        }
    })

    $('[data-toggle="tooltip"]').tooltip(); 
});

$('.search-form').submit(function(event) {
    var header_checkin = $('input[name="checkin"]').val();
    var header_checkout = $('input[name="checkout"]').val();
    var header_guests = $('#header-search-guests').val();
    var header_room_type = '';

    var sm_room_type = '';
    var sm_cat_type = '';

    $('.head_room_type:checked').each(function(i) {
        sm_room_type += $(this).val() + ',';
    });

    $('.head_cat_type:checked').each(function(i) {
        sm_cat_type += $(this).val() + ',';
    });

    sm_room_type = sm_room_type.slice(0, -1);
    sm_cat_type = sm_cat_type.slice(0, -1);

    var location = $('#header-search-form').val();
    var locations = location.replace(" ", "+");
    window.location.href = APP_URL + '/s?location=' + locations + '&checkin=' + header_checkin + '&checkout=' + header_checkout + '&guests=' + header_guests + '&room_type=' + sm_room_type +'&host_experience_category='+sm_cat_type+ '&current_refinement=' + current_refinement;
    event.preventDefault();
});

$('#search-form-sm-btn').click(function(event) {
    var location = $('#header-search-form-mob').val();
    $('.search_location_error').addClass('d-none');
    if(location == '') {
        $('.set_location').removeClass('d-none');
        event.preventDefault();
        return false;
    }

    if(last_part != '/s' && ($('.home_latitude').val() == '' || $('.home_longitude').val() == '')) {
        $('.invalid_location').removeClass('d-none');
        event.preventDefault();
        return false;
    }

    var sm_checkin = $('#modal_checkin').val();
    var sm_checkout = $('#modal_checkout').val();
    var sm_guests = $('#modal_guests').val();
    var sm_room_type = '';
    var sm_cat_type = '';

    $('.mob_room_type').each(function() {
        if ($(this).is(':checked'))
            sm_room_type += $(this).val() + ',';
    });
    $('.mob_cat_type').each(function() {
        if ($(this).is(':checked'))
            sm_cat_type += $(this).val() + ',';
    });
    sm_room_type = sm_room_type.slice(0, -1);
    sm_cat_type = sm_cat_type.slice(0, -1);
    var locations="";

    if(location) { 
        locations = location.replace(" ", "+"); 
    }

    window.location.href = APP_URL + '/s?location=' + locations + '&checkin=' + sm_checkin + '&checkout=' + sm_checkout + '&guests=' + sm_guests + '&room_type=' + sm_room_type +'&host_experience_category='+sm_cat_type+ '&current_refinement=' + current_refinement;
    event.preventDefault();
});

// Hide header search form when click outside of that container
$('html').click(function() {
    if (last_part != '/s') {
        $("#header-search-settings").removeClass('shown');
    }
});

//click download app scroll down the page
$(document).on('click', '.menu-item', function() {
    var link = $(this).attr('href');
    if (link == '#') {
        $('body').removeClass('pos-fix');
    }
});

$('#header-search-settings').click(function(event) {
    event.stopPropagation();
});
$('#ui-datepicker-div').click(function(event) {
    event.stopPropagation();
});
$('.daterangepicker').click(function(event) {
    event.stopPropagation();
});

function trigger_checkin() {
    $("#checkin").trigger("click");
}

if ($(".search-bar").length) {
    start = moment();
    $('#checkin').daterangepicker({
        minDate: start,
        dateLimitMin : {
            "days" :1
        },
        autoApply: true,
        autoUpdateInput: false,
        locale: {
            format: daterangepicker_format
        },
    });

    $('#checkin').on('apply.daterangepicker', function(ev, picker) {
        startDateInput = $('input[name="checkin"]');
        endDateInput = $('input[name="checkout"]');

        startDate = picker.startDate;
        endDate = picker.endDate;

        $('#checkout').data('daterangepicker').setStartDate(startDate);
        $('#checkout').data('daterangepicker').setEndDate(endDate);

        startDateInput.val(startDate.format(daterangepicker_format));
        endDateInput.val(endDate.format(daterangepicker_format));
        $('#checkin').val(startDate.format(daterangepicker_format));
        $('#checkout').val(endDate.format(daterangepicker_format)); 
    });

    $('#checkout').daterangepicker({
        minDate: start,
        dateLimitMin : {
            "days" :1
        },
        autoApply: true,
        autoUpdateInput: false,
        locale: {
            format: daterangepicker_format
        },
    });

    $('#checkout').on('apply.daterangepicker', function(ev, picker) {
        startDateInput = $('input[name="checkin"]');
        endDateInput = $('input[name="checkout"]');

        startDate = picker.startDate;
        endDate = picker.endDate;

        $('#checkin').data('daterangepicker').setStartDate(startDate);
        $('#checkin').data('daterangepicker').setEndDate(endDate);

        startDateInput.val(startDate.format(daterangepicker_format));
        endDateInput.val(endDate.format(daterangepicker_format));
        $('#checkin').val(startDate.format(daterangepicker_format));
        $('#checkout').val(endDate.format(daterangepicker_format)); 
    });
}

// Coupon Code
app.controller('payment', ['$scope', '$http', function($scope, $http) {

    // Stripe 3D Secure Payment Starts
    var stripe = Stripe(STRIPE_PUBLISH_KEY);
    $(document).ready(function() {
        if(payment_intent_client_secret != '') {
            $scope.handleServerResponse(payment_intent_client_secret);
        }
    });

    $scope.handleServerResponse = function (payment_intent_client_secret) {
        stripe.handleCardAction(payment_intent_client_secret)
        .then(function(result) {
          if (result.error) {
            // Show error in payment form
          }
          else {
            // The card action has been handled & The PaymentIntent can be confirmed again on the server
            $('#payment_intent_id').val(result.paymentIntent.id);
            // Disable Payment Button and confirm Booking
            $scope.disableButton();
          }
        });
    };
    // Stripe 3D Secure Payment Ends

    $('.open-coupon-section-link').click(function() {
        $(".coupon-input").removeClass("d-none");
        $(".coupon-input").addClass("d-flex");
        $('#restrict_apply').hide();
        $('#coupon_disabled_message').show();
        $('.cancel-coupon').show();
    });

    $('.cancel-coupon').click(function() {
        $(".coupon-input").removeClass("d-flex");
        $(".coupon-input").addClass("d-none");
        $('#restrict_apply').show();
        $('#coupon_disabled_message').hide();
        $(this).hide();
    });

    $('#apply-coupon').click(function() {
        var coupon_code = $('.coupon-code-field').val();
        var sessionkey = $("input[name=session_key]").val();
        var coupon_url = APP_URL + '/payments/apply_coupon';
        var token = $("input[name=guest_token]").val();
        if(token)
            coupon_url = APP_URL + '/api_payments/apply_coupon?token='+token;
        $http.post(coupon_url, {
            coupon_code: coupon_code,
            s_key: sessionkey
        }).then(function(response) {
            if (response.data.message) {
                $("#coupon_disabled_message").show();
                $('#coupon_disabled_message').text(response.data.message);
                $("#after_apply_remove").hide();
            } else {
                $("#coupon_disabled_message").hide();
                $("#restrict_apply").hide();
                $("#after_apply").hide();
                $("#after_apply_remove").show();
                $("#after_apply_coupon").show();
                $("#after_apply_amount").show();
                $('#applied_coupen_amount').text(response.data.coupon_amount);
                $('#payment_total').text(response.data.coupen_applied_total);
                window.location.reload();
            }
        });
    });

    $('#remove_coupon').click(function() {
        var coupon_url = APP_URL + '/payments/remove_coupon';
        var token = $("input[name=guest_token]").val();
        if(token)
            coupon_url = APP_URL + '/api_payments/remove_coupon?token='+token;
        $http.post(coupon_url, {}).then(function(response) {
            window.location.reload();
        });
    });

    $scope.disableButton = function() {
        $("#checkout-form").submit();
        $("#payment-form-submit").attr('disabled','disabled');
        $("#checkout-form :input").prop("disabled", true);
    }
}]);
// Coupon Codeopen-coupon-section-link 
$(document).ready(function(){
    $('#payment-method-select').trigger('change')
})
$(document).on('change', '#payment-method-select', function() {
    if ($(this).val() == 'paypal') {
        $('#payment-method-cc').hide();
        $('.bill-info-wrap').hide();
        $('.cc').hide();
        $('.' + $(this).val()).removeClass('d-none');
        $('.' + $(this).val() + ' > .payment-logo').removeClass('inactive');
    } else {
        $('#payment-method-cc').show();        
        $('.bill-info-wrap').show();
        $('.cc').show();
        $('.paypal').addClass('d-none');
        $('.paypal > .payment-logo').addClass('inactive');
    }
    $('[name="payment_method"]').val($(this).val());
});
//change for mobile
$(document).ready(function() {
    setTimeout(function() {
        if ($('#payment-method-select').val() == 'paypal') {
            $('#payment-method-cc').hide();
            $('.cc').hide();
            $('.' + $('#payment-method-select').val()).addClass('active');
            $('.' + $('#payment-method-select').val()).addClass('active');
            $('.' + $('#payment-method-select').val() + ' > .payment-logo').removeClass('inactive');
        } else {
            $('#payment-method-cc').show();
            $('.cc').show();
            $('.paypal').removeClass('active');
            $('.paypal > .payment-logo').addClass('inactive');
        }
        $('[name="payment_method"]').val($('#payment-method-select').val());
    }, 1000);
});
//end change for mobile.

$('#country-select').change(function() {
    $('#billing-country').text($("#country-select option:selected").text());
    $('[name="country"]').val($(this).val());
});

$('#billing-country').text($("#country-select option:selected").text());
$('[name="country"]').val($("#country-select option:selected").val());
var previous_currency;
app.controller('footer', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {

    // assign Inbox Count to rootscope - to access this rootscope from other angular controller 
    $rootScope.inbox_count = inbox_count;

    $("#currency_footer").click(function() {
        // Store the current value on focus, before it changes
        previous_currency = this.value;
    }).change(function() {
        $http.post(APP_URL + "/set_session", {
            currency: $(this).val(),
            previous_currency: previous_currency,
            headers: { 'X-CSRF-TOKEN': csrf_token },
        }).then(function(data) {
            location.reload();
        });
    });

    $('#language_footer').change(function() {
        $http.post(APP_URL + "/set_session", {
            language: $(this).val(),
            headers: { 'X-CSRF-TOKEN': csrf_token }
        }).then(function(data) {
            location.reload();
        });
    });

    $('.room_status_dropdown').change(function() {
        var data_params = {};

        data_params['status'] = $(this).val();

        var data = JSON.stringify(data_params);

        var id = $(this).attr('data-room-id');

        $http.post('manage-listing/' + id + '/update_rooms', {
            data: data
        }).then(function(response) {
            if (data_params['status'] == 'Unlisted') {
                $('[data-room-id="div_' + id + '"] > i').addClass('dot-danger');
                $('[data-room-id="div_' + id + '"] > i').removeClass('dot-success');
            } else if (data_params['status'] == 'Listed') {
                $('[data-room-id="div_' + id + '"] > i').removeClass('dot-danger');
                $('[data-room-id="div_' + id + '"] > i').addClass('dot-success');
            }
        });
    });

    $(document).on('click', '.create-wl', function() {
        $('.wl-modal-form').removeClass('d-none');
        $(this).addClass('d-none');
    });

    $('#send-email').unbind("click").click(function() {
        var emails = $('#email-list').val();
        if (emails != '') {
            $http.post('invite/share_email', {
                emails: emails
            }).then(function(response) {
                if (response.data == true) {
                    $('#success_message').fadeIn(800);
                    $('#success_message').fadeOut();
                    $('#email-list').val('');
                } else {
                    $('#error_message').fadeIn(800);
                    $('#error_message').fadeOut();
                }
            });
        }
    });

}]);

app.controller('payout_preferences', ['$scope', '$http', function($scope, $http) {

    $scope.disablePayoutOption = function(event,target_base_url,payout_id) {
        if($scope.isDisabled == true) {
            event.preventDefault();
            return true;
        }
        $scope.isDisabled = true;
        $('.payout_options').addClass('disabled');
        window.location.href = target_base_url+payout_id;
    };

    $(document).ready(function () {  
        $("#ssn_last_4").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {            
                return false;
            }
        });
    });

    $('#address').submit(function() {
        $('#address .text-danger').addClass('d-none');
        var blank_error = false;
        if ($('#payout_info_payout_address1').val().trim() == '') {
            $('.address1_error').removeClass('d-none');
            blank_error = true;
        }
        if ($('#payout_info_payout_city').val().trim() == '') {
            $('.city_error').removeClass('d-none');
            blank_error = true;
        }
        if ($('#payout_info_payout_zip').val().trim() == '') {
            $('.postal_error').removeClass('d-none');
            blank_error = true;
        }
        if ($('#payout_info_payout_country').val().trim() == null) {
            $('.country_error').removeClass('d-none');
            blank_error = true;
        }
        if(blank_error) {
            return false;
        }
        $('#payout_info_payout2_address1').val($('#payout_info_payout_address1').val());
        $('#payout_info_payout2_address2').val($('#payout_info_payout_address2').val());
        $('#payout_info_payout2_city').val($('#payout_info_payout_city').val());
        $('#payout_info_payout2_state').val($('#payout_info_payout_state').val());
        $('#payout_info_payout2_zip').val($('#payout_info_payout_zip').val());
        $('#payout_info_payout2_country').val($('#payout_info_payout_country').val());

        $('#payout-preference-popup1').modal('hide');
        setTimeout(function(){
            $('#payout-preference-popup2').modal('show');
        }, 1000);
    });

    $('#payout_info_payout_country').change(function() {
        $scope.country = $(this).val();
        $('#payout_info_payout_country1').val($(this).val());
        if($('#payout_info_payout_country1').val() == '' || $('#payout_info_payout_country1').val() == undefined)
        {            
            $("#payout_info_payout_country1").val('');
            $scope.payout_country = '';
            $scope.payout_currency = '';
        }
        else
        {
            $scope.payout_country = $(this).val();
            $('#payout_info_payout_country1').trigger("change");
            $scope.change_currency();
        }        
    });

    $('#select-payout-method-submit').click(function() {

        if ($scope.payout_method == undefined) {
            return false;
        }

        $('#payout_info_payout3_address1').val($('#payout_info_payout2_address1').val());
        $('#payout_info_payout3_address2').val($('#payout_info_payout2_address2').val());
        $('#payout_info_payout3_city').val($('#payout_info_payout2_city').val());
        $('#payout_info_payout3_state').val($('#payout_info_payout2_state').val());
        $('#payout_info_payout3_zip').val($('#payout_info_payout2_zip').val());
        $('#payout_info_payout3_country').val($('#payout_info_payout2_country').val());
        $('#payout3_method').val($scope.payout_method);

        $('#payout_info_payout4_address1').val($('#payout_info_payout2_address1').val());
        $('#payout_info_payout4_address2').val($('#payout_info_payout2_address2').val());
        $('#payout_info_payout4_city').val($('#payout_info_payout2_city').val());
        $('#payout_info_payout4_state').val($('#payout_info_payout2_state').val());
        $('#payout_info_payout4_zip').val($('#payout_info_payout2_zip').val());
        $('#payout_info_payout4_country').val($('#payout_info_payout2_country').val());
        $('#payout4_method').val($('[id="payout2_method"]:checked').val());

        payout_method = $("#payout3_method").val();
        if(payout_method == 'Stripe')
        {
            $('#payout-preference-popup2').modal('hide');
            setTimeout(function(){
               $('#payout_popupstripe').modal('show');
           }, 1000);            
        }
        else
        {
            $('#payout-preference-popup2').modal('hide');
            setTimeout(function(){
               $('#payout-prefernce-popup3').modal('show');
           }, 1000);      
        }        
    });
    
    $('#payout_paypal').submit(function() {
        payout_method = $("#payout3_method").val();
        $('.paypal_email_error').addClass('d-none');
        if(payout_method != 'PayPal') {
            return true;
        }
        var validation_container = '<div class="alert alert-error alert-error alert-header"><a class="close alert-close" href="javascript:void(0);"></a><i class="icon alert-icon icon-alert-alt"></i>';
        var emailChar = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (emailChar.test($('#paypal_email').val())) {
            return true;
        }
        $('.paypal_email_error').removeClass('d-none');
        return false;
    });

    // change currency based on country selected
    $scope.change_currency = function()
    {        
        var selected_country = [];
        angular.forEach($scope.country_currency, function(value, key) {          
          if($('#payout_info_payout_country1').val() == key)
           selected_country = value;
   });
        
        if(selected_country)
        {
            var $el = $("#payout_info_payout_currency");
                    $el.empty(); // remove old options
                    $.each(selected_country, function(key,value) {
                      $el.append($("<option></option>")
                       .attr("value", value).text(value));
                      if($scope.old_currency != '')
                      {

                        $('#payout_info_payout_currency').val($scope.payout_currency);
                    }
                    else
                    {

                        $('#payout_info_payout_currency').val(selected_country[0]);
                    }


                });
                    
                    if($('#payout_info_payout_country1').val() == 'GB' && $('#payout_info_payout_currency').val() == 'EUR')
                    {
                     $('.routing_number_cls').addClass('d-none');
                     $('.account_number_cls').html('IBAN');

                 }
                 else
                 {
                    $('.routing_number_cls').removeClass('d-none');
                    $('.account_number_cls').html('Account Number');
                }
            }
            else
            {
                var $el = $("#payout_info_payout_currency");
                    $el.empty(); // remove old options                   
                    $el.append($("<option></option>")
                       .attr("value", '').text('Select'));
                    
                }
                
                if($('#payout_info_payout_currency').val() == '' || $('#payout_info_payout_currency').val() == null)
                {

                    $("#payout_info_payout_currency").val($("#payout_info_payout_currency option:first").val());
                }

            }

            $(document).on('change', '#payout_info_payout_country1', function() {

                $scope.change_currency();

                if($('#payout_info_payout_country1').val() == 'GB' && $('#payout_info_payout_currency').val() == 'EUR')
                {
                 $('.routing_number_cls').addClass('d-none');
                 $('.account_number_cls').html('IBAN');

             }
             else
             {
                $('.routing_number_cls').removeClass('d-none');
                $('.account_number_cls').html('Account Number');
            }
            $scope.payout_currency = $('#payout_info_payout_currency').val();
            $("#payout_info_payout_currency").val($("#payout_info_payout_currency option:first").val());
            $('#payout_info_payout_country').val($('#payout_info_payout_country1').val());

        });

            $(document).on('change', '#payout_info_payout_currency', function() {
                $scope.payout_currency = $('#payout_info_payout_currency').val()
                if($('#payout_info_payout_country1').val() == 'GB' && $('#payout_info_payout_currency').val() == 'EUR') {
                 $('.routing_number_cls').addClass('d-none');
                 $('.account_number_cls').html('IBAN');
             }
             else {
                $('.routing_number_cls').removeClass('d-none');
                $('.account_number_cls').html('Account Number');
            }

        });

    // set publishable key for stripe validation on js //
    var stripe_publish_key = document.getElementById("stripe_publish_key").value;
    var stripe = Stripe.setPublishableKey(stripe_publish_key);

    $('#payout_stripe').submit(function() {

        $('#payout_info_payout4_address1').val($('#payout_info_payout_address1').val());
        $('#payout_info_payout4_address2').val($('#payout_info_payout_address2').val());
        $('#payout_info_payout4_city').val($('#payout_info_payout_city').val());
        $('#payout_info_payout4_state').val($('#payout_info_payout_state').val());
        $('#payout_info_payout4_zip').val($('#payout_info_payout_zip').val());        

        // check stripe token already exist
        stripe_token = $("#stripe_token").val();
        if(stripe_token != ''){
            return true;
        }
        // required field validation --start-- //
        if($('#payout_info_payout_country1').val() == '') {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }

        if($('#payout_info_payout_currency').val() == '') {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }

        if($('#holder_name').val() == '') {
            $("#stripe_errors").html('Please fill all required fields');               
            return false;
        }
        
        is_iban = $('#is_iban').val();
        is_branch_code = $('#is_branch_code').val();

        // bind bank account params to get stripe token
        var bankAccountParams = {
          country: $('#payout_info_payout_country1').val(),
          currency: $('#payout_info_payout_currency').val(),              
          account_number: $('#account_number').val(),
          account_holder_name: $('#holder_name').val(),
          account_holder_type: $('#holder_type').val()
        };
        // check whether iban supported country or not for bind routing number
        if(is_iban == 'No') {
            if(is_branch_code == 'Yes') {
                // here routing number is combination of routing number and branch code
                if($('#payout_info_payout_country1').val() == 'CA' || $('#payout_info_payout_country1').val() == 'SG') {
                    if($('#bank_name').val() == '') {
                        $("#stripe_errors").html('Please fill all required fields');
                        return false;
                    }
                }
                if($('#payout_info_payout_country1').val() != 'GB' && $('#payout_info_payout_currency').val() != 'EUR') {
                    if($('#routing_number').val() == '') {
                        $("#stripe_errors").html('Please fill all required fields');
                        return false;
                    }
                    if($('#branch_code').val() == '') {
                        $("#stripe_errors").html('Please fill all required fields');
                        return false;
                    }
                   
                    if($('#payout_info_payout_country1').val() != 'HK' ) {
                        bankAccountParams.routing_number = $('#routing_number').val()+$('#branch_code').val();
                    }
                    else {
                        bankAccountParams.routing_number = $('#routing_number').val()+'-'+$('#branch_code').val();                        
                    }
                }
            }
            else {
                if($('#payout_info_payout_country1').val() != 'GB' && $('#payout_info_payout_currency').val() != 'EUR') {
                    if($('#routing_number').val() == '') {
                        $("#stripe_errors").html('Please fill all required fields');                
                        return false;
                    }
                    bankAccountParams.routing_number = $('#routing_number').val();
                }
            }
        }

        // required field validation --end-- //
        $('#payout_stripe').addClass('loading');
        country = $scope.payout_country;
        Stripe.bankAccount.createToken(bankAccountParams, stripeResponseHandler);
        return false;
    });

    $('.panel-close').click(function() {
        $(this).parent().parent().parent().parent().parent().addClass('d-none');
    });

    $('[id$="_flash-container"]').on('click', '.alert-close', function() {
        $(this).parent().parent().html('');
    });

    // response handler function from for create stripe token
    function stripeResponseHandler(status, response) {

       $('#payout_stripe').removeClass('loading');

       if (response.error) {       
          $("#stripe_errors").html("");
          if(response.error.message == "Must have at least one letter"){
            $("#stripe_errors").html('Please fill all required fields');
        }else{
            $("#stripe_errors").html(response.error.message); 
        }
        return false;
    } else {
      $("#stripe_errors").html("");
      var token = response['id'];
      $("#stripe_token").val(token); 
      $('#payout_stripe').removeClass('loading');
      $("#payout_stripe").submit();
      return true;
  }
}



}]);

app.directive('postsPaginationTransaction', function() {
    return {
        restrict: 'E',
        template: '<h4 class="status-text text-center" ng-show="loading">{{trans_lang.loading}}...</h4><h4 class="status-text text-center" ng-hide="result.length || loading">{{trans_lang.no_transactions}}</h4><ul class="pagination" ng-show="result.length">' +
        '<li ng-show="currentPage > 1"><a href="javascript:void(0)" ng-click="pagination_result(type, 1)">&laquo;</a></li>' +
        '<li ng-show="currentPage > 1"><a href="javascript:void(0)" ng-click="pagination_result(type, currentPage-1)">&lsaquo; ' + $('#pagin_prev').val() + ' </a></li>' +
        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
        '<a href="javascript:void(0)" ng-click="pagination_result(type, i)">{{i}}</a>' +
        '</li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="pagination_result(type, currentPage+1)">' + $('#pagin_next').val() + ' &rsaquo;</a></li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="pagination_result(type, totalPages)">&raquo;</a></li>' +
        '</ul>'
    };
}).controller('transaction_history', ['$scope', '$http', function($scope, $http) {

    $scope.paid_out = 0;

    $('li > .tab-item').click(function() {
        var tab_name = $(this).attr('aria-controls');
        var tab_selected = $(this).attr('aria-selected');
        if (tab_selected == 'false') {
            $('.tab-item').attr('aria-selected', 'false');
            $(this).attr('aria-selected', 'true');
            $('.tab-panel').hide();
            $('#' + tab_name).show();
        }
        $scope.type = tab_name;
        $scope.pagination_result(tab_name, 1);
    });

    $scope.pagination_result = function(type, page) {
        var data_params = {};

        data_params['type'] = type;

        data_params['payout_method'] = $('#' + data_params['type'] + ' .payout-method').val();
        data_params['listing'] = $('#' + data_params['type'] + ' .payout-listing').val();
        data_params['year'] = $('#' + data_params['type'] + ' .payout-year').val();
        data_params['start_month'] = $('#' + data_params['type'] + ' .payout-start-month').val();
        data_params['end_month'] = $('#' + data_params['type'] + ' .payout-end-month').val();
        data_params['end_month'] = (data_params['end_month']==null)?"12":data_params['end_month'];

        if (parseInt(data_params['start_month']) > parseInt(data_params['end_month'])) {
            data_params['end_month'] = data_params['start_month']
            $scope.payout_endMonth = data_params['start_month'];
        }

        var data = JSON.stringify(data_params);

        if (page == undefined)
            page = 1;

        if (type == 'completed-transactions')
            $scope.completed_csv_param = 'type=' + data_params['type'] + '&payout_method=' + data_params['payout_method'] + '&listing=' + data_params['listing'] + '&year=' + data_params['year'] + '&start_month=' + data_params['start_month'] + '&end_month=' + data_params['end_month'] + '&page=' + page;

        if (type == 'future-transactions')
            $scope.future_csv_param = 'type=' + data_params['type'] + '&payout_method=' + data_params['payout_method'] + '&listing=' + data_params['listing'] + '&page=' + page;

        $scope.result_show = false;
        $scope.loading = true;
        $http.post(APP_URL + '/users/result_transaction_history?page=' + page, {
            data: data
        }).then(function(response) {
            $scope.loading = false;
            $scope.result = response.data.data;
            if ($scope.result.length != 0) {
                $scope.result_show = true;
                $scope.totalPages = response.data.last_page;
                $scope.currentPage = response.data.current_page;
                $scope.type = type;

                var pages = [];
                for (var i = 1; i <= response.data.last_page; i++) {
                    pages.push(i);
                }
                $scope.range = pages;

                var total = 0;
                for (var i = 0; i < $scope.result.length; i++) {
                    total += $scope.result[i].amount;
                }
                $scope.paid_out = $scope.result[0].currency_symbol + total;
            }
        });
    }

    $scope.pagination_result('completed-transactions', 1);

}]);

app.controller('reviews', ['$scope', '$http', function($scope, $http) {

    $('li > .tab-item').click(function() {
        var tab_name = $(this).attr('aria-controls');
        var tab_selected = $(this).attr('aria-selected');
        if (tab_selected == 'false') {
            $('.tab-item').attr('aria-selected', 'false');
            $(this).attr('aria-selected', 'true');
            $('.tab-panel').hide();
            $('#' + tab_name).show();
        }
    });

}]);

app.controller('help', ['$scope', '$http', function($scope, $http) {

    $('.help-nav .navtree-list .navtree-next').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('.help-nav #navtree').addClass('active').removeClass('not-active');
        $('.help-nav .subnav-list li:first-child a').attr('aria-selected', 'false');
        $('.help-nav .subnav-list').append('<li> <a class="subnav-item" href="#" data-node-id="0" aria-selected="true"> ' + name + ' </a> </li>');
        $('.sidenav-item').addClass('d-none');
        $('.sidenav-item-'+id+','+' .sidenav-item-'+id+' .sidenav-item').removeClass('d-none');
        $(this).addClass('d-none');
        $('.help-nav #navtree-'+id).show();
    });

    $('.help-nav .navtree-list .navtree-back').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('.help-nav #navtree').removeClass('active');
        $('.help-nav #navtree').addClass('not-active');
        $('.sidenav-item').removeClass('d-none');
        $('[data-id="'+id+'"]').removeClass('d-none');
        $('.help-nav .subnav-list li:first-child a').attr('aria-selected', 'true');
        $('.help-nav .subnav-list li').last().remove();
        $('.help-nav #navtree-' + id).hide()
    });

    $('#help_search').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: APP_URL + "/ajax_help_search",
                type: "GET",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                    $(this).removeClass('ui-autocomplete-loading');
                }
            });
        },
        search: function() {
            $(this).addClass('loading');
        },
        open: function() {
            $(this).removeClass('loading');
        },
        select: function(event, ui) {
            if(ui.item.target != undefined) {
                window.location = ui.item.target;
            }
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item) {
        if (item.id != 0) {
            $('#help_search').removeClass('ui-autocomplete-loading');
            return $("<li>")
            .append("<a href='"+item.target+"' class='d-flex align-items-center'><i class='icon icon-description mr-2'></i>" + item.value + "</a>")
            .appendTo(ul);
        } 
        else {
            $('#help_search').removeClass('ui-autocomplete-loading');
            return $("<li style='pointer-events: none;'>")
            .append("<span class='d-flex align-items-center'><i class='icon icon-description mr-2'></i>" + item.value + "</span>")
            .appendTo(ul);
        }
    };
}]);

app.controller('reviews_edit_host', ['$scope', '$http', function($scope, $http) {
    $('.next-facet').click(function() {
        $('#double-blind-copy').addClass('d-none');
        $('#host-summary').removeClass('d-none');
        $('#guest').removeClass('d-none');
    });

    $('.exp_review_submit').click(function() {
        var section = $(this).parent().parent().attr('id');

        var data_params = {};

        $('#' + section + '-form input, #' + section + ' textarea').each(function() {
            if ($(this).attr('type') != 'radio') {
                data_params[$(this).attr('name')] = $(this).val();
            } else {
                if ($(this).is(':checked'))
                    data_params[$(this).attr('name')] = $(this).val();
            }
        });

        var id = $('#reservation_id').val();
        if (section == 'host-summary' || section == 'guest') {
            if ($('#review_private_feedback').val() == '') {
                $('[for="review_private_feedback"]').show();
                $('#review_private_feedback').addClass('invalid');
                return false;
            } else {
                $('[for="review_private_feedback"]').hide();
                $('#review_private_feedback').removeClass('invalid');
            }

            if (section == 'host-summary') {
                if ($('#improve_comments').val() == '') {
                    $('[for="review_private_feedback"]').show();
                    $('#improve_comments').addClass('invalid');
                    return false;
                } else {
                    $('[for="review_private_feedback"]').hide();
                    $('#improve_comments').removeClass('invalid');
                }
                if (!$('[name="rating"]').is(':checked')) {
                    $('[for="review_rating"]').show();
                    return false;
                } else
                $('[for="rating"]').hide();
            }

            if (section == 'guest') {
                if (!$('[name="cleanliness"]').is(':checked')) {
                    $('[for="review_rating"]').show();
                    return false;
                } else
                $('[for="review_rating"]').hide();
            }
        }

        data_params['review_id'] = $('#review_id').val();

        var data = JSON.stringify(data_params);

        $('.review-container').addClass('loading');
        $http.post(APP_URL + '/host_experience_reviews/edit/' + id, {
            data: data
        }).then(function(response) {
            $('.review-container').removeClass('loading');
            if (response.data.success) {
                window.location.href = APP_URL + '/users/reviews';
            }
        });
    })
    $('.review_submit').click(function() {
        var section = $(this).parent().parent().attr('id');

        var data_params = {};

        $('#' + section + '-form input, #' + section + ' textarea').each(function() {
            if ($(this).attr('type') != 'radio') {
                data_params[$(this).attr('name')] = $(this).val();
            } else {
                if ($(this).is(':checked'))
                    data_params[$(this).attr('name')] = $(this).val();
            }
        });

        var id = $('#reservation_id').val();
        if (section == 'host-summary' || section == 'guest') {
            if ($('#review_comments').val() == '') {
                $('[for="review_comments"]').show();
                $('#review_comments').addClass('invalid');
                return false;
            } else {
                $('[for="review_comments"]').hide();
                $('#review_comments').removeClass('invalid');
            }
            if (section == 'host-summary') {
                if (!$('[name="rating"]').is(':checked')) {
                    $('[for="review_rating"]').show();
                    return false;
                } else
                $('[for="review_rating"]').hide();
            }
        }

        data_params['review_id'] = $('#review_id').val();

        var data = JSON.stringify(data_params);

        $('.review-container').addClass('loading');
        $http.post(APP_URL + '/reviews/edit/' + id, {
            data: data
        }).then(function(response) {
            $('.review-container').removeClass('loading');
            if (response.data.success) {
                if (section == 'host-details' || section == 'guest')
                    window.location.href = APP_URL + '/users/reviews';
                $('#review_id').val(response.data.review_id);
                $('#' + section).addClass('d-none');
                $('#' + section).next().removeClass('d-none');
            }
        });
    });

}]);

$(document).on('change', '#user_profile_pic', function() {
    $('#ajax_upload_form').submit();
});

// cancel reservation
app.controller('cancel_reservation', ['$scope', '$http', function($scope, $http) {

    $(document).ready(function() {

        $("[id$='-trigger']").click(function() {
            var reservation_id = $(this).attr('id').replace('-trigger', '');
            if (reservation_id != 'header-avatar') {
                $("#reserve_code").val(reservation_id);
                $("#reserve_id").val(reservation_id);
                var id = '#cancel-modal';
                var data_params = {};

                data_params['id'] = reservation_id;

                var data = JSON.stringify(data_params);

                $http.post(APP_URL + '/reservation/cencel_request_send', {
                    data: data
                }).then(function(response) {
                    if (response.data.success == 'false') {
                        var id = '#cancel-modal';
                        $(id).removeClass('d-none');
                        $(id).addClass('show');
                        $(id).attr('aria-hidden', 'false');
                    } else {
                        location.reload();
                    }
                });
            }
        });

        $("[id$='-trigger-pending']").click(function() {

            var reservation_id = $(this).attr('id').replace('-trigger-pending', '');
            $("#reserve_code_pending").val(reservation_id);
            $("#reserve_id").val(reservation_id);
            //$("#cancel_reservation_form").attr('action' , APP_URL+'/trips/guest_cancel_pending_reservation')
            var id = '#pending-cancel-modal';
            $(id).removeClass('d-none');
            $(id).addClass('show');
            $(id).attr('aria-hidden', 'false');
        });

        $('[data-behavior="modal-close"]').click(function(event) {
            event.preventDefault();
            $('.modal').removeClass('show');
            $('.modal').attr('aria-hidden', 'true');
            $('body').removeClass('pos-fix');
        });
    });

    $scope.dispute_form_errors = [];
    $scope.trigger_create_dispute = function(reservation_data) {
        $scope.dispute_reservation_data = reservation_data;
        // Clear Previous file input
        dispute_documents.value = null;
        $scope.dispute_form_errors = [];
        $('#dispute_modal').modal('show');
    }

    $scope.submit_create_dispute = function() {
        $("#dipute_form_content").addClass('loading');
        $http({
            method: 'POST',
            url: APP_URL+'/disputes/create',
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            data: $scope.dispute_reservation_data,
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    if(jQuery.type(value) == 'object')
                    {
                        $.each(value, function(i, val){
                            formData.append(key+"[]", val);
                        });
                    }
                    else
                    {
                        formData.append(key, value);
                    }
                });

                var headers = headersGetter();
                delete headers['Content-Type'];
                return formData;
            }
        })
        .success(function (response) {
            if(response.status == 'error')
            {
                $scope.dispute_form_errors = response.errors;
            }
            else 
            {
                $scope.dispute_form_errors = [];
                window.location.reload();
                if(response.status == 'success')
                {
                    return;
                }
            }
            $("#dipute_form_content").removeClass('loading');
        })
        .error(function (data, status) {
            $scope.dispute_form_errors = [];
            $("#dipute_form_content").removeClass('loading');
        });
    }
}]);

app.directive('file', function () {
    return {
        scope: {
            file: '='
        },
        link: function (scope, el, attrs) {
            el.bind('change', function (event) {
                var file = event.target.files;
                scope.file = file ? file : undefined;
                scope.$apply();
            });
        }
    };
});

app.controller('edit_profile', ['$scope', '$http','$timeout', function($scope, $http, $timeout) {
    $scope.users_phone_numbers = [];
    $scope.phone_number_val = [];
    $scope.phone_code_val = [];
    $scope.otp_val = [];
    $scope.showResendBtn = [];

    var phone_numbers_wrapper = $("#phone_numbers_wrapper");

    $http.post(APP_URL + '/users/get_users_phone_numbers', {}).then(function(response) {
        $scope.users_phone_numbers = response.data;
        $scope.users_phone_numbers.forEach( function(value,key) {
            $scope.showResendBtn[key] = true;
        });
    });

    $scope.add_phone_number = function() {
        phone_numbers_wrapper.addClass('loading');
        new_phone_number = {
            'id': '',
            'phone_number': '',
            'phone_code': $scope.default_phone_code,
            'status': 'Null'
        };
        $scope.users_phone_numbers.push(new_phone_number);
        phone_numbers_wrapper.removeClass('loading');
    }

    $scope.remove_phone_number = function($index) {
        phone_numbers_wrapper.addClass('loading');
        phone_numbers_wrapper.removeClass('loading');
    }

    $scope.update_phone_number = function($index) {
        phone_numbers_wrapper.addClass('loading');
        phone_number_val = $scope.phone_number_val[$index] ? $scope.phone_number_val[$index] : '';
        phone_code_val = $scope.phone_code_val[$index];

        $http.post(APP_URL + '/users/update_users_phone_number', {
            'phone_number': phone_number_val,
            'phone_code': phone_code_val
        }).then(function(response) {
            if (response.data.status == 'Success') {
                $scope.users_phone_numbers[$index].phone_number_error = '';
                $scope.users_phone_numbers = response.data.users_phone_numbers;
                $scope.phone_number_val[$index] = '';
            } else {
                $scope.users_phone_numbers[$index].phone_number_error = response.data.message;
            }
            $scope.showResendBtn[$index] = false;
            $timeout(function () {
                $scope.showResendBtn[$index] = true;
            }, 1000);
            phone_numbers_wrapper.removeClass('loading');
        });
    };

    $scope.verify_phone_number = function($index) {
        phone_numbers_wrapper.addClass('loading');

        phone_number = $scope.users_phone_numbers[$index];
        otp_val = $scope.otp_val[$index] ? $scope.otp_val[$index] : '';

        $http.post(APP_URL + '/users/verify_users_phone_number', {
            'otp': otp_val,
            'id': phone_number.id
        }).then(function(response) {
            if (response.data.status == 'Success') {
                $scope.users_phone_numbers[$index].otp_error = '';
                $scope.users_phone_numbers = response.data.users_phone_numbers;
                $scope.otp_val[$index] = '';
            }
            else {
                $scope.users_phone_numbers[$index].otp_error = response.data.message;
            }
            phone_numbers_wrapper.removeClass('loading');
        });
    };

    $scope.resend_verification_code = function($index) {
        phone_numbers_wrapper.addClass('loading');
        phone_number_val = $scope.users_phone_numbers[$index].phone_number;
        phone_code_val   = $scope.users_phone_numbers[$index].phone_code;
        verification_id  = $scope.users_phone_numbers[$index].id;

        $http.post(APP_URL + '/users/update_users_phone_number', {
            'phone_number': phone_number_val,
            'phone_code': phone_code_val,
            'id': verification_id,
        }).then(function(response) {
            if (response.data.status == 'Success') {
                $scope.users_phone_numbers[$index].phone_number_error = '';
                $scope.users_phone_numbers = response.data.users_phone_numbers;
                $scope.phone_number_val[$index] = '';
            }
            else {
                $scope.users_phone_numbers[$index].phone_number_error = response.data.message;
            }
            $scope.showResendBtn[$index] = false;
            $timeout(function () {
                $scope.showResendBtn[$index] = true;
            }, 1000);
            phone_numbers_wrapper.removeClass('loading');
        });
    };

    $scope.remove_phone_number = function($index) {
        phone_numbers_wrapper.addClass('loading');

        phone_number = $scope.users_phone_numbers[$index];

        $http.post(APP_URL + '/users/remove_users_phone_number', {
            'id': phone_number.id
        }).then(function(response) {
            if (response.data.status == 'Success') {
                $scope.users_phone_numbers[$index].phone_number_error = '';
                $scope.users_phone_numbers = response.data.users_phone_numbers;
            } else {
                $scope.users_phone_numbers[$index].phone_number_error = response.data.message;
            }
            phone_numbers_wrapper.removeClass('loading');
        });
    }

    $('.top-home').click(function(event) {
        event.stopPropagation();
    });

    $("#language_save_button").click(function() {
        $('#selected_language').html('');
        $('.language_select').each(function() {
            if ($(this).is(':checked')) {
                $("#selected_language").append('<span class="btn my-2 mr-2">' + $(this).data('name') + '  <a href="javascript:void(0)" class="ml-2 profile-lang-remove" id="remove_language"> <input type="hidden" value=" ' + $(this).val() + '" name="language[]"> <i class="icon icon-remove" title="Remove from selection"></i></a> </span>');
            }

            $(".mini-language").hide();
            $("body").removeClass("pos-fix");
        });
    });

    $(document).on('click', '[id^="remove_language"]', function() {
        $(this).parent().remove();
    });
}]);

app.controller('user_media', function($scope, $http) {

    $scope.remove_profile_picture = function() {
        if(typeof USER_ID == 'object') {
            return false;
        }
        $('.profile_pic_container').addClass('loading');
        $http.post(APP_URL+'/users/remove_images', {
            user_id: USER_ID
        }).then(function(response) {
            $(".user_profile_pic").attr('src',response.data.profile_pic_src);
            $scope.original_src = response.data.original_src;
            $('.profile_pic_container').removeClass('loading');
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    };
});

// User Verification Document
app.controller('verification_controller', ['$scope', '$http','fileUploadService', function($scope, $http, fileUploadService) {

    $(document).ready(function() {
        $scope.get_verification_documents();
    });

    $scope.destroy_slider = function(selector) {
        $(selector).owlCarousel('destroy');
    };

    $scope.update_slider = function(selector) {
        $(selector).owlCarousel({
            loop:false,
            margin:20,
            navText:['<i class="icon icon-chevron-right custom-rotate"></i>','<i class="icon icon-chevron-right"></i>'],
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                528:{
                    items:2,
                    nav:true
                },
                1024:{
                    items:3,
                    nav:true
                }
            }
        });
    };

    // Get All Verification Documents
    $scope.get_verification_documents = function() {
        $http.get('get_verification_documents', {}).then(function(response) {
            $scope.id_documents = response.data.id_documents;
            $scope.id_verification_status = response.data.id_verification_status;
            setTimeout(() => $scope.update_slider('.id_documents_slider') ,500);
            $('.delete_document-icon').removeAttr('disabled');
        });
    };

    // Get All Verification Documents
    $scope.upload_verification_documents = function(element) {
        $('.doc_error').hide()
        var file = [];
        files = element.files;
        if(files){
            file = files;
            if(file.length) {
                $('.document_upload-btn').addClass('loading');
                url = APP_URL+'/users/upload_verification_documents';

                upload = fileUploadService.uploadFileToUrl(file, url);
                upload.then(function(response) {
                    $scope.id_verification_status = response.id_verification_status;
                    if(response.success == 'false') {
                        $('.doc_error').html(response.error.error_description)
                        $('.doc_error').show()
                    }
                    else {
                        $scope.id_documents = response.id_documents;
                        $("#id_document").val(null);

                        $scope.destroy_slider('.id_documents_slider');
                        $(".id_documents_slider").children("div:last").remove();
                        setTimeout(() => $scope.update_slider('.id_documents_slider') ,10);
                    }
                    $('.document_upload-btn').removeClass('loading');
                });
            }
        }
    };

    $scope.delete_document = function(item, id) {
        var index = $scope.id_documents.indexOf(item);
        $('.delete_document-icon').attr('disabled','disabled');
        $('.item-'+id).addClass('loading');

        $http.post('delete_document', {
            image_id: id
        }).then(function(response) {
            if (response.data.success == 'true') {
                if(response.data.refresh == 'true') {
                    window.location.reload();
                }
                $scope.destroy_slider('.id_documents_slider');
                $scope.get_verification_documents();
            }
            else {
                if (response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
            }
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    };

}]);

app.service('fileUploadService', function ($http, $q) {
    this.uploadFileToUrl = function (file, uploadUrl, data) {
        //FormData, object of key/value pair for form fields and values
        var fileFormData = new FormData();
        $.each(file, function( index, value ) {
            fileFormData.append('file[]', value);
        });

        if(data) {
            $.each(data, function(i, v) {
                fileFormData.append(i, v);
            })
        }

        var deffered = $q.defer();
        $http.post(uploadUrl, fileFormData, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined},
            config:{
                uploadEventHandlers: {
                    progress: function(e) {
                        console.log('UploadProgress -> ' + e);
                    }
                }
            }
        })
        .success(function (response) {
            deffered.resolve(response);
        })
        .error(function (response) {
            deffered.reject(response);
        });

        var getProgressListener = function(deffered) {
            return function(event) {
                eventLoaded = event.loaded;
                eventTotal = event.total;
                percentageLoaded = ((eventLoaded/eventTotal)*100);
                deffered.notify(Math.round(percentageLoaded));
            };
        };
        return deffered.promise;
    }
});

app.controller('Tabsh',['$scope', function ($scope){
    $scope.show = 1;
    $scope.tab1 = true;
}]);

$(window).on("scroll touchmove",function() {
 if($(window).scrollTop() + $(window).height() == $(document).height() || $(window).scrollTop() == 0 ) {
     $('.select-date-wrap').removeClass('overall');
 } else {
    $('.select-date-wrap').addClass('overall');   
}
});

$(document).ready(function(){
    $("#home-refinement").click(function(){
        $(".home_pro").show();
        $(".exp_cat").hide();
    });

    $("#experience-refinement").click(function(){
        $(".exp_cat").show();
        $(".home_pro").hide();
    });
});

$(document).ready(function(){
    $("#search-modal-sm #home-refinement").click(function(){
        $("#search-modal-sm .home_pro").show();
        $("#search-modal-sm .exp_cat").hide();
    });

    $("#search-modal-sm #experience-refinement").click(function(){
        $("#search-modal-sm .exp_cat").show();
        $("#search-modal-sm .home_pro").hide();
    });
});

lang = $("html").attr('lang');
rtl = false;
if(lang  == 'ar') {
 rtl = true;
}

$(document).ready(function() {
    $('.bed-type-slider').owlCarousel({
       rtl:rtl,
       margin:15,
       nav:true,
       dots:false,
       autoplay:false,
       navText:['<i class="icon icon-chevron-right custom-rotate"></i>','<i class="icon icon-chevron-right"></i>'],  
       responsive : {
            // breakpoint from 0 up
            0 : {
                items:1,
            },
            // breakpoint from 480 up
            568 : {
             items:2,
         },
         768 : {
            items:3,
        },
            // breakpoint from 768 up
            1099 : {
                items:3,
            }
        }
    });

    var home_banner_slider;
    home_banner_slider = $('.home-banner-slider.owl-carousel').owlCarousel({
        lazyLoad:true,
        margin:0,
        nav:false,
        dots:false,
        autoplay:true,
        loop:true,
        autoplayHoverPause:false,
        autoplaySpeed: 5,
        items:1,
        animateOut: 'fadeOut'
    });
    home_banner_slider.on('changed.owl.carousel', function(e) {
        home_banner_slider.trigger('stop.owl.autoplay');
        home_banner_slider.trigger('play.owl.autoplay');
    }); 
});

function res_menu()
{
    $('.sub_menu_header').click(function()
    {
        $('.sub_menu_header').toggleClass('open');
    });
};

$(document).ready(function(){
  res_menu();
});

$(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:false,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        enableTouch:false,
        thumbnail: true,
        currentPagerPosition:'left',

        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide',
                mode: 'lg-fade',
                closable:false,
                mousewheel:false,
                enableDrag:false,
                enableSwipe:false,
                loop:false,
                hideControlOnEnd:true,
                slideEndAnimatoin:false,
            });
        }
    });
});

$(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:false,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        enableTouch:false,
        thumbnail: true,
        currentPagerPosition:'left',

        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide',
                mode: 'lg-fade',
                closable:false,
                mousewheel:false,
                loop:false,
                enableDrag:false,
                enableSwipe:false,
                hideControlOnEnd:true,
                slideEndAnimatoin:false,
            });
        }
    });
});

$(".more_photo").on("click", function() {
    $(".lslide.active").trigger("click");
});

function header_fixed() {
    var header_height = $('header').outerHeight();
    $('main').css({ "margin-top": header_height + "px" });
}

function menu_fixed() {
    var header_height = $('header').outerHeight();
    $('.main-menu').css({ "top": header_height + "px" });
}

$('.nav-sections .nav-item').click(function() {
    header_fixed();
    menu_fixed();
});

$(document).ready(function() {
    header_fixed();
    menu_fixed();
});

$(window).scroll(function () {
    header_fixed();
    menu_fixed();
});

$(window).resize(function () {
    header_fixed();
    menu_fixed();
});

$(document).on('click', '.lg-toogle-custom > span', function() {
    $('.lg-thumb-outer').toggleClass('thumb-closed');
    $('.lg-toogle-custom').toggleClass('active');
});

$(document).ready(function() {
    $('.navbar-toggler').click(function() {
        $('body').toggleClass('non_scroll');
    });

    $('.dropdown-menu').click(function(event) {
        event.stopPropagation();
    });

    function pay_scroll() {
        var header_height = $("header").outerHeight();
        $(".payment-wrap > div").css({"top": header_height + "px" });
    }

    pay_scroll();

    $(window).scroll(function() {
        pay_scroll();
    });

    $(window).resize(function() {
        pay_scroll();
    });
});
function listing_cnt() {
    var subnav_height = $('#ajax_header').outerHeight();
    var header_height = $('header').outerHeight();
    var window_height = $(window).outerHeight();
    var footer_height = $('.manage-listing-footer').outerHeight();
    var list_nav = $('#js-manage-listing-nav').width();
    $('.manage-listing-container').css("height" , window_height - (subnav_height + header_height + footer_height) + "px");
    $('#calendar-rules').css({"height" : window_height - (subnav_height + header_height + footer_height) + "px" , 
        "top" : (header_height + subnav_height) + "px"});
    $('#calendar-rules-custom').css({"height" : window_height - (subnav_height + header_height + footer_height) + "px" , 
        "top" : (header_height + subnav_height) + "px",
        "left" : list_nav + "px"});
}
$(document).ready(function() {

    $('.email-btn').click(function() {
        setTimeout(function() {
            $('#signup-popup2').modal('show');
        }, 400);
    }); 

    $('.signup-open').click(function() {
        setTimeout(function() {
            $('#signup-popup').modal('show');
        }, 400);
    });

    $('.login-open').click(function() {
        setTimeout(function() {
            $('#login-popup').modal('show');
        }, 400);
    });

    $('.forgot-open').click(function() {
        setTimeout(function() {
            $('#forgot-popup').modal('show');
        }, 400);
    });

    $('.back-btn').click(function() {
        setTimeout(function() {
            $('#login-popup').modal('show');
        }, 400);
    });

    $('.log-page .login-open').click(function() {
        setTimeout(function() {
            $('#login-popup').modal('show');
        }, 10);
    }); 

    $('.log-page .signup-open').click(function() {
        setTimeout(function() {
            $('#signup-popup').modal('show');
        }, 10);
    }); 

    $('.log-page .forgot-open').click(function() {
        setTimeout(function() {
            $('#forgot-popup').modal('show');
        }, 10);
    });    

    listing_cnt();

    function experience_btn() {
        var experience_banner = $('.experience-info-wrap').outerHeight();
        var window_scroll = $(window).scrollTop();
        if (experience_banner < window_scroll) {
            $('.experience-info').addClass('active');
        }
        else {
         $('.experience-info').removeClass('active');
     }
     var header_height = $('header').outerHeight();
     $('.experience-info').css({ "top": header_height + "px" });
 }

 experience_btn();

 $(window).scroll(function() {
    listing_cnt();
    experience_btn();
});

 $(window).resize(function() {
    listing_cnt();
    experience_btn();
});

 $('.login_popup_open').click(function() {
    $('#login-popup').modal('toggle');
});
});

app.controller('home_owl', function($scope, $http) {
    
    $(document).ready(function() {
        $scope.update_slider('#explore-slider','explore_city');
        $scope.update_slider('#experience-slider');
        $scope.update_slider('#community-slider','our_community');
    });

    $(window).ready(function() {
        $('.explore-wrap').hide();
        // $scope.ajax_home();
    });

    $scope.ajax_home = function() {
        $(".whole-slider-wrap").addClass("dot-loading");
        $http.get(APP_URL + '/ajax_home')
        .then(function(response) {
            $scope.home_city_explore = response.data.home_city;
            $scope.featured_host_experience_categories = response.data.featured_host_experience_categories;
            $scope.just_booked = response.data.just_booked;
            $scope.recommended = response.data.recommended;
            $scope.most_viewed = response.data.most_viewed;
            setTimeout( () => { 
                $scope.update_slider('#explore-slider','explore_city');
                // $scope.update_slider('#booked');
                // $scope.update_slider('#recommended');
                // $scope.update_slider('#most-viewed');
                $('.whole-slider-wrap').removeClass("dot-loading")
            },20);
            $('.explore-wrap').addClass('dot-loading');
            $scope.ajax_home_explore();
        });
    };

    $scope.ajax_home_explore = function() {
        $http.get(APP_URL + '/ajax_home_explore')
        .then(function(response) {
            $scope.host_experiences = response.data.host_experiences;
            $scope.city_count = response.data.city_count;
            $scope.our_community = response.data.our_community_banners;
            setTimeout( () => {
                $('.explore-wrap').removeClass('dot-loading');
                $('.explore-wrap').hide();
                $scope.update_slider('#experience-slider');
                $scope.update_slider('#community-slider','our_community');
            },10);
        });
    };

    $scope.update_slider = function(selector,type = 'common') {        
        $(selector).owlCarousel({
            lazyLoad:true,
            loop: false,
            autoplay: false,
            margin: 20,
            rtl:rtl,
            nav: true,
            items: 5,
            responsiveClass: true,
            navText:['<i class="icon icon-chevron-right custom-rotate"></i>','<i class="icon icon-chevron-right"></i>'],  
            responsive:{
                0: {
                    items: 1
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                },
                1025: {
                    items: 5
                }
            }
        });
    };

});

// initialize Owl Carousel Slider for User Profile Page
$(document).ready(function() {
    $('.profile-slider').owlCarousel({
        loop:false,
        margin:20,
        rtl:rtl,
        responsiveClass:true,
        navText: ["<i class='icon icon-chevron-left'>","<i class='icon icon-chevron-right'>"],
        responsive:{
            0:{
                items:1,
                nav:true
            },
            425:{
                items:2,
                nav:true
            },
            736:{
                items:2,
                nav:true
            },
            992:{
                items:2,
                nav:true
            },
            1024:{
                items:3,
                nav:true
            }
        }
    });
});

$(document).ready(function() {
    function flash_msg() {
        var header_height = $('header').outerHeight();
        $('.flash-container').css({ "margin-top": header_height + "px" });
    }

    function host_slider() {
        var header_height = $('header').outerHeight();
        $('.host-exp-slider').css({ "top": header_height + "px" });
    }

    flash_msg();
    host_slider();

    $(window).resize(function() {
        flash_msg();
        host_slider();
    });

    $(window).scroll(function() {
        flash_msg();
        host_slider();
    });

    $(document).on('click', ".side-menu-bar", function() {
        $('.experience-step-wrap .side-bar').addClass('active');
    });

    $(document).on('click', ".exp-responsive-icon", function() {
        $('.experience-step-wrap .side-bar').removeClass('active');
    });

    $(window).scroll(function() {    
        var scroll = $(window).scrollTop();
        if (scroll >= 100) {
            $("header").addClass("active");
        } 
        else {
            $("header").removeClass("active");
        }
    });

    $('.footer-toggle').click(function() {
        $('footer').toggleClass('footer-shown');
        $(this).toggleClass('active');
    });

    $("#map-toggle").on("click", function() {
        var checkBoxes = $("#map-toggle input[type='checkbox']");
        $(this).toggleClass('active');
        $('.search-content-filters').addClass('loading');
        if(checkBoxes.prop("checked")==true) {
            $('.search-content').addClass('map-off');
            setTimeout(() => {
                $('.search-img-slide').owlCarousel('refresh');
            },500);
        }
        else {
         $('.search-content').removeClass('map-off');
         setTimeout(() => {
            $('.search-img-slide').owlCarousel('refresh');            
        },500);
     }

     setTimeout(() => {
        $('.search-content-filters').removeClass('loading');
    },500);
 });
});
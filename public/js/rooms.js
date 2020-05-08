var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
var datedisplay_format = $('meta[name="datedisplay_format"]').attr('content');

function initialize() {
    var mapCanvas = document.getElementById('map');
    if(!mapCanvas){
        return false;
    }
    var mapOptions = {
        center: new google.maps.LatLng($('#map').attr('data-lat'), $('#map').attr('data-lng')),
        zoom: 13,
        zoomControl: true,
        scrollwheel: false,
        mapTypeControl: false,
        streetViewControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        panControl: false,
        scaleControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var geolocpoint = new google.maps.LatLng($('#map').attr('data-lat'), $('#map').attr('data-lng'));
    map.setCenter(geolocpoint );

    var citymap = {
        center: { lat: parseFloat($('#map').attr('data-lat')), lng: parseFloat($('#map').attr('data-lng')) }
    };

    // Add the circle for this city to the map.
    var cityCircle = new google.maps.Circle({
        strokeColor: '#11848E',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#7FDDC4',
        fillOpacity: 0.35,
        map: map,
        center: citymap['center'],
        radius: 1000
    });
}

google.maps.event.addDomListener(window, 'load', initialize);

app.controller('rooms_detail', ['$scope', '$http', '$filter', function($scope, $http, $filter) {

    //restrict tab key when image popup shown
    $(document).on('keydown', function(e) {
        var target = e.target;
        var shiftPressed = e.shiftKey;
        if (e.keyCode == 9) {
            if ($(target).parents('.lg-on').length) {                            
                return false;
            }
        }
        return true;
    });

    // Room Slider
    $scope.detail_slider = function() {
        $('#detail-gallery').lightSlider({
            gallery: false,
            item:1,
            loop: true,
            pager: false,
            thumbItem:9,
            slideMargin:0,
            enableDrag: false,
            enableTouch:false,
            currentPagerPosition:'left',
            onSliderLoad: function(el) {
                el.lightGallery({
                    selector: '#detail-gallery .lslide',
                    subHtmlSelectorRelative:true,
                    mode: 'lg-fade',
                    closable:true,
                    autoWidth:true,
                    mousewheel:false,
                    enableDrag:true,
                    enableSwipe:true,
                    loop: true,
                    hideControlOnEnd:true,
                    slideEndAnimatoin:false,
                    thumbItem: 5,
                    thumbnail:true,
                    animateThumb: true,
                });
            }
        });
    };

    $(document).ready(function() {
        $('.bx-prev').addClass('icon icon-chevron-left icon-gray icon-size-2 ');
        $('.bx-prev').text('');
        $('.bx-next').addClass('icon icon-chevron-right icon-gray icon-size-2 ');
        $('.bx-next').text('');
        $scope.detail_slider();
    });

    $('.open-gallery').click(function() {
        $('#detail-gallery .lslide').trigger('click');
        $scope.detail_slider();
    });

    $('.rooms_amenities_after').hide();

    $(".amenities_trigger").click(function(){
        $('.rooms_amenities_before').hide();
        $('.rooms_amenities_after').show();
    });

    //-------------- date picker block ---------------- //
    setTimeout(function() {
        var data = $scope.room_id;

        $http.post('rooms_calendar', { data:data }).then(function(response) {

            $('#room_blocked_dates').val(response.data.not_avilable);

            $('#calendar_available_price').val(response.data.changed_price);

            $('#room_available_price').val(response.data.price);

            $('#weekend_price_tooltip').val(response.data.weekend);

            var array =  $('#room_blocked_dates').val();

            var price = $('#room_available_price').val();

            var weekend = ($('#weekend_price_tooltip').val()!=0) ? $('#weekend_price_tooltip').val() : $('#room_available_price').val();

            var change_price = $('#calendar_available_price').val();

            var changed_price = response.data.changed_price;
            var tooltip_price = price;
            var currency_symbol = response.data.currency_symbol;

            $('#list_checkin').datepicker({
                minDate: 0,
                dateFormat: datepicker_format,
                beforeShow: function(input, inst) {
                    setTimeout(function() {
                        inst.dpDiv.find('a.ui-state-highlight').removeClass('ui-state-highlight');
                        $('.ui-state-disabled').removeAttr('title');
                        $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
                    }, 100);
                },
                beforeShowDay: function(date){
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    var dayname =jQuery.datepicker.formatDate('DD', date);
                    var now = new Date();
                    now.setDate(now.getDate()-1);

                    if(array.indexOf(string) == -1) {
                        if(typeof changed_price[string] == 'undefined') {
                            //Determine if a date is a Saturday or a Friday and assign values
                            if(dayname =='Friday' || dayname =='Saturday') {
                                changed_price[string] = weekend;
                            }
                            else {
                                changed_price[string] = price;
                            }
                            //end
                            return [array.indexOf(string) == -1, 'highlight', currency_symbol+changed_price[string]];
                        }
                        else if(date > now) {
                            return [ array.indexOf(string) == -1, 'highlight', currency_symbol+changed_price[string] ];
                        }
                        else {
                            return [ array.indexOf(string) == -1 ];    
                        }
                    }
                    else {
                        return [ array.indexOf(string) == -1 ];
                    }
                },
                onSelect: function (date,obj) 
                {
                    var selected_month = obj.selectedMonth + 1;
                    var checkin_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('.formatted_checkin').val(checkin_formatted_date);
                    var checkout = $('#list_checkin').datepicker('getDate'); 
                    checkout.setDate(checkout.getDate() + 1); 
                    $('#list_checkout').datepicker('option', 'minDate',checkout );
                    $('#list_checkout').datepicker('setDate', checkout);
                    var checkout_date = checkout.getDate();
                    var checkout_month = checkout.getMonth() + 1;
                    var checkout_year = checkout.getFullYear();
                    var checkout_formatted_date = checkout_date+'-'+checkout_month+'-'+checkout_year;
                    $('.formatted_checkout').val(checkout_formatted_date);
                    setTimeout(function(){
                        $("#list_checkout").datepicker("show");
                    },20);

                    var checkin = $('.formatted_checkin').val();
                    var checkout = $('.formatted_checkout').val();
                    var guest =  $("#number_of_guests").val();
                    if(checkin != '' && checkout !='')
                    {
                        $('.js-book-it-status').addClass('loading');
                        calculation(checkout,checkin,guest);
                    }
                    $('.tooltip').hide();

                    if(date != new Date())
                    {
                        $('.ui-datepicker-today').removeClass('ui-datepicker-today');
                    }
                },
                onChangeMonthYear: function(){
                    setTimeout(function(){
                        $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
                    },100);  
                }
            });

            $('html body').on('mouseenter', '.ui-datepicker-calendar a.ui-state-hover, .ui-datepicker-calendar a.ui-state-default', function(e){ //console.log(e); 
                $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
            });

            $('#list_checkout').datepicker({
                minDate: 1,
                dateFormat: datepicker_format,
                beforeShow: function(input, inst) {
                    setTimeout(function() {
                        $('.ui-state-disabled').removeAttr('title');
                        $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
                    }, 100);
                },
                beforeShowDay: function(date) {
                    var prev_Date = moment(date).subtract(1, 'd');;
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', prev_Date.toDate());
                    var dayname =jQuery.datepicker.formatDate('DD', date);
                    
                    //Determine if a date is a Saturday or a Sunday and assign values
                    if(array.indexOf(string) == -1) {
                        if(typeof changed_price[string] == 'undefined')
                        {
                            if(dayname =='Friday' || dayname =='Saturday')
                            {
                                changed_price[string] = weekend;
                            }
                            else
                            {
                                changed_price[string] = price;
                            }        
                        }
                        return [ array.indexOf(string) == -1, 'highlight', currency_symbol+changed_price[string] ];
                    }
                    else {
                        return [ array.indexOf(string) == -1 ];
                    }
                },
                onSelect: function(date,obj)
                {
                    $('.tooltip').hide();
                    var selected_month = obj.selectedMonth + 1;
                    var checkout_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('.formatted_checkout').val(checkout_formatted_date);
                    var checkin = $('.formatted_checkin').val();
                    var checkout = $('.formatted_checkout').val();
                    var guest =  $("#number_of_guests").val();

                    if(checkin != '' && checkout !='')
                    {
                        $('.js-book-it-status').addClass('loading');
                        calculation(checkout,checkin,guest);  
                    }
                    else
                    {
                        checkin = moment();
                        checkin_date = checkin.format('YYYY-MM-DD');

                        while(!(array.indexOf(checkin_date) == -1))
                        {
                            checkin = checkin.add('1', 'days');
                            checkin_date = checkin.format('YYYY-MM-DD');
                        }
                        checkout = checkin.clone().add('1', 'days');

                        $('#list_checkin').datepicker('setDate', checkin.toDate());
                        $('#list_checkout').datepicker('option', 'minDate',checkout.toDate());
                        setTimeout(function(){
                            $("#list_checkin").datepicker("show");
                        },20);
                        return false;   
                    }
                },
                onChangeMonthYear: function(){
                    setTimeout(function(){
                        $('.highlight').not('.ui-state-disabled').tooltip({container:'body'});
                    },100);  
                }
            });

            if($('#url_checkin').val() != '' && $('#url_checkout').val() != '') {

                $("#list_checkin").datepicker('setDate', new Date($('#url_checkin').val()));
                $("#list_checkout").datepicker('setDate', new Date($('#url_checkout').val()));
                $('#number_of_guests').val($('#url_guests').val());

                $("#message_checkin").datepicker('setDate', new Date($('#url_checkin').val()));
                $("#message_checkout").datepicker('setDate', new Date($('#url_checkout').val()));
                $('#message_guests').val($('#url_guests').val());

                var checkin = $('.formatted_checkin').val();
                var checkout = $('.formatted_checkout').val();
                var guest = $('#number_of_guests').val();
                $('.js-book-it-status').addClass('loading');
                calculation(checkout,checkin,guest);
            }
            else {
                if($('#url_guests').val() != '' ) {
                    $('#number_of_guests').val($('#url_guests').val());
                }
            }
        });
    }, 10);

    //---- date picker block---- //
    $("#number_of_guests").change(function() {
        var guest = $(this).val();
        var checkin = $('.formatted_checkin').val();
        var checkout =  $('.formatted_checkout').val();

        $("#guest_error").hide();
        if(checkin != '' && checkout !='' )
        {
            $('.js-book-it-status').addClass('loading');
            calculation(checkout,checkin,guest);
        }
    });

    //---- Rooms calculation---- //
    $(".additional_price").hide();
    $(".security_price").hide();
    $(".cleaning_price").hide();
    $(".js-subtotal-container").hide();
    $("#book_it_disabled").hide();

    function calculation(checkout,checkin,guest) {

        var room_id = $scope.room_id;
        $http.post('price_calculation', { checkin :checkin,checkout : checkout, guest_count : guest,   room_id : room_id }).then(function(response) {
            if(response.data.status == "Not available") {
                $(".js-subtotal-container").hide();
                $("#book_it_disabled").show();
                $(".js-book-it-btn-container").hide();
                $('.book_it_disabled_msg').hide();
                if(response.data.error =='') {
                    $('#book_it_disabled_message').show();
                }
                else {
                    $('#book_it_error_message').text(response.data.error);   
                    $('#book_it_error_message').show();   
                }
            }
            else {
                $(".js-subtotal-container").show();
                $("#book_it_disabled").hide();
                $(".js-book-it-btn-container").show();
            }

            $('.js-book-it-status').removeClass('loading');
            $('#total_night_price').text(response.data.total_night_price);
            $('#service_fee').text(response.data.service_fee);
            $('#total').text(response.data.total);
            $('#total_night_count').text(response.data.total_nights);
            $('#rooms_price_amount').text(response.data.rooms_price);
            $('#rooms_price_amount_1').text(response.data.base_rooms_price);

            if(response.data.length_of_stay_type == 'weekly') {
                $(".weekly").show();
                $("#weekly_discount").text(response.data.length_of_stay_discount);
                $("#weekly_discount_price").text(response.data.length_of_stay_discount_price);
            }
            else {
                $(".weekly").hide();
            }
            if(response.data.length_of_stay_type == 'monthly') {
                $(".monthly").show();
                $("#monthly_discount").text(response.data.length_of_stay_discount);
                $("#monthly_discount_price").text(response.data.length_of_stay_discount_price);
            }
            else {
                $(".monthly").hide();
            }

            if(response.data.length_of_stay_type == 'custom') {
                $(".long_term").show();
                $("#long_term_discount").text(response.data.length_of_stay_discount);
                $("#long_term_discount_price").text(response.data.length_of_stay_discount_price);
            }
            else {
                $(".long_term").hide();
            }

            if(response.data.booked_period_type != '') {
                $(".booking_period").hide();
                $("."+response.data.booked_period_type).show();
                $(".booked_period_discount").text(response.data.booked_period_discount);
                $(".booked_period_discount_price").text(response.data.booked_period_discount_price);
            }
            else {
                $(".booking_period").hide();
            }

            if(response.data.additional_guest)
            {
                $(".additional_price").show();
                $('#additional_guest').text(response.data.additional_guest);
            }
            else {
                $(".additional_price").hide();
            }

            if(response.data.security_fee) {
                $(".security_price").show();
                $('#security_fee').text(response.data.security_fee);
            }
            else {
                $(".security_price").hide();
            }
            if(response.data.cleaning_fee) {
                $(".cleaning_price").show();
                $('#cleaning_fee').text(response.data.cleaning_fee);
            }
            else {
                $(".cleaning_price").hide();
            }
        }); 
    }

    $('#contact-host-link, #host-profile-contact-btn').click(function() {
        $('.contact-modal').removeClass('d-none');
    });

    setTimeout(function() {

        var data = $scope.room_id;
        var room_id = data;

        $http.post(APP_URL+'/rooms/rooms_calendar', { data:data }).then(function(response) {
            var changed_price = response.data.changed_price;
            var array =  response.data.not_avilable;

            $('#message_checkin').datepicker({
                minDate: 0,
                dateFormat:datepicker_format,
                setDate: new Date($('#message_checkin').val()),
                beforeShowDay: function(date) {
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    if($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onSelect: function (date,obj) {
                    var selected_month = obj.selectedMonth + 1;
                    var msg_checkout_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('input[name="message_checkin"]').val(msg_checkout_formatted_date);
                    var checkout = $('#message_checkin').datepicker('getDate');
                    checkout.setDate(checkout.getDate() + 1); 
                    $('#message_checkout').datepicker('option', 'minDate', checkout);
                    $('#message_checkout').datepicker('setDate', checkout);
                    var msg_checkout_date = checkout.getDate();
                    var msg_checkout_month = checkout.getMonth() + 1;
                    var msg_checkout_year = checkout.getFullYear();
                    var msg_checkout_formatted_date = msg_checkout_date+'-'+msg_checkout_month+'-'+msg_checkout_year;
                    $('input[name="message_checkout"]').val(msg_checkout_formatted_date);
                    setTimeout(function() {
                        $("#message_checkout").datepicker("show");
                    },20);

                    var checkin = $('input[name="message_checkin"]').val();
                    var checkout = $('input[name="message_checkout"]').val();
                    var guest =  $("#message_guests").val();
                    calculation_message(checkout,checkin,guest,room_id);

                    if(date != new Date()) {
                        $('.ui-datepicker-today').removeClass('ui-datepicker-today');
                    }
                }
            });

            $('#message_checkout').datepicker({
                minDate: 1,
                dateFormat:datepicker_format,
                setDate: new Date($('#message_checkout').val()),
                beforeShowDay: function(date) {
                    var prev_Date = moment(date).subtract(1, 'd');;
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', prev_Date.toDate());
                    if($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onSelect: function(date,obj) {

                    var selected_month = obj.selectedMonth + 1;
                    var msg_checkout_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                    $('input[name="message_checkout"]').val(msg_checkout_formatted_date);


                    var checkout = $('input[name="message_checkout"]').val();
                    var checkin  = $('input[name="message_checkin"]').val();
                    var guest    = $("#message_guests").val();

                    if(checkin != '') {
                        calculation_message(checkout,checkin,guest,room_id);  
                    }
                    else {
                        $('#message_checkin').datepicker('setDate',  new Date());
                        setTimeout(function(){
                            $("#message_checkin").datepicker("show");
                        },20);
                    }
                }
            });
        });
    }, 10);

    function calculation_message(checkout,checkin,guest,room_id) {
        $http.post(APP_URL+'/rooms/price_calculation', { checkin :checkin,checkout : checkout, guest_count : guest, room_id : room_id }).then(function(response) {
            if(response.data.status == 'Not available') {
                if(response.data.error != '') {
                    $('.contacted-before #error').text(response.data.error);
                    $('.contacted-before #not_available').addClass('d-none');
                    $('.contacted-before #error').removeClass('d-none');
                }
                else {
                    $('.contacted-before #error').addClass('d-none');
                    $('.contacted-before #error').text('');
                    $('.contacted-before #not_available').removeClass('d-none');
                }
                $('.contacted-before').removeClass('d-none');
                $('.contacted-before').removeClass('error-block');
            }
            else {
                $('.contacted-before').addClass('d-none');
                $('.contacted-before').addClass('error-block');
            }
        });
    }

    $(document).on('click', '.rich-toggle-unchecked,.rich-toggle-checked', function() {
        if(typeof USER_ID == 'object') {
            window.location.href = APP_URL+'/login';
            return false;
        }
        $('.add-wishlist').addClass('loading');
        $http.get(APP_URL+"/wishlist_list?id="+$scope.room_id+'&type=Rooms', {  }).then(function(response) {
            $('.add-wishlist').removeClass('loading');
            $('.wl-modal__col:nth-child(2)').removeClass('d-none');
            $scope.wishlist_list = response.data;
        });
    });

    $scope.wishlist_row_select = function(index) {
        $http.post(APP_URL+"/save_wishlist", { data: $scope.room_id, wishlist_id: $scope.wishlist_list[index].id, saved_id: $scope.wishlist_list[index].saved_id }).then(function(response) {
            if(response.data == 'null')
                $scope.wishlist_list[index].saved_id = null;
            else
                $scope.wishlist_list[index].saved_id = response.data;
        });

        if($('#wishlist_row_'+index).hasClass('text-dark-gray'))
            $scope.wishlist_list[index].saved_id = null;
        else
            $scope.wishlist_list[index].saved_id = 1;
    };

    $(document).on('submit', '.wl-modal-form', function(event) {
        event.preventDefault();
        $('.add-wishlist').addClass('loading');
        $http.post(APP_URL+"/wishlist_create", { data: $('.wl-modal-input').val(), id: $scope.room_id }).then(function(response) 
        {
            $('.wl-modal-form').addClass('d-none');
            $('.add-wishlist').removeClass('loading');
            $('.create-wl').removeClass('d-none');
            $scope.wishlist_list = response.data;
            event.preventDefault();
        });
        event.preventDefault();
    });

    $(document).on('click','.detail-sticky li a',function(e) {
        e.preventDefault();
        var target = $(this).attr("href");
        var top = $(target).offset().top - $('header').outerHeight() - $('.detail-sticky').outerHeight();

        $('html, body').stop().animate({
            scrollTop: top
        }, 1000);
    });

    $(window).scroll(function () {
        var scrollDistance = $(window).scrollTop();
        var header_height = $('header').outerHeight();
        var detail_sticky = $('.detail-sticky').outerHeight();
        $('.scroll-section').each(function (i) {
            // Calculate extra height because Map placed outer div
            var extra_height = ($(this).attr('id') == 'detail-map') ? -(header_height + detail_sticky) : 300;
            if ($(this).position().top <= scrollDistance - extra_height) {
                $('.detail-sticky li a.active').removeClass('active');
                $('.detail-sticky li a').eq(i).addClass('active');
            } else {
                $('.detail-sticky li a').eq(i).removeClass('active');
            }
        });
    }).scroll();

    $('.wl-modal-close').click(function() {
        var null_count = $filter('filter')($scope.wishlist_list, {saved_id : null});
        if(null_count.length == $scope.wishlist_list.length)
            $('#wishlist-button').prop('checked', false);
        else
            $('#wishlist-button').prop('checked', true);
    });

}]);

// Similar listing Slider
$(document).ready(function() {
    length = $('#similar-slider').attr('item-length');
    loop = false
    if (length>3) {
        loop = true
    }
    $('#similar-slider').owlCarousel({
        loop: loop,
        autoplay: true,
        margin: 20,
        rtl:rtl,
        nav: true,
        items: 3,
        responsiveClass: true,
        navText:['<i class="icon icon-chevron-right custom-rotate"></i>','<i class="icon icon-chevron-right"></i>'],  
        responsive:{
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {           
                items: 3  
            }
        }
    });
});

//  calendar triggered
$("#view-calendar").click(function(event) {
    $("#list_checkin").datepicker("show");
});

//  calendar triggered
$(".review_link").click(function(event) {
    header_height = $('header').height();
    detail_sticky = $('.detail-sticky').height();
    $(window).scrollTop($('#review-info').offset().top - (header_height+detail_sticky));
});

$("#contact_message_send").click(function() {
    if($('#message_checkin').val()=='') {
        $('#errors').removeClass('d-none');
        return false;
    }
    if($('#message_checkout').val()=='') {
        $('#errors').removeClass('d-none');
        return false;
    }
    if($('#message_checkout').val()!='' && $('#message_checkin').val()!='') {  
        $("#contact_message_send").prop('disabled', true);
        $("#message_form").trigger("submit");
        return false;
    }
});

$(".js-book-it-btn-container").click(function() {
    var checkin = $("#list_checkin").val();
    var checkout =  $("#list_checkout").val();
    var guests = $('#number_of_guests').val();

    if(checkin == '' || checkout ==''){
        $("#list_checkin").trigger("select");
        return false;
    }
    else if(guests == '' || guests == null){
        $("#number_of_guests").focus();
        $("#guest_error").show();
        return false;    
    }
});
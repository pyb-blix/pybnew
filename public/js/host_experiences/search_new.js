var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');
$(".header_refinement").removeClass("active");
$('.header_refinement[data = "Experiences"]').addClass('active');
$(".header_refinement_modal").removeClass("active");
$('.header_refinement_modal[data = "Experiences"]').addClass('active');

$(".exp_cat").show();
$(".home_pro").hide();

guests_select_option("#modal_guests", 'Experiences');
guests_select_option("#header-search-guests", 'Experiences');

//These variables are used to during cancel process
var prop_app_fil = '',
amen_app_fil = '',
broom_app_fil = '',
bed_app_fil = '',
bath_app_fil = '',
search_on_map=''
map_search_first='';

$('.customBox').hover(function() {
    $(mark).addClass('hover');
});

app.directive('postsPagination', function() {
    return {
        restrict: 'E',
        template: '<ul class="pagination">' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(1)">&laquo;</a></li>' +
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="search_result(currentPage-1)">&lsaquo; ' + $('#pagin_prev').val() + '</a></li>' +
        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">' +
        '<a href="javascript:void(0)" ng-click="search_result(i)">{{i}}</a>' +
        '</li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(currentPage+1)">' + $('#pagin_next').val() + ' &rsaquo;</a></li>' +
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="search_result(totalPages)">&raquo;</a></li>' +
        '</ul>'
    };
}).controller('search-page', ['$scope', '$http', '$compile', '$filter', function($scope, $http, $compile, $filter) {
    $scope.first_search = 'Yes';
    $scope.current_date = new Date();

    $scope.totalPages = 0;
    $scope.currentPage = 1;
    $scope.range = [];
    $scope.marker_click = 0;
    $scope.map_lat_long = '';

    $scope.map_fit_bounds = '';

    $(document).ready(function() {

        function map_position() {
            var header_height = $('header').outerHeight();
            var search_filter_height = $('.search_filter').outerHeight();
            $("#map_canvas").css({"top": header_height + search_filter_height + "px"});
        }

        function search_wrap() {
            var header_height = $('header').outerHeight();
            var search_filter_height = $('.search_filter').outerHeight();
            $(".search-content").css({"margin-top": header_height + search_filter_height + "px"});
        }

        function more_filter() {
            $(".more-filter-btn").click(function() {
                var header_height = $("header").outerHeight();
                var search_filter_height = $(".search_filter").outerHeight();
                var more_filter_height = $(".more-filter .filter-btn").outerHeight();
                var window_height = $(window).outerHeight();
                $(".more-filter").css({"height": (window_height - (header_height + search_filter_height + more_filter_height)) + "px"});
            });     
        }

        function mobile_mode_functions() {
            map_position();
            search_wrap();
            more_filter();
        }

        $(window).scroll(function () {
            mobile_mode_functions();
        });

        $(window).resize(function() {
            $('#more_filter_submit').html('Apply filters');
            if ($(window).width() < 760) {
                $('#more_filter_submit').html('See Homes');
            }

            mobile_mode_functions();
        });

        $(window).ready(function() {
            $('#more_filter_submit').html('Apply filters');
            if ($(window).width() < 760) {
                $('#more_filter_submit').html('See Homes');
            }

            mobile_mode_functions();
        });

        $('.filter-btn a').click(function() {
            $(this).closest(".dropdown-menu").removeClass("show");
            $(this).closest(".dropdown").removeClass("show");
            $(this).closest(".dropdown-toggle").removeClass("active");
            $(this).closest(".dropdown-toggle").attr("[aria-expanded='true']");
        });

        $('.search-content-filters .cancel-filter, .search-content-filters .apply-filter').click(function() {
            $('.more-filter').removeClass('active');
            $('.search-content-filters').removeClass('active');
            $('.search-wrap').addClass('d-md-flex');
            $('.search-wrap').removeClass('d-none');
        });

        $('.show-map').click(function() {
            $(this).hide();
            $('.show-result').show();
            $('.map-canvas').addClass('active');
            $scope.search_result();
        });

        $('.show-result').click(function() {
            $(this).hide();
            $('.show-map').show();
            $('.map-canvas').removeClass('active');
        }); 

        mobile_mode_functions();

        $('.guest-filter-btn').click(function() {
            $('.guest-mobile-drop').addClass('active');
        });

        $('.guest-mobile-drop .close, .guest-mobile-drop .cancel-link').click(function() {
            $('.guest-mobile-drop').removeClass('active');
        });


        $('.date-filter-btn').click(function() {
            $('.date-mobile-drop').addClass('active');
        });

        $('.date-mobile-drop .close, .date-mobile-drop .cancel-link').click(function() {
            $('.date-mobile-drop').removeClass('active');
        });

        $('.category-filter-btn').click(function() {
            $('.category-mobile-drop').addClass('active');
        });

        $('.category-mobile-drop .close, .category-mobile-drop .cancel-link').click(function() {
            $('.category-mobile-drop').removeClass('active');
        });
    });

    $(document).ready(function(){
        var st_date = moment($('#checkin').val(),daterangepicker_format).toDate(); 
        new Date($('#checkin').val()); 
        var end_date = moment($('#checkout').val(),daterangepicker_format).toDate(); 
        new Date($('#checkout').val());  //alert(st_date); alert(end_date);
        var today = new Date();
        start = moment();
        $('.dbdate').daterangepicker({
            autoApply: false,
            applyButton: true,
            cancelClass: 'd-none',
            resetButton: true,
            parentEl: '.search_filter',
            autoUpdateInput: false,
            locale: {
                format: daterangepicker_format,
                resetLabel: CLEAR_LANG,
                applyLabel: APPLY_LANG
            },
            minDate: today,
            dateLimitMin : {
                "days" :1
            }
        });

        $('.date-filter-btn').daterangepicker({
            autoApply: true,
            minDate: today,
            dateLimitMin : {
                "days" :1
            },
            autoUpdateInput: false,
            locale: {
                format: daterangepicker_format
            },
            parentEl: '#daterangepicker_modal_div',
            alwaysShowCalendars: true,
            inline: true,
        });

        
        if(st_date !='Invalid Date' && end_date != 'Invalid Date') {
            var picker = $('.dbdate').data('daterangepicker');
            picker.setStartDate(st_date );
            picker.setEndDate(end_date );
        }

        $('.mobile_date_clear').click(function(ev, picker) {
            var picker = $(".date-filter-btn").data('daterangepicker');
            picker.setStartDate(today);
            picker.setEndDate("");

            /*if($scope.checkin)
            {
                $scope.checkin = "";
                $scope.checkout = "";
                $scope.search_result();
            }
            else
            {
                $scope.checkin = "";
                $scope.checkout = "";
            }
            $('.date-mobile-drop').removeClass('active');
            $('.date-filter-btn .dbdate').removeClass('active');

            $('#checkin').val('');
            $('#checkout').val('');

            setTimeout(function(){
                var picker = $(".date-filter-btn").data('daterangepicker');
                picker.setStartDate(today);
                picker.setEndDate("");
            },2000);*/
        });
    });

    $('.dbdate').on('show.daterangepicker', function(ev, picker) {
        $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 1 !important');
        $scope.reload_on_close = false
        /*if ($scope.checkin == '' || $scope.checkout == '') {
            $('.search_filter .resetBtn').prop('disabled', true);
            $('.search_filter .in-range.available').removeClass('in-range');
        }*/
    });

    $(".dbdate").on('hide.daterangepicker', function(ev, picker) {
        if (!picker.startDate || !picker.endDate) {
            $(this).parent().css('opacity', 1);
            $(this).parent().parent().find('.DateRangePickerDiv').parent().css('cssText', 'opacity: 0 !important');
        }
        $scope.apply_date_filter(picker)

    });

    $('.dbdate').on('apply.daterangepicker', function(ev, picker) {
        $scope.apply_date_filter(picker)
    });

    $scope.apply_date_filter = function(picker){
        startDate = picker.startDate;
        endDate = picker.endDate;

        if (($scope.checkin == startDate.format(daterangepicker_format) && $scope.checkout == endDate.format(daterangepicker_format))
            || (startDate.format(daterangepicker_format) == endDate.format(daterangepicker_format) && !$scope.reload_on_close)){
            if($scope.checkin == '' && $scope.checkout == ''){
                $('.dbdate').removeClass('active');
                $('.date-filter-btn').removeClass('active');
            }
            return true;
        }

        $scope.checkin = startDate.format(daterangepicker_format);
        $scope.checkout = endDate.format(daterangepicker_format);
        if($scope.checkin == $scope.checkout) {
            $scope.checkin = $scope.checkout = '';
        }

        if($scope.checkin != '' && $scope.checkout != ''){
            $('.dbdate').addClass('active');
            $('.date-filter-btn').addClass('active');
        }else{
            $('.dbdate').removeClass('active');
            $('.date-filter-btn').removeClass('active');
        }

        // $('.search_filter .resetBtn').prop('disabled', false);
        $scope.search_result();
    }

    $scope.reload_on_close = false
    $('.dbdate').on('reset.daterangepicker', function(ev, picker) {
        $scope.reload_on_close = false
        if ($scope.checkin != '' && $scope.checkout != '') {
            $scope.reload_on_close = true
        }

        // $('.search_filter .resetBtn').prop('disabled', true);
        $scope.checkin = $scope.checkout = '';
        $('.dbdate').removeClass('active');
        $('.date-filter-btn').removeClass('active');
        var picker = $('#checkin,.dbdate, .date-filter-btn .dbdate').data('daterangepicker');
        $scope.is_filter_active('date')
    });

    $scope.saveWishlist = function(exp_details) {
        if (typeof USER_ID == 'object') {
            window.location.href = APP_URL + '/login';
            return false;
        }

        var name = exp_details.name;
        var img = exp_details.photo_name;
        var city_name = exp_details.host_experience_location.city;
        var host_img = exp_details.user.profile_picture.src;
        $scope.room_id = exp_details.id;
        $('.background-listing-img').css('background-image', 'url(' + img + ')');
        $('.host-profile-img').attr('src', host_img);
        $('.wl-modal-listing-name').text(name);
        $('.wl-modal-listing-address').text(city_name);
        $('#wish_list_text').val(city_name);

        $('.add-wishlist').addClass('loading');
        $http.get(APP_URL + "/wishlist_list?id=" + $scope.room_id +"&type="+ $scope.current_refinement, {}).then(function(response) {
            $('.wl-modal-form').addClass('d-none');
            $('.add-wishlist').removeClass('loading');
            $('.create-wl').removeClass('d-none');
            $scope.wishlist_list = response.data;
        });
    };

    $scope.wishlist_row_select = function(index) {

        $http.post(APP_URL + "/save_wishlist_experience", {
            data: $scope.room_id,
            wishlist_id: $scope.wishlist_list[index].id,
            saved_id: $scope.wishlist_list[index].saved_id
        }).then(function(response) {
            if (response.data == 'null')
                $scope.wishlist_list[index].saved_id = null;
            else
                $scope.wishlist_list[index].saved_id = response.data;
        });

        if ($('#wishlist_row_' + index).hasClass('text-dark-gray'))
            $scope.wishlist_list[index].saved_id = null;
        else
            $scope.wishlist_list[index].saved_id = 1;
    };

    $(document).on('submit', '.wl-modal-form', function(event) {
        event.preventDefault();
        $('.add-wishlist').addClass('loading');
        $http.post(APP_URL + "/wishlist_create", {
            data: $('#wish_list_text').val(),
            id: $scope.room_id
        }).then(function(response) {
            $('.wl-modal-form').addClass('d-none');
            $('.add-wishlist').removeClass('loading');
            $('.create-wl').removeClass('d-none');
            $('#wish_list_text').val('');
            $scope.wishlist_list = response.data;
            event.preventDefault();
        });
        event.preventDefault();
    });

    $('#wishlist-modal').on('hidden.bs.modal', function () {
        var null_count = $filter('filter')($scope.wishlist_list, {
            saved_id: null
        });
        var checked = (null_count.length == $scope.wishlist_list.length) ? false : true;
        $('#wishlist-widget-' + $scope.room_id).prop('checked', checked);
    });

    $(document).ready(function() {
        $scope.map_lat_long = '';

        var location_val = $("#location").val();
        $("#header-search-form").val(location_val);
        $("#modal-locations").val(location_val);

        $('.show-more').click(function() {
            $(this).children('span').toggleClass('d-none');
            $(this).parent().parent().children('div').children().toggleClass('filters-more');
        });

        $("#more_filters").click(function() {
            $(".toggle-group").show();
            $(".toggle-hide").hide();
            $(".sidebar").css("height", "87%");
        });
    });

    function no_results() {
        if ($('.search-wrap').hasClass('loading'))
            $('#no_results').hide();
        else
            $('#no_results').show();
    }

    function map_loading() {
        if ($('.search-wrap').css('display') == 'none') {
            $('.map').addClass('loading');
        }
    }

    function map_loading_remove() {
        if ($scope.first_search == 'Yes') {
            $scope.first_search = 'No';
            $('.button_ipunk').show();
            $('.button_ipunk-result').hide();
            $('.map.hide-sm-view').hide();
            $('.search-wrap').show();
            $('.filter-div').hide();
        }
        $('.map').removeClass('loading');
    }

    var location1 = getParameterByName('location');

    var current_url = (window.location.href).replace('/s', '/searchResult');

    pageNumber = 1;

    if (pageNumber === undefined) {
        pageNumber = '1';
    }

    $('.search-wrap').addClass('loading');
    map_loading();
    no_results();
    $scope.on_mouse = function(index) {
        if (markers[index] != undefined) {
            mark = markers[index].div_;
            $(mark).addClass('hover');
        }
    };
    $scope.out_mouse = function(index) {
        if (markers[index] != undefined) {
            mark = markers[index].div_;
            $(mark).removeClass('hover');
        }
    };

    $scope.search_result = function(pageNumber) {
        if ($scope.currentPage == pageNumber) {
            return false
        }
        
        if (pageNumber === undefined) {
            pageNumber = '1';
        }

        var max_price = $(".max_value").val();
        var min_price = $(".min_value").val();

        var host_experience_category = [];
        $('.host_experience_category:checked').each(function(i) {
            host_experience_category[i] = $(this).val();
        });

        if(host_experience_category.length) {
            host_experience_category=jQuery.unique(host_experience_category);    
        }

        var checkin = $scope.checkin;
        var checkout = $scope.checkout;
        // var guest_select = $(".guest-select").val();
        var guest_select = $scope.search_guest;
        if(guest_select==null || guest_select=='') guest_select=1;
        if ($.trim($scope.map_lat_long) != '' && search_on_map != '') {
            var map_details = $scope.map_lat_long;
        } else {
            var map_details = "";
        }
        current_refinement="Experiences";
        setGetParameter('host_experience_category', host_experience_category);
        setGetParameter('current_refinement', current_refinement);
        setGetParameter('checkin', checkin);
        setGetParameter('checkout', checkout);
        setGetParameter('guests', guest_select);
        setGetParameter('page', pageNumber);

        var location1 = getParameterByName('location');

        $('.search-wrap').addClass('loading');
        map_loading();
        no_results();
        $http.post('searchexperienceResult?page=' + pageNumber, {
            location: location1,
            min_price: min_price,
            max_price: max_price,
            host_experience_category: host_experience_category,
            checkin: checkin,
            checkout: checkout,
            guest: guest_select,
            map_details: map_details
        })
        .then(function(response) {
            $scope.room_result = response.data;
            $scope.checkin = checkin;
            $scope.checkout = checkout;
            $scope.totalPages = response.data.last_page;
            $scope.currentPage = response.data.current_page;
                // Pagination Range
                var pages = [];

                for (var i = 1; i <= response.data.last_page; i++) {
                    pages.push(i);
                }
                var amenities_check = '';

                var propertytype_check = '';

                $scope.range = pages;
                var bounds = new google.maps.LatLngBounds();
                angular.forEach(response.data.data, function(value,key){
                    var lat = value["host_experience_location"]["latitude"];
                    var lng = value["host_experience_location"]["longitude"]; 
                    bounds.extend(new google.maps.LatLng(lat,lng));
                });

                $scope.map_fit_bounds = 'Yes';
                if($(window).width() > 760 || $scope.first_search != 'Yes'){
                    if(response.data.total>0 && search_on_map==''){
                        $scope.viewport = bounds;
                        $scope.cLat=response.data.data[0]["host_experience_location"]["latitude"];
                        $scope.cLong=response.data.data[0]["host_experience_location"]["longitude"];
                        map_search_first='Yes';

                        initialize(bounds);
                    }
                    else if(search_on_map=='')
                    {
                        $scope.viewport = $scope.locationViewport;
                        $scope.cLat=$scope.locationLat;
                        $scope.cLong=$scope.locationLong;

                        initialize();
                    }
                }

                // Initalize Image Slider for experiences
                setTimeout(function() {
                    $('.search-img-slide').owlCarousel({
                      loop: false,
                      autoplay: false,
                      rtl:rtl,
                      nav: true,
                      dots: true,
                      items: 1,
                      responsiveClass: true,
                      navText:['<i class="icon icon-chevron-right custom-rotate"></i>','<i class="icon icon-chevron-right"></i>']
                  });
                },1);

                $('.search-img-slide').owlCarousel('refresh');

                $('.search-wrap').removeClass('loading');
                map_loading_remove();
                no_results();
                marker(response.data);
            });
    };

    $(document).ready(function(){
        $('.host_experience_category').change(function () {
            prop = false;
            if ($(this).is(":checked")) {
                prop = true;
            }
            $('#mob_category_'+$(this).val()).prop('checked', prop);
            $('#category_'+$(this).val()).prop('checked', prop);
        });
    });

    $scope.apply_filter = function() {
        if ($(window).width() < 760) {
            $('.search-wrap').show();
            $('.filter-div').hide();
        }
        //save the values for uncheck the boxes during cancel
        else {
            $('[id^="map-search"]').each(function() {
                if ($(this).hasClass('property_type') && $(this).prop('checked') == true) {
                    if (prop_app_fil == '')
                        prop_app_fil = $(this).val();
                    else
                        prop_app_fil = prop_app_fil + ',' + $(this).val();
                } else if ($(this).hasClass('amenities') && $(this).prop('checked') == true) {
                    if (amen_app_fil == '')
                        amen_app_fil = $(this).val();
                    else
                        amen_app_fil = amen_app_fil + ',' + $(this).val();
                }
            })

            broom_app_fil = $('#map-search-min-bedrooms').val();
            bath_app_fil = $('#map-search-min-bathrooms').val();
            bed_app_fil = $('#map-search-min-beds').val();

            $(".toggle-hide").css("display", "block");
            $(".toggle-group").css("display", "none");
            $scope.search_result();
        }
    };

    $scope.remove_filter = function(parameter) {
        $('.' + parameter).removeAttr('checked');
        var paramName = parameter.replace('-', '_');
        var paramValue = '';
        setGetParameter(paramName, paramValue)
        $('.' + parameter + '_tag').addClass('d-none');

        $scope.search_result();
    };

    $scope.format_date =function(date, format) {
        return moment(date,daterangepicker_format).format(daterangepicker_format);
    }

    $scope.filter_status = [];
    $scope.filter_text = [];
    $scope.update_filter_status= function() {

        category_count = $('[id^="category_"]:checked').length;

        filters_count = 0;
        filters_count += $('[id^="mob_category"]:checked').length;
        
        $scope.filter_status['dates'] = ($scope.checkin && $scope.checkout)? true: false;
        $scope.filter_status['guests'] = $scope.search_guest > 1 ? true: false;
        $scope.filter_status['category_types'] = (category_count > 0) ? true: false;
        $scope.filter_status['filters'] = (filters_count > 0) ? true: false;

        $scope.filter_text['category_types'] =  ' · '+category_count;
        $scope.filter_text['filters'] =  ' · '+filters_count;
        $scope.filter_text['filters_count'] =  filters_count;

        if(!$scope.$$phase) {
            $scope.$apply();
        }
    }

    $(document).ready(function(){
        $scope.update_filter_status();
    });
    $('.guestbut').click(function(){
        $scope.update_filter_status();
    });
    $('.room-type, .property_type, .amenities').click(function(){
        $scope.update_filter_status();
    }); 
    $scope.filter_btn_text = function(filter)
    {
        $scope.update_filter_status();
        btn_text = $scope.filter_text[filter];
        return btn_text;
    }
    $scope.is_filter_active = function(filter)
    {
        $scope.update_filter_status();
        result = false;
        result = $scope.filter_status[filter];
        return result;
    }
    $scope.filter_active = function(filter)
    {
        is_active = ($scope.is_filter_active(filter) || $scope.opened_filter == filter);
        class_name = (is_active) ? 'active' : '';
        return class_name;
    }
    $scope.update_opened_filter = function(filter)
    {
        // Close Previous Opened Filter Dropdown
        $(".show").removeClass('show');
        if($scope.opened_filter == filter) {
            setTimeout( () => {
                $(".show").removeClass('show');
                if(filter == 'dates') {
                    $('.dbdate').data('daterangepicker').hide();
                }
            },1);
            $scope.reset_filters(filter);
        }
        else {
            $scope.opened_filter = filter;
        }

        if(filter == 'guests') {
            $('.guest-mobile-drop').addClass('active');
        }

        if(filter == 'dates') {
           $('.date-mobile-drop').addClass('active');
       }   

       if(filter == 'category_types') {
           $('.category-mobile-drop').addClass('active');
       }
   };

   $scope.apply_filters = function(filter)
   {
    $(".morefit5").hide();
    $(".morefit3").hide();
    $(".morefit2").hide();
    $(".morefit1").hide();
    $(".morefit").hide();
    $(".button_ipunk").show();
    $(".hide-sm-view").addClass("loading");
    $('.map,.sidesear').removeClass('mapfil');
    $(".guestbut1").hide();
    $('.sidebar').removeClass('newdp');
    $("body").removeClass("non_scrl");
    $("body").removeClass("pos-fix3");
        // if(!$scope.is_filter_active(filter))
            // $('.guestbut').removeClass('active');
            
            if(filter == 'dates')
            {
                picker = $(".date-filter-btn").data('daterangepicker');
                startDateInput = $('#checkin');
                endDateInput = $('#checkout');

                startDate = picker.startDate;
                endDate = picker.endDate;

                if(!endDate.isValid()){
                    $scope.checkin = '';
                    $scope.checkout = '';
                }
                else{
                    $scope.checkin = startDate.format(daterangepicker_format);
                    $scope.checkout = endDate.format(daterangepicker_format);
                }
                
                startDateInput.val(startDate.format(daterangepicker_format));
                endDateInput.val(endDate.format(daterangepicker_format));

                $('.date-mobile-drop').removeClass('active');
                $('.date-filter-btn .dbdate').addClass('active');
            }

            if(filter == 'location_refinement')
            {
                var location = $('#header-search-form-mob').val();
                var locations="";
                if(location){ locations = location.replace(" ", "+"); }
                setGetParameter('location', locations);
                var url_current_refinement = getParameterByName("current_refinement");
                if(url_current_refinement != current_refinement) {
                    /*setGetParameter('current_refinement', current_refinement);
                    window.location.reload();*/
                    window.location=APP_URL+'/s?location='+getParameterByName('location')+'&checkin='+getParameterByName('checkin')+'&checkout='+getParameterByName('checkout')+'&guests='+getParameterByName('guests')+'&current_refinement='+current_refinement;
                    return true;
                }
                $('#search-modal--sm').addClass('d-none');
                $('#search-modal--sm').attr('aria-hidden', 'true');
                $('#search-modal-sm .close').trigger('click')
                $('.search-settings').removeClass('shown');

            }

            if(filter == 'guests') {
                $('.guest-mobile-drop').removeClass('active');
            }

            if(filter == 'category_types') {
                $('.category-mobile-drop').removeClass('active');
            }

            $scope.search_result();
            $scope.opened_filter = '';
            $scope.update_filter_status();
        }
        $scope.reset_filters = function(filter)
        {    $("body").removeClass("pos-fix3");
        if(filter == 'dates')
        {
            picker = $(".date-filter-btn").data('daterangepicker');
            startDateInput = $('#checkin');
            endDateInput = $('#checkout');

            startDate = startDateInput.val();
            endDate = endDateInput.val();
            
            startDateMoment = moment(startDate, daterangepicker_format);
            endDateMoment = moment(endDate, daterangepicker_format);
            if(startDateMoment.isValid() && endDateMoment.isValid())
            {
                picker.setStartDate(startDateMoment);
                picker.setEndDate(endDateMoment);                
            }
        }
        if(filter == 'guests')
        {
            guests = getParameterByName('guests');
            if(!guests)
            {
                guests = 1;
            }
            guests = guests-0;
            $scope.search_guest = guests;
        }
        if(filter == 'prices')
        {
            $scope.price_reset();
        }
        if(filter == 'category_types')
        {
            $scope.category_types_reset();   
        }
        if(filter == 'filters')
        {
            $scope.price_reset();
            $scope.category_types_reset();
        }
        $scope.opened_filter = '';
        $scope.update_filter_status();
        if(!$scope.$$phase) {
            $scope.$apply();
        }
    }
    $scope.reset_filters('dates');

    $scope.category_types_reset = function() {
        category_types = getParameterByName('host_experience_category');
        category_types_array = category_types.split(',');
        $('.host_experience_category').prop('checked', false);
        $.each(category_types_array, function(i, v){
            $("#category_"+v).prop('checked', true);
            $("#mob_category_"+v).prop('checked', true);
        });
    }

    function setGetParameter(paramName, paramValue) {
        var url = window.location.href;

        if (url.indexOf(paramName + "=") >= 0) {
            var prefix = url.substring(0, url.indexOf(paramName));
            var suffix = url.substring(url.indexOf(paramName));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        } else {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
        history.pushState(null, null, url);
    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    var viewport = $scope.locationViewport = JSON.parse($('#viewport').val());
    var lat0 = '';
    var long0 = '';
    var lat1 = '';
    var long1 = '';
    var infoBubble = new InfoBubble({
        maxWidth: 3000
    });
    var bounds;

    angular.forEach(viewport, function(key, value) {
        lat0 = viewport['southwest']['lat'];
        long0 = viewport['southwest']['lng'];
        lat1 = viewport['northeast']['lat'];
        long1 = viewport['northeast']['lng'];
    });

    var bounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(lat0, long0),
        new google.maps.LatLng(lat1, long1)
        );

    $scope.viewport = $scope.locationViewport = bounds;

    setTimeout(function() {

        initializeMap();

        $scope.map_lat_long = '';
    }, 1000);


    function initializeMap() {

        autocomplete = new google.maps.places.Autocomplete(document.getElementById('header-search-form'), {
            types: ['geocode']
        });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var location = $('#header-search-form').val();
            var locations = location.replace(" ", "+");
            setGetParameter('location', locations);
            var place = autocomplete.getPlace();
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            if (place && place.geometry && place.geometry.viewport)
                $scope.viewport  = $scope.locationViewport = place.geometry.viewport;

            $scope.cLat = $scope.locationLat = latitude;
            $scope.cLong = $scope.locationLong = longitude;

            $scope.map_lat_long = '';
            search_on_map = '';
            $('.search-settings').addClass('shown');
            // $(".search_new_header").show();
        });

        sm_autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('header-search-form-mob'), {
            types: ['geocode']
        });
        google.maps.event.addListener(sm_autocomplete1, 'place_changed', function() {
            $("#header-search-form").val($("#header-search-form-mob").val());
            var location = $('#header-search-form-mob').val();
            var locations = location.replace(" ", "+");
            var place = sm_autocomplete1.getPlace();
            if(!place.geometry) {
                return false;
            }
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            if (place && place.geometry && place.geometry.viewport)
                $scope.viewport  = $scope.locationViewport= place.geometry.viewport;

            $scope.cLat = $scope.locationLat = latitude;
            $scope.cLong = $scope.locationLong = longitude;

            $scope.map_lat_long = '';
            search_on_map = '';
            /*setGetParameter('location', locations);
            $scope.apply_filters('location_refinement');*/
        });
    }

    $(document).ready(function(){
        $('.header_refinement').click(function(){
           /* var current_refinement = $(this).attr('data');
            $(".header_refinement_input").val(current_refinement);
            $(".header_refinement").removeClass("active");
            $(this).addClass("active");
            $(".search_new_header").hide();
            window.location=APP_URL+'/s?location='+getParameterByName('location')+'&checkin='+getParameterByName('checkin')+'&checkout='+getParameterByName('checkout')+'&guests='+getParameterByName('guests')+'&current_refinement='+current_refinement;*/
            
            $(".header_refinement").removeClass("active");
            $(this).addClass("active");
            current_refinement = $(this).attr('data');
            $('#header-search-form-mob').val($('#header-search-form').val())
            $scope.apply_filters('location_refinement')

        });
    });

    $(document).on('click','.header_refinement_modal',function() {
        $(".header_refinement_modal").removeClass("active");
        $(this).addClass("active");
        current_refinement = $(this).attr('data');
        $('#header-search-form-mob').val($('#header-search-form').val())
        $scope.apply_filters('location_refinement')
    });

    $scope.zoom = '';
    $scope.cLat = '';
    $scope.cLong = '';
    $scope.locationLat = '';
    $scope.locationLong = '';
    var html = '';
    var markers = [];
    var map;
    var infowindow = new google.maps.InfoWindow({
        content: html
    });

    $(document).ready(function(){
        $scope.search_result();
    });

    function initialize(value) {
        if(!value) {
            value = '';
        }

        var zoom_set = 10;

        if($("#lat").val() == 0) {
            var zoom_set = 1;
        }
        else if($scope.zoom > 0) {
            zoom_set = $scope.zoom;
        }

        if ($scope.cLat == '' && $scope.cLong == '') {
            var latitude = $("#lat").val();
            var longitude = $("#long").val();
        }
        else {
            var latitude = $scope.cLat;
            var longitude = $scope.cLong;
        }

        var myCenter = new google.maps.LatLng(latitude, longitude);
        
        var mapProp = {
            scrollwheel: false,
            center: myCenter,
            zoom: zoom_set,
            minZoom: 2,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.LEFT_TOP,
                style: google.maps.ZoomControlStyle.SMALL
            },
            mapTypeControl: false,
            streetViewControl: false,
            navigationControl: false,
            backgroundColor: '#a4ddf5',
            styles: [{
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{
                    color: '#a4ddf5'
                }]
            }],
        }

        map = new google.maps.Map(document.getElementById("map_canvas"), mapProp);
        if(latitude != 0 && longitude != 0) {
            map.fitBounds($scope.viewport);
        }

        google.maps.event.addListener(map, 'idle', function() {
            $scope.map_fit_bounds = '';
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();

            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();

            map_display = $(".map").css('display');
            if (map_display != 'none') {
                $scope.map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;
            }
            else {
                $scope.map_lat_long = '';
            }
            var redo_search = '';
            $('.map-auto-refresh-checkbox:checked').each(function(i) {
                redo_search = $(this).val();
            });
            // alert(redo_search);
            if (redo_search == 'true') {
                // if(map_search_first!='Yes')
                // $scope.search_result();
            } else {
                $(".map-auto-refresh").addClass('d-none');
                $(".map-manual-refresh").removeClass('d-none');
            }
        });

        var homeControlDiv = document.createElement('div');
        var homeControl = new HomeControl(homeControlDiv, map);

        map.controls[google.maps.ControlPosition.LEFT_TOP].push(homeControlDiv);

        google.maps.event.addListener(map, 'dragend', function() {
            search_on_map='Yes';
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
            $scope.zoom = map.getZoom();

            var zoom = map.getZoom();
            var bounds = map.getBounds();
            var minLat = bounds.getSouthWest().lat();
            var minLong = bounds.getSouthWest().lng();
            var maxLat = bounds.getNorthEast().lat();
            var maxLong = bounds.getNorthEast().lng();
            var cLat = bounds.getCenter().lat();
            var cLong = bounds.getCenter().lng();

            $scope.cLat = bounds.getCenter().lat();
            $scope.cLong = bounds.getCenter().lng();

            var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;

            $scope.map_lat_long = map_lat_long;
            var redo_search = '';
            $('.map-auto-refresh-checkbox:checked').each(function(i) {
                redo_search = $(this).val();
            });

            if (redo_search == 'true') {
                $scope.search_result();
            } else {
                $(".map-auto-refresh").addClass('d-none');
                $(".map-manual-refresh").removeClass('d-none');
            }
        });

        google.maps.event.addListener(map, 'click', function() {
            if($scope.marker_click > 0) {
                $scope.marker_click = 0;
                return true;
            }
            if(infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }
        });

        google.maps.event.addListenerOnce(map, 'mousemove', function(){
            google.maps.event.addListener(map, 'zoom_changed', function() {
                search_on_map='Yes';
                if (infoBubble.isOpen()) {
                    infoBubble.close();
                    infoBubble = new InfoBubble({
                        maxWidth: 3000
                    });
                }
                $scope.zoom = map.getZoom();

                var zoom = map.getZoom();
                var bounds = map.getBounds();
                var minLat = bounds.getSouthWest().lat();
                var minLong = bounds.getSouthWest().lng();
                var maxLat = bounds.getNorthEast().lat();
                var maxLong = bounds.getNorthEast().lng();
                var cLat = bounds.getCenter().lat();
                var cLong = bounds.getCenter().lng();
                $scope.cLat = bounds.getCenter().lat();
                $scope.cLong = bounds.getCenter().lng();
                var map_lat_long = zoom + '~' + bounds + '~' + minLat + '~' + minLong + '~' + maxLat + '~' + maxLong + '~' + cLat + '~' + cLong;

                old_map_lat_long = $scope.map_lat_long
                $scope.map_lat_long = map_lat_long;
                var redo_search = '';
                $('.map-auto-refresh-checkbox:checked').each(function(i) {
                    redo_search = $(this).val();
                });

                if (redo_search == 'true') {
                    if(old_map_lat_long != $scope.map_lat_long){
                        $scope.search_result();
                    }
                }
                else {
                    $(".map-auto-refresh").addClass('d-none');
                    $(".map-manual-refresh").removeClass('d-none');
                }
            });
        });
    }

    function HomeControl(controlDiv, map) {
        var controlText = document.createElement('div');
        controlText.style.position = 'relative';
        controlText.style.padding = '5px';
        controlText.style.margin = '-65px 0px 0px 50px';
        controlText.style.fontSize = '14px';
        controlText.innerHTML = '<div class="map-refresh-controls google"><a   class="map-manual-refresh btn btn-primary d-none" style="background-color:#ff5a5f;color: #ffffff;">' + $('#redo_search_value').val() + ' <i class="icon icon-refresh icon-space-left"></i></a><div class="panel map-auto-refresh"><label class="checkbox"><input type="checkbox" checked="checked" name="redo_search" value="true" class="map-auto-refresh-checkbox"><small>' + $('#current_language').val() + '</small></label></div></div>'
        controlDiv.appendChild(controlText);

        // Setup click-event listener: simply set the map to London
        google.maps.event.addDomListener(controlText, 'click', function() {});
    }

    /*Overlay Script*/
    function TxtOverlay(pos, txt, cls, map) {
        // Now initialize all properties.
        this.pos = pos;
        this.txt_ = txt;
        this.cls_ = cls;
        this.map_ = map;

        // We define a property to hold the image's
        // div. We'll actually create this div
        // upon receipt of the add() method so we'll
        // leave it null for now.
        this.div_ = null;

        // Explicitly call setMap() on this overlay
        this.setMap(map);
    }

    TxtOverlay.prototype = new google.maps.OverlayView();

    TxtOverlay.prototype.onAdd = function() {

        // Note: an overlay's receipt of onAdd() indicates that
        // the map's panes are now available for attaching
        // the overlay to the map via the DOM.

        // Create the DIV and set some basic attributes.
        var div = document.createElement('DIV');
        div.className = this.cls_;

        div.innerHTML = this.txt_;

        // Set the overlay's div_ property to this DIV
        this.div_ = div;
        var overlayProjection = this.getProjection();
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);
        div.style.left = position.x - 25 + 'px';
        div.style.top = position.y - 25 + 'px';
        // We add an overlay to a map via one of the map's panes.

        var panes = this.getPanes();
        panes.overlayMouseTarget.appendChild(div);

        var me = this;
        google.maps.event.addDomListener(div, 'click', function(event) {
            google.maps.event.trigger(me, 'click');
            event.stopPropagation();
        });
        google.maps.event.addDomListener(div, 'dblclick', function(event) {
            event.stopPropagation();
        });
    }

    TxtOverlay.prototype.draw = function() {

        var overlayProjection = this.getProjection();

        // Retrieve the southwest and northeast coordinates of this overlay
        // in latlngs and convert them to pixels coordinates.
        // We'll use these coordinates to resize the DIV.
        var position = overlayProjection.fromLatLngToDivPixel(this.pos);

        var div = this.div_;
        div.style.left = position.x - 25 + 'px';
        div.style.top = position.y - 25 + 'px';
        div.style.position = 'absolute';
        div.style.cursor = 'pointer';

    }

    //Optional: helper methods for removing and toggling the text overlay.  
    TxtOverlay.prototype.onRemove = function() {
        this.div_.parentNode.removeChild(this.div_);
        this.div_ = null;
    }

    TxtOverlay.prototype.hide = function() {
        if (this.div_) {
            this.div_.style.visibility = "hidden";
        }
    }

    TxtOverlay.prototype.show = function() {
        if (this.div_) {
            this.div_.style.visibility = "visible";
        }
    }

    TxtOverlay.prototype.toggle = function() {
        if (this.div_) {
            if (this.div_.style.visibility == "hidden") {
                this.show();
            } else {
                this.hide();
            }
        }
    }

    TxtOverlay.prototype.toggleDOM = function() {
        if (this.getMap()) {
            this.setMap(null);
        } else {
            this.setMap(this.map_);
        }
    }

    /*Overlay Script*/
    function marker(response) {
        var checkout = $scope.checkout;
        var checkin = $scope.checkin;
        var guests = $scope.guests;     
        
        setAllMap(null);
        markers = [];
        angular.forEach(response.data, function(obj) {

            var map_slider = '';
            var photos_class = (obj["all_photos"].length > 1 ) ? '' : 'd-none';
            
            angular.forEach(obj["all_photos"], function(obj1) {
                map_slider +=  '<img id="marker_image_' + obj["id"] + '" rooms_image = "" alt="' + obj1["name"] + '" class="img-fluid w-100" data-current="0" src="' + obj1["image_url"] + '">';
            });

            var html = '<div id="info_window_' + obj["id"] + '" class="listing listing-map-popover" data-price="' + obj["currency"]["original_symbol"] + '" data-id="' + obj["id"] + '" data-user="' + obj["user_id"] + '" data-url="' + obj["link"] + '" data-name="' + obj["title"] + '" data-lng="' + obj['host_experience_location']["longitude"] + '" data-lat="' + obj['host_experience_location']["latitude"] + '"><div class="panel-image listing-img">';

            html += '<a class="media-photo media-cover" target="listing_' + obj["id"] + '" href="' + obj["link"] + '?checkin=' + checkin + '&checkout=' + checkout + '&guests=' + guests + '"><div class="search-map-slider owl-carousel">' + map_slider + '</div></a>';
            html += '<div style="display: '+(obj["all_photos"].length>1?'block':'none')+';" class="target-prev target-control block-link marker_slider '+ photos_class +'" ng-click="marker_slider($event,\'prev\')" data-room_id="' + obj["id"] + '""><i class="icon icon-chevron-left icon-size-2 icon-white"></i></div><a class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" target="listing_' + obj["id"] + '" href="' + obj["link"] + '?checkin=' + checkin + '&checkout=' + checkout + '&guests=' + guests + '"><div>';

            var instant_book = '';
            var per_guest = $scope.per_guest_text;

            html += '</div></a><div style="display: '+(obj["all_photos"].length>1?'block':'none')+';" class="target-next target-control marker_slider block-link '+ photos_class +'" ng-click="marker_slider($event,\'next\')" data-room_id="' + obj["id"] + '""><i class="icon icon-chevron-right icon-size-2 icon-white"></i></div></div>';

            html += '<div class="search-info"><h4 class="text-truncate"><span>'+obj["category_details"]["name"] +'</span><span>·</span><span>'+obj["host_experience_location"]["city"] +'</span></h4><a class="text-truncate" itemprop="name" title="' + obj["title"] + '">' + obj["title"] + '</a>';
            html += '<p class="search-price"> <span>'+obj["currency"]["symbol"]+'</span>'+obj["session_price"]+' '+per_guest;

            html += '</p>';
            var star_rating = '';

            if(obj['overall_star_rating'] != '') {
                star_rating = '' + obj['overall_star_rating'];
            }

            var reviews_count = '';
            var review_seperator = '';
            var review_text = (obj['reviews_count'] > 1) ? $scope.reviews_text : $scope.review_text;

            if (obj['reviews_count'] != 0){
                reviews_count = ' ' + obj['reviews_count'] + ' ' + review_text;
                review_seperator = '.';
            }

            html += '<div class="listing-location text-truncate" itemprop="description">';
            html +='<span>' + star_rating + '</span><span>' + reviews_count + '</span></div></div></a></div></div>';

            var lat = obj["host_experience_location"]["latitude"];
            var lng = obj["host_experience_location"]["longitude"];
            var point = new google.maps.LatLng(lat, lng);
            var name = obj["name"];
            var currency_symbol = obj["currency"]["symbol"];
            var currency_value = obj["session_price"];
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                icon: getMarkerImage('normal'),
                title: name,
                zIndex: 1
            });
            customTxt = currency_symbol + currency_value;
            txt = new TxtOverlay(point, customTxt, "customBox", map);

            markers.push(txt);
            google.maps.event.addListener(marker, "mouseover", function() {
                marker.setIcon(getMarkerImage('hover'));
            });
            google.maps.event.addListener(marker, "mouseout", function() {
                marker.setIcon(getMarkerImage('normal'));
            });
            createInfoWindow(txt, html);

        });

angular.forEach($scope.place_result, function(obj) {
    var lat = obj["latitude"];
    var lng = obj["longitude"];
    var point = new google.maps.LatLng(lat, lng);

    var marker = new Marker({
        map: map,
        position: point,
        icon: ' ',
        map_icon_label: getMarkerLabel(obj['type'])
    });
    markers.push(marker);

    place_info = '<p>' + obj['name'] + '</p><p>' + obj['address_line_1'] + ' ' + obj['address_line_2'] + ', ' + obj['city'] + '</p><p>' + obj['state'] + ', ' + obj['country'] + '</p>';
    $scope.places_info.push(place_info);

    html = '<div style="font-size:16px"><h3 style="margin:0px; color:#000;">' + obj['name'] + '</h3><div class="popup-review">' + obj['reviews_star_rating_div'] + '</div><div class="address-align">' + obj['address_line_1'] + '</div><div class="address-align">' + obj['address_line_2'] + '</div><div class="address-align">' + obj['city'] + '</div><div class="address-align">' + obj['state'] + '</div><div class="address-align">' + obj['country_name'] + '</div><div class="address-align">' + obj['postal_code'] + '</div>';
    html += '<br><br><a class="review-btn-pop" href="' + APP_URL + '/add_place_reviews/place/' + obj['id'] + '" target="_blank" >Review</a>';
    html += '<div class="review-search-popup"><div onclick="reviews_popup(event, this)" class="close" >close</div>';
    angular.forEach(obj['reviews'], function(review) {
        html += '<div class="review-content flt-left">';
        html += '<div class="left-blk review-content-blk" ><img width="40" height="40" src="' + review.users_from.profile_picture.src + '" class="flt-left img-rnd" ></div>';
        html += '<div class="right-blk review-content-blk" ><div class="place_comments">' + review.place_comments + '</div><div class="place_stars" >' + review.place_review_stars_div + '</div></div>';
        html += '</div>';
    });
    html += '</div></div></div></div>';
    createPlaceInfoWindow(marker, html);

});
}

function createInfoWindow(marker, popupContent) {
    infoBubble = new InfoBubble({
        maxWidth: 3000
    });

    var contentString = $compile(popupContent)($scope);
    google.maps.event.addListener(marker, 'click', function() {

        var useragent = navigator.userAgent;
            /*console.log(useragent);
            console.log(useragent.indexOf('iPhone'));*/

            if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('iPad') != -1 || useragent.indexOf('Android') != -1) {
                $scope.marker_click = 1;
            }
            if (infoBubble.isOpen()) {
                infoBubble.close();
                infoBubble = new InfoBubble({
                    maxWidth: 3000
                });
            }

            infoBubble.addTab('', contentString[0]);

            var borderRadius = 0;
            infoBubble.setBorderRadius(borderRadius);
            var maxWidth = 300;
            infoBubble.setMaxWidth(maxWidth);

            var maxHeight = 300;
            infoBubble.setMaxHeight(maxHeight);
            var minWidth = 282;
            infoBubble.setMinWidth(minWidth);

            var minHeight = 245;
            infoBubble.setMinHeight(minHeight);
            infoBubble.setPosition(marker.pos);
            infoBubble.open(map);
        });
}

function createPlaceInfoWindow(marker, popupContent) {
    infoBubble = new InfoBubble({
        maxWidth: 1500
    });

    var contentString = popupContent;
    google.maps.event.addListener(marker, 'click', function() {

        if (infoBubble.isOpen()) {
            infoBubble.close();
            infoBubble = new InfoBubble({
                maxWidth: 1500
            });
        }

        infoBubble.addTab('', contentString);

        var borderRadius = 0;
        infoBubble.setBorderRadius(borderRadius);
        var maxWidth = 300;
        infoBubble.setMaxWidth(maxWidth);

        var maxHeight = 250;
        infoBubble.setMaxHeight(maxHeight);
        var minWidth = 300;
        infoBubble.setMinWidth(minWidth);

        var minHeight = 250;
        infoBubble.setMinHeight(minHeight);

        infoBubble.open(map, marker);
    });
}

function getMarkerImage(type) {
    var image = '';

    if (type == 'hover')
        image = '';

    var gicons = new google.maps.MarkerImage("images/" + image,
        new google.maps.Size(50, 50),
        new google.maps.Point(0, 0),
        new google.maps.Point(9, 20));

    return gicons;

}

function setAllMap(map) {
    if (infoBubble != undefined) {
        if (infoBubble.isOpen()) {
            infoBubble.close();
            infoBubble = new InfoBubble({
                maxWidth: 3000
            });
        }
    }
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

$('.footer-toggle').click(function() {
    $(".footer-container").slideToggle("fast", function() {
        if ($(".footer-container").is(":visible")) {
            $('.open-content').hide();
            $('.close-content').show();
        } else {
            $('.open-content').show();
            $('.close-content').hide();
        }
    });
});


$(document).on('click', '.map-manual-refresh', function() {
    $(".map-manual-refresh").addClass('d-none');
    $(".map-auto-refresh").removeClass('d-none');
    $scope.search_result();
});
$(document).on('click', '.rooms-slider', function() {

    var rooms_id = $(this).attr("data-room_id");
    var dataurl = $("#rooms_image_" + rooms_id).attr("rooms_image");
    var img_url = $("#rooms_image_" + rooms_id).attr("src");
    if ($.trim(dataurl) == '') {
        $(this).parent().addClass("loading");
        $http.post(APP_URL + '/host_experience_photos', {
            rooms_id: rooms_id
        })
        .then(function(response) {
            angular.forEach(response.data, function(obj) {
                if ($.trim(dataurl) == '') {
                    dataurl = obj['image_url'];
                } else
                dataurl = dataurl + '^>' + obj['image_url'];
            });

            $("#rooms_image_" + rooms_id).attr("rooms_image", dataurl);
            var all_image = dataurl.split('^>');
            var rooms_img_count = all_image.length;
            var i = 0;
            var set_img_no = '';
            angular.forEach(all_image, function(img) {
                if ($.trim(img) == $.trim(img_url)) {
                    set_img_no = i;
                }
                i++;
            });
            if ($(this).is(".target-prev") == true) {
                var cur_img = set_img_no - 1;
                var count = rooms_img_count - 1;
            } else {
                var cur_img = set_img_no + 1;
                var count = 0;
            }

            if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
                var img = all_image[cur_img];
            } else {

                var img = all_image[count];
            }

            var set_img_url = img;

            $(".panel-image").removeClass("loading");
            $("#rooms_image_" + rooms_id).attr("src", set_img_url);
        });
    } else {
        $(this).parent().addClass("loading");
        var all_image = dataurl.split('^>');
        var rooms_img_count = all_image.length;
        var i = 0;
        var set_img_no = '';
        angular.forEach(all_image, function(img) {
            if ($.trim(img) == $.trim(img_url)) {
                set_img_no = i;
            }
            i++;
        });
        if ($(this).is(".target-prev") == true) {
            var cur_img = set_img_no - 1;
            var count = rooms_img_count - 1;
        } else {
            var cur_img = set_img_no + 1;
            var count = 0;
        }

        if (typeof(all_image[cur_img]) != 'undefined' && $.trim(all_image[cur_img]) != "null") {
            var img = all_image[cur_img];
        } else {
            var img = all_image[count];
        }
        var set_img_url =img;

        $(".panel-image").removeClass("loading");
        $("#rooms_image_" + rooms_id).attr("src", set_img_url);

    }

});


$scope.marker_slider = function($event,type) {
    var map_owl = $('.search-map-slider').owlCarousel({
        loop: true,
        nav: true,
        autoplay: true,
        rtl:rtl,
        responsiveClass: true,
        items: 1,
    });

    map_owl.on('changed.owl.carousel', function(e) {
        map_owl.trigger('stop.owl.autoplay');
        map_owl.trigger('play.owl.autoplay');
    });

    $('.search-map-slider.owl-loaded').trigger(type+'.owl.carousel');

    $event.stopPropagation();
};


var min_price_checks    = getParameterByName('min_price');
var max_price_checks = getParameterByName('max_price');
var room_type_checks = getParameterByName('room_type');
var property_type_checks = getParameterByName('property_type');
var amenities_checks = getParameterByName('amenities');
var checkin_checks = getParameterByName('checkin');
var checkout_checks = getParameterByName('checkout');
var guests_checks = getParameterByName('guests');
var beds_checks = getParameterByName('beds');
var bathrooms_checks = getParameterByName('bathrooms');
var bedrooms_checks = getParameterByName('bedrooms');

$(document).ready(function(){
    $('#checkin,#checkout,#slider').click(function(){        
       if ($(window).width() < 760) { 
        if(min_price_checks != '' || max_price_checks != ''|| room_type_checks != ''|| property_type_checks != ''|| amenities_checks != ''|| checkin_checks != ''|| checkout_checks != ''|| guests_checks != ''||beds_checks != '' || bathrooms_checks != ''|| bedrooms_checks != '') {
            $('#more_filter_submit').removeAttr('disabled');
        }
        else{
            $('#more_filter_submit').attr('disabled', 'disabled');
        }
    } 
});
});
$(document).on('change','#room-options,#instant_book,#min_max_pricerange,#guest-select', function()
{   
    if ($(window).width() < 760)
    {
        if(min_price_checks != '' || max_price_checks != ''|| room_type_checks != ''|| property_type_checks != ''||
         amenities_checks != ''|| checkin_checks != ''|| checkout_checks != ''|| guests_checks != ''||beds_checks != ''
         || bathrooms_checks != ''|| bedrooms_checks != ''){
            $('#more_filter_submit').removeAttr('disabled');
    }else{
        $('#more_filter_submit').attr('disabled', 'disabled');
    }
}         
});

$(document).on('change', '[id^="map-search"]', function() {       
    var amenities_check = getParameterByName('amenities');
    var propertytype_check = getParameterByName('property_type');
    var i = 0;
    $('[id^="map-search"]').each(function() {
        if ($(this).is(':checkbox')) {
            if ($(this).is(':checked')) {
                i++;
            } else if (amenities_check != '' || propertytype_check != '') {

                i++;
            }
        } else if ($(this).val() != '') {
            i++
        }
    });


    if (i == 0) {
        if ($(window).width() < 760) {
            $('#more_filter_submit').removeAttr('disabled');
        } else {
            $('#more_filter_submit').attr('disabled', 'disabled');
        }
    } else {
        $('#more_filter_submit').removeAttr('disabled');
    }

});


$(document).on('click', '#cancel-filter', function() {

    var split_prop = prop_app_fil.split(',');
    var split_amen = amen_app_fil.split(',');


    $('[id^="map-search"]').each(function() {
        if ($(this).is(':checkbox')) {
            if ($(window).width() > 760) {
                if (((jQuery.inArray($(this).val(), split_prop) != '-1' && $(this).hasClass('property_type')) || (jQuery.inArray($(this).val(), split_amen) != '-1' && $(this).hasClass('amenities')))) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            }
        } else {
            if ($(window).width() > 760) {
                $(this).val('');
            }
        }
    });

    if ($(window).width() > 760) {

        $('#map-search-min-bedrooms').val(broom_app_fil);
        $('#map-search-min-bathrooms').val(bath_app_fil);
        $('#map-search-min-beds').val(bed_app_fil);

    }

    $('#more_filter_submit').attr('disabled', 'disabled');
    $(".toggle-hide").css("display", "block");
    $(".toggle-group").css("display", "none");
    $('.filter-div').hide();
    $('.button_ipunk').show();
    $('.button_ipunk4').show();
    $(".sidebar").css("height", "100%");
    $scope.search_result();
});

}]);
$('.show-filters').click(function() {
    $('.sidebar').addClass('fixed-hieght');

});
$('#cancel-filter').click(function() {
    $('.sidebar').removeClass('fixed-hieght');

});
$('#more_filter_submit').click(function() {
    if ($(window).width() < 480) {
        $('.filter-div').hide();
        $('.button_ipunk').show();
        $('.button_ipunk4').show();
    }
});
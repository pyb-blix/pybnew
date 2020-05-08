app.controller('manage_listing', ['$scope', '$http', '$rootScope', '$compile', '$filter', 'fileUploadService', function($scope, $http, $rootScope, $compile, $filter, fileUploadService) {

    $(document).ready(function() {
        $scope.full_calendar();
        $scope.initDatePickers();
        $scope.availability_datepickers();
        $scope.initDraggablePhotos();
        $scope.getPhotos();
        if($scope.is_started != 'Yes') {
            $('#steps_complete-popup').modal('show');
        }
    });

    $scope.back_button_clicked = 0;
    $(document).on('click', '.nav-sections .nav-item a, .next_step a, #calendar_edit_cancel', function() {

        var current_url = $(this).attr('href');

        if(current_url == '' || $(this).closest("li").hasClass('nav-active')) {
            return false;
        }

        $("#ajax_container").addClass('loading');

        $http.post(current_url.replace('manage-listing', 'ajax-manage-listing'), {}).then(function(response) {
            if(response.data.success_303 == "false"){
                window.location = APP_URL + '/login';
                return false;
            }

            $("#ajax_container").html($compile(response.data)($scope));

            $scope.name    = $scope.rooms_default_description.name;
            $scope.summary = $scope.rooms_default_description.summary;
            $scope.other_notes = '';
            $('#ajax_container').removeClass('loading');

            $scope.date = moment().format('YYYY-MM-DD');
            listing_cnt();
            $scope.full_calendar();
            $scope.initDraggablePhotos();
            $scope.getPhotos();
        },function(response) {
            if(response.status == '300')
                window.location = APP_URL + '/login';
        });

        var ex_pathname = (window.location.href).split('/');
        var cur_step = $(ex_pathname).get(-1);
        $('#href_' + cur_step).attr('href', window.location.href);
        var ex_pathname = $(this).attr('href').split('/');
        var next_step = $(ex_pathname).get(-1);

        $scope.step = next_step;

        $('#js-manage-listing-nav').removeClass('collapsed');
        $('body').removeClass('non_scroll');

        if(!$scope.back_button_clicked){
            window.history.pushState(
            {
                path: $(this).attr('href')
            }, 
            '', 
            $(this).attr('href')
            );
        }
        $scope.back_button_clicked = 0
        return false;
    });

    $scope.text_length_calc = function(text) {
        tag_free_text = text ? String(text).replace(/<[^>]+>/gm, '') : '';
        return tag_free_text.length;
    }

    // Calendar Related Functionality Start

    $scope.date = moment().format('YYYY-MM-DD');

    $scope.full_calendar = function() {
        $("#ajax_container").addClass('loading');
        $('#calendar').fullCalendar({
            selectable: true,
            unselectAuto: false,
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            header: {
              left: 'prev,next',
              center: 'title',
              right: '' // To Set Weekly and Daily set view as month,agendaWeek,agendaDay
          },
          defaultDate: $scope.date,
          locale: LANGUAGE_CODE,
          firstDay: 1,
          height:'parent',
            longPressDelay: 500, // In Mobile Hold Click only works set long press time to 1 ms to work as normal select
            events: $scope.calendar_data,
            select: function(startDate, endDate) {
                $scope.showUpdateForm = false;
                if(startDate.isBefore(moment().subtract(1, 'd'))) {
                    $scope.unSelectCalendar();
                    return false;
                }

                cDateCheck = startDate.clone();
                while(cDateCheck < endDate) {
                    if($('#'+$scope.changeFormat(cDateCheck)).hasClass('status-r')) {
                        $scope.unSelectCalendar();
                        return false;
                    }
                    cDateCheck.add(1, 'd');
                }

                // Next day also selected when select days
                endDate.subtract(1, 'd');

                var c_formatDate = $scope.changeFormat(startDate);

                var formatted_sDate = $scope.changeFormat_EN(startDate);
                var formatted_eDate = $scope.changeFormat_EN(endDate);

                $('#calendar-edit-start').val($scope.changeFormat(startDate,daterangepicker_format));
                $('#calendar-edit-end').val($scope.changeFormat(endDate,daterangepicker_format));
                $('#calendar-start').val(formatted_sDate);
                $('#calendar-end').val(formatted_eDate);
                var c_date = $('#'+c_formatDate);

                $scope.showUpdateForm = true;
                $scope.calendar_edit_price = parseInt(c_date.find('.fc-bgevent-data').attr('data-price'));
                $scope.segment_status = c_date.find('.fc-bgevent-data').attr('data-status');
                $scope.notes = c_date.find('.fc-bgevent-data').attr('data-notes');
                $scope.isAddNote = $scope.notes != '';

                if(!$scope.$$phase) {
                    $scope.$apply();
                }
            },
            unselect: function(event) {
                $scope.unSelectCalendar();
            },
            eventRender: function(event, element, view) {
                element.attr('id',$scope.changeFormat(event.start));
                if(event.className.length) {
                    element.addClass(event.className);
                }
                var spots_left_text = '';
                if(event.spots_left) {
                    spots_left_text = '<span class="spots_left">'+ event.spots_left +' '+ $scope.spots_left_text +'</span>';
                }
                $('<div class="fc-bgevent-data" data-price="'+event.price+'" data-notes="'+event.notes+'" data-status="'+event.description+'"> <span class="price">'+ event.title +'</span> '+ spots_left_text +' <span class="notes">'+ event.notes +'</span> </div>').appendTo(element);
            },
            dayRender: function(date, cell) {
                var today = $.fullCalendar.moment(moment().format('YYYY-MM-DD'));
                if (date < today) {
                    cell.css("background", "#e0e0e0");
                }
            }
        });

        $scope.unSelectCalendar();
        $("#ajax_container").removeClass('loading');
    };

    $scope.initDatePickers = function() {
        $("#calendar-edit-start").datepicker({
            dateFormat: datepicker_format,
            minDate: 0,
            onSelect: function(date,obj) {
                var selected_month = obj.selectedMonth + 1;
                var start_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                $('#calendar-start').val(start_formatted_date);
                var checkout = $("#calendar-edit-start").datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkout);

                setTimeout(() => $('#calendar-edit-end').datepicker("show"), 20);
            }
        });

        $('#calendar-edit-end').datepicker({
            dateFormat: datepicker_format,
            minDate: 1,
            onClose: function() {
                var checkin = $("#calendar-edit-start").datepicker('getDate');
                var checkout = $('#calendar-edit-end').datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
                if (checkout <= checkin) {
                    var minDate = $('#calendar-edit-end').datepicker('option', 'minDate');
                    $('#calendar-edit-end').datepicker('setDate', minDate);
                }
            },
            onSelect: function(date,obj) {
                var selected_month = obj.selectedMonth + 1;
                var end_formatted_date = obj.selectedDay+'-'+selected_month+'-'+obj.selectedYear;
                $('#calendar-end').val(end_formatted_date);
                var checkout = $("#calendar-edit-start").datepicker('getDate');
                $('#calendar-edit-end').datepicker('option', 'minDate', checkout);
            }
        });
    };

    $scope.unSelectCalendar = function() {
        $('#calendar').fullCalendar('unselect');
        $scope.showUpdateForm = false;
        if(!$scope.$$phase) {
            $scope.$apply();
        }
    };

    $scope.changeFormat = function(date,format = 'YYYY-MM-DD') {
        return date.locale(LANGUAGE_CODE).format(format);
    };

    $scope.changeFormat_EN = function(date,format = 'YYYY-MM-DD') {
        return date.locale('en').format(format);
    };

    function change_format(date) {
        if(date != undefined) {
            var split_date = date.split('-');
            return split_date[2] + '-' + split_date[1] + '-' + split_date[0];
        }
    }

    $scope.calendar_edit_submit = function() {
        var data_params = {};
        data_params['status'] = $scope.segment_status;
        data_params['start_date'] = $('#calendar-start').val();
        data_params['end_date'] = $('#calendar-end').val();
        data_params['price'] = $scope.calendar_edit_price;
        data_params['notes'] = $scope.notes;
        var data = JSON.stringify(data_params);
        $('.calendar-side-option').addClass('loading');
        var callback_function = function(response_data) {
            $scope.showUpdateForm = false;
            $scope.notes = '';
            /*var sDate = $('#calendar').fullCalendar('getDate');
            var month = sDate.format('MM');
            var year = sDate.format('YYYY');*/
            var sDate = $('#calendar').fullCalendar('getDate');
            var month_year = sDate.format();
            month_year =month_year.split('-');
            var month = month_year[1];
            var year = month_year[0];

            var data_params = {};
            data_params['month'] = month;
            data_params['year'] = year;

            var data = { data : JSON.stringify(data_params) };
            $('.calendar-side-option').removeClass('loading');
            $('#calendar').addClass('loading');
            var url= document.URL.replace('manage-listing', 'ajax-manage-listing');
            var callback_function = function(response_data) {
                $("#ajax_container").html($compile(response_data)($scope));
                $scope.date = year+'-'+ month +'-10';
                $scope.full_calendar();
                $scope.initDatePickers();
                $('#calendar').removeClass('loading');
            };
            $scope.http_post(url,data,callback_function);
        };

        $scope.http_post('calendar_edit',data,callback_function);
    };

    function strip(html)
    {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    function getMonthFromString(mon)
    {
        return moment().month(mon).format("MM");
    }

    $(document).on('click','.fc-prev-button,.fc-next-button,.fc-today-button',function() {
        $('#calendar').addClass('loading');
        var sDate = $('#calendar').fullCalendar('getDate');
        var month_year = sDate.format();
        /*var month = sDate.format('M');
        var year = sDate.format('YYYY');*/
        // $('#calendar').addClass('loading');
        // var month_year = strip($('.fc-center').html());
        month_year =month_year.split('-');
        var month = month_year[1];
        var year = month_year[0];

        var data_params = {};
        data_params['month'] = month;
        data_params['year'] = year;
        var data = { data : JSON.stringify(data_params) };
        var url= document.URL;
        url = url.replace('manage-listing', 'ajax-manage-listing');

        var callback_function = function(response_data) {
            $("#ajax_container").html($compile(response_data)($scope));
            $scope.date = year+'-'+ month +'-10';
            $scope.full_calendar();
            $scope.initDatePickers();
            $('#calendar').removeClass('loading');
        };

        $scope.http_post(url,data,callback_function);
    });

    $(document).on('click', '.fc-day-top, .fc-bgevent-data', function() {
        $scope.unSelectCalendar();
    });

    $(document).on('click', '.fc-bgevent-skeleton > table > tbody > tr > td', function() {
        $scope.unSelectCalendar();
    });

    // Calendar Related Functionality End

    $(document).on('click', '#add_language', function() {
        $('#add_language_des').show();
        $('.description_form').hide();
        $('.tab-item').attr('aria-selected', 'false');
        $('#write-description-button').prop('disabled', true);

        $http.post('get_all_language', { }).then(function(response) {
            $scope.all_language = response.data;
        });
    });

    $(document).on('click', '#delete_language', function() {
        var current_tab = $('#current_tab_code').val();
        var url = 'delete_language';
        var data = {current_tab:current_tab };
        var callback_function = function(response_data) {
            $scope.http_post('lan_description',{},function(response_data) {
                $scope.lan_description = response_data;
                current_tab = $('#current_tab_code').val('en');
            });
            $scope.getdescription('en');
        };

        $scope.http_post(url,data,callback_function);
    });

    $(document).on('change', '#language-select', function() {
        $('#write-description-button').prop('disabled', false);
    });

    $http.post('lan_description', { }).then(function(response) {
        $scope.lan_description = response.data;
    });

    $http.post('get_all_language', { }).then(function(response) {
        $scope.all_language = response.data;
    });

    $http.post('get_description', { lan_code : 'en'}).then(function(response) {
        $scope.name    = response.data[0].name;
        $scope.summary = response.data[0].summary;
        $scope.space   = response.data[0].rooms_description.ori_space;
        $scope.access   = response.data[0].rooms_description.ori_access;
        $scope.interaction   = response.data[0].rooms_description.ori_interaction;
        $scope.other_notes   = response.data[0].rooms_description.ori_notes;
        $scope.house_rules   = response.data[0].rooms_description.ori_house_rules;
        $scope.neighborhood_overview   = response.data[0].rooms_description.ori_neighborhood_overview;
        $scope.transit   = response.data[0].rooms_description.ori_transit;    
    });

    $scope.getdescription = function(lan_code) {
        var lan_code = lan_code;
        $http.post('get_description', {lan_code :lan_code }).then(function(response) {
            if(lan_code != 'en'){
                $scope.name    = response.data[0].name;
                $scope.summary = response.data[0].summary;
                $scope.space   = response.data[0].space;
                $scope.access   = response.data[0].access;
                $scope.interaction   = response.data[0].interaction;
                $scope.other_notes   = response.data[0].notes;
                $scope.house_rules   = response.data[0].house_rules;
                $scope.neighborhood_overview   = response.data[0].neighborhood_overview;
                $scope.transit   = response.data[0].transit;
            }
            else {
                $scope.name    = response.data[0].name;
                $scope.summary = response.data[0].summary;
                $scope.space   = response.data[0].rooms_description.ori_space;
                $scope.access   = response.data[0].rooms_description.ori_access;
                $scope.interaction   = response.data[0].rooms_description.ori_interaction;
                $scope.other_notes   = response.data[0].rooms_description.ori_notes;
                $scope.house_rules   = response.data[0].rooms_description.ori_house_rules;
                $scope.neighborhood_overview   = response.data[0].rooms_description.ori_neighborhood_overview;
                $scope.transit   = response.data[0].rooms_description.ori_transit;
            }

            if(response.data[0].lang_code) {
                var tab_selected = $("#"+response.data[0].lang_code).attr('aria-selected');
                $('#current_tab_code').val(response.data[0].lang_code);
                $('#delete_language').show();
            }
            else {
                var tab_selected = $("#en").attr('aria-selected');
                response.data[0].lang_code = 'en';
                $('#current_tab_code').val(response.data[0].lang_code);
                $('#delete_language').hide();

            }

            if(tab_selected == 'false') {
                $('.tab-item').attr('aria-selected', 'false');
                $("#"+response.data[0].lang_code).attr('aria-selected', 'true');

            }

            $('#add_language_des').hide();
            $('.description_form').show();
        });
    };

    $scope.addlanguageRow = function() {
        var lan_code = $('#language-select').val();
        $('#current_tab_code').val(lan_code);

        $http.post('add_description', {lan_code :lan_code }).then(function(response) {
            $scope.name    = response.data[0].name;
            $scope.summary = response.data[0].summary;     
            $scope.space   = response.data[0].space;
            $scope.access   = response.data[0].access;
            $scope.interaction   = response.data[0].interaction;
            $scope.other_notes   = response.data[0].notes;
            $scope.house_rules   = response.data[0].house_rules;
            $scope.neighborhood_overview   = response.data[0].neighborhood_overview;
            $scope.transit   = response.data[0].transit;
            $('#write-description-button').prop('disabled', true);

            $http.post('lan_description', { }).then(function(response) {
                $scope.lan_description = response.data;
                $('#add_language_des').hide();
                $('.description_form').show();
                $('.description-tabs').show();
                $('#delete_language').show();
                var count = (response.data[0].lan_id - 1 );
                $('.tab-item').attr('aria-selected', 'false');

                setTimeout(function() { 
                    $("#"+response.data[count].lan_code).attr('aria-selected', 'true');
                }, 100);
            });
            $('#language-select').prop('selectedIndex',0);
        });
    };

    // browser back button click previous page
    $(document).ready(function($) {
        if(window.history) {
            $(window).on('popstate', function() {
                var ex_pathname = (window.location.href).split('/');
                var cur_step = $(ex_pathname).get(-1);
                $('#href_' + cur_step).attr('href', window.location.href);
                $scope.back_button_clicked = 1;
                $('#href_' + cur_step).trigger('click');
            });
        }
    });

    $scope.update_status = function(data_track, status) {
        if(status == 1) {
            $('[data-track="'+data_track+'"] a div.transition').addClass('d-none');
            $('[data-track="'+data_track+'"] a div.transition').removeClass('visible');
            $('[data-track="'+data_track+'"] a div.success-icon .icon-ok-alt').removeClass('d-none');
        }
        else {
            $('[data-track="'+data_track+'"] a div.transition').removeClass('d-none');
            $('[data-track="'+data_track+'"] a div.transition').addClass('visible');
            $('[data-track="'+data_track+'"] a div.transition .icon').removeClass('d-none');
            $('[data-track="'+data_track+'"] a div.success-icon .icon-ok-alt').addClass('d-none');
        }
    };

    $scope.http_post = function(url, data, callback) {

        data = (!data) ? {} : data;

        $http.post(url,data).then(function(response) {
            if(response.status == 200) {
                if(callback) {
                    callback(response.data);
                }
            }
        }, function(response) {
            if(response.status == '300') {
                window.location = APP_URL + '/login';
            }
            else if(response.status == '500') {
                window.location.reload();
            }
        });
    };

    $rootScope.show_bed_room = [];
    
    $scope.range = function(min, max, step) {
        step = step || 1;
        var input = [];
        for(var i = min; i <= max; i += step) {
            input.push(i);
        }
        return input;
    };

    $scope.firstElem = function(ele) {
      return ele.$first
  };

  Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if(obj.hasOwnProperty(key)) size++;
    }
    return size;
};

$scope.bedrooms_changes = function() {
    var bed_types_name= $scope.bed_types_name;
    var size = Object.size(bed_types_name);
    if($scope.bedrooms < size) {
        for (var i = 0; i < size; i++) {
           if(i > ($scope.bedrooms-1)) {
            delete $scope.bed_types_name[i+1];
        }
    }
}
else {
    for(var i = 0; i <= $scope.bedrooms; i++) {
        if(i > (size)) {
            $scope.bed_types_name[i]=angular.copy($scope.get_single_bed_type);
        }
    }
}     
};

$scope.get_first_bed_type = function() {
    return angular.copy($scope.first_bed_type);
};

$scope.total_bed_count = function(array) {
    var tot = 0;
    angular.forEach(array,function(val,key){
        var counts = val.count > 0 ? val.count:0;
        tot +=counts-0;
    });
    return tot;
}

$scope.total_bed_type_count =function(array,id) {
    var len= Object.keys(array).length;
    angular.forEach(array,function(val,key){
        if(key!=len) {
            return 'sdf11,';
        }
    });
};

$scope.show_bded_room =function(no) {
    $scope.common_bed = false;

    angular.forEach($scope.bed_types_name,function(val,key){
        if(key==no){
            if($rootScope.show_bed_room[key])
                $rootScope.show_bed_room[key]=false;
            else
                $rootScope.show_bed_room[key]=true;
        }
        else
            $rootScope.show_bed_room[key]=false;
    });

    if($scope.show_bed_room.indexOf(true) !== -1) {
        $('.help_div').hide(); 
        $('.preview-panel').show();
    }
    else {
        $('.help_div').show();
        $('.preview-panel').hide();
    }
};

$scope.show_common_bded_room =function() {
    $scope.common_bed = ($scope.common_bed) ? false : true;

    angular.forEach($scope.bed_types_name,function(val,key){
        $rootScope.show_bed_room[key]=false;
    });

    if($scope.common_bed) {
        $('.help_div').hide(); 
        $('.preview-panel').show();
    }
    else {
        $('.help_div').show();
        $('.preview-panel').hide();
    }
};

$scope.show_bed_icon =function(array,room_no,room) {
    var tot = 0;
    var total = 0;
    var j=0;
    var k=0;
    var over_total = 0;

    $('.icon_div').html('');

    angular.forEach(array,function(val,key){
        var icon = val.icon > 0 ? val.icon:'';
        var counts = val.count > 0 ? val.count:0;
        over_total +=counts-0;
    });

    if(over_total == 0){
      $('.no_data').removeClass('d-none');
      $('.data_result').addClass('d-none');
  }
  else {
    $('.no_data').addClass('d-none');
    $('.data_result').removeClass('d-none');
    $('.current_bed_room').text(room);
    $('.current_bed_room_count').text(room_no);
}

angular.forEach(array,function(val,key) {
    var icon = val.icon > 0 ? val.icon:'';
    var counts = val.count > 0 ? val.count:0;
    total += counts-0;
    tot++;
    var show_count = total-2+0;
    if(val.count > 0){
        for(j=1;j<=val.count;j++) {
            k++;
            if(k < 4) {
                if(k == 3) {
                    if(over_total <= 3) {
                       $('.icon_div').append('<div class="bed-details_preview_item text-center col-4 px-2 cls_bedimg" aria-hidden="true" role="presentation"><div class="bed-details_preview_item_icon"><img  class="icon_img"  src="'+val.icon+'"></span><span class="d-block">'+val.name+'</span></div></div>');  
                   }  
               }
               else{
                $('.icon_div').append('<div class="bed-details_preview_item text-center col-4 px-2 cls_bedimg" aria-hidden="true" role="presentation"><div class="bed-details_preview_item_icon"><img  class="icon_img"  src="'+val.icon+'"></span><span class="d-block">'+val.name+'</span></div></div>');  
            }
        }
        else if(k== 4) {
          $('.icon_div').append('<div class="bed-details__preview_item bed-details__preview_item--ellipsis va-top text-center text-babu col-sm-4"><span class="select_bed_count">'+show_count+'</span>+</div>'); 
      }
      else if(k >4) {
        $('.select_bed_count').text(show_count); 
    }
}
}
else {
    tot=tot-1;  
}
});
return tot;
};

$scope.bed_type_item_available = function(bed_id, array_name) {
    var available = true;
    angular.forEach(array_name,function(key,val){
        if(bed_id==key.id){
            available = false; 
        }
    });
    return available;
};

$scope.add_bed_types = function(n,selected,all_beds) {
    angular.forEach($scope.all_bed_type, function(item) {
        if(item.id==selected) {
            $scope.bed_types_name[n].push({'id':item.id,'name':item.name,'count':1,'icon':item.icon});
            $scope.save_room_types();
        }
    });
    return '';
};
$scope.obj_size = function(obj){
    return Object.size(obj)
}
$scope.save_bathrooms = function() {
    var data_params = {};
    data_params['bathroom_shared'] = $scope.bathroom_shared;
    data_params['bathrooms'] =$scope.bathrooms;
    $('.basics2 h5').text(SAVING);
    $('.basics2').fadeIn();
    var data = JSON.stringify(data_params);
    var callback_function = function(response_data) {
        $('.basics2 h5').text(SAVED);
        $('.basics2').fadeOut();
        $('#steps_count').text(response_data.steps_count);
        $scope.steps_count = response_data.steps_count;
        $scope.status = response_data.status;
    };
    $scope.http_post('update_bath_rooms',data,callback_function);
};

$scope.add_common_bed_types = function(selected) {
    angular.forEach($scope.all_bed_type, function(item){
        if(item.id==selected) {
            $scope.get_common_bed_type.push({'id':item.id,'name':item.name,'count':1,'icon':item.icon});
            $scope.save_common_room_types();
        }
    });
    return '';
};

$scope.bedrooms_changes = function() {
    var sdf= $scope.bed_types_name;
    var size = Object.size(sdf);
    if($scope.bedrooms < size) { 
        for (var i = 0; i < size; i++) {
            if(i > ($scope.bedrooms-1) ) {
                delete $scope.bed_types_name[i+1];
            }
        }
    }
    else {
        for(var i = 0; i <= $scope.bedrooms; i++) {
            if(i > (size)) {
                $scope.bed_types_name[i]=angular.copy($scope.get_single_bed_type);
            }
        }
    }     
};

$scope.bed_change_disable = 0
$scope.save_room_types = function() {
    $scope.bed_change_disable = 1
    var data_params = {};
    data_params['bed_room'] = $scope.bedrooms;
    data_params['bed_types'] =$scope.bed_types_name;
    var data = JSON.stringify(data_params);
    var url = 'update_bed_rooms';
    var callback_function = function(response_data) {
        $scope.update_status("basics",response_data.basics_status);
        $('#steps_count').text(response_data.steps_count);
        $scope.steps_count = response_data.steps_count;
        $scope.steps_count.apply
        $scope.status = response_data.status;
        $scope.bed_change_disable = 0
    }

    $scope.http_post(url,data,callback_function);
};

$scope.save_common_room_types = function() { 
    $scope.bed_change_disable = 1
    var data_params = {};
    data_params['bed_types'] =$scope.get_common_bed_type;
    var data = { data : JSON.stringify(data_params) };
    var url  = 'update_common_bed_rooms';
    var callback_function = function(response_data) {
        $scope.update_status("basics",response_data.basics_status);
        $('#steps_count').text(response_data.steps_count);
        $scope.steps_count = response_data.steps_count;
        $scope.status = response_data.status;
        $scope.bed_change_disable = 0
    };

    $scope.http_post(url,data,callback_function);
};


$scope.update_bedrooms = function() {

    var data_params = {};
    data_params['bedrooms'] = $scope.bedrooms;
    var data = { data : JSON.stringify(data_params) };

    $('.basics1 h5').text(SAVING);
    $('.basics1').fadeIn();

    var url = 'update_rooms';

    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('.basics1 h5').text(SAVED);
            $('.basics1').fadeOut();
            $('#steps_count').text(response_data.steps_count);
            $scope.steps_count = response_data.steps_count;
            $scope.status = response_data.status;
        }
        if(response_data.redirect != '' && response_data.redirect != undefined) {
            window.location = response_data.redirect;
        }

        $scope.update_status("basics",response_data.basics_status);
    };

    $scope.http_post(url,data,callback_function);
};

$(document).on('click', '[data-track="welcome_modal_finish_listing"]', function() {
    var data_params = {};
    data_params['started'] = 'Yes';
    var data = { data : JSON.stringify(data_params) };
    var url = 'update_rooms';
    var callback_function = function(response_data) {
        $('#steps_complete-popup').modal('hide');
    };

    $scope.http_post(url,data,callback_function);
});

$(document).on('change', '[id^="basics-select-"], [id^="select-"]', function() {
    var data_params = {};
    data_params[$(this).attr('name')] = $(this).val();
    var data = { data : JSON.stringify(data_params) };
    var url = 'update_rooms';

    var saving_class = $(this).attr('data-saving');
    $('.' + saving_class + ' h5').text(SAVING);
    $('.' + saving_class).fadeIn();

    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('.' + saving_class + ' h5').text(SAVED);
            $('.' + saving_class).fadeOut();
            $('#steps_count').text(response_data.steps_count);
            $scope.steps_count = response_data.steps_count;
            $scope.status = response_data.status;
        }
        if(response_data.redirect != '' && response_data.redirect != undefined) {
            window.location = response_data.redirect;
        }
        if($scope.beds != '' && $scope.bedrooms != '' && $scope.bathrooms != '' && $scope.bed_type != '') {
            var track = saving_class.substring(0, saving_class.length - 1);
            $scope.update_status(track,1);
        }
    };

    $scope.http_post(url,data,callback_function);
});

$(document).on('blur', '#video', function() {
    var data_params = {};
    data_params[$(this).attr('name')] = $(this).val();
    var data = { data : JSON.stringify(data_params) };
    var url = 'update_rooms';
    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('.saving-progress h5').text(SAVED);
            $('.saving-progress').fadeOut();
            $scope.video = response_data.video;
            $('#rooms_video_preview').parent().removeClass('d-none');
            $('#rooms_video_preview').attr('src', response_data.video);
        }
        else {
            $('.saving-progress').fadeOut();
            $('#video_error').fadeIn();
        }
    };

    $('#video_error').fadeOut();
    $('.saving-progress h5').text(SAVING);
    $('.saving-progress').fadeIn();

    $scope.http_post(url,data,callback_function);
});

$(document).on('click', '#remove_rooms_video', function() {
    var saving_class = $(this).attr('data-saving');
    $('.saving-progress h5').text(REMOVING);
    $('.saving-progress').fadeIn();

    var url = 'remove_video';
    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('.saving-progress h5').text(REMOVED);
            $('.saving-progress').fadeOut();
            $('#video').val('');
            $('#rooms_video_preview').parent().addClass('d-none');
            $('#rooms_video_preview').attr('src', '');
            $scope.video = response_data.video;

        }
    };

    $scope.http_post(url,{},callback_function);
});

$(document).on('blur', '[class^="overview-"]', function() {
    var data_params = {};

    data_params[$(this).attr('name')] = $(this).val();
    var current_tab = $('#current_tab_code').val();
    var data = JSON.stringify(data_params);
    var saving_class = $(this).attr('data-saving');

    value = $(this).val()
    if(value.trim()) {
        $('.'+saving_class+' h5').text(SAVING);
        $('.'+saving_class).fadeIn();
        if(current_tab=='en'){
            $('.name_required_msg').addClass('d-none');
            $('.summary_required_msg').addClass('d-none');
            $('.name_required').removeClass('invalid');
            $('.summary_required').removeClass('invalid');
        }

        $http.post('update_rooms', {
            data: data , current_tab:current_tab
        }).then(function(response) {
            if(response.data.success == 'true') {
                $('.'+saving_class+' h5').text(SAVED);
                $('.'+saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.steps_count = response.data.steps_count;
                $scope.status = response.data.status;

            }
            if($scope.name != '' && $scope.summary != '' && current_tab=='en') {
                $scope.update_status("description",1);
            }
        }, function(response) {
            if(response.status == '300'){
                window.location = APP_URL + '/login';
            } else if(response.status == '500'){
                window.location.reload();
            }
        });
    }
    else {
        if($(this).attr('name') == 'name') {
            if(current_tab=='en') {
                $('.name_required').addClass('invalid');
                $('.name_required_msg').removeClass('d-none');
                return true;
            }

            $http.post('update_rooms', {
                data: data, current_tab:current_tab
            }).then(function(response) {
                if(response.data.success == 'true') {
                    if(current_tab=='en') {
                        $('.name_required').addClass('invalid');
                        $('.name_required_msg').removeClass('d-none');
                    }
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                    $scope.status = response.data.status;
                }
            }, function(response) {
                if(response.status == '300'){
                    window.location = APP_URL + '/login';
                } else if(response.status == '500'){
                    window.location.reload();
                }
            });
        }
        else {
            if(current_tab=='en'){
                $('.summary_required').addClass('invalid');
                $('.summary_required_msg').removeClass('d-none');
                return true;
            }
            $http.post('update_rooms', {
                data: data, current_tab:current_tab
            }).then(function(response) {
                if(response.data.success == 'true') {
                    if(current_tab=='en'){
                        $('.summary_required').addClass('invalid');
                        $('.summary_required_msg').removeClass('d-none');
                    }
                    $('#steps_count').text(response.data.steps_count);
                    $scope.steps_count = response.data.steps_count;
                    $scope.status = response.data.status;
                }
            }, function(response) {
                if(response.status == '300'){
                    window.location = APP_URL + '/login';
                } else if(response.status == '500'){
                    window.location.reload();
                }
            });

        }
        if(current_tab=='en') {
            $scope.update_status("description",0);
        }
    }
    if(current_tab == 'en') {
        $scope.rooms_default_description.name = $scope.name;
        $scope.rooms_default_description.summary = $scope.summary;    
    }
});

$(document).on('click', '#js-write-more', function() {
    $('.write_more_p').hide();
    $('#js-section-details').show();
    $('#js-section-details_2').show();
});

$(document).on('click', '#show_long_term', function() {
    $('#js-long-term-prices').removeClass('d-none');
    $('#js-set-long-term-prices').addClass('d-none');
});

$(document).on('click', '#js-add-address, #js-edit-address', function() {
    var data_params = {};
    $scope.autocomplete_used = false;
    data_params['country'] = $scope.country;
    data_params['address_line_1'] = $scope.address_line_1;
    data_params['address_line_2'] = $scope.address_line_2;
    data_params['city'] = $scope.city;
    data_params['state'] = $scope.state;
    data_params['postal_code'] = $scope.postal_code;
    data_params['latitude'] = $scope.latitude;
    data_params['longitude'] = $scope.longitude;
    var data = JSON.stringify(data_params);
    $('#js-address-container').addClass('enter_address');
    $('#address-flow-view .modal').fadeIn();
    $('#address-flow-view .modal').attr('aria-hidden', 'false');
    $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
        data: data
    }).then(function(response) {
        $("#js-address-container").html($compile(response.data)($scope));
        initAutocomplete();
    });
});

$(document).on('click', '#js-next-btn', function() {
    var data_params = {};
    data_params['country'] = $scope.country = $('#country').val();
    data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
    data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
    data_params['city'] = $scope.city = $('#city').val();
    data_params['state'] = $scope.state = $('#state').val();
    data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
    data_params['latitude'] = $scope.latitude;
    data_params['longitude'] = $scope.longitude;

    if(!data_params['country']) {
        $("#location_country_field_error").removeClass("d-none");
        return false;
    }

    $("#location_country_field_error").addClass("d-none");
    var data = JSON.stringify(data_params);
    if(!$scope.autocomplete_used)
        $scope.location_found = true;
    $('#js-address-container .panel').addClass('loading');

    var geocoder = new google.maps.Geocoder();
    address = $scope.address_line_1 + ', ' + $scope.address_line_2 + ', ' + $scope.city + ', ' + $scope.state + ', ' + $scope.country + ', ' + $scope.postal_code;
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if(status == google.maps.GeocoderStatus.OK) {
            $scope.latitude = results[0].geometry.location.lat();
            $scope.longitude = results[0].geometry.location.lng();
            result = results[0];
            if(result['types'] == "street_address" || result['types'] == "premise") {
                $scope.location_found = true;
                $scope.autocomplete_used = true;
            } else {
                $scope.location_found = false;
                $scope.autocomplete_used = false;
            }
        }
        $http.post((window.location.href).replace('manage-listing', 'location_not_found'), {
            data: data
        }).then(function(response) {
            if(response.data.status == "country_error") {
                $("#location_country_field_error").removeClass("d-none");
                $('#js-address-container .panel').removeClass('loading');
                return false;
            }
            $('#js-address-container .panel').removeClass('loading');
            $('#js-address-container').addClass('location_not_found');
            $("#js-address-container").html($compile(response.data)($scope));
        });
    });
});

$(document).on('click', '#js-next-btn2', function() {
    var data_params = {};

    data_params['country'] = $scope.country;
    data_params['address_line_1'] = $scope.address_line_1;
    data_params['address_line_2'] = $scope.address_line_2;
    data_params['city'] = $scope.city;
    data_params['state'] = $scope.state;
    data_params['postal_code'] = $scope.postal_code;
    data_params['latitude'] = $scope.latitude;
    data_params['longitude'] = $scope.longitude;

    var data = JSON.stringify(data_params);
    $('#js-address-container').addClass('loading');
    $http.post((window.location.href).replace('manage-listing', 'verify_location'), {
        data: data
    }).then(function(response) {
        if(response.data.status == "country_error") {
            $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
                data:data
            }).then(function(response) {
                $("#js-address-container").html($compile(response.data)($scope));
                initAutocomplete();
                $scope.country = data_params['country'];
                $("#location_country_field_error").removeClass("d-none");
            });
            $('#js-address-container').removeClass('loading');
            return false;
        }
        $('#js-address-container').addClass('location_not_found');
        $("#js-address-container").html($compile(response.data)($scope));
        initMap();
        setTimeout( () => {
            $('#js-address-container').removeClass('loading');
        } , 250);
    });
});

    //amenity tooltip show
    $(document).on('mouseover', '[id^="amenity-tooltip"]', function() {
        var id = $(this).data('id');
        $('#ame-tooltip-' + id).show();
    });

    $(document).on('mouseout', '[id^="amenity-tooltip"]', function() {
        $('[id^="ame-tooltip"]').hide();
    });

    $(document).on('click', '#js-next-btn3', function() {
        var data_params = {};

        data_params['country'] = $scope.country = $('#country').val();
        data_params['address_line_1'] = $scope.address_line_1 = $('#address_line_1').val();
        data_params['address_line_2'] = $scope.address_line_2 = $('#address_line_2').val();
        data_params['city'] = $scope.city = $('#city').val();
        data_params['state'] = $scope.state = $('#state').val();
        data_params['postal_code'] = $scope.postal_code = $('#postal_code').val();
        data_params['latitude'] = $scope.latitude;
        data_params['longitude'] = $scope.longitude;

        var data = JSON.stringify(data_params);

        $('#js-address-container .panel:first').addClass('loading');
        $http.post((window.location.href).replace('manage-listing', 'finish_address'), {
            data: data
        }).then(function(response) {
           $('#js-address-container .panel').removeClass('loading');
           if(response.data.status == "country_error") {
            $http.post((window.location.href).replace('manage-listing', 'enter_address'), {
                data:data
            }).then(function(response) {
                $("#js-address-container").html($compile(response.data)($scope));
                initAutocomplete();
                $scope.country = data_params['country'];
                $("#location_country_field_error").removeClass("d-none");
            });

            return false;
        }

        $('.location-map-container-v2').removeClass('empty-map');

        $('.location-map-pin-v2').removeClass('moving').addClass('set');
        $('.address-static-map img').remove();
        $('.address-static-map').append('<img style="width:100%; height:275px;" src="https://maps.googleapis.com/maps/api/staticmap?size=570x275&amp;center=' + response.data.latitude + ',' + response.data.longitude + '&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key=' + map_key + '&amp;markers=icon:' + APP_URL + '/images/map-pin-set-3460214b477748232858bedae3955d81.png%7C' + response.data.latitude + ',' + response.data.longitude + '">');

        $('.edit-address .text-center').remove();

        $('.edit-address address').removeClass('d-none');
        $('#js-add-address').addClass('d-none');
        $('#js-edit-address').removeClass('d-none');
        $scope.address_line_1   = response.data.address_line_1;
        $scope.address_line_2   = response.data.address_line_2;
        $scope.city             = response.data.city;
        $scope.state            = response.data.state;
        $scope.postal_code      = response.data.postal_code;
        $scope.country_name     = response.data.country_name;

        $scope.update_status("location",1);

        $('#address-flow-view .close').trigger('click');
        $('#steps_count').text(response.data.steps_count);
        $scope.steps_count      = response.data.steps_count;
        $scope.status = response.data.status;
        $scope.location_found   = false;
    });
    });

    // Call Google Autocomplete Initialize Function
    initAutocomplete();

    // Google Place Autocomplete Code
    $scope.location_found = false;
    $scope.autocomplete_used = false;
    var autocomplete;

    function initAutocomplete() {
        var ex_pathname = (window.location.href).split('/');
        var cur_step = $(ex_pathname).get(-1);

        if(cur_step == 'location') {
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_line_1'));
            autocomplete.addListener('place_changed', fillInAddress);
        }
    }

    $("#address-flow-view .modal").scroll(function() {
        $(".pac-container").hide();
    });

    function fillInAddress() {
        $scope.autocomplete_used = true;
        fetchMapAddress(autocomplete.getPlace());
    }

    if($('#state').val() || $('#city').val() == '') {
        $('#js-next-btn').prop('disabled', true);
    }

    $(document).on('keyup', '#state', function() {
        if($(this).val() == '') {
            $('#js-next-btn').prop('disabled', true);
        }
        else {
            $('#js-next-btn').prop('disabled', false);
        }
    });

    $(document).on('keyup', '#city', function() {
        if($(this).val() == '') {
            $('#js-next-btn').prop('disabled', true);
        }
        else {
            $('#js-next-btn').prop('disabled', false);
        }
    });

    var map, geocoder;

    function initMap() {
        geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: parseFloat($scope.latitude),
                lng: parseFloat($scope.longitude)
            },
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        });

        $('<div/>').addClass('verify-map-pin').appendTo(map.getDiv()).click(function() {});

        map.addListener('dragend', function() {
            geocoder.geocode({
                'latLng': map.getCenter()
            }, function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                    if(results[0]) {
                        fetchMapAddress(results[0]);
                        $('#js-next-btn3').prop('disabled', false);
                    }
                }
            });
            $('.verify-map-pin').removeClass('moving');
            $('.verify-map-pin').addClass('unset');
        });

        map.addListener('zoom_changed', function() {
            geocoder.geocode({
                'latLng': map.getCenter()
            }, function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                    if(results[0]) {
                        fetchMapAddress(results[0]);
                    }
                }
            });
        });

        map.addListener('drag', function() {
            $('.verify-map-pin').removeClass('unset');
            $('.verify-map-pin').addClass('moving');
        });
    }

    function fetchMapAddress(data)
    {
        if(data['types'] == 'street_address') {
            $scope.location_found = true;
        }
        
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            sublocality_level_1: 'long_name',
            sublocality: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'short_name',
            postal_code: 'short_name'
        };

        $('#city').val('');
        $('#state').val('');
        $('#country').val('');
        $('#address_line_1').val('');
        $('#address_line_2').val('');
        $('#postal_code').val('');

        var place = data;
        $scope.street_number = '';
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            
            if(componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                if(addressType == 'street_number') {
                    $scope.street_number = val;
                }
                if(addressType == 'route') {
                    var street_address = $scope.street_number + ' ' + val;
                    $('#address_line_1').val($.trim(street_address));
                }
                if(addressType == 'postal_code') {
                    $('#postal_code').val(val);
                }
                if(addressType == 'locality') {
                    $('#city').val(val);
                }
                if(addressType == 'administrative_area_level_1') {
                    $('#state').val(val);
                }
                if(addressType == 'country') {
                    $('#country').val(val);
                }
            }
        }

        $scope.address_line_1   = $('#address_line_1').val();
        $scope.address_line_2   = $('#address_line_2').val();
        $scope.city             = $('#city').val();
        $scope.state            = $('#state').val();
        $scope.postal_code      = $('#postal_code').val();
        $scope.country_name     = $('#country').val();

        var address = $('#address_line_1').val();
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        if($('#state').val() == '' || $('#city').val() == '') {
            $('#js-next-btn').prop('disabled', true);
        }
        else {
            $('#js-next-btn').prop('disabled', false);
        }

        $scope.latitude = latitude;
        $scope.longitude = longitude;
    }

    $(document).on('click', '[name="amenities"]', function() {
        var value = '';
        $('[name="amenities"]').each(function() {
            if($(this).prop('checked') == true) {
                value = value + $(this).val() + ',';
            }
        });

        var callback_function = function(response_data) {
            if(response_data.success == 'true') {
                var saving_class = $(this).attr('data-saving');
                $('.' + saving_class + ' h5').text(SAVING);
                $('.' + saving_class).fadeIn();
                $('.' + saving_class + ' h5').text(SAVED);
                $('.' + saving_class).fadeOut();
            }
            else if(response_data.redirect != '' && response_data.redirect != undefined) {
                window.location = response_data.redirect;
            }
        };

        $scope.http_post('update_amenities', {data: value},callback_function)
    });

    $scope.initDraggablePhotos = function() {
        if($(window).width() > 767){
            $('.photo-grid').sortable({
                axis: "x,y",
                revert: true,
                scroll: true,
                placeholder: 'sortable-placeholder',
                cursor: 'move',
                tolerance:'pointer',
                containment: $('.sortable_image_view'),
                start: function(){
                    $('.photo-grid').addClass('sorting');
                },
                stop: function(){
                    $('.photo-grid').removeClass('sorting');
                    $scope.change_photo_order();
                }
            });
        }
    };

    $scope.getPhotos = function() {
        $http.get('photos_list', {}).then(function(response) {
            $scope.photos_list = response.data;
        });
    };

    $scope.uploadPhotos = function(element) {
        var photos = [];
        files = element.files;
        if(files) {
            photos = files;
            if(photos.length) {
                $('.sortable_image_view').addClass('loading');
                if ($scope.photos_list.length<=0) {
                    $(".sortable_image_view").css("min-height", "100px");
                }
                url = APP_URL+'/add_photos/'+ $('#room_id').val();

                upload = fileUploadService.uploadFileToUrl(photos, url);
                upload.then(function(response) {

                    if (response.error['error_title']) {
                        $('#js-error .modal-header').text(response.error['error_title']);
                        $('#js-error .modal-body').text(response.error['error_description']);
                        $('#js-error .js-delete-photo-confirm').addClass('d-none');
                        $('#js-error').modal('show');
                    }

                    if(response.photos_list && response.photos_list != '') {
                        $scope.photos_list = response.photos_list;
                        $scope.steps_count = response.steps_count;
                        $scope.status = response.status;
                        $('#steps_count').text($scope.steps_count);
                        $scope.initDraggablePhotos();
                        document.getElementById('upload_photos').value='';
                    }

                    $('.sortable_image_view').removeClass('loading');
                    $(".sortable_image_view").removeAttr("style")
                });
            }
        }
    };

    $scope.delete_photo = function(item, delete_photo, delete_message) {
        id = item.id
        var index = $scope.photos_list.indexOf(item);
        $('#js-error .modal-header').text(delete_photo);
        $('#js-error .modal-body').text(delete_message);
        $('.js-delete-photo-confirm').attr('data-id', id);
        $('.js-delete-photo-confirm').attr('data-index', index);
        $('#js-error .js-delete-photo-confirm').removeClass('d-none');
        $('#js-error').modal('show');
    };

    $scope.featured_image = function(index, photo_id) {
        var url = 'featured_image';
        var data = { id: $('#room_id').val(), photo_id: photo_id };
        $scope.http_post(url,data,function(response_data) {
            $scope.photos_list = response_data;
        });
    };

    $scope.change_photo_order = function() {
        var image_order_list = $(".image_order_list").map(function() {
            return $(this).val();
        }).get();

        var url = 'change_photo_order';
        var data = { id: $('#room_id').val(), image_order: image_order_list };
        var callback_function = function(response_data) {
            // 
        };
        $scope.http_post(url,data,callback_function);
  };

  $(document).on('click', '.js-delete-photo-confirm', function() {
    var index   = $(this).attr('data-index');
    var id   = $(this).attr('data-id');
    var data    = { photo_id: id };
    var callback_function = function(response_data) {
        $('#js-photo-grid').removeClass('loading');
        if(response_data.success == 'true') {
            $scope.photos_list.splice(index, 1);
            $('#steps_count').text(response_data.steps_count);
            $scope.steps_count = response_data.steps_count;
            $scope.status = response_data.status;
        }
        else {
            if(response_data.redirect != '' && response_data.redirect != undefined) {
                window.location = response.data.redirect;
            }
        }
        if($scope.photos_list != undefined) {
            if($scope.photos_list.length != 0) {
                $scope.update_status("photos",1);
            }
            else {
                $scope.update_status("photos",0);
            }
        }
    };
    $('#js-photo-grid').addClass('loading');
    // Close Delete Popup
    $('#js-error').modal('hide');
    $scope.http_post('delete_photo',data,callback_function);
});

  $scope.$watch('photos_list', function(value) {
    if($scope.photos_list != undefined) {
        if($scope.photos_list.length != 0) {
            $scope.update_status("photos",1);
        }
        else {
            $scope.update_status("photos",0);
        }
    }
});

  $scope.$watch('steps_count', function(value) {
    if($scope.steps_count != undefined) {
        rooms_status = $('#room_status').val();
        if($scope.steps_count == 0) {
            $('#finish_step').hide();
            $('.js-steps-remaining').addClass('d-none');
            $('.js-steps-remaining').removeClass('show');
            if(!rooms_status) {
                $('.listing-nav-sm').addClass('collapsed');
                $('body').addClass('non_scroll');
            }

            $('#js-list-space-tooltip').addClass('show');
            $('#js-list-space-tooltip').attr('aria-hidden', 'false');
            setTimeout(function() {
                $('#js-list-space-tooltip').attr('aria-hidden', 'true');
            }, 3000);
            $('#js-list-space-tooltip').fadeIn();
            setTimeout( () => $('#js-list-space-tooltip').fadeOut(2000) , 3000);
        }
        else {
            $('#finish_step').show();
            $('.js-steps-remaining').removeClass('d-none');
            $('.js-steps-remaining').addClass('show');

            if(!rooms_status) {
                $('.listing-nav-sm').removeClass('collapsed');
                $('body').removeClass('non_scroll');
            }

            $('#js-list-space-button').css('display', 'none');
            $('#js-list-space-tooltip').attr('aria-hidden', 'true');
            // $('#js-list-space-tooltip').css({'opacity': '0'});
        }
    }
});

  $('.finish-tooltip .close').click(function() {
    $('#js-list-space-tooltip').hide();
});

  $scope.keyup_highlights = function(id, value) {
    $http.post('photo_highlights', {
        photo_id: id,
        data: value
    }).then(function(response) {
        if(response.data.redirect != '' && response.data.redirect != undefined) {
            window.location = response.data.redirect;
        }

    });
};

$(document).on('change', '[id^="price-select-"]', function() {
    var data_params = {};

    data_params[$(this).attr('name')] = $(this).val();
    data_params['night'] = $('#price-night').val();
    data_params['currency_code'] = $('#price-select-currency_code').val();

    var data = JSON.stringify(data_params);

    var saving_class = $(this).attr('data-saving');

    $('.' + saving_class + ' h5').text(SAVING);
    $('.' + saving_class).fadeIn();

    $http.post('update_price', {
        data: data
    }).then(function(response) {
        if(response.data.success == 'true') {
            if(response.data.night_price) {
                $('#price-night').val(response.data.night_price);
            }    
            $('[data-error="price"]').text('');
            if($('#price-week').val() < response.data.min_amt) {
                $('[data-error="week"]').removeClass('d-none');
                $('[data-error="week"]').html(response.data.msg);
            }
            else {
                $('[data-error="week"]').addClass('d-none');
                $('[data-error="week"]').text('');
            }
            if($('#price-month').val() < response.data.min_amt) {
                $('[data-error="month"]').removeClass('d-none');
                $('[data-error="month"]').html(response.data.msg);
            }
            else {
                $('[data-error="month"]').addClass('d-none');
                $('[data-error="month"]').text('');
            }
            $('[data-error="weekly_price"]').text('');
            $('[data-error="monthly_price"]').text('');
            $scope.currency_symbol = response.data.currency_symbol;
            $('.' + saving_class + ' h5').text(SAVED);
            $('.' + saving_class).fadeOut();
            $('#steps_count').text(response.data.steps_count);
            $scope.steps_count = response.data.steps_count;
            $scope.status = response.data.status;
        }
        else {
            if(response.data.redirect != '' && response.data.redirect != undefined) {
                window.location = response.data.redirect;
            }
            $scope.currency_symbol = response.data.currency_symbol;
            $('[data-error="price"]').html(response.data.msg);
            if($('#price-week').val() < response.data.min_amt) {
                $('[data-error="week"]').removeClass('d-none');
                $('[data-error="week"]').html(response.data.msg);
            } else {
                $('[data-error="week"]').addClass('d-none');
                $('[data-error="week"]').text('');
            }
            if($('#price-month').val() < response.data.min_amt) {
                $('[data-error="month"]').removeClass('d-none');
                $('[data-error="month"]').html(response.data.msg);
            } else {
                $('[data-error="month"]').addClass('d-none');
                $('[data-error="month"]').text('');
            }
            $('.' + saving_class).fadeOut();
        }
    }, function(response) {
        if(response.status == '300')
            window.location = APP_URL + '/login';
    });
});

$(document).on('blur', '.autosubmit-text[id^="price-"]', function() {
    var data_params = {};

    data_params[$(this).attr('name')] = $(this).val();
    this_val = Math.round($(this).val());
    $(this).val(this_val);
    data_params['currency_code'] = $('#price-select-currency_code').val();
    if($(this).attr('name') == 'additional_guest') {
        data_params['guests'] = $('#price-select-guests_included').val();
    }
    var data = JSON.stringify(data_params);

    var saving_class = $(this).attr('data-saving');
    var error_class = 'price';
    if($(this).attr('name') != 'night') {
        error_class = $(this).attr('name');
    }
    $('.' + saving_class + ' h5').text(SAVING);

    if($('#price-night').val() != 0) {
        $('.' + saving_class).fadeIn();
        $http.post('update_price', {
            data: data
        }).then(function(response) {
            if(response.data.success == 'true') {
                $('[data-error="' + error_class + '"]').text('');
                $('.' + saving_class + ' h5').text(SAVED);
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.currency_symbol = response.data.currency_symbol;
                $scope.steps_count = response.data.steps_count;
                $scope.status = response.data.status;
            }
            else {
                if(response.data.redirect != '' && response.data.redirect != undefined) {
                    window.location = response.data.redirect;
                }
                if(response.data.attribute != '' && response.data.attribute != undefined) {
                    $('[data-error="' + response.data.attribute + '"]').removeClass('d-none');
                    $('[data-error="' + response.data.attribute + '"]').html(response.data.msg);
                    $scope.currency_symbol = response.data.currency_symbol;
                } else {
                    $('[data-error="price"]').html(response.data.msg);
                }
                $('.' + saving_class).fadeOut();
            }
            if($('#price-night').val() != 0) {
                $('#price-night-old').val($('#price-night').val());
                if(!response.data.msg) {
                    $scope.update_status("pricing",1);
                }
            }
        }, function(response) {
            if(response.status == '300')
                window.location = APP_URL + '/login';
        });
    }
    else {
        if($('#price-night-old').val() == 0) {
            $('#price-night').val($('#price-night-old').val());
            $scope.update_status("pricing",0);
        }
        else {
            $('#price-night').val($('#price-night-old').val());
            $scope.update_status("pricing",1);
        }
    }
});

$(document).on('change', '[id$="_checkbox"]', function() {
    if($(this).prop('checked') == false) {
        var data_params = {};

        var id = $(this).attr('id');
        var selector = '#'+$(this).data('selector');

        $(selector).val('');

        if(id == 'price_for_extra_person_checkbox') {
            var additional_selector = '#'+$(this).data('additional_selector');
            $(additional_selector).val(1);

            data_params[$(additional_selector).attr('name')] = 0;
        }

        data_params[$(selector).attr('name')] = $(selector).val();
        data_params['currency_code'] = $('#price-select-currency_code').val();

        var data = JSON.stringify(data_params);

        var saving_class = $(selector).attr('data-saving');

        $('.' + saving_class + ' h5').text(SAVING);
        $('.' + saving_class).fadeIn();

        $http.post('update_price', {
            data: data
        }).then(function(response) {
            if(response.data.success == 'true') {
                $('.' + saving_class + ' h5').text(SAVED);
                $('.' + saving_class).fadeOut();
                $('#steps_count').text(response.data.steps_count);
                $scope.currency_symbol = response.data.currency_symbol;
                $scope.steps_count = response.data.steps_count;
                $scope.status = response.data.status;
            }
        }, function(response) {
            if(response.status == '300')
                window.location = APP_URL + '/login';
        });
    }
});

$(document).on('click', '[id^="available-"]', function() {
    var data_params = {};

    var value = $(this).attr('data-slug');

    data_params['calendar_type'] = value.charAt(0).toUpperCase() + value.slice(1);;

    var data = JSON.stringify(data_params);

    $('.saving-progress h5').text(SAVING);

    $('.saving-progress').fadeIn();

    $http.post('update_rooms', {
        data: data
    }).then(function(response) {
        if(response.data.success == 'true') {
            $scope.selected_calendar = value;
            $('[data-slug="' + value + '"]').addClass('selected');
            $('.saving-progress h5').text(SAVED);
            $('.saving-progress').fadeOut();
            $('#steps_count').text(response.data.steps_count);
            $scope.steps_count = response.data.steps_count;
        } else {
            if(response.data.redirect != '' && response.data.redirect != undefined) {
                window.location = response.data.redirect;
            }
        }
        $scope.update_status("calendar",1);
    }, function(response) {
        if(response.status == '300'){
            window.location = APP_URL + '/login';
        } else if(response.status == '500'){
            window.location.reload();
        }
    });
});

$(document).on('mouseover', '[id^="available-"]', function() {
    $('[id^="available-"]').removeClass('selected');
});

$(document).on('mouseout', '[id^="available-"]', function() {
    $('[id="available-' + $scope.selected_calendar + '"]').addClass('selected');
});

var ex_pathname = (window.location.href).split('/');
$scope.step = $(ex_pathname).get(-1);

$(document).on('click', '#finish_step', function() {
    $http.get('rooms_steps_status', {}).then(function(response) {
        for (var key in response.data) {
            if(response.data[key] == '0') {
                angular.element('#href_' + key).trigger('click');
                return false;
            }
        }
    });
});

$(document).on('click', '#js-list-space-button', function() {
    var data_params = {};

    data_params['status'] = 'Listed';

    var data = JSON.stringify(data_params);

    $http.post('update_rooms', {
        data: data
    }).then(function(response) {
        $http.get('rooms_data', {}).then(function(response) {
            $('#symbol_finish').html(response.data.symbol);
            $scope.popup_photo_name = response.data.photo_name;
            $scope.popup_night = response.data.night;
            $scope.popup_room_name = response.data.name;
            $scope.popup_room_type_name = response.data.room_type_name;
            $scope.popup_property_type_name = response.data.property_type_name;
            $scope.popup_state = response.data.state;
            $scope.popup_country = response.data.country_name;
            $('.finish-modal').attr('aria-hidden', 'false');
            $('.finish-modal').removeClass('d-none');
        });
    }, function(response) {
        if(response.status == '300'){
            window.location = APP_URL + '/login';
        } else if(response.status == '500'){
            window.location.reload();
        }
    });
});

$(document).on('blur', '[id^="help-panel"] > textarea', function() {
    var data_params = {};
    var input_name = $(this).attr('name');
    var current_tab = $('#current_tab_code').val();
    data_params[input_name] = $(this).val();  
    var data = JSON.stringify(data_params);
    var saving_class = $(this).attr('data-saving');
    $('.'+saving_class+' h5').text(SAVING);
    $('.'+ saving_class).fadeIn();

    $http.post('update_description', {
        data: data , current_tab:current_tab
    }).then(function(response) {
        if(response.data.success == 'true') {
            $('.'+saving_class+' h5').text(SAVED);
            $('.'+ saving_class).fadeOut();
        }
        else {
            if(response.data.redirect != '' && response.data.redirect != undefined) {
                window.location = response.data.redirect;
            }
        }
    }, function(response) {
        if(response.status == '300')
            window.location = APP_URL + '/login';
    });
});

$(document).on('click', '#collapsed-nav', function() {
    updateCollapsedNav();
});

function updateCollapsedNav()
{
    if($('#js-manage-listing-nav').hasClass('collapsed')) {
        $('#js-manage-listing-nav').removeClass('collapsed');
        $('body').removeClass('non_scroll');
    }
    else {
        $('#js-manage-listing-nav').addClass('collapsed');
        $('body').addClass('non_scroll');
    }
}

$(document).on('change', '.availability_dropdown', function() {

    var data_params = {};
    data_params['status'] = $(this).val();
    var data = { data :JSON.stringify(data_params) };
    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $scope.status = response_data.status;
        }
    };

    $scope.http_post('update_rooms', data, callback_function);
});

$(document).on('click', '.remove_sync_button', function() {
    $('.remove_sync_cal_container').addClass('loading');

    $http.post(APP_URL+'/get_sync_calendar', {
        room_id: $('#room_id').val()
    }).then(function(response) {
        $scope.sync_cal_details = response.data;
        $('.remove_sync_cal_container').removeClass('loading');
    });
});

$scope.show_confirm_popup = function(ical_id) {
    $('.remove_ical_link').attr('data-ical_id', ical_id);
    $('#remove_sync_popup').modal('hide');
    $('#remove_sync_confirm_popup').modal('show');
}

$scope.remove_sync_cal = function() {
    $('.remove_sync_confirm_panel').addClass('loading');
    var ical_id = $('.remove_ical_link').attr("data-ical_id");
    $http.post(APP_URL+'/remove_sync_calendar', {
        ical_id: ical_id
    }).then(function(response) {
        $('.remove_sync_confirm_panel').removeClass('loading');
        $('#remove_sync_confirm_popup').modal('hide');
    });
};

$scope.booking_select = function(value) {
    var data_params = {};
    data_params['booking_type'] = value;
    var data = { data :JSON.stringify(data_params) };
    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('#before_select').addClass('d-none');
            $('#' + value).removeClass('d-none');
        }
    };

    $scope.http_post('update_rooms', data,callback_function);
};

$scope.booking_change = function(value) {
    var data_params = {};
    data_params['booking_type'] = '';
    var data = { data :JSON.stringify(data_params) };
    var callback_function = function(response_data) {
        if(response_data.success == 'true') {
            $('#before_select').removeClass('d-none');
            $('#' + value).addClass('d-none');
        }
    };

    $scope.http_post('update_rooms', data,callback_function);
};

$scope.add_price_rule = function(type) {
    if(type == 'length_of_stay') {
        new_period = $scope.length_of_stay_period_select;
        $scope.length_of_stay_items.push({'period' : new_period-0});
        $scope.length_of_stay_period_select = '';
    }
    else if(type== 'early_bird') {
        $scope.early_bird_items.push({'period' : ''});
    }
    else if(type== 'last_min') {
        $scope.last_min_items.push({'period' : ''});
    }
};

$scope.remove_price_rule = function(type, index) {
    if(type == 'length_of_stay') {
        item =$scope.length_of_stay_items[index];
        $scope.length_of_stay_items.splice(index, 1);
        errors = $scope.ls_errors;
    }
    else if(type == 'early_bird') {
        item =$scope.early_bird_items[index];
        $scope.early_bird_items.splice(index, 1);
        errors = $scope.eb_errors;
    }
    else if(type == 'last_min') {
        item =$scope.last_min_items[index];
        $scope.last_min_items.splice(index, 1);
        errors = $scope.lm_errors;
    }
    errors[index] = [];
    if(item.id != '' && item.id) {
        $('#js-'+type+'_wrapper').addClass('loading');
        $http.post('delete_price_rule/'+item.id, {}).then(function(response) {
            $('#js-'+type+'_wrapper').removeClass('loading');
        })
    }
};

$scope.length_of_stay_option_avaialble = function(option) {
    var found = $filter('filter')($scope.length_of_stay_items, {'period': option}, true);
    var found_text = $filter('filter')($scope.length_of_stay_items, {'period': ''+option}, true);
    return !found.length && !found_text.length;
};

$(document).on('change', '.ls_period, .ls_discount', function() {
    index = $(this).attr('data-index');
    $scope.update_price_rules('length_of_stay', index);
});

$(document).on('change', '.eb_period, .eb_discount', function() {
    index = $(this).attr('data-index');
    $scope.update_price_rules('early_bird', index);
});

$(document).on('change', '.lm_period, .lm_discount', function() {
    index = $(this).attr('data-index');
    $scope.update_price_rules('last_min', index);
});

$scope.update_price_rules = function(type, index) {
    if(type == 'length_of_stay') {
        rules = $scope.length_of_stay_items;
        errors = $scope.ls_errors;
    }
    else if(type == 'early_bird') {
        rules = $scope.early_bird_items;
        errors = $scope.eb_errors;
    }
    else if(type == 'last_min') {
        rules = $scope.last_min_items;
        errors = $scope.lm_errors;
    }
    data = rules[index];

    if(data.discount == undefined) {
        return false;
    }

    $('#js-'+type+'-rm-btn-'+index).attr('disabled', 'disabled');
    $('.price_rules-'+type+'-saving h5').text($scope.saving_text);
    $('.price_rules-'+type+'-saving').fadeIn();

    $http.post('update_price_rules/'+type, {data: data}).then(function(response) {
        if(response.data.success != 'true') {
            errors[index] = response.data.errors;
        }
        else {
            errors[index] = [];
            rules[index].id = response.data.id;
            $('.price_rules-'+type+'-saving h5').text($scope.saved_text);
        }
        $('.price_rules-'+type+'-saving').fadeOut();
        $('#js-'+type+'-rm-btn-'+index).removeAttr('disabled');
    })
};

$scope.remove_availability_rule = function(index) {
    item = $scope.availability_rules[index];
    type = 'availability_rules';
    if(item.id != '' && item.id) {
        $('#'+type+'_wrapper').addClass('loading');
        $http.post('delete_availability_rule/'+item.id, {}).then(function(response) {
            $('#'+type+'_wrapper').removeClass('loading');
        })
    }
    $scope.availability_rules.splice(index, 1); 
};

$scope.edit_availability_rule = function(index) {
    item = $scope.availability_rules[index];
    $("#calendar-rules-custom").removeClass('d-none');
    $("#calendar-rules-custom").addClass('show');
    $scope.availability_rule_item = angular.copy(item);
    $scope.availability_rule_item.type ='prev';
    $scope.availability_rule_item.start_date =$scope.availability_rule_item.start_date_formatted;
    $scope.availability_rule_item.end_date   =$scope.availability_rule_item.end_date_formatted
    if(!$scope.$$phase){
        $scope.$apply();
    }
    $scope.availability_datepickers();
};

$scope.availability_rules_type_change = function() {
    rule = $scope.availability_rule_item;
    if(rule.type != 'custom')
    {
        this_elem = $("#availability_rule_item_type option:selected");
        start_date = this_elem.attr('data-start_date');
        end_date = this_elem.attr('data-end_date');
        $scope.availability_rule_item.start_date = start_date;
        $scope.availability_rule_item.end_date = end_date;
    }
};

$scope.availability_datepickers = function() {
    var start_date_element = $("#availability_rules_start_date");
    var end_date_element = $("#availability_rules_end_date");
    start_date_element.datepicker({
        'minDate':0,
        'dateFormat': datepicker_format,
        onSelect: function(date, obj){
            var start_date = start_date_element.datepicker('getDate'); 
            start_date.setDate(start_date.getDate() + 1); 
            end_date_element.datepicker('option', 'minDate',start_date );
            $scope.availability_rule_item.start_date = start_date_element.val();
        }
    });

    end_date_element.datepicker({
        'minDate':1,
        'dateFormat': datepicker_format,
        onSelect: function(date, obj){
            var end_date = end_date_element.datepicker('getDate'); 
            $scope.availability_rule_item.end_date = end_date_element.val();
        }
    });
};

$scope.copy_data =function(data) {
    return angular.copy(data);
};

$(document).on('click', '#js-calendar-settings-btn', function() {
    $("#calendar-rules").addClass('show');
});

$(document).on('click', '#js-close-calendar-settings-btn', function() {
    $("#calendar-rules").removeClass('show');
});

$(document).on('click', '#js-add-availability-rule-link', function() {
    $("#calendar-rules-custom").removeClass('d-none');
    $("#calendar-rules-custom").addClass('show');
    $scope.availability_rule_item = {type : '', start_date: '', end_date: '', start_date_formatted:'', end_date_formatted:''};
    if(!$scope.$$phase) {
        $scope.$apply();
    }
    $scope.availability_datepickers();
    $('.manage-listing-container').addClass('overflow-hidden');
});

$(document).on('click', '#js-close-availability-rule-btn, #js-cancel-availability-rule-btn', function() {
    $("#calendar-rules-custom").removeClass('show');
    $("#calendar-rules-custom").addClass('d-none');
    $("#js-manage-listing-content-container").removeClass('overflow-hidden');
});

$(document).on('change', '.reservation_settings_inputs', function() {
    data =  {};
    $(".reservation_settings_inputs").each(function(i, elem) {
        field = $(elem);
        data[field.attr('name')] = field.val();
    })
    $('.reservation_settings-saving h5').text($scope.saving_text);
    $('.reservation_settings-saving').fadeIn();

    $http.post('update_reservation_settings', data).then(function(response) {
        if(response.data.success != 'true') {
            $scope.rs_errors = response.data.errors;
        }
        else {
            $scope.rs_errors = [];
        }
        $('.reservation_settings-saving h5').text($scope.saved_text);
        $('.reservation_settings-saving').fadeOut();
    });
});

$scope.update_availability_rule = function() {
    data = {'availability_rule_item':$scope.availability_rule_item};
    $("#availability_rule_item_wrapper, #availability_rules_wrapper").addClass('loading');
    $http.post('update_availability_rule', data).then(function(response) {
        if(response.data.success != 'true') {
            $scope.ar_errors = response.data.errors;
        }
        else {
            $scope.ar_errors = [];
            $scope.availability_rules = response.data.availability_rules;
            $("#js-close-availability-rule-btn").trigger('click');
            $("#calendar-rules-custom").addClass('d-none');
        }
        $("#availability_rule_item_wrapper, #availability_rules_wrapper").removeClass('loading');
    });
};
}]);

app.directive("limitTo", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);

            angular.element(elem).on("keypress", function(event) {
                var key = window.event ? event.keyCode : event.which;

                if(this.value.length == limit) {
                    if(event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
                        return true;
                    } else {
                        event.preventDefault();
                    }
                } else {
                    if(event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39) {
                        return true;
                    }
                    if(key < 48 || key > 57) {
                        event.preventDefault();
                    }
                }
            });
        }
    }
}]);

app.filter('objToArray', function() { return function(obj) {
  delete obj.$$hashKey 
  return obj
}});

app.filter('toArray', function() { return function(obj) {
    if(!(obj instanceof Object)) return obj;
    return _.map(obj, function(val, key) {
        return Object.defineProperty(val, '$key', {__proto__: null, beds_id: key});
    });
}});

app.filter('toArrayView', function() { return function(obj) {
    if(!(obj instanceof Object)) return obj;
    return _.map(obj, function(val, key) {
        return Object.defineProperty(val, '$key', {__proto__: null, bed_name: key});
    });
}});

app.filter('orderObjectBy', function() {
  return function(input, attribute) {
    if(!angular.isObject(input)) return input;

    var array = [];
    array[0] = '';
    for(var objectKey in input) {
        array.push(input[objectKey]);
    }
    array.sort(function(a, b){
        a = parseInt(a[attribute]);
        b = parseInt(b[attribute]);
        return a - b;
    });
    return array;
}
});

app.filter('nonZeroElem', function() {
  return function(input, attribute) {
    if(!angular.isObject(input)) return input;
    var array = [];
    for(var objectKey in input) {
      if(input[objectKey][attribute]>0) 
        array.push(input[objectKey]);
}
return array;
}
});

app.service('fileUploadService', function ($http, $q) {
    this.uploadFileToUrl = function (file, uploadUrl, data) {
        var fileFormData = new FormData();
        $.each(file, function( index, value ) {
            fileFormData.append('photos[]', value);
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

//disable double click when add language
$(document).on('click', '#write-description-button', function() {
    $('#write-description-button').attr("disabled", true); 
});

$('.list-nav-link a').click(function() {
    $('#js-manage-listing-nav').removeClass('manage-listing-nav');
});
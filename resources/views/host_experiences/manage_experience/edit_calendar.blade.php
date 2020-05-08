<div class="main-wrap host-calendar-wrap bg-white d-xl-flex" ng-init="calendar_data = {{json_encode($calendar_data)}};minimum_amount='{{ $minimum_amount}}';spots_left_text='{{ __('messages.shared_rooms.spots_left') }}';">    
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="row">
    <div class="calendar-setting col-12 col-lg-8 my-3 text-right">
      <ul class="float-left d-flex mr-3">
        <li>
          <span>
            @lang('messages.lys.mobile_select_desc') 
          </span>
        </li>
      </ul>
      <ul class="float-left d-flex">
        <li>
          <div class="d-inline-flex" style="background-color: #46A4A7;height: 10px; width: 10px;"></div> 
          <span>
            @lang('messages.lys.today') 
          </span> 
        </li>
        <li class="ml-2"> 
          <div class="d-inline-flex" style="background-color: #fff3df;height: 10px; width: 10px;"></div> 
          <span>
            @lang('messages.lys.Blocked')
          </span> 
        </li>
        <li class="ml-2"> 
          <div class="d-inline-flex" style="background-color: #ffdadc;height: 10px; width: 10px;"></div> 
          <span>
            @lang('messages.inbox.reservations') 
          </span> 
        </li>
      </ul>
    </div>
    <div class="col-12 col-lg-8 mt-4 mb-1">
      <div id="calendar" class="calendar"></div>
      <div class="check_detail1-1 pt-4 pt-md-5 d-none d-xl-block">
        <button class="btn experience-btn host-primary next_step" data-step-num="{{$step_num +1}}" type="button"> 
          {{trans('experiences.manage.next')}}
        </button>
      </div>
    </div>
    <div class="calendar-side-option col-12 col-md-8 col-lg-6 col-xl-4 mx-auto pt-4 pt-xl-5" ng-show="showUpdateForm">
      <div class="host-calendar-form">
        <div class="panel-header text-center" ng-init="segment_status = 'Available'">
          <div class="segmented-control d-md-flex">
            <label id="avi" class="segmented-control-option segmented-option-selected" ng-class="(segment_status == 'Available') ? 'segmented-option-selected' : ''">
              <span>
                {{trans('messages.lys.Available')}}
              </span>
              <input type="radio" id="available_check" ng-checked="segment_status == 'Available'" name="radio" ng-model="segment_status" value="Available" class="segmented-control-input ng-pristine ng-untouched ng-valid" checked="checked">
            </label>
            <label id="unavi" class="segmented-control-option" ng-class="(segment_status == 'Not available') ? 'segmented-option-selected' : ''">
              <span>
                {{trans('messages.lys.Blocked')}}
              </span>
              <input type="radio" id="notavailable_check" ng-checked="segment_status == 'Not available'" name="radio" value="Not available" ng-model="segment_status" class="segmented-control-input ng-pristine ng-untouched ng-valid">
            </label>
          </div>
        </div>
        <div class="panel-body text-center">
          <div class="d-flex">
            <div class="col-6">
              <label> 
                @lang('experiences.manage.start_date') 
              </label>
              <input type="text" id="calendar-edit-start" ng-model="calendar_edit_start_date" readonly="readonly">
              <input type="hidden" id="calendar-start">
            </div>
            <div class="col-6">
              <label> 
                @lang('experiences.manage.end_date') 
              </label>
              <input type="text" id="calendar-edit-end" ng-model="calendar_edit_end_date" readonly="readonly">
              <input type="hidden" id="calendar-end">
            </div>
          </div>
          <div class="price-changer my-3">
            <div class="input-wrap d-flex align-items-center mx-auto">
              <span>
                {{ html_string($host_experience->currency->original_symbol) }}
              </span>
              <input type="number" limit-to="9" class="sidebar-price get_price" id="calendar_edit_price" ng-model="calendar_edit_price">
            </div>
            <p ng-show="calendar_edit_price < minimum_amount" class="text-danger">
              @lang('validation.min.numeric', ['attribute' => __('messages.inbox.price'), 'min' => html_string($host_experience->currency->original_symbol).$minimum_amount])
            </p>
          </div>
        </div>
        <div class="panel-footer d-flex align-items-center justify-content-center pt-0 calendar_ctrl_btn_area">
          <button class="btn" id="calendar_edit_cancel" ng-click="full_calendar();">
            {{trans('experiences.manage.cancel')}}
          </button>
          <button type="button" class="btn btn-host" id="calendar_edit_form_save" ng-disabled="!calendar_edit_price || calendar_edit_price <= 0 || calendar_edit_price < minimum_amount">
            {{trans('experiences.manage.save')}}
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="check_detail1-1 col-12 mt-4 mt-md-5 d-block d-xl-none">
    <button class="btn experience-btn host-primary next_step" data-step-num="{{$step_num +1}}" type="button"> 
      {{trans('experiences.manage.next')}}
    </button>
  </div>
</div>
<!--  main_bar end -->
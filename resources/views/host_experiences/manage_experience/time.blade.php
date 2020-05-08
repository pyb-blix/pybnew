<div class="main-wrap time-wrap bg-white" ng-init="times_array = {{json_encode($times_array)}}">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.set_your_default_time')}}
    </h3>
    <p>
      {{trans('experiences.manage.time_page_desc')}}
    </p>

    <div class="default-time mt-4 d-md-flex align-items-center">
      <div class="col-md-5 p-0">
        <div class="select">
          <i class="icon-chevron-down"></i>
          <select name="start_time" id="host_experience_start_time" ng-model="host_experience.start_time" ng-init="host_experience.start_time = host_experience.start_time == null ? '' : host_experience.start_time;">
            <option ng-if="host_experience.start_time == ''" value=''>
              {{trans('experiences.manage.start_time')}}
            </option>
            <option ng-repeat="(key, time) in times_array" ng-selected="host_experience.start_time == key" ng-if="key < '23:00:00'" value="@{{key}}">
              @{{time}}
            </option>
          </select>
          <p class="text-danger" ng-show="form_errors.start_time.length">
            @{{form_errors.start_time[0]}}
          </p>
        </div>
      </div>
      <div class="col-md-2">
        <p class="my-3 m-md-0 text-center">
          {{trans('experiences.manage.to')}}
        </p>
      </div>
      <div class="col-md-5 p-0">
        <div class="select">
          <i class="icon-chevron-down"></i>
          <select name="end_time" id="host_experience_end_time" ng-model="host_experience.end_time" ng-init="host_experience.end_time = host_experience.end_time == null ? '' : host_experience.end_time;">
            <option ng-if="host_experience.end_time == ''" value=''>
              {{trans('experiences.manage.end_time')}}
            </option>
            <option ng-repeat="(key, time) in times_array" ng-selected="host_experience.end_time == key" ng-if="key >= minimum_end_time" value="@{{key}}">
              @{{time}}
            </option>
          </select>
          <p class="text-danger" ng-show="form_errors.end_time.length">
            @{{form_errors.end_time[0]}}
          </p>
        </div>
      </div>
    </div>
    <div class="mt-4 mt-md-5">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
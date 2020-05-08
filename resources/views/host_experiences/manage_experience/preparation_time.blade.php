<div class="main-wrap prepare-time-wrap bg-white" ng-init="preparation_times_array = {{json_encode($host_experience->preparation_times_array)}}">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.how_much_time_need_to_prepare')}}
    </h3>
    <p>
      {{trans('experiences.manage.we_recommend_a_day_or_two_to_prepare')}}
    </p>
    <div class="row mt-2">
      <div class="col-md-8">
        <div class="select">
          <i class="icon-chevron-down"></i>
          <select class="" name="preparation_hours" id="host_experience_preparation_hours" ng-model="host_experience.preparation_hours">
            <option value="" ng-if="host_experience.preparation_hours == ''">
              {{trans('experiences.manage.choose_number_of_days')}}
            </option>
            @foreach($host_experience->preparation_times_array as $k => $v)
            <option value="{{$k}}">
              {{$v}}
            </option>
            @endforeach
          </select>            
          <p class="text-danger" ng-show="form_errors.preparation_hours.length">
            @{{form_errors.preparation_hours[0]}}
          </p>
        </div>
      </div>
    </div>
    <small ng-show="host_experience.preparation_hours" ng-init="preparation_time_warning = '{{trans('experiences.manage.if_no_one_books_before_preparation_time_unscheduled')}}'">
      @{{locale_string(preparation_time_warning, {'preparation_time': preparation_times_array[host_experience.preparation_hours]})}}
    </small>
    <div class="mt-4" ng-show="host_experience.preparation_hours">
      <h4>
        {{trans('experiences.manage.can_accommmodate_last_min_guests')}}
      </h4>
      <p>
        {{trans('experiences.manage.we_want_max_guests_already_one_guest_cut_off_time')}}
      </p> 
      <div class="radio_grp">
        <label>
          <input type="radio" name="last_minute_guests" value="No" class="radio2" id="host_experience_last_minute_guests_no"  ng-model="host_experience.last_minute_guests"> 
          <span>
            {{trans('experiences.manage.no_thanks')}}
          </span>
        </label>
        <label>
          <input type="radio" name="last_minute_guests" value="Yes" class="radio1" id="host_experience_last_minute_guests_yes" ng-model="host_experience.last_minute_guests"> 
          <span>
            {{trans('experiences.manage.yes_i_am_flexible')}}
          </span> 
        </label> 
      </div>
      <div class="radio_show mt-4" ng-show="host_experience.last_minute_guests == 'Yes'">
        <h4>
          {{trans('experiences.manage.cutoff_time')}}
        </h4>
        <div class="row">
          <div class="col-sm-6">
            <div class="select">
              <i class="icon-chevron-down"></i>
              <select name="cutoff_time" id="host_experience_cutoff_time" ng-model="host_experience.cutoff_time">
                @foreach($host_experience->cutoff_times_array as $k => $v)
                <option value="{{$k}}">
                  {{$v}}
                </option>
                @endforeach
              </select>            
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
</div>
<!--  main_bar end -->
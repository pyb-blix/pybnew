<div class="phone-wrap" style="background-image: url('{{url('images/host_experiences/mb_cover.png')}}');">
  <ul class="phone-wrap-scroll">
    <li class="p-0">
      <a href="javascript:void(0)" target="_blank" class="refresh_main_content_step d-block" data-step="photos">
        <div class="bg-cover" ng-style="get_mobile_photo_elem_style()"></div>
      </a>
    </li>
    <li>
      <div class="phone-list-wrap border-0">
        <h3 class="refresh_main_content_step" id="mobile_host_experience_title" data-step="title" ng-init="experience_locale = '{{trans('experiences.home.experience')}}'">
          @{{host_experience.title ? host_experience.title : experience_locale}}
        </h3>
        <a href="javascript:void(0)" class="refresh_main_content_step" id="mobile_host_experience_tagline" data-step="tagline">
          {{$host_experience->city_details->name}}
          <span ng-init="tagline_placeholder = '{{trans('experiences.manage.your_tagline_goes_here')}}'">
            @{{host_experience.tagline ? host_experience.tagline : tagline_placeholder}}
          </span>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <div class="user-table row">
          <div class="col-md-8">
            <p>
              {{@$host_experience->category_details->name}} {{trans('experiences.manage.experience_hosted_by')}}
              <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank">
                {{$host_experience->user->first_name}}
              </a>
            </p>
          </div>
          <div class="col-md-4">
            <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank">
              <div class="pro-img" style="background-image: url('{{$host_experience->user->profile_picture->header_src}}');"></div>
            </a>
          </div>
        </div>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap total-hours d-flex align-items-center">
        <span class="icon icon2-clock"></span>
        <p>
          @{{total_hours()}} {{trans('experiences.manage.hours_total')}}
        </p>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="what_will_do">
          <h4>
            {{trans('experiences.manage.what_will_do')}}
          </h4>
          <p ng-init="what_will_do_placeholder = '{{trans('experiences.manage.give_an_overview_description_of_what_will_do')}}'; more_link_status[0] = false;" ng-bind-html="text_more_content((host_experience.what_will_do ? host_experience.what_will_do : what_will_do_placeholder), 200, 300, 0)">
          </p>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="what_will_provide">
          <h4>
            {{trans('experiences.manage.what_will_provide')}}
          </h4>
          <p ng-if="!steps_status['what_will_provide']">
            {{trans('experiences.manage.let_your_guests_know_you_include_anything')}}
          </p>
          <p ng-repeat="provide in host_experience_provides" ng-if="steps_status['what_will_provide'] && provide.name.length > 0 && provide.host_experience_provide_item_id > 0">
            <span>
              @{{provide.name}}
            </span>
            <img src="@{{get_provide_image(provide.host_experience_provide_item_id)}}" class="provide_icon"> 
            <br>
            @{{provide.additional_details}} 
            <br ng-if="provide.additional_details">
          </p>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="where_will_be">
          <h4>
            {{trans('experiences.manage.where_will_be')}}
          </h4>
          <p ng-init="where_will_be_placeholder = '{{trans('experiences.manage.tell_your_guests_where_you_taking_them')}}'">
            @{{host_experience.where_will_be ? host_experience.where_will_be : where_will_be_placeholder}}
          </p>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="notes">
          <h4>
            {{trans('experiences.manage.notes')}}
          </h4>
          <p ng-init="notes_placeholder = '{{trans('experiences.manage.is_there_anything_guest_to_know_before_booking')}}'" ng-bind-html="text_more_content((host_experience.notes ? host_experience.notes : notes_placeholder), 200, 300, 0)">
          </p>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap py-4">
        <a href="javascript:void(0)" class="mobile_location_area refresh_main_content_step" data-step="where_will_meet" role="button" tabindex="0">
          <div class="mobile_location_title">
            <h4>
              {{trans('experiences.manage.where_will_meet')}}
            </h4>
            <p>
              @{{host_experience.host_experience_location.location_name ? host_experience.host_experience_location.location_name+' - ': ''}} @{{host_experience.host_experience_location.city != '' ? host_experience.host_experience_location.city : host_experience.city_details.name}}
            </p>
          </div>
          <div id="host_experience_location_mobile_map"></div>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="about_you">
          <h4>
            {{trans('experiences.manage.about_your_host')}}, {{$host_experience->user->first_name}}
          </h4>
          <p>
            @{{host_experience.about_you}}
          </p>
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap" ng-show="host_experience.number_of_guests">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="group_size">
          <p ng-init="group_size_text = '{{trans('experiences.manage.group_size_upto_guests')}}'">
            @{{ locale_string(group_size_text, {number_of_guests : (host_experience.number_of_guests ? host_experience.number_of_guests : 0)}) }}
          </p>    
        </a>
      </div>
    </li>
    <li>
      <div class="phone-list-wrap" ng-show="host_experience.guest_requirements.minimum_age">
        <a href="javascript:void(0)" class="refresh_main_content_step" data-step="guest_requirements">
          <h4>
            {{trans('experiences.manage.who_can_come')}}
          </h4>    
          <p ng-init="who_can_come_text = '{{trans('experiences.details.guest_ages_age_and_up_can_attend')}}'">
            @{{ locale_string(who_can_come_text, {count : (host_experience.guest_requirements.minimum_age ? host_experience.guest_requirements.minimum_age : 0)}) }}
          </p> 
        </a>
      </div>
      </div>
    </li>
  </ul>
</div>

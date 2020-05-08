<div class="main-wrap" id="manage_experience_main_content" ng-cloak>
  <div class="save-info">
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.create_the_page_guests_will_see')}}
    </h3>
    <p>
      {{trans('experiences.manage.use_preview_description_will_display')}}
    </p>
    <p>
      {{trans('experiences.manage.write_clear_straight_give_tips')}}
    </p>
    <button class="btn experience-btn next_step mt-4 mt-lg-5" type="button" data-step-num="{{$step_num +1}}">
      {{trans('experiences.manage.next')}}
    </button>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>

<div class="main-wrap" id="manage_experience_main_content" ng-cloak>
  <div class="save-info">
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.finishing_thoughts')}}
    </h3>
    <p>
      {{trans('experiences.manage.just_a_few_more_things_you_ready_to_submit')}}
    </p>
    <div class="mt-4 mt-md-5">
      <button class="btn experience-btn next_step" type="button" data-step-num="{{$step_num +1}}" >
        {{trans('experiences.manage.next')}}
      </button>
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>

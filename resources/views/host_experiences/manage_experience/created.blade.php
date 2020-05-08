<div class="main-wrap" id="manage_experience_main_content" ng-cloak>
  <div class="save-info">
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.congrats_you_all_set')}}
    </h3>
    <p>
      {{trans('experiences.manage.added_to_waitlist_back_within_weeks_expect_email')}}
    </p>
    <p>
      {{trans('experiences.manage.feel_free_to_edit_make_it_unique')}}
    </p>
    <div class="my-4">
      <a class="btn experience-btn host-primary" href="{{url('host/experiences')}}">
        {{trans('experiences.manage.exit')}}
      </a>
      <a class="btn experience-btn host-secondary ml-3" href="{{url('host/manage_experience/'.$host_experience->id)}}">
        {{trans('experiences.manage.edit_submission')}}
      </a>
    </div>
  </div>
</div>

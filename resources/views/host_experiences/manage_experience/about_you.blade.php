<div class="main-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.write_your_bio')}}
    </h3>
    <p>
      {{trans('experiences.manage.about_you_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup1"> 
      <span class="icon icon2-light-bulb"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>
    <div class="mt-4">
      <textarea placeholder="{{trans('experiences.manage.about_you_example')}}" rows="5" id="host_experience.about_you" ng-model="host_experience.about_you">
      </textarea> 
      <p class="focus_show" ng-class="character_length_class(150, 600, host_experience.about_you.length)">
        @{{character_length_validation(150, 600, host_experience.about_you.length)}}
      </p>
      <p class="text-danger" ng-show="form_errors.about_you.length">
        @{{form_errors.about_you[0]}}
      </p>
    </div>
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
</div>
<!--  main_bar end -->
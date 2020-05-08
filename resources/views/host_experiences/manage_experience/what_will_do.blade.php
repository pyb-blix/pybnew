<div class="main-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.mention_what_will_you_do')}}
    </h3>
    <p>
      {{trans('experiences.manage.what_will_do_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup1"> 
      <span class="icon icon2-light-bulb"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>
    <textarea class="input_new1 mt-4" placeholder="{{trans('experiences.manage.what_will_do')}}" rows="5" id="host_experience.what_will_do" ng-model="host_experience.what_will_do" >
    </textarea> 
    <p class="focus_show mt-2" ng-class="character_length_class(200, 1200, host_experience.what_will_do.length)">
      @{{character_length_validation(200, 1200, host_experience.what_will_do.length)}}
    </p>
    <p class="text-danger" ng-show="form_errors.what_will_do.length">
      @{{form_errors.what_will_do[0]}}
    </p>
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
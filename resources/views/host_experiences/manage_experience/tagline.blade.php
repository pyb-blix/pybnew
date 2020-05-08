<div class="main-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.write_a_tagline')}}
    </h3>
    <p>
      {{trans('experiences.manage.tagline_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup2"> 
      <span class="icon icon2-light-bulb h3"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>
    <br>
    <input type="text" name="tagline" class="input_new1 space-top-6" id="host_experience_tagline" placeholder="{{trans('experiences.manage.write_your_tagline_here')}}" ng-model="host_experience.tagline" />
    <p class="focus_show space-top-2" ng-class="character_length_class(1, 60, host_experience.tagline.length)">
      @{{character_length_validation(1, 60, host_experience.tagline.length)}}
    </p>
    <p class="text-danger" ng-show="form_errors.tagline.length">
      @{{form_errors.tagline[0]}}
    </p>
    @include('host_experiences.manage_experience.control_buttons')
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
<div class="main-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.title_your_experience')}}
    </h3>
    <p>
      {{trans('experiences.manage.title_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup2"> 
      <span class="icon icon2-light-bulb h3"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>
    <br>
    <input type="text" name="title" class="" id="host_experience_title" placeholder="{{trans('experiences.manage.enter_experience_name')}}" ng-model="host_experience.title" />
    <p class="focus_show mt-2" ng-class="character_length_class(10, 38, host_experience.title.length)">
      @{{character_length_validation(10, 38, host_experience.title.length)}}
    </p>
    <p class="text-danger" ng-show="form_errors.title.length">
      @{{form_errors.title[0]}}
    </p>
    <!-- check_detail end -->
    @include('host_experiences.manage_experience.control_buttons')
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
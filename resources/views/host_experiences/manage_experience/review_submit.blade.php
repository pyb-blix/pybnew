<div class="main-wrap" id="manage_experience_main_content" ng-cloak>  
  <div class="save-info">
    @include('host_experiences.manage_experience.header')  
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.review_experience_last_time')}}
    </h3>
    <p>
      {{trans('experiences.manage.to_qualify_you_re_responsible_please_review', ['site_name' => SITE_NAME])}}
    </p>
    <div class="tabl mt-4">
      <label class="verify-check" for="check1">    
        <input type="checkbox" name="quality_standards_reviewed" id="check1" class="chekbox1" id="host_experience_quality_standards_reviewed" ng-model="host_experience.quality_standards_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        <span>
          {!! trans('experiences.manage.you_meet_site_quality_standards', ['quality_standards_link' => '<a target="_blank" href="'.url('quality_standards').'"> '.trans('experiences.manage.quality_standards').'</a>', 'site_name' => SITE_NAME]) !!}
        </span>
      </label>
    </div>
    <div class="tabl mt-4">
      <label class="verify-check" for="check2">
        <input type="checkbox" name="local_laws_reviewed" id="check2" class="chekbox1 " id="host_experience_local_laws_reviewed" ng-model="host_experience.local_laws_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        <span>
          {!! trans('experiences.manage.compiles_with_local_laws_lear_more_read', ['read_link' => '<a href="javascript:void(0)"> '.trans('experiences.manage.read').'.</a>']) !!}
        </span>
      </label>
    </div>
    <div class="tabl mt-4">
      <label class="verify-check" for="check3">
        <input type="checkbox" name="terms_service_reviewed" id="check3" class="chekbox1 " id="host_experience_terms_service_reviewed" ng-model="host_experience.terms_service_reviewed" ng-true-value="'Yes'" ng-false-value="'No'">
        <span>
          {!! trans('experiences.manage.agree_site_terms_and_condition', ['terms_link' => '<a target="_blank" href="'.url('terms_of_service').'"> '.trans('experiences.manage.site_additional_terms_and_conditions', ['site_name' => SITE_NAME]).'.</a>']) !!}
        </span>
      </label>
    </div>
    <div class="mt-4 mt-md-5">
      <button class="btn experience-btn host-secondary save_next_step" data-step-num="@{{step_num-0+1}}" type="button" ng-disabled="host_experience.quality_standards_reviewed =='No' || host_experience.local_laws_reviewed == 'No' || host_experience.terms_service_reviewed == 'No' ">
        {{trans('experiences.manage.submit')}}
      </button>
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>

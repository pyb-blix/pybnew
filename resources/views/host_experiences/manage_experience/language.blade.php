<div class="main-wrap language-info-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.which_language_will_you_host')}}
    </h3>
    <p>
      {{trans('experiences.manage.language_step_desc')}}
    </p>
    <p>
      {{trans('experiences.manage.submission_language')}}
    </p>
    <div class="col-md-9 mt-4 p-0">
      <div class="select">
        <i class="icon-chevron-down"></i>
        {!! Form::select('language', $languages, '', ['id' => 'host_experience_language', 'ng-model' => 'host_experience.language', 'class' => 'light', 'placeholder' => trans('experiences.manage.select_a_language')]) !!}
      </div>
      <p class="text-danger" ng-show="form_errors.language.length">
        @{{form_errors.language[0]}}
      </p>
    </div>
    <div class="d-none">
      <h4>
        {{trans('experiences.manage.fluent_in_more_languages')}}
      </h4>
      <p>
        {{trans('experiences.manage.you_have_chance_to_translate')}}
      </p>
      <div class="my-4">
        <label class="verify-check">
          <input type="checkbox" name="" checked="" class="chekbox1" disabled=""> 
          <span>
            {{trans('experiences.manage.let_me_know_when_i_translate')}}
          </span>
        </label>
      </div>
    </div>
    <div class="mt-4 mt-md-5">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img mt-5">
    <h3>
      {{trans('experiences.manage.languages_spoken_by_city', ['city' => $host_experience->city_details->name, 'site_name' => SITE_NAME])}}
    </h3>
    @foreach($language_spoken_data as $language_data)
    <div class="row mt-4">
      <div class="col-md-8">
        <p>
          {{$language_data['name']}}
        </p>
      </div>
      <div class="col-md-4">
        <p class="text-right">
          {{$language_data['percentage']}}%
        </p>
      </div>
    </div>
    <div class="progress_bar">
      <div class="progress_val w-0" style="width: {{$language_data['percentage']}}%"></div>
    </div>
    @endforeach
    <div ng-init="language_progress_start()"></div>
  </div>
</div>
<!--  main_bar end -->
<div class="main-wrap bg-white d-lg-flex" ng-cloak>
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.what_else_guests_know')}}
    </h3>
    <p>
      {{trans('experiences.manage.notes_page_desc')}}
    </p>
    <textarea class="input_new1 mt-4" placeholder="{{trans('experiences.manage.notes_example_placeholder')}}" rows="3" id="host_experience.notes" ng-model="host_experience.notes" ng-change="need_notes_change();" >
    </textarea> 
    <p class="focus_show" ng-class="character_length_class(1, 200, host_experience.notes.length)">
      @{{character_length_validation(1, 200, host_experience.notes.length)}}
    </p>
    <p class="text-danger" ng-show="form_errors.notes.length">
      @{{form_errors.notes[0]}}
    </p>
    <div id="need_notes_part" ng-show="host_experience.notes.length <= 0">
      <p>
        {{trans('experiences.manage.is_there_nothing_else_guests_should_know')}}
      </p>
      <div class="my-4">
        <label class="verify-check">
          <input type="checkbox" name="need_notes" ng-model="host_experience.need_notes" ng-true-value="'No'" ng-false-value="'Yes'" ng-checked="host_experience.need_notes == 'No'"> 
          <span style="color: black">
            {{trans('experiences.manage.i_have_no_additional_notes_guests')}}
          </span>
          <p class="text-danger" ng-show="form_errors.need_notes.length">
            @{{form_errors.need_notes[0]}}
          </p>
        </label>
      </div>
    </div>
    <div class="mt-4 mt-md-5">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
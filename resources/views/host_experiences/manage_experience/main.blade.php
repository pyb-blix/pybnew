@extends('template')
@section('main')
<style type="text/css">
  header, 
  footer {
    display: none;
  }

  .footer-toggle {
    display: none;
  }
</style>
<main id="site-content" role="main" class="mt-0">
  <div class="experience-step-wrap" ng-controller="manage_experiences" ng-cloak>
    {!! Form::open(['url' => url('host/manage_experience/'.$host_experience->id.'/update_experience'), 'id' => 'update_host_experience', 'accept-charset' => 'UTF-8' , 'name' => 'update_host_experience', 'method' => 'post', 'class' => 'd-md-flex']) !!}
    <input type="hidden" name="step_num" ng-model="step_num" value="{{$step_num}}">
    <input type="hidden" name="step" ng-model="step" value="{{$step}}">
    <div class="side-bar" ng-init="host_experience = {{json_encode($host_experience_array)}}; host_experience_id={{$host_experience->id}}; host_experience_steps = {{json_encode($host_experience->steps)}}; step_num={{$step_num}}; step='{{$step}}'; ajax_base_url = '{{$ajax_base_url}}'; field_validations = {{json_encode(trans('experiences.field_validations'))}}; host_experience_provides = {{json_encode(@$host_experience_provides)}}; host_experience_photos = {{json_encode(@$host_experience_photos)}}; host_experience_packing_lists = {{json_encode(@$host_experience->host_experience_packing_lists)}}; provide_items={{json_encode(@$provide_items)}};">
      <a href="{{url('/')}}" aria-label="Homepage" data-prevent-default="" class="logo-new">
        <img src="{{ url(LOGO_URL) }}"/>
      </a>
      <div class="exp-responsive-icon d-md-none">
        <i class="fa fa-close side-menu-bar" style="cursor: pointer;"></i>
      </div>
      <div id="manage_experience_menu" class="experience-menu" ng-cloak>
        @include('host_experiences.manage_experience.menu')
      </div>
    </div>
    <!--  side_bar end -->
    <div id="manage_experience_main_content" class="experience-step-info">
      @include('host_experiences.'.$main_content_section)
    </div>
    {!! Form::close() !!}
    <!--  main_bar end -->

    <div class="host-save-popup d-none" id="control_btns_popup">
      <div class="host-popup-content">
        <a href="javascript:void(0)" class="host-close close_control_btns_popup">
          <i class="icon icon2-cancel"></i>
        </a>
        <p>
          {{trans('experiences.manage.there_are_some_unsaved_changes_please_complete_the_step')}}
        </p>
        @include('host_experiences.manage_experience.control_buttons')      
      </div>
    </div>

    <div class="host-save-popup d-none">
      <div class="host-popup-content">
        <a href="javascript:void(0)" class="host-close close_pop">
          <i class="icon icon2-cancel"></i>
        </a>
        <p>
          Are you sure you want to change your submission language? If saved, this will delete your descriptions written in English.
        </p>
        <a href="javascript:void(0)" class="btn btn-primary">
          Yes
        </a>
        <a href="javascript:void(0)" class="btn btn-primary">
          No, undo
        </a>
      </div>
    </div>

    <div class="host-save-popup d-none" id="photo_error_popup">
      <div class="host-popup-content">
        <a href="javascript:void(0)" class="host-close close_photo_error_popup">
          <i class="icon icon2-cancel"></i>
        </a>
        <h3 id="title"></h3>
        <p id="description"></p>
        <div class="mt-4">
          <a href="javascript:void(0);" class="btn btn-primary" id="choose_another_photo_btn" data-index="">
            {{trans('experiences.manage.choose_another_photo')}}
          </a>
          <a href="javascript:void(0);" class="btn btn-primary close_photo_error_popup" id="photo_upload_cancel_btn">
            {{trans('experiences.manage.cancel')}}
          </a>
        </div>
      </div>
    </div>

    <div class="host-save-popup d-none" id="photo_image_popup">
      <div class="host-popup-content">
        <a href="javascript:void(0)" class="host-close close_photo_error_popup">
          <i class="icon icon2-cancel"></i>
        </a>
        <p class="h3 bold space-top-8" id="title">
        </p>
      </div>
    </div>
  </div>
</main>
@stop
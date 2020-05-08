@extends('template')
@section('main')
<main id="site-content" role="main">      
  @include('common.subheader')  
  <div class="photos-content my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-3 side-nav">
          @include('common.sidenav')
          <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-primary">
            {{ trans('messages.dashboard.view_profile') }}
          </a>
        </div>
        <div class="col-md-8 col-lg-9 dashboard-content mt-4 mt-md-0" ng-controller="user_media">
          <div class="card">
            <div class="card-header">
              <h3> {{ trans('messages.profile.profile_photo') }} </h3>
            </div>
            <div class="card-body photos-section" ng-init="original_src='{{ $result->profile_picture->getOriginal('src') }}'">
              <div class="row">
                <div class="col-lg-4 text-center">
                  <div data-picture-id="91711885" class="profile-pic-container picture-main space-sm-2 space-md-2">
                    <div class="profile-img" style="display:none;">
                      <img class="img-fluid w-100 px-4 px-md-5 px-lg-0 user_profile_pic" title="{{ $result->first_name }}" src="{{ $result->profile_picture->src }}" alt="{{ $result->first_name }}">
                    </div>
                    <div class="profile-img">
                      <img class="img-fluid w-100 px-4 px-md-5 px-lg-0 user_profile_pic" title="{{ $result->first_name }}" src="{{ $result->profile_picture->src }}" alt="{{ $result->first_name }}">
                    </div>
                    <a href="javascript:;" class="delete_profile" ng-click="remove_profile_picture()" ng-show="original_src != ''">
                      <i class="profile_delete_icon fa fa-trash" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
                <div class="col-lg-8 mt-4 mt-lg-0 text-center text-md-left">
                  <ul class="list-layout picture-tiles clearfix ui-sortable"></ul>
                  <p>
                    {{ trans('messages.profile.profile_photo_desc') }}
                  </p>
                  <div class="btn file-input-container">
                    {{ trans('messages.profile.upload_photo') }}
                    <form name="ajax_upload_form" method="post" id="ajax_upload_form" enctype="multipart/form-data" action="{{ url('users/image_upload') }}" accept-charset="UTF-8">
                      {!! Form::token() !!}
                      <input type="hidden" value="{{ $result->id }}" name="user_id" id="user_id">
                      <input type="file" name="profile_pic" id="user_profile_pic">
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
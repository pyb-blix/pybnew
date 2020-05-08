@extends('template')
@section('main')
<main id="site-content" role="main">
  @include('common.subheader')        
  <div class="verification-content my-4 my-md-5" ng-controller="verification_controller" ng-cloak>
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-3 side-nav">
          @include('common.sidenav')
          <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-primary">
            {{ trans('messages.dashboard.view_profile') }}
          </a>
        </div>
        <div class="col-md-8 col-lg-9 mt-4 mt-md-0" id="dashboard-content" ng-init="id_verification_status ='{{Auth::user()->id_document_verification_status}}';">
          @if(Auth::user()->users_verification->email != 'no' || Auth::user()->users_verification->facebook != 'no' || Auth::user()->users_verification->google != 'no' || Auth::user()->users_verification->linkedin != 'no' || Auth::user()->verification_status == 'Verified')
          <div class="card verified-container">
            <div class="card-header">
              <h3>
                {{ trans('messages.profile.current_verifications') }}
              </h3>
            </div>
            <div class="card-body">
              <ul class="list-layout edit-verifications-list">

                @if(Auth::user()->verification_status == 'Verified')
                <li>
                  <div class="row flex-wrap align-items-center">
                    <div class="col-12 col-md-7">
                      <h4 class="m-0"> 
                        {{ trans('messages.dashboard.id_verification') }} 
                      </h4>
                    </div>
                    <div class="col-12 col-md-5 mt-3 mt-md-0">
                      <div class="disconnect-button-container">
                        <a href="javascript:;" class="btn btn-block" data-method="post" rel="nofollow">
                          @{{ id_verification_status }}
                        </a>
                      </div>
                    </div>
                    <!-- Verified Documents -->
                    <div class="id_documents_slider col-12 mt-3 owl-carousel">
                      <div class="item item-@{{ photos.id }}" ng-repeat="photos in id_documents">
                        <img ng-src="@{{ photos.src }}">
                        <button type="button" data-photo-id="@{{ photos.id }}" ng-click="delete_document(photos,photos.id)" ng-show="id_verification_status != 'Verified'" class="document-img-delete">
                          <i class="fa fa-trash delete_document-icon" aria-hidden="true"></i>
                        </button>
                        <a href="@{{photos.download_src }}" download="@{{photos.name }}" class="delete_but_edit document-img-upload">
                          <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                @endif

                @if(Auth::user()->users_verification->email == 'yes')
                <li>
                  <h4>
                    {{ trans('messages.dashboard.email_address') }}
                  </h4>
                  <p class="description">
                    {{ trans('messages.profile.you_have_confirmed_email') }} 
                    <b>
                      {{ Auth::user()->email }}.
                    </b>  {{ trans('messages.profile.email_verified') }}
                  </p>
                </li>
                @endif

                @if(Auth::user()->users_verification->phone_number == 'yes')
                <li>
                  <h4>
                    {{ trans('messages.profile.phone_number') }}
                  </h4>
                  <p class="description">
                    {{ trans('messages.profile.you_have_confirmed_phone') }} 
                    <b>
                      {{ Auth::user()->primary_phone_number }}.
                    </b>
                  </p>
                </li>
                @endif

                @if(Auth::user()->users_verification->facebook == 'yes')
                <li>
                  <h4>Facebook</h4>
                  <div class="row flex-wrap">
                    <div class="col-12 col-md-7">
                      <p class="description verification-text-description">
                        {{ trans('messages.profile.facebook_verification') }}
                      </p>
                    </div>
                    <div class="col-12 col-md-5 mt-3 mt-md-0">
                      <div class="disconnect-button-container">
                        <a href="{{ url('facebookDisconnect') }}" class="btn btn-block" data-method="post" rel="nofollow">
                          {{ trans('messages.profile.disconnect') }}
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                @endif

                @if(Auth::user()->users_verification->google == 'yes')
                <li>
                  <h4>Google</h4>
                  <div class="row">
                    <div class="col-12 col-md-7">
                      <p class="description verification-text-description">
                        {{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
                      </p>
                    </div>
                    <div class="col-12 col-md-5 mt-3 mt-md-0">
                      <div class="disconnect-button-container">
                        <a href="{{ url('googleDisconnect') }}" class="btn btn-block" data-method="post" rel="nofollow">
                          {{ trans('messages.profile.disconnect') }}
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                @endif

                @if(Auth::user()->users_verification->linkedin == 'yes')
                <li>
                  <h4>LinkedIn</h4>
                  <div class="row">
                    <div class="col-12 col-md-7">
                      <p class="description verification-text-description">
                        {{ trans('messages.profile.linkedin_verification', ['site_name'=>$site_name]) }}
                      </p>
                    </div>
                    <div class="col-12 col-md-5 mt-3 mt-md-0">
                      <div class="disconnect-button-container">
                        <a href="{{ url('linkedinDisconnect') }}" class="btn btn-block" data-method="post" rel="nofollow">
                          {{ trans('messages.profile.disconnect') }}
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
                @endif
              </ul>
            </div>
          </div>
          @endif

          @if(Auth::user()->users_verification->email != 'yes' || Auth::user()->users_verification->facebook != 'yes' || Auth::user()->users_verification->google != 'yes' || Auth::user()->users_verification->linkedin != 'yes' || Auth::user()->verification_status != 'Verified')
          <div class="card mt-4 unverified-container">
            <div class="card-header">
              <h3>
                {{ trans('messages.profile.add_more_verifications') }}
              </h3>
            </div>
            <div class="card-body">
              <ul class="list-layout edit-verifications-list">
                @if(Auth::user()->verification_status != 'Verified')
                <li>
                  <h4> {{ trans('messages.profile.id_verification') }} </h4>
                  <div class="row">
                    <div class="col-12 col-md-7 document_upload-btn mt-2 mt-md-0">
                      <button class="upload_btn btn btn-block" onclick="$('#id_document').trigger('click');"> {{ trans('messages.profile.upload_document') }} <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                      </button>
                      <input class="upload_photos" type="file" ng-model="id_documents" style="display:none" accept="image/*" multiple="true" id="id_document" name='id_document[]' onchange="angular.element(this).scope().upload_verification_documents(this,'id_document')" />
                      <span class="text-danger"></span>
                      <div class="doc_error" style="color: red;display: none;"></div>
                    </div>

                    <div class="col-12 col-md-5 document_status-btn mt-3 mt-md-0" ng-show="id_verification_status != 'Connect'">
                      <button class="pending_btn btn btn-block">
                       @{{id_verification_status}}
                     </button>
                   </div>

                   <div class="id_documents_slider col-12 mt-3 owl-carousel">
                    <div class="item item-@{{ photos.id }}" ng-repeat="photos in id_documents">
                      <img ng-src="@{{ photos.src }}">
                      <button type="button" data-photo-id="@{{ photos.id }}" ng-click="delete_document(photos,photos.id)" ng-show="id_verification_status != 'Verified'" class="document-img-delete">
                        <i class="fa fa-trash delete_document-icon" aria-hidden="true"></i>
                      </button>
                      <a href="@{{photos.download_src }}" download="@{{photos.name }}" class="delete_but_edit document-img-upload">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                    </div>
                  </div>

                  <div class="col-12 mt-3" ng-if='!id_documents.length'>
                    @lang('messages.profile.no_photos_uploaded')
                  </div>
                </div>
              </li>
              @endif

              @if(Auth::user()->users_verification->email == 'no')
              <li>
                <h4>
                  {{ trans('messages.login.email') }}
                </h4>
                <div class="row">
                  <div class="col-12 col-md-7">
                    <p class="description verification-text-description">
                      {{ trans('messages.profile.email_verification') }} 
                      <b>{{ Auth::user()->email }}.</b>
                    </p>
                  </div>
                  <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="connect-button">
                      <a href="{{ url('users/request_new_confirm_email?redirect=verification') }}" class="btn btn-block email-button">
                        {{ trans('messages.profile.connect') }}
                      </a>
                    </div>
                  </div>
                </div>
              </li>
              @endif

              @if(Auth::user()->users_verification->facebook == 'no')
              <li>
                <h4>
                  Facebook
                </h4>
                <div class="row">
                  <div class="col-12 col-md-7">
                    <p class="description verification-text-description">
                      {{ trans('messages.profile.facebook_verification') }}
                    </p>
                  </div>
                  <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="connect-button">
                      <a href="{{ $fb_url }}" class="btn btn-block facebook-button">
                        {{ trans('messages.profile.connect') }}
                      </a>
                    </div>
                  </div>
                </div>
              </li>
              @endif

              @if(Auth::user()->users_verification->google == 'no')
              <li>
                <h4>
                  Google
                </h4>
                <div class="row">
                  <div class="col-12 col-md-7">
                    <p class="description verification-text-description">
                      {{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
                    </p>
                  </div>
                  <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="connect-button">
                      <a class="btn btn-block" id="google_connect" href="javascript:;">
                        {{ trans('messages.profile.connect') }}
                      </a>
                    </div>
                  </div>
                </div>
              </li>
              @endif

              @if(Auth::user()->users_verification->linkedin == 'no')
              <li>
                <h4>
                  LinkedIn
                </h4>
                <div class="row">
                  <div class="col-12 col-md-7">
                    <p class="description verification-text-description">
                      {{ trans('messages.profile.linkedin_verification', ['site_name'=>$site_name]) }}
                    </p>
                  </div>
                  <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="connect-button">
                      <a class="btn btn-block" href="{{URL::to('linkedinLoginVerification')}}">
                        {{ trans('messages.profile.connect') }}
                      </a>
                    </div>
                  </div>
                </div>
              </li>
              @endif
            </ul>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
</main>
@stop
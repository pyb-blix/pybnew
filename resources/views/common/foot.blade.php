@php
  if ((request()->get('device') && request()->get('device') == 'mobile') || Session::get('get_token')!=null) {
    $view_device = 'mobile';
  }
@endphp

@if(!isset($view_device))
  @if(Route::currentRouteName() != 'payments.book' || Route::currentRouteName() != 'api_payments.book')
  <button class="footer-toggle">
    <span class="open-footer align-items-center">
      <i class="icon icon-globe mr-md-2" asd="icon-globe"></i> 
      {{ trans('messages.home.terms_privacy_more') }}
    </span>
    <span class="close-footer align-items-center">
      <i class="icon icon-remove mr-md-2"></i> 
      {{ trans('messages.home.close') }}
    </span>
  </button>
  @endif
@endif
<div class="host-pay-height"></div>
<div id="gmap-preload" class="hide"></div>
<div class="ipad-interstitial-wrapper">
  <span data-reactid=".1"></span>
</div>
<div id="fb-root"></div>
<!-- remove for console error -   &sensor=false -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&libraries=places&language={{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"></script>

{!! Html::script('js/underscore-min.js') !!}
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/jquery-3.4.1.js') !!}
{!! Html::script('js/popper.min.js') !!}
{!! Html::script('js/fullcalendar.min.js') !!}
{!! Html::script('js/fullcalendar_locale.min.js') !!}
{!! Html::script('js/scheduler.min.js') !!}
{!! Html::script('js/jquery-ui.js') !!}
{!! Html::script('js/owl.carousel.min.js') !!}
@if(Session::get('language') != 'en')
{!! Html::script('js/i18n/datepicker-'.Session::get('language').'.js') !!}
@endif
<script type="text/javascript" src="https://cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.js"></script>

{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/angular.js') !!}
{!! Html::script('js/angular-sanitize.js') !!}
{!! Html::script('js/me-lazyload.js') !!}

{!! Html::script('js/responsiveslides.min.js') !!}
{!! Html::script('js/jquery.sticky-sidebar-scroll.min.js') !!}
{!! Html::script('js/jquery.selectBoxIt.js') !!}

{!! Html::script('js/daterangepicker.js') !!}
{!! Html::script('js/lightgallery-all.min.js') !!}
{!! Html::script('js/lightslider.min.js') !!}
{!! Html::script('js/lg-thumbnail.min.js') !!}

<script type="text/javascript">
  $(document).ready(function() {
    $('.top-home').click(function(event){
      event.stopPropagation();
    });

    $(function() {
      var selectBox = $("select.footer-select").selectBoxIt();
      var selectBox2 = $("select.custom-select").selectBoxIt();
    });

    $('ul.menu-group li a').click(function() {
      $('.nav--sm').css('visibility','hidden');
    });

    $('.burger--sm').click(function() {
      $('.header--sm .nav--sm').css('visibility','visible');
      $('.makent-header .header--sm .nav-content--sm').css('left','0', 'important');
    });

    $('.nav-mask--sm').click(function()
    {
      $('.header--sm .nav--sm').css('visibility','hidden');
      $('.makent-header .header--sm .nav-content--sm').css('left','-285px');
    });

    $('.nav-mask--sm').click(function() {
      $('.header--sm .nav--sm').css('visibility','hidden');
      $('.makent-header .header--sm .nav-content--sm').css('left','-285px');
    });

    $(document).on('change','#user_profile_pic', function() {
      $('#ajax_upload_form').submit();
    });
  });
</script>

<script>
  var app = angular.module('App', ['ngSanitize','me-lazyload']);
  var APP_URL = {!! json_encode(url('/')) !!};
  var LANGUAGE_CODE = "{!! (Session::get('language')) ? Session::get('language') : $default_language[0]->value !!}";
  var USER_ID = {!! @Auth::user()->id ? @Auth::user()->id : json_encode([]) !!};
  var more_text_lang = "{{trans('messages.profile.more')}}";
  var validation_messages  = {!! json_encode(trans('validation')) !!};
  var please_set_location = "{{trans('messages.home.please_set_location')}}";
  var GOOGLE_CLIENT_ID = '{{ $google_client_id }}';
  var STRIPE_PUBLISH_KEY = '{{ $stripe_publish_key }}';
  var inbox_count = {!! @Auth::user()->id ? @Auth::user()->inbox_count() : 0 !!};
  var popup_code  = {!! session('error_code') ? session('error_code') : 0  !!};

  $.datepicker.setDefaults($.datepicker.regional[ "{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}" ])
</script>
<script src="https://apis.google.com/js/api:client.js"></script>
<script src="{{url('js/googleapilogin.js?v1=1.0')}}"></script>

{!! $head_code !!}
{!! Html::script('js/common.js?v='.$version) !!}
{!! Html::script('js/nouislider.min.js?v='.$version) !!}
{!! Html::script('js/jquery.textfill.min.js?v='.$version) !!}
{!! Html::script('js/jquery.bxslider.js') !!}  

@if (!isset($exception))
@if(Route::current()->uri() == '/')
{!! Html::script('js/jquery.bxslider.min.js?v='.$version) !!}
{!! Html::script('js/home_two.js') !!}
@endif

@if (Route::current()->uri() == 'rooms/new')
{!! Html::script('js/rooms_new.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif

@if (Route::current()->uri() == 'manage-listing/{id}/{page}')
{!! Html::script('js/manage_listing.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif

@if (Route::current()->uri() == 's')
{!! Html::script('js/home_two.js?v='.$version) !!}
{!! Html::script('js/infobubble.js') !!}
@endif
@if (Route::current()->uri() == 'home_two')
{!! Html::script('js/jquery.bxslider.min.js') !!}  
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif
@if (Route::current()->uri() == 'trips/current')   
{!! Html::script('js/home_two.js?v='.$version) !!} 
@endif
@if (Route::current()->uri() == 'trips/previous')   
{!! Html::script('js/home_two.js?v='.$version) !!} 
@endif
@if (Route::current()->uri() == 'users/transaction_history')
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif
@if (Route::current()->uri() == 'users/security')
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif
@if (Route::current()->uri() == 'rooms/{id}')
{!! Html::script('js/rooms.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
{!! Html::script('js/jquery.bxslider.min.js') !!}
@endif

@if (Route::current()->uri() == 'wishlists/popular' || Route::current()->uri() == 'wishlists/my' || Route::current()->uri() == 'wishlists/picks' || Route::current()->uri() == 'wishlists/{id}' || Route::current()->uri() == 'users/{id}/wishlists')
{!! Html::script('js/wishlists.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif

@if (Route::current()->uri() == 'inbox' || Route::current()->uri() == 'z/q/{id}' || Route::current()->uri() == 'messaging/qt_with/{id}')
{!! Html::script('js/inbox.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif

@if(Route::current()->uri() == 'disputes' || Route::current()->uri() == 'dispute_details/{id}')
{!! Html::script('js/disputes.js?v='.$version) !!}
<script src="https://js.stripe.com/v3/"></script>
@endif

@if (Route::current()->uri() == 'dashboard')
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif
@if (Route::current()->uri() == 'reservation/{id}')
{!! Html::script('js/reservation.js?v='.$version) !!}
{!! Html::script('js/home_two.js?v='.$version) !!}
@endif
@endif

@if (Request::segment(1) == 'host' || Request::segment(1) == 'experiences')
{!! Html::script('js/host_experiences/owl.carousel.js?v='.$version) !!}
{!! Html::script('js/host_experiences/host_experience.js?v='.$version) !!}
@endif

@stack('scripts')

<script type="text/javascript">
  $(document).ready(function() {
    if(popup_code == 1) {
      $('#signup-popup2').modal('show');
    }
    else if(popup_code == 2) {
      $('#login-popup').modal('show');
    }
    else if(popup_code == 3) {
      $('#forgot-popup').modal('show');
    }
    else if(popup_code == 4) {
      $('#import_popup').modal('show');
    }
    else if(popup_code == 5) {
      $('#payout_popupstripe').modal('show');
    }
  });
</script>

<script>
  $(document).ready(function() {
    $("#photos, .photo-gallery1, .mob_photo-gallery, .button_1b5aaxl, .link-reset.burger--sm.header-logo, .vid_pop").click(function(e){
      $("body, html").addClass("non_scroll");
    });

    $(document).on('click', "#header .nav-mask--sm, .popup", function(){
      $("body, html").removeClass("non_scroll");
    });    
  });

  $(document).ready(function(){
    $(".subnav-item.show-collapsed-nav-link").click(function(){
      $("body").toggleClass("non_scrl");
    });
  });
</script>

<div class="sign-popup modal fade" role="dialog" id="login-popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
        </button>
      </div>
      <div class="modal-body">
        <ul class="social-log">
          <li>
            <a href="{{ $fb_url }}" class="fb-btn btn d-flex align-items-center justify-content-center">
              <i class="icon icon-facebook"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.login_with')}} Facebook
              </span>
            </a>
          </li>
          <li>
            <a href="javascript:;" id="pop_google_login" class="google-btn btn d-flex align-items-center justify-content-center">
              <i class="icon icon-google-plus"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.login_with')}} Google
              </span>
            </a>
          </li>
          <li>
            <a href="{{ getAppleLoginUrl() }}" id="apple_login" class="apple-btn btn d-flex align-items-center justify-content-center">
              <i class="fa fa-apple push-half--right"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.login_with')}} Apple
              </span>
            </a>
          </li>
          {{--
            <li>
              <a href="{{URL::to('auth/linkedin')}}" class="linkedin-btn btn d-flex align-items-center justify-content-center">
                <i class="icon icon-linkedin"></i>
                <span class="d-inline-block ml-3">
                  {{ trans('messages.login.login_with')}} LinkedIn
                </span>
              </a>
            </li>
            --}}
          </ul>
          <div class="or-block my-4 d-flex align-items-center px-0">
            <span class="d-inline-block mx-3">
              {{ trans('messages.login.or')}}
            </span>
          </div>
          <div class="log-form">
            {!! Form::open(['url' => route('login'), 'method' => 'POST', 'novalidate' => 'true', 'data-action' => 'Signin']) !!}
            {!! Form::hidden('from', 'email_login', ['id' => 'login_from']) !!}

            <div class="control-group">
              @if ($errors->has('login_email')) 
              <p class="error-msg mb-1">
                {{ $errors->first('login_email') }}
              </p> 
              @endif
              <div class="d-flex align-items-center">
                <input class="{{ $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.email_address') }}" id="signin_email" name="login_email" type="email" value="">
                <i class="icon icon-envelope"></i>
              </div>
            </div>

            <div class="control-group">
              @if ($errors->has('login_password')) 
              <p class="error-msg mb-1">
                {{ $errors->first('login_password') }}
              </p> 
              @endif
              <div class="d-flex align-items-center">
                <input class="{{ $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.password') }}" id="signin_password" data-hook="signin_password" name="login_password" type="password" value="">
                <i class="icon icon-lock"></i>
              </div>
            </div>

            <div class="d-flex my-3 align-items-center justify-content-between">
              <label for="remember_me2" class="checkbox remember-me m-0">
                <input id="remember_me2" class="remember_me mr-1" name="remember_me" type="checkbox" value="1"> 
                {{ trans('messages.login.remember_me')}}
              </label>
              <a href="javascript:void(0)" class="forgot-open green-link" data-toggle="modal" data-target="#login-popup">
                {{ trans('messages.login.forgot_pwd')}}
              </a>
            </div>

            <input class="btn btn-primary" id="user-login-btn" type="submit" value="{{ trans('messages.header.login') }}">
            {!! Form::close() !!}
            <div class="form-footer mt-3 pt-3 text-center"> 
              {{ trans('messages.login.dont_have_account')}}
              <a href="javasscript:void(0)" class="signup-open green-link" data-toggle="modal" data-target="#login-popup">
                {{ trans('messages.header.signup')}} 
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="sign-popup modal fade" role="dialog" id="forgot-popup">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close icon" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="log-form">
            {!! Form::open(['url' => url('forgot_password')]) !!}
            <h5>
             {{ trans('messages.login.reset_pwd') }}
           </h5>
           <p>
            {{ trans('messages.login.reset_pwd_desc') }}
          </p>          
          <div class="control-group"> 
            @if ($errors->has('email')) 
            <p class="error-msg">
              {{ $errors->first('email') }}
            </p> 
            @endif
            <div class="input-group">
              {!! Form::email('email', '', ['placeholder' => trans('messages.login.email'), 'id' => 'forgot_email', 'class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid input_new' : 'decorative-input inspectletIgnore input_new']) !!}
              <i class="icon icon-envelope"></i>
            </div>
          </div>
          <div class="d-flex mt-4 align-items-center justify-content-between">
            <a href="javascript:void(0)" class="back-btn d-flex align-items-center" data-toggle="modal" data-target="#forgot-popup"> 
              <i class="icon icon-chevron-left"></i>
              <span class="d-inline-block ml-2">
                Back to Login
              </span>
            </a>
            <button id="reset-btn" class="btn btn-primary" type="submit">
              {{ trans('messages.login.send_reset_link') }}
            </button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="sign-popup modal fade" role="dialog" id="signup-popup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="social-log">
          <li>
            <a href="{{ $fb_url }}" class="fb-btn btn d-flex align-items-center justify-content-center">
              <i class="icon icon-facebook"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.signup_with')}} Facebook
              </span>
            </a>
          </li>
          <li>
            <a href="javascript:void(0);" id="pop_google_signup_login" class="google-btn btn d-flex align-items-center justify-content-center">
              <i class="icon icon-google-plus"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.signup_with')}} Google
              </span>
            </a>
          </li>
          <li>
            <a href="{{ getAppleLoginUrl() }}" id="apple_login" class="apple-btn btn d-flex align-items-center justify-content-center">
              <i class="fa fa-apple push-half--right"></i>
              <span class="d-inline-block ml-3">
                {{ trans('messages.login.signup_with')}} Apple
              </span>
            </a>
          </li>
          {{--
            <li>
              <a href="{{URL::to('auth/linkedin')}}" class="linkedin-btn btn d-flex align-items-center justify-content-center">
                <i class="icon icon-linkedin"></i>
                <span class="d-inline-block ml-3">
                  {{ trans('messages.login.signup_with')}} LinkedIn
                </span>
              </a>
            </li>
            --}}
          </ul>
          <div class="or-block my-4 d-flex align-items-center px-0">
            <span class="d-inline-block mx-3">
              {{ trans('messages.login.or')}}
            </span>
          </div>
          <ul class="social-log">
            <li>
              <a href="{{ $fb_url }}" class="email-btn btn d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#signup-popup">
                <i class="icon icon-envelope"></i>
                <span class="d-inline-block ml-3">
                  {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
                </span>
              </a>
            </li>
          </ul>
          <div class="log-form">
            <div class="mt-3">
              <div class="agree-links">
                <ul class="clearfix">
                  <li>
                    @lang('messages.login.signup_agree') {{ $site_name }}'s
                  </li>
                  @foreach($company_pages as $company_page)
                  <li>
                    <a href="{{ url($company_page->url) }}" target="new">
                      {{ $company_page->name }}
                      <span>,</span>
                    </a>
                  </li>
                  @endforeach 
                </ul>
              </div>
            </div>
            <div class="form-footer mt-3 pt-3 text-center"> 
              {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
              <a href="javascript:void(0)" class="login-open green-link" data-toggle="modal" data-target="#signup-popup">
                {{ trans('messages.header.login') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="sign-popup modal fade" role="dialog" id="signup-popup2">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="my-3">
            <p class="text-center m-0">
              {{ trans('messages.login.signup_with') }}
              <a href="{{ $fb_url }}" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate" class="green-link">
                Facebook
              </a>,
              <a href="javascript:;" id="pop_google_signup_link" class="green-link">
                Google
              </a>,
              or
              <a href="{{ getAppleLoginUrl() }}" id="apple_signup_link" class="green-link">
                Apple
              </a>
              {{--
                <a href="{{URL::to('auth/linkedin')}}" class="green-link">
                  LinkedIn
                </a>
                --}}
              </p>
            </div>
            <div class="or-block my-4 d-flex align-items-center px-0">
              <span class="d-inline-block mx-3">
                {{ trans('messages.login.or')}}
              </span>
            </div>
            <div class="log-form">
              {!! Form::open(['action' => 'UserController@create', 'class' => 'signup-form', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
              <div class="signup-form-fields">
                {!! Form::hidden('from', 'email_signup', ['id' => 'signup_from']) !!}
                <div class="control-group" id="inputFirst">
                  @if ($errors->has('first_name')) 
                  <p class="error-msg">
                    {{ $errors->first('first_name') }}
                  </p> 
                  @endif
                  <div class="d-flex align-items-center justify-content-center">
                    {!! Form::text('first_name', '', ['class' =>  $errors->has('first_name') ? 'decorative-input invalid ' : 'decorative-input name-icon input_new', 'placeholder' => trans('messages.login.first_name')]) !!}
                    <i class="icon icon-users"></i>
                  </div>
                </div>
                <div class="control-group" id="inputLast">
                  @if ($errors->has('last_name')) 
                  <p class="error-msg">
                    {{ $errors->first('last_name') }}
                  </p> 
                  @endif
                  <div class="d-flex align-items-center justify-content-center">
                    {!! Form::text('last_name', '', ['class' => $errors->has('last_name') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon input_new', 'placeholder' => trans('messages.login.last_name')]) !!}
                    <i class="icon icon-users"></i>
                  </div>
                </div>
                <div class="control-group" id="inputEmail">
                  @if ($errors->has('email')) 
                  <p class="error-msg">
                    {{ $errors->first('email') }}
                  </p> 
                  @endif
                  <div class="d-flex align-items-center justify-content-center">
                    {!! Form::email('email', '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-mail name-icon input_new', 'placeholder' => trans('messages.login.email_address')]) !!}
                    <i class="icon icon-envelope"></i>
                  </div>
                </div>
                <div class="control-group" id="inputPassword">
                  @if ($errors->has('password')) 
                  <p class="error-msg">
                    {{ $errors->first('password') }}
                  </p> 
                  @endif
                  <div class="d-flex align-items-center justify-content-center">
                    {!! Form::password('password', ['class' => $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-pwd name-icon input_new', 'placeholder' => trans('messages.login.password'), 'id' => 'user_password', 'data-hook' => 'user_password']) !!}
                    <i class="icon icon-lock"></i>
                  </div>
                  <div data-hook="password-strength" class="password-strength hide"></div>
                </div>
                <div class="control-group mt-3">
                  <h5>
                    {{ trans('messages.login.birthday') }}
                  </h5>
                  <p class="m-0">
                    {{ trans('messages.login.birthday_message') }}
                  </p>
                </div>
                <div class="control-group" id="inputBirthday"></div>
                @if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) 
                <p class="error-msg"> 
                  {{ $errors->has('birthday_day') ? $errors->first('birthday_day') : ( $errors->has('birthday_month') ? $errors->first('birthday_month') : $errors->first('birthday_year') ) }} 
                </p> 
                @endif
                <div class="control-group calander_new d-md-flex">
                  <div class="select flex-grow-1">
                    <i class="icon icon-chevron-down"></i>
                    {!! Form::selectMonthWithDefault('birthday_month', null, trans('messages.header.month'), [ 'class' => $errors->has('birthday_month') ? 'invalid' : '', 'id' => 'user_birthday_month']) !!}
                  </div>
                  <div class="select flex-grow-1 my-3 my-md-0 mx-0 mx-md-3">
                    <i class="icon icon-chevron-down"></i>
                    {!! Form::selectRangeWithDefault('birthday_day', 1, 31, null, trans('messages.header.day'), [ 'class' => $errors->has('birthday_day') ? 'invalid' : '', 'id' => 'user_birthday_day']) !!}
                  </div>
                  <div class="select flex-grow-1">
                    <i class="icon icon-chevron-down"></i>
                    {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, null, trans('messages.header.year'), [ 'class' => $errors->has('birthday_year') ? 'invalid' : '', 'id' => 'user_birthday_year']) !!}
                  </div>
                </div>

                <div class="mt-3 d-flex">
                  <input type="checkbox" ng-model="agree_toc">
                  <div class="agree-links">
                    <ul class="clearfix">
                      <li>
                        @lang('messages.login.signup_agree') {{ $site_name }}'s
                      </li>
                      @foreach($company_pages as $company_page)
                      <li>
                        <a href="{{ url($company_page->url) }}" target="new">
                          {{ $company_page->name }}
                          <span>,</span>
                        </a>
                      </li>
                      @endforeach 
                    </ul>
                  </div>
                </div>

                {!! Form::submit( trans('messages.header.signup'), ['class' => 'my-3 d-flex justify-content-center btn btn-primary' , 'id' => 'user-signup-btn', 'ng-disabled' => '!agree_toc'])  !!}
                {!! Form::close() !!}

                <div class="form-footer mt-3 pt-3 text-center"> 
                  {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
                  <a href="javascript:void(0)" class="login-open green-link" data-toggle="modal" data-target="#signup-popup2">
                    {{ trans('messages.header.login') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cookie Alert -->
    <div class="alert cookie-alert alert-dismissible m-0">
      <i class="close" data-dismiss="alert" style='cursor: pointer'></i>
      <p>
        @lang('messages.footer.using_cookies',['site_name' => $site_name])
        <a href="{{url('privacy_policy')}}" class="theme-link">
         @lang('messages.login.privacy_policy'). 
       </a>
     </p>
   </div>

   <script type="text/javascript">
    $(document).on('click','.cookie-alert .close',function() {
      writeCookie('accept_cookie','1',10);
    });

    var getCookiebyName = function() {
      var pair = document.cookie.match(new RegExp('accept_cookie' + '=([^;]+)'));
      var result = pair ? pair[1] : 0;  
      $('.cookie-alert').show();
      if(result) {
        $('.cookie-alert').hide();
      }
    };

    var url = window.location.href;
    var arr = url.split("/");
    var result = arr[0] + "//" + arr[2];
    var domain =  result.replace(/(^\w+:|^)\/\//, '');

    writeCookie = function(cname, cvalue, days) {
      var dt, expires;
      dt = new Date();
      dt.setTime(dt.getTime()+(days*24*60*60*1000));
      expires = "; expires="+dt.toGMTString();
      document.cookie = cname+"="+cvalue+expires+'; domain='+domain+ "; path=/"; 
    }
    getCookiebyName();
  </script>
  <!-- Cookie Alert -->
</body>
</html>
@extends('template')
@section('main')
<main id="site-content" role="main">
  <div class="container py-4 py-md-5">
    <div class="log-page p-4">
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
          <a href="javascript:;" id="google_login" class="google-btn btn d-flex align-items-center justify-content-center">
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
        {!! Form::open(['url' => route('login'), 'method' => 'POST', 'novalidate' => 'true']) !!}
          <input id="from" name="from" type="hidden" value="email_login">

          <div class="control-group">
            @if ($errors->has('login_email')) 
            <p class="error-msg mb-1">
              {{ $errors->first('login_email') }}
            </p> 
            @endif
            <div class="d-flex align-items-center">
              <input class="{{ $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.email_address') }}" name="login_email" type="email" value="">
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
              <input class="{{ $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.password') }}" data-hook="signin_password" name="login_password" type="password" value="">
              <i class="icon icon-lock"></i>
            </div>
          </div>

          <div class="d-flex my-3 align-items-center justify-content-between">
            <label for="remember_me3" class="checkbox remember-me m-0">
              <input id="remember_me3" class="remember_me mr-1" name="remember_me" type="checkbox" value="1"> 
              {{ trans('messages.login.remember_me')}}
            </label>
            <a href="javascript:void(0)" class="forgot-open green-link">
              {{ trans('messages.login.forgot_pwd')}}
            </a>
          </div>

          <input class="btn btn-primary" type="submit" value="{{ trans('messages.header.login') }}">
        {!! Form::close() !!}
        <div class="form-footer mt-3 pt-3 text-center"> 
          {{ trans('messages.login.dont_have_account')}}
          <a href="javascript:void(0)" class="signup-open green-link">
              {{ trans('messages.header.signup')}} 
            </a>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
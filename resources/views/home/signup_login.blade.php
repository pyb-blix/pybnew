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
              {{ trans('messages.login.signup_with')}} Facebook
            </span>
          </a>
        </li>
        <li>
          <a href="#" id="google_login" class="google-btn btn d-flex align-items-center justify-content-center">
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
              {{ trans('messages.login.login_with')}} Apple
            </span>
          </a>
        </li>
        {{--
        <li>
          <a href="{{ url('auth/linkedin')}}" class="linkedin-btn btn d-flex align-items-center justify-content-center">
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
          <a href="{{ $fb_url }}" class="email-btn btn d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#signup-popup2">
            <i class="icon icon-envelope"></i>
            <span class="d-inline-block ml-3">
              {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
            </span>
          </a>
        </li>
      </ul>
      <div class="log-form">
        <div class="mt-3">
          <p class="m-0">
            {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s
            @foreach($company_pages as $company_page)
              <a class="green-link" href="{{ url($company_page->url) }}" data-popup="true">, {{ $company_page->name }}
              </a>
            @endforeach
          </p>
        </div>
        <div class="form-footer mt-3 pt-3 text-center"> 
          {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
         <a href="javascript:void(0)" class="login-open green-link">
            {{ trans('messages.header.login') }}
          </a>
        </div>
      </div>
    </div>
  </div>
</main> 
@stop
@extends('template')
@section('main')
<main id="site-content" role="main"> 
  <div class="referrals-wrap py-4">
    <div class="container">
      <h1>
        {{ trans('messages.referrals.earn_free_coupons',['site_name'=>$site_name]) }}!<br>{{ trans('messages.referrals.get_up_to') }} {{ html_string($result->value(5)) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.every_friend_invite') }}.
      </h1>      
      <a href="{{ url('login') }}" class="btn btn-primary my-4" data-login-modal="">
        {{ trans('messages.referrals.login_invite_friends') }}
      </a>
      <h4>
        {{ trans('messages.referrals.dont_have_an_account') }} 
        <a class="theme-link" href="{{ url('signup_login') }}">
          {{ trans('messages.referrals.signup') }}
        </a>
      </h4>
    </div>
  </div>

  <div class="container my-4">
    <div class="col-12 text-center">
      <p>
        {{ trans('messages.referrals.invite_your_friends',['site_name'=>$site_name]) }}. <br>{{ trans('messages.referrals.when_send_friend') }} {{ html_string($result->value(5)) }}{{ $result->value(4) }} {{ trans('messages.payments.in') }} {{ $site_name }} {{ trans('messages.referrals.credit_you_will_get') }} {{ html_string($result->value(5)) }}{{ $result->value(2) }} {{ trans('messages.referrals.when_they_travel') }} {{ html_string($result->value(5)) }}{{ $result->value(3) }} {{ trans('messages.referrals.when_they_host') }}. <br>{{ trans('messages.referrals.available_travel_credit') }}.
      </p>
    </div>
  </div>
</main>
@stop
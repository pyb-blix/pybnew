@extends('template')
@section('main')
<main id="site-content" role="main">
  <div class="container py-1">
    <div class="account-disabled my-4 my-md-5 mx-auto col-11 col-md-6 col-lg-4 p-0">
      <div id="account_recovery_panel" class="security-check-panel card text-center">
        <div class="card-body">
          <div class="icon-circle">
            <i class="icon icon-user"></i>
          </div>
          <h3>
            {{ trans('messages.profile.account_disabled') }}
          </h3>
          <p>
            {{ trans('messages.profile.pls_email_us') }}
          </p>
          <form action="mailto:{{ $admin_email }}" method="GET">
            <button class="search-button form-inline btn btn-primary">
              {{ trans('messages.profile.email_us') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
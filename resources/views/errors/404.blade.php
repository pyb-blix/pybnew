@extends('template')
@section('main')
<main id="site-content" role="main">
  <div class="container pt-1">
    <div class="error-page-content my-4 my-md-5 text-center">
      <h1 class="text-jumbo text-ginormous d-none d-md-block">
        {{ trans('messages.errors.oops') }}!
      </h1>
      <h2>
        {{ trans('messages.errors.404_desc') }}
      </h2>
      <h6>
        {{ trans('messages.errors.error_code') }}: 404
      </h6>
      <ul class="mt-3">
        <li>
          {{ trans('messages.errors.helpful_links') }}:
        </li>
        <li>
          <a href="{{URL::to('/')}}/">
            {{ trans('messages.header.home') }}
          </a>
        </li>
        <li>
          <a href="{{URL::to('/')}}/dashboard">
            {{ trans('messages.header.dashboard') }}
          </a>
        </li>
        <li>
          <a href="{{URL::to('/')}}/users/edit">
            {{ trans('messages.header.profile') }}
          </a>
        </li>
      </ul>
    </div>
  </div>
</main>
@stop

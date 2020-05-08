@extends('template')
@section('main')
<main id="site-content" role="main">
  @include('common.subheader')
  <div class="security-content my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-3 side-nav">
          @include('common.sidenav')
        </div>
        <div class="col-md-8 col-lg-9 mt-4 mt-md-0">
          {!! Form::open(['url' => url('change_password'), 'class' => (Auth::user()->password) ? 'show' : 'hide']) !!}
          <div id="change_your_password" class="card">
            <div class="card-header">
              <h3>
                {{ trans('messages.account.change_your_pwd') }}
              </h3>
            </div>
            <div class="card-body">
              <input id="id" name="id" type="hidden" value="33661974">
              <input id="redirect_on_error" name="redirect_on_error" type="hidden" value="/users/security?id=33661974">
              <input id="user_password_ok" name="user[password_ok]" type="hidden" value="true">
              @if(Auth::user()->password!='')
                <div class="d-md-flex col-lg-8 p-0">
                  <div class="col-md-5 p-0 text-md-right">
                    <label for="old_password">
                      {{ trans('messages.account.old_pwd') }}
                    </label>
                  </div>
                  <div class="col-md-7 mt-2 p-0 pl-md-4 mt-md-0">
                    <input class="input-block" id="old_password" name="old_password" type="password">
                    @if ($errors->has('old_password')) 
                    <p class="help-block text-danger">
                      {{ $errors->first('old_password') }}
                    </p> 
                    @endif
                  </div>
                </div>
              @endif

              <div class="d-md-flex col-lg-8 mt-3 p-0">
                <div class="col-md-5 p-0 text-md-right">
                  <label for="user_password">
                    {{ trans('messages.login.new_pwd') }}
                  </label>
                </div>
                <div class="col-md-7 mt-2 p-0 pl-md-4 mt-md-0">
                  <input class="input-block" data-hook="new_password" id="new_password" name="new_password" size="30" type="password">
                  @if ($errors->has('new_password')) 
                  <p class="help-block text-danger">
                    {{ $errors->first('new_password') }}
                  </p> 
                  @endif
                </div>
              </div>

              <div class="d-md-flex col-lg-8 mt-3 p-0">
                <div class="col-md-5 p-0 text-md-right">
                  <label for="user_password_confirmation">
                    {{ trans('messages.login.confirm_pwd') }}
                  </label>
                </div>
                <div class="col-md-7 mt-2 p-0 pl-md-4 mt-md-0">
                  <input class="input-block" id="user_password_confirmation" name="password_confirmation" size="30" type="password">
                  @if ($errors->has('password_confirmation')) 
                  <p class="help-block text-danger">
                    {{ $errors->first('password_confirmation') }}
                  </p> 
                  @endif
                </div>
              </div>
              <div class="col-lg-5 password-strength" data-hook="password-strength"></div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                {{ trans('messages.account.update_pwd') }}
              </button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</main>
@stop

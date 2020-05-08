@extends('template')
@section('main')
<main id="site-content" role="main">
    <div class="alert alert-with-icon alert-error alert-header panel-header hidden-element notice" id="notice">
        <i class="icon alert-icon icon-alert-alt"></i>
        @lang('messages.login.provide_miss_info')
    </div>
    <div class="container py-4 py-md-5">
        <div class="log-page p-4">
            <div class="log-form">
                {!! Form::open(['action' => 'UserController@finish_signup_email', 'class' => 'signup-form', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}

                <div class="signup-form-fields">
                    {!! Form::hidden('from', 'email_signup', ['id' => 'from']) !!}
                    {!! Form::hidden('auth_type', $auth_type, ['id' => 'auth_type']) !!}
                    {!! Form::hidden('auth_id', $user['auth_id'], ['id' => 'auth_id']) !!}

                    <div class="control-group row-space-1" id="inputFirst">
                        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                        {!! Form::text('first_name', $user['first_name'] ?? '', ['class' =>  $errors->has('first_name') ? 'decorative-input invalid ' : 'decorative-input name-icon', 'placeholder' => trans('messages.login.first_name')]) !!}
                    </div>
                    <div class="control-group row-space-1" id="inputLast">
                        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                        {!! Form::text('last_name', $user['last_name'] ?? '', ['class' => $errors->has('last_name') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon', 'placeholder' => trans('messages.login.last_name')]) !!}
                    </div>
                    <div class="control-group" id="inputEmail">
                        @if ($errors->has('email'))
                        <p class="error-msg">
                            {{ $errors->first('email') }}
                        </p>
                        @endif
                        <div class="d-flex align-items-center justify-content-center">
                            {!! Form::email('email', $user['email'] ?? '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-mail name-icon input_new', 'placeholder' => trans('messages.login.email_address')]) !!}
                            <i class="icon icon-envelope"></i>
                        </div>
                    </div>
                    <div class="control-group mt-3">
                        <h5> {{ trans('messages.login.birthday') }} </h5>
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
                    <p class="text-center mt-3"> @lang('messages.login.info_from') {{ ucfirst($auth_type) }} </p>
                    {!! Form::submit(trans('messages.login.finish_signup'), ['class' => 'my-3 d-flex justify-content-center btn btn-primary',])  !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>    
</main>
@endsection
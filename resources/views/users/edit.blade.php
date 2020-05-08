@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="edit_profile">      
  @include('common.subheader')
  <div class="edit-profile my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 side-nav">
          @include('common.sidenav')
          <a href="{{ url('users/show/'.Auth::user()->id) }}" class="btn btn-primary">
            {{ trans('messages.dashboard.view_profile') }}
          </a>
        </div>
        <div class="col-md-9 mt-4 mt-md-0 edit-profile-wrap" id="dashboard-content">
          {!! Form::open(['url' => url('users/update/'.Auth::user()->id), 'id' => 'update_form']) !!}
          <div class="card">
            <div class="card-header">
              <h3>
                {{ trans('messages.profile.required') }}
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_first_name">
                  {{ trans('messages.profile.first_name') }} 
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('first_name', Auth::user()->first_name, ['id' => 'user_first_name', 'size' => '30', 'class' => 'focus']) !!}
                  <span class="error-msg">
                    {{ $errors->first('first_name') }}
                  </span>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_last_name">
                  {{ trans('messages.profile.last_name') }}
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('last_name', Auth::user()->last_name, ['id' => 'user_last_name', 'size' => '30', 'class' => 'focus']) !!}
                  <span class="error-msg">
                    {{ $errors->first('last_name') }}
                  </span>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.last_name_never_share', ['site_name'=>$site_name]) }}
                  </div>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_gender">
                  {{ trans('messages.profile.i_am') }} 
                  <i class="icon icon-lock theme-color" data-behavior="tooltip" aria-label="Private"></i>
                </label>
                <div class="col-md-8 col-lg-9">
                  <div class="select">
                    {!! Form::select('gender', ['Male' => trans('messages.profile.male'), 'Female' => trans('messages.profile.female'), 'Other' => trans('messages.profile.other')], Auth::user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus']) !!}
                  </div>
                  <span class="error-msg">
                    {{ $errors->first('gender') }}
                  </span>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.gender_never_share') }}
                  </div>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_birthdate">
                  {{ trans('messages.profile.birth_date') }} 
                  <i class="icon icon-lock theme-color" data-behavior="tooltip" aria-label="Private"></i>
                </label>
                <div class="col-md-8 col-lg-9">
                  <div class="row">
                    <div class="col-12 col-md-4 pr-md-0">
                      <div class="select">
                        {!! Form::selectMonthWithDefault('birthday_month', (int)$dob[1], trans('messages.header.month'), ['id' => 'edit_user_birthday_month', 'class' => 'focus']) !!}
                      </div>
                    </div>
                    <div class="col-12 col-md-4 pr-md-0 mt-3 mt-md-0">
                      <div class="select">
                        {!! Form::selectRangeWithDefault('birthday_day', 1, 31, (int)$dob[2], trans('messages.header.day'), ['id' => 'edit_user_birthday_day', 'class' => 'focus']) !!}
                      </div>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                      <div class="select">
                        {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, $dob[0], trans('messages.header.year'), ['id' => 'edit_user_birthday_year', 'class' => 'focus']) !!}
                      </div>
                    </div>
                  </div>
                  <span class="error-msg">
                    @if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) {{ $errors->has('birthday_day') ? $errors->first('birthday_day') : ( $errors->has('birthday_month') ? $errors->first('birthday_month') : $errors->first('birthday_year') ) }} @endif
                  </span>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.birth_date_never_share') }}
                  </div>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_email">
                  {{ trans('messages.dashboard.email_address') }} 
                  <i class="icon icon-lock theme-color" data-behavior="tooltip" aria-label="Private"></i>
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('email', Auth::user()->email, ['id' => 'user_email', 'size' => '30', 'class' => 'focus']) !!}
                  <span class="error-msg">
                    {{ $errors->first('email') }}
                  </span>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.email_never_share', ['site_name'=>$site_name]) }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card my-4">
            <div class="card-header">
              <h3>
                {{ trans('messages.profile.optional') }}
              </h3>
            </div>
            <div class="card-body">
              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right">
                  @lang('messages.profile.phone_number')
                  <i class="icon icon-lock theme-color" data-behavior="tooltip" aria-label="Private"></i>
                </label>
                <div class="col-md-8 col-lg-9" id="phone_numbers_wrapper" ng-cloak>
                  <input type="hidden" ng-model="default_phone_code" ng-init="default_phone_code ='{{$country_phone_codes[0]->phone_code}}'" />
                  <div class="phone-number-block" ng-repeat="phone_number in users_phone_numbers">
                    <div class="phone-status-block d-flex" ng-show="phone_number.status == 'Confirmed'">
                      <input type="text" name="phone" readonly value="@{{phone_number.phone_number_full}}">
                      <label class="d-flex align-items-center" id="phone_number_status">
                        <span class="confirm-tick mr-2">
                          <i class="icon icon-ok" aria-hidden="true"></i>
                        </span>
                        @{{phone_number.phone_number_status_message}} 
                        <a class="icon icon-remove ml-auto" id="remove_phone_number" ng-click="remove_phone_number($index)" href="javascript:void(0);"></a>
                      </label>
                    </div>

                    <div class="phone-number-verify-widget" ng-show="phone_number.status == 'Null'">
                      <div class="card card-body pb-1 pnaw-step1">
                        <div class="phone-number-input-widget" id="phone-number-input-widget-c5c92311">
                          <label class="mt-0" for="phone_country">
                            @lang('messages.profile.choose_a_country'):
                          </label>
                          <div class="select">
                            <select id="phone_country" name="phone_country" ng-model="phone_code_val[$index]" ng-init="phone_code_val[$index] = default_phone_code">
                              @foreach($country_phone_codes as $k => $country)
                              <option value="{{$country->phone_code}}">
                                {{$country->long_name}}
                              </option>
                              @endforeach
                            </select>
                          </div>
                          <label for="phone_number" class="mt-3">
                            @lang('messages.profile.add_a_phone_number'):
                          </label>
                          <div class="pniw-number-container d-flex border">
                            <div class="pniw-number-prefix border-right p-2 text-nowrap d-flex align-items-center">
                              <span class="h5 font-weight-normal mb-0 mr-1">
                                + 
                              </span>
                              <span>
                                @{{phone_code_val[$index]}}
                              </span>
                            </div>
                            <input type="tel" ng-model="phone_number_val[$index]" class="pniw-number border-0" id="phone_number">
                          </div>
                        </div>
                        <div class="pnaw-verify-container mt-3">
                          <a class="btn btn-primary sms-btn" ng-click="update_phone_number($index)" href="javascript:void(0);" rel="sms">
                            @lang('messages.profile.verify_via_sms')
                          </a>
                        </div>
                        <p class="error-msg my-2">
                          @{{phone_number.phone_number_error}}
                        </p>
                      </div>
                    </div>

                    <div class="phone-number-verify-widget verify" ng-show="phone_number.status == 'Pending'">
                      <div class="pnaw-step2" style="display: block;">
                        <p class="message">
                          @lang('messages.profile.we_sent_verification')
                          <strong>
                            @{{phone_number.phone_number_full}}
                          </strong>
                        </p>
                        <div class="otp-wrap d-md-flex align-items-center">
                          <label for="phone_number_verification">
                            @lang('messages.profile.please_enter_ver_code'):
                          </label>
                          <input type="text" pattern="[0-9]*" ng-model="otp_val[$index]" id="phone_number_verification" class="ml-md-3">
                        </div>
                        <div class="pnaw-verify-container mt-3">
                          <a class="btn btn-primary mr-2" href="javascript:void(0);" ng-click="verify_phone_number($index)" rel="verify">
                            @lang('messages.profile.verify')
                          </a>
                          <a class="cancel" rel="cancel" href="javascript:void(0);" ng-click="remove_phone_number($index)">
                            @lang('messages.profile.cancel')
                          </a>
                          <a class="btn btn-primary ml-2 float-right" href="javascript:void(0);" ng-click="resend_verification_code($index)" ng-show="showResendBtn[$index]">
                            @lang('messages.profile.resend')
                          </a>
                        </div>
                        <p class="cancel-message error-msg">
                          @{{phone_number.otp_error}}
                        </p>
                        <p class="error-msg my-2">
                          @{{phone_number.phone_number_error}}
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="phone-number-add-block">
                    <a class="normal-link d-flex align-items-center" ng-click="add_phone_number()"> 
                      <span class="mr-2 h4 mb-0">+</span>
                      <span class="mt-1 cursor-pointer theme-color">
                        @lang('messages.profile.add_phone_number')
                      </span>
                    </a>
                  </div>
                  <div class="mt-1 input-info">
                    {{ trans('messages.profile.phone_nuber_share_text',['site_name'=>$site_name]) }} 
                  </div>
                </div>
              </div> 

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_live">
                  {{ trans('messages.profile.where_you_live') }}
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('live', Auth::user()->live, ['id' => 'user_live', 'placeholder' => 'e.g. Paris, FR / Brooklyn, NY / Chicago, IL', 'size' => '30', 'class' => 'focus']) !!}
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_about">
                  {{ trans('messages.profile.describe_yourself') }}
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::textarea('about', Auth::user()->about, ['id' => 'user_about', 'cols' => '40', 'rows' => '5', 'class' => 'focus']) !!}
                  <div class="my-2 input-info">
                    <p>
                      {{ trans('messages.profile.about_desc1', ['site_name'=>$site_name]) }}
                    </p>
                    <p>
                      {{ trans('messages.profile.about_desc2') }}
                    </p>
                    <p>
                      {{ trans('messages.profile.about_desc3', ['site_name'=>$site_name]) }}
                    </p>
                    <p>
                      {{ trans('messages.profile.about_desc4') }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_profile_info_university">
                  {{ trans('messages.profile.school') }} 
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('school', Auth::user()->school, ['id' => 'user_profile_info_university', 'size' => '30', 'class' => 'focus']) !!}
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_profile_info_employer">
                  {{ trans('messages.profile.work') }} 
                </label>
                <div class="col-md-8 col-lg-9">
                  {!! Form::text('work', Auth::user()->work, ['id' => 'user_profile_info_employer', 'size' => '30', 'placeholder' => 'e.g. Airbnb / Apple / Taco Stand', 'class' => 'focus']) !!}
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_time_zone">
                  {{ trans('messages.profile.timezone') }} 
                </label>
                <div class="col-md-8 col-lg-9">
                  <div class="select">
                    {!! Form::select('timezone', $timezones, @$time_zone, ['id' => 'user_time_zone', 'class' => 'focus']) !!}
                  </div>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.timezone_desc') }}
                  </div>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_time_zone">
                  @lang('messages.profile.languages')
                </label>
                <div class="col-md-8 col-lg-9">
                <!--   <p class="d-none"> 
                    @lang('messages.account.none')
                  </p> -->
                  <span id="selected_language" class="multiselect-option d-block">
                    @if($known_languages)
                    @php $i=0 @endphp
                    @foreach($known_languages_name as $value)
                    @if($value == null)
                 <!--    <p> 
                      @lang('messages.account.none')
                    </p> -->
                    @else
                    <span class="btn my-2">
                      {{ $value }} 
                      <a href="javascript:void(0)" class="ml-2 profile-lang-remove" id="remove_language">
                        <i class="icon icon-remove" title="Remove from selection"></i>
                        <input type="hidden" value="{{ $known_languages[$i] }}" name="language[]"> 
                      </a>
                    </span>
                    @endif
                    @php $i++ @endphp
                    @endforeach
                    @endif
                  </span>
                  <div class="d-flex align-items-center">
                    <span class="d-inline-block mr-2 mb-0 h4"> + </span>
                    <a class="language-link mt-1 theme-link" href="javascript:void(0);" data-toggle="modal" data-target="#language-modal">
                      @lang('messages.profile.add_more')
                    </a>
                  </div>
                  <div class="mt-2 input-info">
                    @lang('messages.profile.desc_msg', ['site_name' => $site_name])
                  </div>
                </div>
              </div>

              <div class="row mt-3 mt-md-4">
                <label class="col-md-4 col-lg-3 text-md-right" for="user_time_zone">
                  {{ trans('messages.profile.email_language') }} 
                </label>
                <div class="col-md-8 col-lg-9">
                  <div class="select">
                    {!! Form::select('user_email_language', @$email_languages, @$email_default_language, ['id' => 'user_email_language', 'class' => 'focus']) !!}
                  </div>
                  <div class="mt-2 input-info">
                    {{ trans('messages.profile.email_language_desc') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">
            {{ trans('messages.profile.save') }}
          </button>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</main>

<div class="add-language-popup modal" id="language-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>
          @lang('messages.profile.spoken_languages')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-wrap">
        @foreach($languages as $lan)
        <div class="d-flex col-md-6 p-0 align-items-center my-2"> 
          <input class="language_select mr-2" type="checkbox" value="{{ $lan->id  }}" data-name="{{ $lan->name }}" id="{{ $lan->id }}" name="language" {{ in_array($lan->id, $known_languages) ? 'checked' : '' }}>
          <label class="m-0" for="{{ $lan->id }}"> 
            {{ $lan->name }}
          </label>
        </div> 
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal">
          @lang('messages.your_reservations.cancel')
        </button>
        <button id="language_save_button" class="btn btn-primary" type="submit" data-dismiss="modal">
          @lang('messages.profile.save')
        </button>
      </div>
    </div>
  </div>
</div>
@stop

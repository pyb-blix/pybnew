@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="payout_preferences">
  @include('common.subheader')
  <div class="payout-content my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-3 side-nav transaction_history_page">
          @include('common.sidenav')
        </div>
        <div class="col-md-8 col-lg-9 mt-4 mt-md-0">
          <div id="payout_setup" ng-init="payout_delete_url = '{{ url('/') }}/users/payout_delete/';payout_default_url = '{{ url('/') }}/users/payout_default/'">
            <div class="card">
              <div class="card-header">
                <h3>
                  {{ trans('messages.account.payout_methods') }}
                </h3>
              </div>
              <div class="card-body payout-card pb-0" id="payout_intro">
                <p>
                  {{ trans('messages.account.payout_methods_desc') }}.
                </p>
                <div class="table-responsive">
                  <table class="table table-striped" id="payout_methods">
                    @if($payouts->count())
                    <thead>
                      <tr class="text-truncate">
                        <th>{{ trans('messages.account.method') }}</th>
                        <th>{{ trans('messages.your_reservations.details') }}</th>
                        <th>{{ trans('messages.your_reservations.status') }}</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($payouts as $row)
                      <tr>
                        <td>
                          {{$row->payout_method}}
                          @if($row->default == 'yes')
                          <span class="label label-info">
                            {{ trans('messages.account.default') }}
                          </span>
                          @endif
                        </td>
                        <td>
                          {{ $row->paypal_email }}  
                          <span>
                            ({{ $row->currency_code }})
                          </span>
                        </td>
                        <td>
                          {{ trans('messages.account.ready') }}
                        </td>
                        <td class="payout-options">
                          @if($row->default != 'yes')
                          <ul class="text-right">
                            <li class="dropdown">
                              <a href="javascript:void(0);" data-toggle="dropdown" class="text-truncate dropdown-toggle d-block" id="payout-options-{{ $row->id }} dropdownMenuButton">
                                {{ trans('messages.your_trips.options') }}
                              </a>
                              <ul data-trigger="#payout-options-{{ $row->id }}" class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a href="javascript:void(0)" rel="nofollow" data-method="post" class="payout_options menu-item" ng-click="disablePayoutOption($event,payout_delete_url,{{ $row->id }})">
                                    @lang('messages.account.remove')
                                  </a>
                                </li>
                                <li>
                                  <a href="javascript:void(0)" rel="nofollow" data-method="post" class="payout_options menu-item" ng-click="disablePayoutOption($event,payout_default_url,{{ $row->id }})">
                                    @lang('messages.account.set_default')
                                  </a>
                                </li>
                              </ul>
                            </li>
                          </ul>
                          @endif        
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    @endif
                    <tfoot>
                      <tr id="add_payout_method_section">
                        <td colspan="4" class="p-0 pt-3">
                          <a id="add-payout-method-button" class="btn btn-primary pop-striped" href="javascript:void(0);" data-toggle="modal" data-target="#payout-preference-popup1">
                            {{ trans('messages.account.add_payout_method') }}
                          </a>
                          <span class="ml-md-2 d-block d-md-inline-block mt-2 mt-md-0">
                            {{ trans('messages.account.direct_deposit') }}, 
                            <span>PayPal, </span>
                            <span>etc...</span>
                          </span>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            <div style="display:none;" class="add_payout_section" id="payout_new_select"></div>
            <div style="display:none;" class="add_payout_section" id="payout_edit"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="payout-preference-popup1" aria-hidden="false" style="" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ trans('messages.account.add_payout_method') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="flash-container" id="popup1_flash-container"></div>
      <form class="modal-add-payout-pref" method="post" id="address">
        {!! Form::token() !!}
        <div class="modal-body">
          <div class="form-group">
            <label for="payout_info_payout_country">
              @lang('messages.account.country')
              <span class="error-msg">*</span>
            </label>
            <div class="select">
              {!! Form::select('country_dropdown', $country,  old('country') ? old('country') : $default_country, ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country']) !!}
            </div>
            <span class="country_error text-danger d-none"> @lang('messages.account.blank_country') </span>
          </div>
          <label ng-show="payout_country=='JP'">
            <b>Address Kana:</b>
          </label>
          <div class="form-group">
            <label for="payout_info_payout_address1">
              @lang('messages.account.address')
              <span class="error-msg">*</span>
            </label>        
            {!! Form::text('address1', '', ['id' => 'payout_info_payout_address1','autocomplete'=>"billing address-line1"]) !!}
            <span class="address1_error text-danger d-none"> @lang('messages.account.blank_address') </span>
          </div>
          <div class="form-group">
            <label for="payout_info_payout_address2">
              {{ trans('messages.account.address') }} 2 / {{ trans('messages.account.zone') }}
            </label>        
            {!! Form::text('address2', '', ['id' => 'payout_info_payout_address2','autocomplete'=>"billing address-line2"]) !!}
            <span class="text-danger"> {{ $errors->first('address2') }} </span>
          </div>
          <div class="form-group">
            <label for="payout_info_payout_city">
              {{ trans('messages.account.city') }} 
              <span class="error-msg">*</span>
            </label>        
            {!! Form::text('city', '', ['id' => 'payout_info_payout_city','autocomplete'=>"billing address-level2"]) !!}
            <span class="city_error text-danger d-none"> @lang('messages.account.blank_city') </span>
          </div>
          <div class="form-group">
            <label for="payout_info_payout_state">
              {{ trans('messages.account.state') }} / {{  trans('messages.account.province') }}
            </label>        
            <input type="text" autocomplete="billing address-level1" value="{{ old('state') ? old('state') : ''}}" id="payout_info_payout_state" name="state">
            <span class="state_error text-danger d-none"> @lang('messages.account.blank_state') </span>
          </div>
          <div class="form-group">
            <label for="payout_info_payout_zip">
              {{ trans('messages.account.postal_code') }} 
              <span class="error-msg">*</span>
            </label>
            {!! Form::text('postal_code', '', ['id' => 'payout_info_payout_zip','autocomplete'=>"billing postal-code"]) !!}
            <span class="postal_error text-danger d-none"> @lang('messages.account.blank_post') </span>
          </div>
        </div>
        <div class="modal-footer">
          <input type="submit" value="{{ trans('messages.account.next') }}" class="btn btn-primary w-auto">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="payout-preference-popup2" aria-hidden="false" style="" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ trans('messages.account.add_payout_method') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="modal-add-payout-pref" id="country_options" accept-charset="UTF-8">
        {!! Form::token() !!}
        <input type="hidden" id="payout_info_payout2_address1" value="" name="address1">
        <input type="hidden" id="payout_info_payout2_address2" value="" name="address2">
        <input type="hidden" id="payout_info_payout2_city" value="" name="city">
        <input type="hidden" id="payout_info_payout2_country" value="" name="country">
        <input type="hidden" id="payout_info_payout2_state" value="" name="state">
        <input type="hidden" id="payout_info_payout2_zip" value="" name="postal_code">
        <div class="modal-body">
          <div>
            <p>{{ trans('messages.account.payout_released_desc1') }}</p>
            <p>{{ trans('messages.account.payout_released_desc2') }}</b> 
              {{ trans('messages.account.payout_released_desc3') }}
            </p>
          </div>
          <table id="payout_method_descriptions" class="table table-striped">
            <thead>
              <tr>
                <th></th>
                <th>{{ trans('messages.account.payout_method') }}</th>
                <th>{{ trans('messages.account.processing_time') }}</th>
                <th>{{ trans('messages.account.additional_fees') }}</th>
                <th>{{ trans('messages.account.currency') }}</th>
                <th>{{ trans('messages.your_reservations.details') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input type="radio" {{ old('payout_method') =='PayPal' ? 'checked' : ''}} value="PayPal" name="payout_method" ng-model="payout_method">
                </td>
                <td class="type">
                  <label for="payout_method">PayPal</label>
                </td>
                <td>3-5 {{ trans('messages.account.business_days') }}</td>
                <td>{{ trans('messages.account.none') }}</td>
                <td>{{ PAYPAL_CURRENCY_CODE }}</td>
                <td>{{ trans('messages.account.business_day_processing') }}</td>
              </tr>
              <tr>
                <td>
                  <input type="radio" {{ old('payout_method') =='Stripe' ? 'checked' : ''}} value="Stripe" name="payout_method" ng-model="payout_method">
                </td>
                <td class="type">
                  <label for="payout_method">Stripe</label>
                </td>
                <td>5-7 {{ trans('messages.account.business_days') }}</td>
                <td>{{ trans('messages.account.none') }}</td>
                <td>{{ PAYPAL_CURRENCY_CODE }}</td>
                <td>{{ trans('messages.account.business_day_processing') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <input type="submit" value="{{ trans('messages.account.next') }}" id="select-payout-method-submit" class="btn btn-primary w-auto" ng-val="@{{payout_method}}" ng-disabled="payout_method != 'PayPal' && payout_method != 'Stripe'">
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="payout-prefernce-popup3" aria-hidden="false" style="" tabindex="-1">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
       {{ trans('messages.account.add_payout_method') }}
     </h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
   </div>
   <form method="post" id="payout_paypal" action="{{ url('users/payout_preferences/'.Auth::user()->id) }}" accept-charset="UTF-8">
    {!! Form::token() !!}
    <input type="hidden" id="payout_info_payout3_address1" value="" name="address1">
    <input type="hidden" id="payout_info_payout3_address2" value="" name="address2">
    <input type="hidden" id="payout_info_payout3_city" value="" name="city">
    <input type="hidden" id="payout_info_payout3_country" value="" name="country">
    <input type="hidden" id="payout_info_payout3_state" value="" name="state">
    <input type="hidden" id="payout_info_payout3_zip" value="" name="postal_code">
    <input type="hidden" id="payout3_method" value="" name="payout_method" ng-model="payout_method">
    <div class="modal-body">
      PayPal {{ trans('messages.account.email_id') }}
      <input type="text" name="paypal_email" id="paypal_email" class="mt-2">
      <span class="paypal_email_error text-danger d-none"> @lang('messages.account.valid_email') </span>
    </div>
    <div class="modal-footer payout_footer">
      <input type="submit" value="{{ trans('messages.account.submit') }}" id="modal-paypal-submit" class="btn btn-primary w-auto">
    </div>
  </form>
</div>
</div>
</div>
</div>

<!-- Popup for get Stripe datas -->
<div class="modal fade" id="payout_popupstripe" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ trans('messages.account.add_payout_method') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="flash-container" id="popup4_flash-container"></div>
      <form method="post" id="payout_stripe" action="{{ url('users/update_payout_preferences/'.Auth::user()->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {!! Form::token() !!}
        <input type="hidden" id="payout_info_payout4_address1" value="" name="address1">
        <input type="hidden" id="payout_info_payout4_address2" value="" name="address2">
        <input type="hidden" id="payout_info_payout4_city" value="" name="city">
        <input type="hidden" id="payout_info_payout4_country" value="" name="country">
        <input type="hidden" id="payout_info_payout4_state" value="" name="state">
        <input type="hidden" id="payout_info_payout4_zip" value="" name="postal_code">
        <input type="hidden" id="payout4_method" value="" name="payout_method" ng-model="payout_method">

        <div class="modal-body" ng-init="payout_country={{json_encode(old('country') ?: '')}};payout_currency={{json_encode(old('currency') ?: '')}};iban_supported_countries = {{json_encode($iban_supported_countries)}};branch_code_required={{json_encode($branch_code_required)}};country_currency={{json_encode($country_currency)}};change_currency();mandatory={{ json_encode($mandatory)}};old_currency='{{ old('currency') ? json_encode(old('currency')) : '' }}'">

          <div class="form-group">
            <label for="payout_info_payout_country1">
              {{ trans('messages.account.country') }} 
              <span class="error-msg">*</span>
            </label>
            <div class="select">
              {!! Form::select('country', $country_list, $default_country, ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country1','placeholder'=>'Select','ng-model'=>'payout_country']) !!}
            </div>
          </div>
          <div class="form-group">
            <label for="payout_info_payout_currency">
              {{ trans('messages.account.currency') }} 
              <span class="error-msg">*</span>
            </label>
            <div class="select">
              {!! Form::select('currency', $currency, $default_currency, ['autocomplete' => 'billing currency', 'id' => 'payout_info_payout_currency','style'=>'min-width:140px;','ng-model'=>'payout_currency','placeholder'=>'Select']) !!}                   
              <p class="text-danger">
                {{$errors->first('currency')}}
              </p>
            </div>
          </div>

          <!-- Bank Name -->
          <div ng-show="mandatory[payout_country][3]">
            <label class="" for="bank_name">
              @{{mandatory[payout_country][3]}}
              <span class="error-msg">*</span>
            </label>
            {!! Form::text('bank_name', '', ['id' => 'bank_name', 'class' => 'form-control']) !!}

            <p class="text-danger">
              {{$errors->first('bank_name')}}
            </p>
          </div>
          <!-- Bank Name -->
          <!-- Branch Name -->
          <div ng-show="mandatory[payout_country][4]">
            <label class="" for="branch_name">
              @{{mandatory[payout_country][4]}}
              <span class="error-msg">*</span>
            </label>
            {!! Form::text('branch_name', '', ['id' => 'branch_name', 'class' => 'form-control']) !!}
            <p class="text-danger">
              {{$errors->first('branch_name')}}
            </p>
          </div>
          <!-- Branch Name -->
          <!-- Routing number  -->

          <div ng-if="payout_country" class="routing_number_cls" ng-hide="iban_supported_countries.includes(payout_country)">
            <label class="" for="routing_number">
              @{{mandatory[payout_country][0]}}
              <span class="error-msg">*</span>
            </label>
            {!! Form::text('routing_number', @$payout_preference->routing_number, ['id' => 'routing_number', 'class' => 'form-control']) !!}
            <p class="text-danger">
              {{$errors->first('routing_number')}}
            </p>
          </div>
          <!-- Routing number -->

          <!-- Branch code -->
          <div ng-show="mandatory[payout_country][2]"> 
            <label for="branch_code">
              @{{mandatory[payout_country][2]}}
              <span class="error-msg">*</span>
            </label>

            {!! Form::text('branch_code', '', ['id' => 'branch_code', 'class' => 'form-control','maxlength'=>'3']) !!}

            <p class="text-danger">
              {{$errors->first('branch_code')}}
            </p>
          </div>
          <!-- Branch code -->

          <!-- Account Number -->
          <div ng-if="payout_country">
            <label class="" for="account_number" ng-hide="iban_supported_countries.includes(payout_country)">
              <span class="account_number_cls">
                @{{mandatory[payout_country][1]}}
              </span>
              <span class="error-msg">*</span>
            </label>
            <label class="" for="account_number" ng-show="iban_supported_countries.includes(payout_country)">{{ trans('messages.account.iban_number') }}
              <span class="error-msg">*</span>
            </label>

            {!! Form::text('account_number', '', ['id' => 'account_number', 'class' => 'form-control']) !!}

            <p class="text-danger">
              {{$errors->first('account_number')}}
            </p>
          </div>
          <!-- Account Number -->

          <!-- Account Holder name -->
          <div class="form-group">
            <label ng-if="payout_country == 'JP'" for="holder_name">
              @{{mandatory[payout_country][5]}}
              <span class="error-msg">*</span>
            </label>          
            <label ng-if="payout_country != 'JP'" for="holder_name">
              {{ trans('messages.account.holder_name') }}
              <span class="error-msg">*</span>
            </label>          
            {!! Form::text('holder_name', '', ['id' => 'holder_name', 'class' => 'form-control']) !!}
            <p class="text-danger">
              {{$errors->first('holder_name')}}
            </p>
          </div>
          <!-- Account Holder name -->

          <!-- SSN Last 4 only for US -->
          <div ng-show="payout_country == 'US'">
            <label ng-if="payout_country == 'US'" for="ssn_last_4">
              {{ trans('messages.account.ssn_last_4') }}
              <span class="error-msg">*</span>
            </label>          
            {!! Form::text('ssn_last_4', '', ['id' => 'ssn_last_4', 'class' => 'form-control','maxlength'=>'4']) !!}
            <p class="text-danger">
              {{$errors->first('ssn_last_4')}}
            </p>
          </div>
          <!-- SSN Last 4 only for US -->

          <!-- Phone number only for Japan -->
          <div>
            <label class="" for="phone_number">
              {{ trans('messages.profile.phone_number') }}
              <span class="error-msg">*</span>
            </label>

            {!! Form::text('phone_number', '', ['id' => 'phone_number', 'class' => 'form-control']) !!}

            <p class="text-danger">
              {{$errors->first('phone_number')}}
            </p>
          </div>
          <!-- Phone number only for Japan -->
          <input type="hidden" id="is_iban" name="is_iban" ng-value="iban_supported_countries.includes(payout_country) ? 'Yes' : 'No'">
          <input type="hidden" id="is_branch_code" name="is_branch_code" ng-value="branch_code_required.includes(payout_country) ? 'Yes' : 'No'">
          <!-- Gender only for Japan -->
          @if(!Auth::user()->gender)
          <div ng-if="payout_country == 'JP'" class="col-md-6 col-sm-12 p-0 select-cls row-space-3">
            <label for="user_gender">
              {{ trans('messages.profile.gender') }}
            </label>
            <div class="select">
              {!! Form::select('gender', ['male' => trans('messages.profile.male'), 'female' => trans('messages.profile.female')], Auth::user()->gender, ['id' => 'user_gender', 'placeholder' => trans('messages.profile.gender'), 'class' => 'focus','style'=>'min-width:140px;']) !!}
              <span class="text-danger">{{ $errors->first('gender') }}</span>    
            </div>
          </div>
          @endif
          <!-- Gender only for Japan -->

          <!-- Address Kanji Only for Japan -->
          <div ng-class="(payout_country == 'JP'? 'jp_form row':'')">       
            <div ng-if="payout_country == 'JP'" class="col-12">
              <label>
                <b>Address Kanji:</b>
              </label>
              <div>
                <label for="payout_info_payout_address2">
                  {{ trans('messages.account.address') }} 1
                  <span class="error-msg">*</span>
                </label>
                {!! Form::text('kanji_address1', '', ['id' => 'kanji_address1', 'class' => 'form-control']) !!}
                <p class="text-danger">
                  {{$errors->first('kanji_address1')}}
                </p>
              </div>

              <div>
                <label for="payout_info_payout_address2">
                  Town
                  <span class="error-msg">*</span>
                </label>
                {!! Form::text('kanji_address2', '', ['id' => 'kanji_address2', 'class' => 'form-control']) !!}
                <p class="text-danger">
                  {{$errors->first('kanji_address2')}}
                </p>
              </div>

              <div>
                <label for="payout_info_payout_city">
                  {{ trans('messages.account.city') }} 
                  <span class="error-msg">*</span>
                </label>
                {!! Form::text('kanji_city', '', ['id' => 'kanji_city', 'class' => 'form-control']) !!}
                <p class="text-danger">
                  {{$errors->first('kanji_city')}}
                </p>
              </div>

              <div>
                <label for="payout_info_payout_state">
                  {{ trans('messages.account.state') }} / {{ trans('messages.account.province') }}
                  <span class="error-msg">*</span>
                </label>
                {!! Form::text('kanji_state', '', ['id' => 'kanji_state', 'class' => 'form-control']) !!}
                <p class="text-danger">
                  {{$errors->first('kanji_state')}}
                </p>
              </div>

              <div>
                <label for="payout_info_payout_zip">
                  {{ trans('messages.account.postal_code') }} 
                  <span class="error-msg">*</span>
                </label>
                {!! Form::text('kanji_postal_code', '', ['id' => 'kanji_postal_code', 'class' => 'form-control']) !!}
                <p class="text-danger">
                  {{$errors->first('kanji_postal_code')}}
                </p>
              </div>
            </div>
          </div>
          <!-- Address Kanji Only for Japan -->

          <!-- Legal document -->
          <div id="legal_document" class="legal_document">
            <div class="row">
              <label class="control-label required-label col-md-12 col-sm-12 row-space-2" for="document">@lang('messages.account.legal_document') @lang('messages.account.legal_document_format')
                <span class="error-msg">*</span>
              </label>
              <div class="col-md-12 col-sm-12">
                {!! Form::file('document', ['id' => 'document', 'class' => '',"accept"=>".jpg,.jpeg,.png"]) !!}
                <p class="text-danger">
                  {{$errors->first('document')}}
                </p>
              </div>  
            </div>                       
          </div>
          <!-- Legal document -->

          <input type="hidden" name="holder_type" value="individual" id="holder_type">
          <input type="hidden" name="stripe_token" id="stripe_token" >
          <p  class="text-danger col-sm-12" id="stripe_errors"></p>
        </div>
        <div class="modal-footer">
          <input type="submit" value="{{ trans('messages.account.submit') }}" id="modal-stripe-submit" class="btn btn-primary w-auto">
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-- end Popup -->
<input type="hidden" id="choose_method" value="{{trans('messages.account.choose_method')}}">
<input type="hidden" id="blank_holder_name" value="{{trans('messages.account.blank_holder_name')}}">
<input type="hidden" name="stripe_publish_key" id="stripe_publish_key" value="{{@$stripe_data[0]->value}}">
@foreach($payouts as $row)
<ul data-sticky="true" data-trigger="#payout-options-{{ $row->id }}" class="tooltip tooltip-top-left list-unstyled dropdown-menu" aria-hidden="true" role="tooltip" style="left: 1019.45px; top: 232.967px;">
  @if($row->default != 'yes')
  <li>
    <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url('/') }}/users/payout_delete/{{ $row->id }}">
      {{ trans('messages.account.remove') }}
    </a>
  </li>
  @endif
  <li>
    <a rel="nofollow" data-method="post" class="link-reset menu-item" href="{{ url('/') }}/users/payout_default/{{ $row->id }}">
      {{ trans('messages.account.set_default') }}
    </a>
  </li>
</ul>
@endforeach
@stop
@push('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  @if (count($errors) > 0)
  $('#payout_popupstripe').removeClass('hide').attr("aria-hidden", "false");
  @endif
</script>
@endpush
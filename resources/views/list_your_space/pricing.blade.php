<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="pricing-content manage-listing-content col-12 col-lg-7" id="js-manage-listing-content" ng-init="currency_symbol = '{{ html_string($result->rooms_price->currency->original_symbol) }}'">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.pricing_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.pricing_desc') }}
      </p>
    </div>
    <div id="help-panel-nightly-price" class="js-section">
      <div style="display: none;" class="js-saving-progress saving-progress base_price">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.base_price') }}
      </h4>

      <div class="pricing_field my-3">
        <label for="listing_price_native">
          {{ trans('messages.lys.nightly_price') }}
        </label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" ng-bind-html="currency_symbol"> </span>
          </div>
          <input type="number" min="0" limit-to=9 data-suggested="" id="price-night" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night" class="autosubmit-text form-control" data-saving="base_price" autocomplete="off">
          <input type="hidden" id="price-night-old" value="{{ ($result->rooms_price->original_night == 0) ? '' : $result->rooms_price->original_night }}" name="night_old" class="autosubmit-text form-control">
        </div>
      </div>
      <span data-error="price" class="error-msg"></span>

      <div class="pricing_field my-3">
        <label>
          {{ trans('messages.account.currency') }}
        </label>
        <div id="currency-picker" class="select">
          {!! Form::select('currency_code',$currency, $result->rooms_price->currency_code, ['id' => 'price-select-currency_code', 'data-saving' => 'base_price']) !!}
        </div>
      </div>
    </div>

    <div class="js-section mb-4">
      <div style="display: none;" class="js-saving-progress saving-progress additional-saving">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.additional_pricing') }}
      </h4>
    </div>

    <div id="js-cleaning-fee" class="js-tooltip-trigger mt-2" ng-init="cleaning_checkbox = {{ ($result->rooms_price->getOriginal('cleaning') == 0) ? 'false' : 'true' }}" ng-cloak>
      <label for="listing_cleaning_fee_native_checkbox">
        <input type="checkbox" data-extras="true" ng-model="cleaning_checkbox" id="listing_cleaning_fee_native_checkbox" ng-checked="{{ ($result->rooms_price->getOriginal('cleaning') == 0) ? 'false' : 'true' }}" class="mr-2" data-selector="price-cleaning">@lang('messages.lys.cleaning')
      </label>
      <div class="pricing_extra_amt" data-checkbox-id="listing_cleaning_fee_native_checkbox" ng-show="cleaning_checkbox">
        <div class="pricing_field_list mt-2 mb-3">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <span class="input-group-text" ng-bind-html="currency_symbol"> </span>
            </div>
            <input type="number" min="0" limit-to=9 data-extras="true" id="price-cleaning" value="{{ ($result->rooms_price->getOriginal('cleaning') == 0) ? '' : $result->rooms_price->getOriginal('cleaning') }}" name="cleaning" class="autosubmit-text form-control" data-saving="additional-saving" autocomplete="off">
          </div>
          <p> @lang('messages.lys.cleaning_desc') </p>
        </div>
      </div>
      <span data-error="extras_price" class="error-msg"></span>
    </div>
    <div id="js-additional-guests" class="mt-2 js-tooltip-trigger">
      <label class="d-inline-flex align-items-center" for="price_for_extra_person_checkbox">
        <input type="checkbox" data-extras="true" ng-model="extra_person_checkbox" id="price_for_extra_person_checkbox" ng-init="extra_person_checkbox = {{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_additional_guest == 0) ? 'false' : 'true' }}" class="mr-2" data-selector="price-extra_person" data-additional_selector="price-select-guests_included">
        {{ trans('messages.lys.additional_guests') }}
      </label>
      <div class="pricing_extra_amt" data-checkbox-id="price_for_extra_person_checkbox" ng-show="extra_person_checkbox" ng-cloak>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" ng-bind-html="currency_symbol"> </span>
          </div>
          <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_additional_guest == 0) ? '' : $result->rooms_price->original_additional_guest }}" id="price-extra_person" name="additional_guest" class="autosubmit-text form-control" data-saving="additional-saving" autocomplete="off">
        </div>

        <div class="pricing_field my-3">
          <label>
            {{ trans('messages.lys.for_each_guest_after') }}
          </label>
          <div class="mb-1" id="guests-included-select">
            <div class="select">
              <select id="price-select-guests_included" name="guests" data-saving="additional-saving">
                @for($i=1;$i
                <=16;$i++)
                <option value="{{ $i }}" {{ ($result->rooms_price->guests == $i) ? 'selected' : '' }}>
                  {{ ($i == '16') ? $i.'+' : $i }}
                </option>
                @endfor 
              </select>
            </div>
          </div>  
          <p>
            {{ trans('messages.lys.additional_guests_desc') }}
          </p>
        </div>
      </div>
      <span data-error="price_for_extra_person" class="error-msg"></span>
    </div>

    <div id="js-security-deposit" class="mt-2 js-tooltip-trigger">
      <label class="d-inline-flex align-items-center" for="security_deposit_checkbox">
        <input type="checkbox" data-extras="true" ng-model="security_checkbox" id="security_deposit_checkbox" ng-init="security_checkbox = {{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_security == 0) ? 'false' : 'true' }}" class="mr-2" data-selector="price-security">
        {{ trans('messages.lys.security_deposit') }}
      </label>
      <div class="pricing_extra_amt mb-3" data-checkbox-id="security_deposit_checkbox" ng-show="security_checkbox" ng-cloak>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <span class="input-group-text" ng-bind-html="currency_symbol"> </span>
          </div>
          <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_security == 0) ? '' : $result->rooms_price->original_security }}" id="price-security" name="security" class="autosubmit-text form-control" data-saving="additional-saving" autocomplete="off">
        </div>
        <p>
          {{ trans('messages.lys.security_deposit_desc') }}
        </p>
      </div>
    </div>
    <span data-error="security_deposit" class="error-msg"></span>

    <div id="js-weekend-pricing" class="mt-2 js-tooltip-trigger">
      <label class="d-inline-flex align-items-center" for="listing_weekend_price_native_checkbox">
        <input type="checkbox" data-extras="true" ng-model="weekend_checkbox" id="listing_weekend_price_native_checkbox" ng-init="weekend_checkbox = {{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}" ng-checked="{{ ($result->rooms_price->original_weekend == 0) ? 'false' : 'true' }}" class="mr-2" data-selector="price-weekend">
        {{ trans('messages.lys.weekend_pricing') }}
      </label>
      <div class="pricing_extra_amt" data-checkbox-id="listing_weekend_price_native_checkbox" ng-show="weekend_checkbox" ng-cloak>
        <div class="input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" ng-bind-html="currency_symbol"> </span>
          </div>
          <input type="number" min="0" limit-to=9 data-extras="true" value="{{ ($result->rooms_price->original_weekend == 0) ? '' : $result->rooms_price->original_weekend }}" id="price-weekend" name="weekend" class="autosubmit-text form-control" data-saving="additional-saving" autocomplete="off">
        </div>
        <p>
          {{ trans('messages.lys.weekend_pricing_desc') }}
        </p>
      </div>
    </div>

    <div class="stay-discount mt-4 js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
      <div style="display: none;" class="js-saving-progress saving-progress">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.length_of_stay_discounts') }}
      </h4>
      <div id="js-length_of_stay_wrapper" class="js-tooltip-trigger" ng-init="length_of_stay_items = {{json_encode($result->length_of_stay_rules)}}; length_of_stay_options= {{json_encode($length_of_stay_options)}}; ls_errors= [];">
        <div class="row">
          <div class="col-md-12 mt-3 p-0" ng-repeat="item in length_of_stay_items">
            <div class="length_whole w-100 d-md-flex">
              <input type="hidden" name="length_of_stay[@{{$index}}][id]" value="@{{item.id}}">
              <div class="col-12 col-md-5 pricing_field">
                <div class="select">
                  <select name="length_of_stay[@{{$index}}][period]" class="form-control ls_period" id="length_of_stay_period_@{{$index}}" data-index="@{{$index}}" ng-model="length_of_stay_items[$index].period">
                    <option disabled>
                      {{trans('messages.lys.select_nights')}}
                    </option>
                    <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights) || option.nights == item.period" ng-selected="item.period == option.nights" value="@{{option.nights}}">
                      @{{option.text}}
                    </option>
                  </select>
                </div>
                <span class="error-msg">
                  @{{ls_errors[$index]['period'][0]}}
                </span>
              </div>
              <div class="col-12 col-md-5 pricing_field mt-3 mt-md-0">
                <div class="input-group">
                  <input type="text" name="length_of_stay[@{{$index}}][discount]" class="form-control ls_discount" id="length_of_stay_discount_@{{$index}}" data-index="@{{$index}}" ng-model="length_of_stay_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}" autocomplete="off">
                  <div class="input-group-append">
                    <span class="input-group-text">
                      %
                    </span>
                  </div>
                </div>
                <span class="error-msg">
                  @{{ls_errors[$index]['discount'][0]}}
                </span>
              </div>
              <div class="col-12 col-md-5 pricing_field mt-3 mt-md-0">
                <button href="javascript:void(0)" class="btn delete_length" id="js-length_of_stay-rm-btn-@{{$index}}" ng-click="remove_price_rule('length_of_stay', $index)">
                  <span class="icon icon-trash"></span>
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-5" ng-init="length_of_stay_period_select = ''" ng-show="length_of_stay_items.length < length_of_stay_options.length">
            <div class="pricing_field " style="margin-top:1rem !important;">
              <div class="select">
                <select name="" class="form-control" id="length_of_stay_period_select" ng-model="length_of_stay_period_select" ng-change="add_price_rule('length_of_stay')">
                  <option value="">
                    {{trans('messages.lys.select_nights')}}
                  </option>
                  <option ng-repeat="option in length_of_stay_options" ng-if="length_of_stay_option_avaialble(option.nights)" value="@{{option.nights}}">
                    @{{option.text}}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>  
      </div>
    </div>

    <div class="early-bird-content js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
      <div style="display: none;" class="js-saving-progress saving-progress price_rules-early_bird-saving">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.early_bird_discounts') }}
      </h4>
      <div id="js-early_bird_wrapper" class="mt-3 early-bird-wrap js-tooltip-trigger"  ng-init="early_bird_items = {{json_encode($result->early_bird_rules)}}; eb_errors= [];">
        <div class="col-md-12 p-0" ng-repeat="item in early_bird_items">
          <div class="row">
            <div class="early_bird_whole w-100 d-md-flex">
              <input type="hidden" name="early_bird[@{{$index}}][id]" value="@{{item.id}}">
              <div class="col-12 col-md-5 pricing_field_list">
                <div class="input-group flex-nowrap pricing-field">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                     {{trans_choice('messages.reviews.day', 2)}}
                   </span>
                 </div>
                 <input type="text" name="early_bird[@{{$index}}][period]" class="form-control eb_period" id="early_bird_period_@{{$index}}" data-index="@{{$index}}" ng-model="early_bird_items[$index].period" placeholder="{{trans('messages.lys.no_of_days')}}" autocomplete="off">
               </div>
               <span class="error-msg">
                @{{eb_errors[$index]['period'][0]}}
              </span>
            </div>
            <div class="col-12 col-md-5 pricing_field_list mt-3 mt-md-0">
              <div class="input-group flex-nowrap pricing-field">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    %
                  </span>
                </div>
                <input type="text" name="early_bird[@{{$index}}][discount]" class="form-control eb_discount" id="early_bird_discount_@{{$index}}" data-index="@{{$index}}" ng-model="early_bird_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}" autocomplete="off">
              </div>
              <span class="error-msg">
                @{{eb_errors[$index]['discount'][0]}}
              </span>
            </div>
            <div class="col-12 col-md-2 pricing_field_list mt-3 mt-md-0">
              <button href="javascript:void(0)" class="btn delete_length" id="js-early_bird-rm-btn-@{{$index}}" ng-click="remove_price_rule('early_bird', $index)">
                <span class="icon icon-trash"></span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="pricing_field add-rule-btn my-3">
        <a href="javascript:void(0)" class="btn" ng-click="add_price_rule('early_bird')">
          <span class="icon icon-add"></span>
          {{trans('messages.lys.add')}}
        </a>
      </div>
    </div>
  </div>

  <div class="last-min-content js-section {{ ($result->status != NULL) ? 'pre-listed' : 'post-listed' }}">
    <div style="display: none;" class="js-saving-progress saving-progress price_rules-last_min-saving">
      <h5>
        {{ trans('messages.lys.saving') }}...
      </h5>
    </div>
    <h4>
      {{ trans('messages.lys.last_min_discounts') }}
    </h4>
    <div id="js-last_min_wrapper" class="mt-3 last-min-wrap js-tooltip-trigger" ng-init="last_min_items = {{json_encode($result->last_min_rules)}}; lm_errors= [];">
      <div class="col-md-12 p-0" ng-repeat="item in last_min_items">
        <div class="row">
          <div class="early_bird_whole w-100 d-md-flex">
            <input type="hidden" name="last_min[@{{$index}}][id]" value="@{{item.id}}">
            <div class="col-12 col-md-5 pricing_field_list">
             <div class="input-group flex-nowrap pricing-field">
              <div class="input-group-prepend">
                <span class="input-group-text">
                 {{trans_choice('messages.reviews.day', 2)}}
               </span>
             </div>
             <input type="text" name="last_min[@{{$index}}][period]" class="form-control lm_period" id="last_min_period_@{{$index}}" data-index="@{{$index}}" ng-model="last_min_items[$index].period" placeholder="{{trans('messages.lys.no_of_days')}}" autocomplete="off">
           </div>
           <span class="error-msg">
            @{{lm_errors[$index]['period'][0]}}
          </span>
        </div>
        <div class="col-12 col-md-5 pricing_field_list mt-3 mt-md-0">
          <div class="input-group flex-nowrap pricing-field">
            <div class="input-group-prepend">
              <span class="input-group-text"> 
                %
              </span>
            </div>
            <input type="text" name="last_min[@{{$index}}][discount]" class="form-control lm_discount" id="last_min_discount_@{{$index}}" data-index="@{{$index}}" ng-model="last_min_items[$index].discount" placeholder="{{trans('messages.lys.percentage_of_discount')}}" autocomplete="off">              
          </div>
          <span class="error-msg">
            @{{lm_errors[$index]['discount'][0]}}
          </span>
        </div>
        <div class="col-12 col-md-2 pricing_field_list mt-3 mt-md-0">
          <button href="javascript:void(0)" class="btn delete_length" id="js-last_min-rm-btn-@{{$index}}" ng-click="remove_price_rule('last_min', $index)">
            <span class="icon icon-trash"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="pricing_field add-rule-btn my-3">
    <a href="javascript:void(0)" class="btn" ng-click="add_price_rule('last_min')">
      <span class="icon icon-add"></span>
      {{trans('messages.lys.add')}}
    </a>
  </div>
</div>
</div>

<div id="js-donation-tool-placeholder">
</div>

<div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
  <div class="prev_step next_step">
    @if($result->status == NULL)
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="back-section-button">
      {{ trans('messages.lys.back') }}
    </a>
    @endif
    @if($result->status != NULL)
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="back-section-button">
      {{ trans('messages.lys.back') }}
    </a>
    @endif
  </div>
  <div class="next_step">
    @if($result->status == NULL)
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/calendar') }}" class="btn btn-primary next-section-button">
      {{ trans('messages.lys.next') }}
    </a>
    @endif
    @if($result->status != NULL)
    <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/booking') }}" class="btn btn-primary next-section-button">
      {{ trans('messages.lys.next') }}
    </a>
    @endif
  </div>
</div>
</div>

<div class="manage-listing-help mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
  <div class="help-icon">
    {!! Html::image('images/lightbulb2x.png', '') !!}
  </div>
  <div class="help-content mb-5">
    <h4 class="text-center">
      {{ trans('messages.lys.nightly_price') }}
    </h4>
    <p>
      {{ trans('messages.lys.nightly_price_desc') }}
    </p>
  </div>
</div>
</div>
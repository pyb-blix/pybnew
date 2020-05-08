<div class="modal-content">
  <div class="modal-header flex-wrap">
    <button type="button" class="close" data-dismiss="modal">
    </button>
    <h1 class="modal-title w-100">
      {{ trans('messages.lys.enter_address') }}
    </h1>
    <span>
      {{ trans('messages.lys.what_your_listing_address') }}
    </span>
  </div>
  <div class="modal-body">
    <div id="js-disaster-address-alert" class="media my-2 d-none">
      <i class="icon icon-flag"></i>
      <strong>
        {{ trans('messages.lys.new_location_outside_disaster') }}
      </strong>
      <span>
        {{ trans('messages.lys.price_reset_daily_rate') }}
      </span>
    </div>

    <form id="js-address-fields-form" name="enter_address">
      <div class="country-field">
        <label for="country">
          {{ trans('messages.account.country') }}
        </label>
        <div id="country-select">
          <div class="select">
            {!! Form::select('country_code',$country,$result->country,['id'=>'country']) !!}
          </div>
          <p class="text-danger d-none" id="location_country_field_error">{{trans('messages.lys.service_not_available_country')}}</p>
        </div>
      </div>
      <div id="localized-fields" class="address-group mt-2">
        <div class="mt-3">
          <label for="address_line_1">
            {{ trans('messages.lys.address_line_1') }}
          </label>
          <input type="text" placeholder="{{ trans('messages.lys.address1_placeholder') }}" value="{{ $result->address_line_1 }}" class="focus" id="address_line_1" name="address_line_1" autocomplete="off">
        </div>

        <div class="mt-3">
          <label for="address_line_2">
            {{ trans('messages.lys.address_line_2') }}
          </label>
          <input type="text" placeholder="{{ trans('messages.lys.address2_placeholder') }}" value="{{ $result->address_line_2 }}" class="focus" id="address_line_2" name="address_line_2">
        </div>

        <div class="mt-3">
          <label for="city">
            {{ trans('messages.lys.city_town_district') }}
          </label>
          <input type="text" placeholder="" class="focus" value="{{ $result->city }}" id="city" name="city" required="true">
        </div>

        <div class="mt-3">
          <label for="state">
            {{ trans('messages.lys.state_province_country_region') }}
          </label>
          <input type="text" placeholder="" class="focus" value="{{ $result->state }}" id="state" name="state" required="true">
        </div>

        <div class="mt-3">
          <label for="postal_code">
            {{ trans('messages.lys.zip_postal_code') }}
          </label>
          <input type="text" placeholder="" class="focus" value="{{ $result->postal_code }}" id="postal_code" name="postal_code">
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer mt-3">
    <button data-behavior="modal-close" class="btn js-secondary-btn" data-dismiss="modal">
      {{ trans('messages.your_reservations.cancel') }}
    </button>
    <button id="js-next-btn" class="btn btn-primary js-next-btn">
      {{ trans('messages.lys.next') }}
    </button>
  </div>
</div>
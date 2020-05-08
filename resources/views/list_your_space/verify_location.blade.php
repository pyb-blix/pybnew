<div class="modal-content">
  <div class="modal-header flex-wrap">
   <button type="button" class="close" data-dismiss="modal">
   </button>
   <h1 class="modal-title w-100">
    {{ trans('messages.lys.verify_location') }}
  </h1>
  <span>
    {{ trans('messages.lys.move_map_to_pin_listing_location') }}
  </span>
</div>
<div class="modal-body">
  <div id="js-disaster-address-alert" class="media my-2 d-none">
    <i class="icon icon-flag"></i>
    <div class="media-body">
      <strong>{{ trans('messages.lys.new_location_outside_disaster') }}</strong><br>
      <span class="text-muted">{{ trans('messages.lys.price_reset_daily_rate') }}</span>
    </div>
  </div>
  <div class="panel">
    <div class="verify-map">
      <div class="popup-map" id="map">
      </div>
      <img class="verify-map-pin" src="{{url('/')}}/images/map-pin-set.png" />
    </div>
    <div class="panel-body mt-3">
      <p id="error_invalid_pin" class="d-none">
        {{ trans('messages.lys.moved_invalid_destination') }}
      </p>
      <address>
        <span class="address-line" ng-init="address_line_1 = '{{ addslashes($result->address_line_1) }}'; address_line_2 = '{{ addslashes($result->address_line_2) }}'">
          @{{ address_line_1 }} <span ng-if="address_line_2"> / </span>@{{ address_line_2 }}
        </span>
        <span class="address-line" ng-init="city = '{{ addslashes($result->city) }}'; state = '{{ addslashes($result->state) }}'">
          @{{ city }} @{{ state }}
        </span>
        <span class="address-line" ng-init="postal_code = '{{ $result->postal_code }}'">
          @{{ postal_code }}
        </span>
        <span class="address-line" ng-init="country='{{ $result->country }}';latitude='{{ $result->latitude }}';longitude='{{ $result->longitude }}'">
          @{{ country_name }}
        </span>
      </address>
      <a data-event-name="edit_address_click" class="theme-link" id="js-edit-address" href="javascript:void(0)">
        {{ trans('messages.lys.edit_address') }}
      </a>
    </div>
  </div>
</div>
<div class="modal-footer mt-3">
  <button class="btn js-adjust-pin-location js-secondary-btn d-none">
    {{ trans('messages.lys.adjust_pin_location') }}
  </button>
  <button id="js-next-btn3" class="btn btn-primary js-next-btn" ng-disabled="location_found == false">
    {{ trans('messages.lys.finish') }}
  </button>
</div>
</div>
<input type="hidden" id="address_line_1" value="{{ $result->address_line_1 }}">
<input type="hidden" id="address_line_2" value="{{ $result->address_line_2 }}">
<input type="hidden" id="city" value="{{ $result->city }}">
<input type="hidden" id="state" value="{{ $result->state }}">
<input type="hidden" id="postal_code" value="{{ $result->postal_code }}">
<input type="hidden" id="country" value="{{ $result->country }}">
<input type="hidden" ng-model="latitude2" id="latitude2" ng-value="{{ $result->latitude }}">
<input type="hidden" ng-model="longitude2" id="longitude2" ng-value="{{ $result->longitude }}">
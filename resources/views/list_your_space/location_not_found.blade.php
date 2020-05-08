<div class="modal-content">
  <div class="modal-header flex-wrap">
    <button type="button" class="close" data-dismiss="modal"></button>
    <h1 class="modal-title w-100" ng-hide="location_found"> @lang('messages.lys.exact_location_not_found') </h1>
    <h1 class="modal-title w-100" ng-show="location_found"> @lang('messages.lys.location_found') </h1>
    <span>
      {{ trans('messages.lys.manually_pin_location') }}
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
    <p ng-hide="location_found"> @lang('messages.lys.couldnot_automatically_find') </p>
    <p ng-show="location_found"> @lang('messages.lys.manually_pin_location_instead') </p>
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
    <a data-event-name="edit_address_click" id="js-edit-address" class="theme-link" href="javascript:void(0)">
      {{ trans('messages.lys.edit_address') }}
    </a>
  </div>
  <div class="modal-footer mt-3">
    <button class="btn" id="js-edit-address">
    {{ trans('messages.lys.edit_address') }}
    </button>
    <button id="js-next-btn2" class="btn btn-primary js-next-btn">
    {{ trans('messages.lys.pin_on_map') }}
    </button>
  </div>
</div>
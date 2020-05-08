<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading js-section my-4">       
      <h3>
        {{ trans('messages.lys.location_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.location_desc1') }}
      </p>
    </div>

    <div id="js-location-container" class="js-section">
      <div style="display: none;" class="js-saving-progress saving-progress">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.account.address') }}
      </h4>
      <p>
        {{ trans('messages.lys.location_desc2') }}
      </p>

      <div class="card">
        <div class="address-static-map">
          <div class="location-map-container {{ ($rooms_status->location == 0) ? '' : 'd-none' }}">
            <img class="location-map-img img-fluid" src="{{url('/')}}/images/empty-map.png"/>
            <img class="location-map-pin" src="{{url('/')}}/images/map-pin-unset-moving.png"/>
          </div>
          @if($rooms_status->location == 1)
          <img width="100%" height="275" src="https://maps.googleapis.com/maps/api/staticmap?size=570x275&amp;center={{ $result->rooms_address->latitude }},{{ $result->rooms_address->longitude }}&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key={{ $map_key }}&amp;markers=icon:{{url('/')}}/images/map-pin-set-3460214b477748232858bedae3955d81.png%7C{{ $result->rooms_address->latitude }},{{ $result->rooms_address->longitude }}">      
          @endif
        </div>
        <div class="edit-address my-3 text-center">
          @if($rooms_status->location == 0)
          <button id="js-add-address" class="btn" data-toggle="modal" data-target="#address-flow-view">
            {{ trans('messages.lys.add_address') }}
          </button>
          @endif
          <address class="{{ ($rooms_status->location == 0) ? 'd-none' : '' }}">
            <span class="address-line" ng-init="address_line_1 = '{{ addslashes($result->rooms_address->address_line_1) }}'; address_line_2 = '{{ addslashes($result->rooms_address->address_line_2) }}'">
              @{{ address_line_1 }} <span ng-if="address_line_2"> / </span>@{{ address_line_2 }}
            </span>
            <span class="address-line" ng-init="city = '{{ addslashes($result->rooms_address->city) }}'; state = '{{ addslashes($result->rooms_address->state) }}'">
              @{{ city }} @{{ state }}
            </span>
            <span class="address-line" ng-init="postal_code = '{{ $result->rooms_address->postal_code }}'">
              @{{ postal_code }}
            </span>
            <span class="address-line" ng-init="country='{{ $result->rooms_address->country }}';latitude='{{ $result->rooms_address->latitude }}';longitude='{{ $result->rooms_address->longitude }}'">
              @{{ country_name }}
            </span>
          </address>
          <a data-event-name="edit_address_click" id="js-edit-address" class="theme-link js-edit-address-link edit-address-link {{ ($rooms_status->location == 0) ? 'd-none' : '' }}" href="javascript:void(0);" data-toggle="modal" data-target="#address-flow-view">
            {{ trans('messages.lys.edit_address') }}
          </a>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/description') }}" class="back-section-button">
          {{ trans('messages.lys.back') }}
        </a>          
      </div>
      <div class="next_step">
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="btn btn-primary next-section-button">
          {{ trans('messages.lys.next') }}
        </a>
      </div>
    </div>
  </div>

  <div class="manage-listing-help mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
    <div class="help-icon">
      {!! Html::image('images/lightbulb2x.png', '') !!}
    </div>
    <div class="help-content mb-5">
      <h4 class="text-center">
        {{ trans('messages.your_trips.location') }}
      </h4>
      <p>
        {{ trans('messages.lys.edit_location_desc') }}
      </p>
      <div class="tip-address-img text-center">
        <img src="{{url('/')}}/images/map-tip.png"/>
      </div>
    </div>
  </div>
</div>
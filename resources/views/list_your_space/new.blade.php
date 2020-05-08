@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="rooms_new">      
  <div class="page-container-full" ng-init="country_list = {{json_encode($country_list)}}">
    <div class="become_new">
      <div class="new_head p-4 p-md-5 text-center">
        <h1>
          {{ trans('messages.header.list_your_space') }}
        </h1>
        <p>
          {{ $site_name }} {{ trans('messages.lys.make_money_renting') }}
        </p>
      </div>

      <div class="panel-body">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 my-4 mx-auto col-md-12 list-space">
              <div class="become_body">
                <div id="alert-row" class="">
                  <div id="alert-status" class="col-lg-10 col-md-11 lys-alert"></div>
                </div>

                {!! Form::open(['url' => 'rooms/create', 'class' => 'host-onboarding-form', 'accept-charset' => 'UTF-8' , 'name' => 'lys_new']) !!}

                {!! Form::hidden('hosting[property_type_id]', '', ['id' => 'property_type_id', 'ng-model' => 'property_type_id', 'required' => 'required', 'ng-value' => 'property_type_id']) !!}

                {!! Form::hidden('hosting[person_capacity]', '', ['id' => 'person_capacity', 'ng-model' => 'selected_accommodates', 'required' => 'required', 'ng-value' => 'selected_accommodates']) !!}

                {!! Form::hidden('hosting[room_type]', '', ['id' => 'room_type', 'ng-model' => 'room_type_id', 'required' => 'required', 'ng-value' => 'room_type_id']) !!}

                {!! Form::hidden('hosting[address]', '', ['id' => 'address', 'ng-model' => 'address', 'ng-value' => 'address']) !!}

                {!! Form::hidden('hosting[street_number]', '', ['id' => 'street_number', 'ng-model' => 'street_number', 'ng-value' => 'street_number']) !!}

                {!! Form::hidden('hosting[route]', '', ['id' => 'route', 'ng-model' => 'route', 'ng-value' => 'route']) !!}

                {!! Form::hidden('hosting[postal_code]', '', ['id' => 'postal_code', 'ng-model' => 'postal_code', 'ng-value' => 'postal_code']) !!}

                {!! Form::hidden('hosting[city]', '', ['id' => 'city', 'ng-model' => 'city', 'ng-value' => 'city']) !!}

                {!! Form::hidden('hosting[state]', '', ['id' => 'state', 'ng-model' => 'state', 'ng-value' => 'state']) !!}

                {!! Form::hidden('hosting[country]', '', ['id' => 'country', 'ng-model' => 'country', 'ng-value' => 'country']) !!}

                {!! Form::hidden('hosting[latitude]', '', ['id' => 'latitude', 'ng-model' => 'latitude', 'ng-value' => 'latitude']) !!}

                {!! Form::hidden('hosting[longitude]', '', ['id' => 'longitude', 'ng-model' => 'longitude', 'ng-value' => 'longitude']) !!}

                <div class="fieldset fieldset_property_type_id">
                  <h3>
                    {{ trans('messages.lys.home_type') }}
                  </h3>
                  <div class="btn-group d-block d-md-flex" ng-hide="property_type_id">
                    @for($i = 0; $i < 3; $i++)
                    @if(isset($property_type[$i]))
                    <button class="btn col-12 col-md-3 my-2 my-md-0 d-flex align-items-center" data-name="{{ $property_type[$i]->name }}" data-type-id="{{ $property_type[$i]->id }}" data-icon-class="{{$property_type[$i]->image_name}}" data-behavior="tooltip" type="button" data-position="bottom" aria-label="Your space is an apartment, flat, or unit in a multi-unit building." ng-click="property_type('{{ $property_type[$i]->id }}', '{{ $property_type[$i]->name }}', '{{$property_type[$i]->image_name}}')">
                      <img src="{{$property_type[$i]->image_name}}">
                      <span class="text-break">
                        {{ $property_type[$i]->name }}
                      </span>
                    </button>
                    @endif
                    @endfor

                    <div class="select p-0 other-select" id="property-select">
                      <select class="custom-select" id="property_type_dropdown" ng-model="change_property_value" ng-change="property_change(change_property_value)">
                        <option disabled="" selected="" value="">
                          {{ trans('messages.profile.other') }}
                        </option>
                        @php $i = 0 @endphp
                        @foreach($property_type as $row)
                        @if($i > 2)
                        <option data-icon-class="{{$property_type[$i]->image_name}}" data-name="{{ $row->name }}" data-type-id="{{ $row->id }}" value="{{ $row->id }}">
                          {{ $row->name }}
                        </option>
                        @endif
                        {{ $i++ }}
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="active-selection" ng-show="property_type_id" style="display:none;">
                    <div data-type="property_type_id" class="selected-item property_type_id">
                      <div class="active-panel d-md-flex" ng-click="property_type_rm()">
                        <div class="active-title d-flex align-items-center col-12 col-md-4">
                          <img src="@{{property_type_icon}}">
                          <span class="text-truncate">
                            @{{ selected_property_type }}
                          </span>                          
                        </div>
                        <div class="active-message align-self-center col-12 col-md-8">
                          <i class="icon icon-caret-right"></i>
                          <p>
                            {{ $site_name }} {{ trans('messages.lys.home_type_desc') }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="fieldset fieldset_room_type">      
                  <h3 ng-init="is_shared == 'No'">
                    {{ trans('messages.lys.room_type') }}
                  </h3>
                  <div class="btn-group d-block d-md-flex" ng-hide="room_type_id">
                    @for($i = 0; $i < 3; $i++)
                    @if(isset($room_type[$i]))
                    <button class="btn my-2 my-md-0 col-12 col-md-3 d-flex align-items-center" data-name="{{ $room_type[$i]->name }}" data-slug="entire_home_apt" data-type="{{ $room_type[$i]->name }}" data-type-id="{{ $room_type[$i]->id }}" data-icon-class="{{$room_type[$i]->image_name}}" data-behavior="tooltip" type="button" data-position="bottom" aria-label="You&#39;re renting out an entire home." ng-click="room_type('{{ $room_type[$i]->id }}', '{{ $room_type[$i]->name }}','{{ $room_type[$i]->image_name }}','{{$room_type[$i]->is_shared}}')">
                      <img src="{{ $room_type[$i]->image_name }}"/>
                      <span class="text-break">
                        {{ $room_type[$i]->name }}
                      </span>
                    </button>
                    @endif
                    @endfor

                    <div class="select p-0 other-select" id="room-select">
                      <select class="custom-select" id="room_type_dropdown" ng-model="change_room_value" ng-change="room_change(change_room_value)">
                        <option disabled="" selected="" value="">{{ trans('messages.profile.other') }}</option>
                        @php $i = 0 @endphp
                        @foreach($room_type as $row)
                        @if($i > 2)
                        <option data-icon-class="{{$room_type[$i]->image_name}}" data-name="{{ $row->name }}" data-type-id="{{ $row->id }}" data-is_shared="{{$row->is_shared}}" value="{{ $row->id }}">
                          {{ $row->name }}
                        </option>
                        @endif
                        {{ $i++ }}
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="active-selection" ng-show="room_type_id" style="display:none;">
                    <div data-type="room_type_id" class="selected-item room_type_id">
                      <div class="active-panel d-md-flex" ng-click="room_type_rm()">
                        <div class="active-title d-flex align-items-center col-12 col-md-4">
                          <img src="@{{ room_type_icon }}">
                          <span>
                            @{{ selected_room_type }}
                          </span>
                        </div>
                        <div class="active-message align-self-center col-12 col-md-8">
                          <i class="icon icon-caret-right"></i>
                          {{ trans('messages.lys.room_type_desc',['site_name'=>$site_name]) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <em ng-show="is_shared == 'Yes'">
                    {{trans('messages.shared_rooms.shared_room_notes')}}
                  </em>
                </div>

                <div class="fieldset fieldset_person_capacity">
                  <h3>
                   {{ trans('messages.lys.accommodates') }}
                 </h3>
                 <div class="unselected" ng-hide="selected_accommodates">
                  <div class="select col-12 col-md-6 icon-field d-flex align-items-center">
                    <i class="icon icon-group icons-accommodates"></i>
                    <select id="accomodates-select" class="hover-select-highlight" ng-model="accommodates_value" ng-change="change_accommodates(accommodates_value)">
                      @for($i=1;$i<=16;$i++)
                      <option class="accommodates" data-accommodates="{{ ($i == '16') ? $i.'+' : $i }}" value="{{ ($i == '16') ? $i.'+' : $i }}">
                        {{ ($i == '16') ? $i.'+' : $i }}
                      </option>
                      @endfor
                    </select>
                  </div>
                </div>

                <div class="active-selection" ng-show="selected_accommodates" ng-click="accommodates_rm()" style="display:none;">
                  <div data-type="person_capacity" class="selected-item person_capacity">
                    <div class="active-panel d-md-flex">
                      <div class="active-title d-flex align-items-center col-12 col-md-4">
                        <i class="icon icon-group mr-2"></i>
                        <span>
                          @{{ selected_accommodates }}
                        </span>
                      </div>
                      <div class="active-message align-self-center col-12 col-md-8">
                        <i class="icon icon-caret-right"></i>
                        <span>
                          {{ trans('messages.lys.accommodates_desc') }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="fieldset fieldset_city">
                <h3>
                  {{ trans('messages.account.city') }}
                </h3>
                <div class="col-12 col-md-6 icon-field d-flex align-items-center" ng-hide="city_show">
                  <i class="icon icon-map-marker"></i>
                  <input class="alert-highlighted-element geocomplete" name="location_input" type="text" value="" placeholder="{{ trans('messages.header.enter_a_location') }}" autocomplete="off" ng-click="city_click()" ng-model="address" id="location_input">
                </div>
                <p class="text-danger d-none" id="location_country_error_message">{{trans('messages.lys.service_not_available_country')}}
                </p>

                <div class="active-selection" ng-show="city_show" style="display:none;">
                  <div class="selected-item city" data-type="city">
                    <div class="active-panel d-md-flex" ng-click="city_rm()">
                      <div class="active-title d-flex align-items-center col-12 col-md-4">
                        <i class="icon icon-map-marker mr-2"></i>
                        <span class="text-truncate">
                          @{{ address }}
                        </span>
                      </div>
                      <div class="active-message align-self-center col-12 col-md-8">
                        <i class="icon icon-caret-right"></i>
                        <span>
                          {{ trans('messages.lys.city_desc') }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="js-submit-button" class="mt-3">
                <button type="submit" class="btn btn-primary" ng-disabled="((city_show == false) || lys_new.$invalid ) || submitDisable" ng-click="disableButton()">
                  {{ trans('messages.lys.continue') }}
                </button>
              </div>
              <div id="cohosting-signup-widget-banner" class="hide-sm hide-md"></div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>

        <div class="host-secure-info d-md-flex py-4 text-center">
          <div class="col-md-4">
            <i class="icon icon-handshake"></i>
            <h4>
              {{ trans('messages.lys.trust_safety') }}
            </h4>
            <p>
              {{ trans('messages.lys.trust_safety_desc') }}
            </p>
          </div>
          <div class="col-md-4">
            <i class="icon icon-host-guarantee"></i>
            <h4>
              {{ trans('messages.lys.host_guarantee') }}
            </h4>
            <p>
              {{ trans('messages.lys.host_guarantee_desc1') }} 
              <a class="theme-link" href="{{ url('host_guarantee') }}" target="_blank">
                {{ trans('messages.lys.eligible') }}
              </a> 
              {{ trans('messages.lys.host_guarantee_desc2',['site_name'=>$site_name]) }}
            </p>
          </div>
          <div class="col-md-4">
            <i class="icon icon-lock"></i>
            <h4>
              {{ trans('messages.lys.secure_payments') }}
            </h4>
            <p>
              {{ trans('messages.lys.secure_payments_desc') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
@stop
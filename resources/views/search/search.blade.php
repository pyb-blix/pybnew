@extends('template')
@section('main')
<main ng-controller="search-page">
	<div class="search_filter" ng-init="opened_filter = '';">
		<nav class="navbar">
			<ul class="navbar-nav flex-wrap flex-row" ng-cloak>
				<li class="nav-item dropdown keep-open date-filter-btn">
					<button class="dbdate" ng-class="filter_active('dates')" data-target-filter="dates" ng-click="update_opened_filter('dates')">
						<span ng-if="!is_filter_active('dates')">
							{{ trans('messages.your_trips.dates') }}
						</span>
						<span ng-if="is_filter_active('dates')">
							@{{format_date(checkin, 'DD MMM')}}-@{{format_date(checkout, 'DD MMM')}}
						</span>
					</button>
				</li>
				<li class="nav-item dropdown keep-open guest-filter-btn">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target-filter="guests" ng-class="filter_active('guests')" ng-click="update_opened_filter('guests')">
						<span ng-if="!is_filter_active('guests')">
							{{ trans_choice('messages.home.guest',2) }}
						</span>
						<span ng-if="is_filter_active('guests')">
							@{{search_guest}} {{ trans_choice('messages.home.guest',2) }}
						</span>
					</button>
					<div class="dropdown-menu">
						<div class="d-flex align-items-center">
							<label>
								{{ trans_choice('messages.home.guest',1) }}
							</label>
							<div class="value-changer d-flex ml-5 align-items-center" ng-init="search_guest={{$guest}}">
								<button ng-disabled="search_guest==1" class="value-button" id="decrease" ng-click="search_guest=search_guest-1" value="Decrease Value">-</button>
								<input type="text" class="guest-input mx-2" ng-value="search_guest+'+'" readonly="" />
								<button ng-disabled="search_guest == 16" class="value-button" id="increase" ng-click="search_guest=search_guest-0+1" value="Increase Value">+</button>
							</div>
						</div>
						<div class="my-4 d-flex align-items-center justify-content-between filter-btn">
							<a href="javascript:void(0)" class="cancel-filter" ng-click="reset_filters('guests')">
								{{ trans('messages.your_reservations.cancel') }}
							</a>
							<a href="javascript:void(0)" class="apply-filter" ng-click="apply_filters('guests')">
								{{ trans('messages.payments.apply') }}
							</a>
						</div>
					</div>
				</li>

				<li class="nav-item dropdown keep-open d-none d-md-block">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target-filter="room_types" ng-class="filter_active('room_types')" ng-click="update_opened_filter('room_types')">
						<span>{{ trans('messages.lys.room_type') }}</span>
						<span ng-if="is_filter_active('room_types')">@{{filter_btn_text('room_types')}}</span>
					</button>
					<div class="dropdown-menu">
						@foreach($room_types as $row)
						<div class="d-flex">
							<input type="checkbox" value="{{ $row->id }}" id="room_type_{{ $row->id }}" class="room-type" {{in_array($row->id, $room_type_selected)  ? "checked" : ""}} />
							<label class="type-info" for="room_type_{{ $row->id }}">
								<h4> {{ $row->name }} </h4>
								<p> {{ $row->description }} </p>
							</label>
							<div class="type-img">
								<i class="icon-activities"> <img src="{{ $row->image_name }}"> </i>
							</div>
						</div>
						@endforeach
						<div class="my-4 d-flex align-items-center justify-content-between filter-btn">
							<a href="javascript:void(0)" class="cancel-filter" ng-click="reset_filters('room_types')">
								{{ trans('messages.your_reservations.cancel') }}
							</a>
							<a href="javascript:void(0)" class="apply-filter" ng-click="apply_filters('room_types')">
								{{ trans('messages.payments.apply') }}
							</a>
						</div>
					</div>
				</li>

				<li class="nav-item dropdown keep-open d-none d-md-block" ng-init="currency_symbol = '{{ html_string($currency_symbol) }}';min_value={{$min_price}};max_value={{$max_price}};max_slider_price = {{ $default_max_price }};">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-class="filter_active('prices')" ng-click="update_opened_filter('prices')">
						<span ng-if="!is_filter_active('prices')">
							@lang('messages.inbox.price')
						</span>
						<span ng-if="is_filter_active('prices')">
							@{{filter_btn_text('prices')}}
						</span>
					</button>
					<div class="dropdown-menu">
						<div class="price-label d-flex align-items-center">
							<div class="price-min">
								<span>{{ html_string($currency_symbol) }}</span>
								<span class="price" class="min_text">
									@{{ min_value }}
								</span>
							</div>
							<span class="mx-2">-</span>
							<div class="price-min">
								<span>{{ html_string($currency_symbol) }}</span>
								<span class="price" class="max_text">
									@{{ max_value }} @{{ (max_value == max_slider_price) ? '+' : '' }}
								</span>
							</div>
						</div>
						<div id="slider" class="mt-4 price-range-slider"></div>
						<div class="my-4 d-flex align-items-center justify-content-between filter-btn">
							<a href="javascript:void(0)" class="cancel-filter" ng-click="reset_filters('prices')">
								@lang('messages.your_reservations.cancel')
							</a>
							<a href="javascript:void(0)" class="apply-filter" ng-click="apply_filters('prices')">
								@lang('messages.payments.apply')
							</a>
						</div>
					</div>
				</li>
				<li class="nav-item dropdown keep-open d-none d-md-block instant-filter-btn">
					<button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-class="filter_active('instant_book')" ng-click="update_opened_filter('instant_book')">@lang('messages.lys.instant_book')</button>
					<div class="dropdown-menu">
						<div class="instant-book d-flex">
							<div class="instant-info">
								<h4>
									@lang('messages.lys.instant_book')
									<span>
										<i class="icon icon-instant-book"></i>
									</span>
								</h4>
								<p>
									@lang('messages.search.instant_book_desc')
								</p>
							</div>
							<div class="instant-checkbox">
								<label class="checkbox" ng-class="instant_book == '1' ? 'instant-checked' : ''">
									<input type="checkbox" name="instant_book" id="instant_book" ng-model="instant_book" ng-init="instant_book = '{{$instant_book}}'" ng-true-value="'1'" ng-false-value="'0'">
								</label>
							</div>
						</div>
						<div class="my-4 d-flex align-items-center justify-content-between filter-btn">
							<a href="javascript:void(0)" class="cancel-filter" ng-click="reset_filters('instant_book')">
								@lang('messages.your_reservations.cancel')
							</a>
							<a href="javascript:void(0)" class="apply-filter" ng-click="apply_filters('instant_book')">
								@lang('messages.payments.apply')
							</a>
						</div>
					</div>
				</li>
				<li class="nav-item dropdown keep-open">
					<button class="dropdown-toggle more-filter-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target-filter="more_filters" ng-class="filter_active('more_filters')" ng-click="update_opened_filter('more_filters')">
						@lang('messages.search.more_filters')
						<span ng-if="is_filter_active('more_filters')">
							@{{filter_btn_text('more_filters')}}
						</span>
					</button>
				</li>
			</ul>
		</nav>
		<div class="map-toggle-wrap">
			<span class="d-inline-block mr-2">
				{{trans('messages.search.show_map')}}
			</span>
			<div id="map-toggle" class="map-toggle-btn active">
				<label>
					<input type="checkbox" name="map-toggle">
					<span class="toggle-on">on</span>
					<span class="toggle-off">off</span>
				</label>
			</div>
		</div>
	</div>

	<input class="d-none" ng-model="checkin" readonly="readonly" autocomplete="off" type="text" ng-change="search_result();" placeholder="{{ trans('messages.home.checkin') }}" ng-init="checkin = '{{ $checkin }}'">
	<input class="d-none" ng-model="checkout" readonly="readonly" autocomplete="off" type="text" ng-change="search_result();" placeholder="{{ trans('messages.home.checkout') }}" ng-init="checkout = '{{ $checkout }}'">
	<div class="search-content d-flex" ng-init="bed_text='{{ trans_choice("messages.lys.bed",1) }}'; beds_text='{{ trans_choice("messages.lys.bed",2)}}'; per_night='{{ trans("messages.rooms.per_night") }}'; review_text='{{ trans_choice("messages.header.review",1) }}'; reviews_text='{{ trans_choice("messages.header.review",2) }}';">
		<div class="col-12 col-lg-8 search-content-filters">
			<div class="search-wrap d-md-flex flex-wrap row">
				<div class="search-list col-12 col-md-6 col-lg-4" ng-repeat="rooms in room_result.data" ng-cloak>
					<div ng-mouseover="on_mouse($index);" ng-mouseleave="out_mouse($index);">
						<div class="search-img">
							<div id="search-img-slide" class="search-img-slide owl-carousel">
								<a href="{{url('rooms')}}/@{{rooms.id}}?checkin=@{{checkin}}&checkout=@{{checkout}}&guests=@{{guests}}" ng-repeat="photo in rooms.all_photos" target="listing_@{{ rooms.id }}" id="rooms_image_@{{ rooms.id}}">
									<img ng-src="@{{ photo.name }}" alt="@{{ photo.name }}">
								</a>
							</div>
							<div class="search-wishlist">
								<input type="checkbox" id="wishlist-widget-@{{ rooms.id }}" name="wishlist-widget-@{{ rooms.id }}" data-for-hosting="@{{ rooms.id }}" ng-checked="rooms.saved_wishlists">
								<label for="wishlist-widget-@{{ rooms.id }}" ng-init="current_refinement='{{ $current_refinement }}';">
									<i class="icon icon-heart"></i>
									<i class="icon icon-heart-alt" @if(Auth::user()) data-toggle="modal"@endif data-target="#wishlist-modal" ng-click="saveWishlist(rooms)"></i>
								</label>
							</div>
						</div>
						<div class="search-info">
							<h4 class="text-truncate">
								<span>
									@{{ rooms.room_type_name }}
								</span>
								<span>·</span>
								<span>@{{ rooms.beds }}
									@{{ (rooms.beds > 1 ) ? beds_text : bed_text }}
								</span>
							</h4>
							<a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="text-truncate" title="@{{ rooms.name }}">
								@{{ rooms.name }}
							</a>
							<p class="search-price">
								<span ng-bind-html="rooms.rooms_price.currency.symbol"></span>
								<span ng-if="guests > 1 && guests > rooms.rooms_price.guests" ng-bind-html="rooms.rooms_price.night + (rooms.rooms_price.additional_guest * (guests-rooms.rooms_price.guests))"></span>
								<span ng-if="guests == 1 || guests <= rooms.rooms_price.guests" ng-bind-html="rooms.rooms_price.night"></span>
								{{ trans("messages.rooms.per_night") }}
								<span ng-if="rooms.booking_type == 'instant_book'">
									<i class="icon icon-instant-book"></i>
								</span>
							</p>
							<div class="search-ratings">
								<a href="{{ url('rooms') }}/@{{ rooms.id }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" class="d-flex align-items-center">
									<span class="d-inline-block" ng-show="rooms.overall_star_rating">
										<span class="d-inline-block align-middle" ng-bind-html="rooms.overall_star_rating"></span>
									</span>
									<span class="d-inline-block ml-2" ng-show="rooms.reviews_count">
										@{{ rooms.reviews_count }} {{ trans_choice('messages.header.review',1) }}@{{ (rooms.reviews_count > 1) ? 's' : '' }}
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="more-filter">
				<!-- Room Type Filter -->
				<div class="filter-room_type d-lg-none d-md-none">
					<h4> {{ trans("messages.lys.room_type") }} </h4>
					<div class="d-flex mt-3 align-items-center">
						<div class="col-12 p-0">
							@foreach($room_types as $row)
							<div class="d-flex">
								<input type="checkbox" value="{{ $row->id }}" id="mob_room_type_{{ $row->id }}" class="room-type" ng-click="update_filter_status()" {{in_array($row->id, $room_type_selected)  ? "checked" : ""}} />
								<label class="type-info" for="mob_room_type_{{ $row->id }}">
									<h4>
										{{ $row->name }}
									</h4>
									<p> 
										{{ $row->description }}
									</p>
								</label>
								<div class="type-img">
									<i class="icon-activities"> 
										<img class="img-fluid" src="{{ $row->image_name }}">
									</i>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
				<!-- Room Type Filter -->

				<!-- Price Filter -->
				<div class="filter-price d-lg-none d-md-none mb-5">
					<h4> {{ trans("messages.inbox.price") }} </h4>
					<div class="d-flex align-items-center">
						<div class="col-12 p-0">
							<div class="price-label d-flex align-items-center">
								<div class="price-min">
									<span>{{ html_string($currency_symbol) }}</span>
									<span class="price" class="min_text" id="min_text">
										@{{ min_value }}
									</span>
								</div>
								<span class="mx-2">-</span>
								<div class="price-min">
									<span>{{ html_string($currency_symbol) }}</span>
									<span class="price" class="max_text" id="max_text">
										@{{ max_value }} @{{ (max_value == max_slider_price) ? '+' : '' }}
									</span>
								</div>
							</div>
							<div id="mob_slider" class="mt-4 price-range-slider"></div>
						</div>
					</div>
				</div>
				<!-- Price Filter -->

				<!-- Instant Book Filter -->
				<div class="filter-instant_book d-lg-none d-md-none my-4">
					<div class="instant-book d-flex">
						<div class="instant-info">
							<h4>
								@lang('messages.lys.instant_book')
								<span>
									<i class="icon icon-instant-book"></i>
								</span>
							</h4>
							<p>
								@lang('messages.search.instant_book_desc')
							</p>
						</div>
						<div class="instant-checkbox">
							<label class="checkbox" ng-class="instant_book == '1' ? 'instant-checked' : ''">
								<input type="checkbox" name="instant_book" id="instant_book" ng-model="instant_book" ng-init="instant_book = '{{$instant_book}}'" ng-true-value="'1'" ng-false-value="'0'">
							</label>
						</div>
					</div>
				</div>
				<!-- Instant Book Filter -->

				<div class="filter-rooms">
					<h4>@lang('messages.lys.rooms_beds')</h4>
					<div class="d-flex align-items-center">
						<div class="col-6 col-md-4 p-0">
							<h4>
								@lang("messages.lys.bedrooms")
							</h4>
						</div>
						<div class="value-changer d-flex align-items-center ml-auto ml-md-0" ng-init="search_bedrooms = {{$bedrooms >0 ? $bedrooms : 0}}">
							<button ng-disabled="search_bedrooms==0" class="value-button" id="decrease" ng-click="search_bedrooms = search_bedrooms - 1">-</button>
							<input type="text" class="guest-input mx-2 search_bedrooms" ng-value="search_bedrooms+'+'" readonly="" />
							<button ng-disabled="search_bedrooms==10" class="value-button" id="increase" ng-click="search_bedrooms = search_bedrooms-0 + 1">+</button>
						</div>
					</div>
					<div class="d-flex align-items-center" ng-init="search_beds = {{$beds > 0 ? $beds : 0}}">
						<div class="col-6 col-md-4 p-0">
							<h4>
								{{trans("messages.lys.beds")}}
							</h4>
						</div>
						<div class="value-changer d-flex align-items-center ml-auto ml-md-0">
							<button ng-disabled="search_beds==0" class="value-button" ng-click="search_beds = search_beds - 1">-</button>
							<input type="text" class="guest-input mx-2 search_beds" value="@{{ search_beds+'+' }}" readonly="" />
							<button ng-disabled="search_beds==16" class="value-button" ng-click="search_beds = search_beds-0 + 1">+</button>
						</div>
					</div>
					<div class="d-flex align-items-center" ng-init="search_bath = {{$bathrooms > 0 ? $bathrooms : 0}}">
						<div class="col-6 col-md-4 p-0">
							<h4>
								{{trans("messages.lys.bathrooms")}}
							</h4>
						</div>
						<div class="value-changer d-flex align-items-center ml-auto ml-md-0">
							<button  ng-disabled="search_bath==0" class="value-button" id="decrease" ng-click="search_bath = search_bath - 0.5">-</button>
							<input type="text" class="guest-input mx-2 search_bath" value="@{{ search_bath+'+' }}" readonly="" />
							<button ng-disabled="search_bath==10" class="value-button" id="increase" ng-click="search_bath = search_bath-0 + 0.5">+</button>
						</div>
					</div>
				</div>
				@if($amenities->count() > 0)
				<div class="filter-list">
					<h4>
						{{ trans('messages.lys.amenities') }}
					</h4>
					<div class="d-flex flex-wrap row mt-3">
						@php $row_inc = 1 @endphp
						@foreach($amenities as $row_amenities)
						@if($row_inc <= 4)
						<div class="col-md-6 d-flex align-items-center">
							<input type="checkbox" id="amenities_{{ $row_amenities->id }}" value="{{ $row_amenities->id }}" class="amenities" {{(in_array($row_amenities->id, $amenities_selected)) ? 'checked' : ''}} />
							<label for="amenities_{{ $row_amenities->id }}">
								{{ $row_amenities->name }}
							</label>
						</div>
						@endif
						@php $row_inc++ @endphp
						@endforeach
						<div class="all-list d-flex flex-wrap w-100">
							@php $amen_inc = 1 @endphp
							@foreach($amenities as $row_amenities)
							@if($amen_inc > 4)
							<div class="col-md-6 align-items-center">
								<input type="checkbox" id="amenities_{{ $row_amenities->id }}" value="{{ $row_amenities->id }}" class="amenities" {{(in_array($row_amenities->id, $amenities_selected)) ? 'checked' : ''}} />
								<label class="search_check_label" for="amenities_{{ $row_amenities->id }}">
									{{ $row_amenities->name }}
								</label>
							</div>
							@endif
							@php $amen_inc++ @endphp
							@endforeach
							<div class="show-all-toggle col-12 mt-2 d-flex align-items-center">
								<div class="all-property mr-2">
									{{ trans('messages.header.seeall') }} {{ trans('messages.lys.amenities') }}
								</div>
								<div class="close-property mr-2">
									{{ trans('messages.home.close') }} {{ trans('messages.lys.amenities') }}
								</div>
								<i class="fa fa-angle-down" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
				@endif
				<div class="filter-list">
					<h4>{{ trans('messages.lys.property_type') }}</h4>
					<div class="d-flex flex-wrap row mt-3">
						@php $pro_inc = 1 @endphp
						@foreach($property_type_dropdown as $row_property_type)
						@if($pro_inc <= 4)
						<div class="col-md-6 d-flex align-items-center">
							<input type="checkbox" id="property_{{ $row_property_type->id }}" value="{{ $row_property_type->id }}" class="property_type" {{(in_array($row_property_type->id, $property_type_selected)) ? 'checked' : ''}} />
							<label class="search_check_label" for="property_{{ $row_property_type->id }}">
								{{ $row_property_type->name }}
							</label>
						</div>
						@endif
						@php $pro_inc++ @endphp
						@endforeach
						<div class="all-list d-flex flex-wrap w-100">
							@php $pro1_inc = 1 @endphp
							@foreach($property_type_dropdown as $row_property_type)
							@if($pro1_inc > 4)
							<div class="col-md-6 align-items-center">
								<input type="checkbox" id="property_{{ $row_property_type->id }}" value="{{ $row_property_type->id }}" class="property_type" {{(in_array($row_property_type->id, $property_type_selected)) ? 'checked' : ''}} />
								<label for="property_{{ $row_property_type->id }}">
									{{ $row_property_type->name }}
								</label>
							</div>
							@endif
							@php $pro1_inc++ @endphp
							@endforeach
							<div class="show-all-toggle col-12 mt-2 d-flex align-items-center">
								<div class="all-property mr-2">
									{{ trans('messages.header.seeall') }} {{ trans('messages.lys.property_type') }}
								</div>
								<div class="close-property mr-2">
									{{ trans('messages.home.close') }} {{ trans('messages.lys.property_type') }}
								</div>
								<i class="fa fa-angle-down" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex align-items-center justify-content-between filter-btn">
				<a href="javascript:void(0)" class="ml-auto mr-4 cancel-filter" ng-click="reset_filters('more_filters')">
					{{ trans('messages.your_reservations.cancel') }}
				</a>
				<a href="javascript:void(0)" class="btn btn-secondary apply-filter" ng-click="apply_filters('more_filters')">
					{{ trans('messages.wishlist.see_homes') }}
				</a>
			</div>
			<h2 ng-hide="room_result.data.length" class="no-results text-center" id="no_results" ng-cloak>
				{{ trans('messages.search.no_results_found') }}
			</h2>
			<div class="results-pagination mb-4" ng-cloak>
				<div class="pagination-container">
					<div class="results-count">
						<p>
							<span ng-if="room_result.to != 0">
								@{{ room_result.from }} –
							</span>
							@{{ room_result.to }} {{ trans('messages.search.of') }} @{{ room_result.total }} {{ trans('messages.search.rentals') }}
						</p>
					</div>
					<posts-pagination ng-if="room_result.total != 0"></posts-pagination>
				</div>
			</div>
		</div>
		<div class="col-lg-4 search-map p-0">
			<div id="map_canvas" role="presentation" class="map-canvas">
			</div>
		</div>
		<div class="filter-section text-center">
			<div class="d-inline-flex align-items-center justify-content-center">
				<button type="button" class="btn btn-primary show-map">
					<span>{{ trans('messages.search.map') }}</span>
				</button>
				<button type="button" class="btn btn-primary show-result">
					<span>{{ trans('messages.search.results') }}</span>
				</button>
			</div>
		</div>
	</div>
	<input type="hidden" id="location" value="{{ $location }}">
	<input type="hidden" id="lat" value="{{ $lat }}">
	<input type="hidden" id="long" value="{{ $long }}">
	<!-- Language Translate for inside Search maps -->
	<input type="hidden" id="current_language" value= "{{ trans('messages.search.search_name') }}">
	<input type="hidden" id="redo_search_value" value= "{{ trans('messages.search.redo_search_name') }}">
	<!-- Pagination next prev used-->
	<input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }}">
	<input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }}">
	<input type="hidden" id="viewport" value='{!! json_encode($viewport) !!}' ng-model="viewport">

	<div class="guest-mobile-drop flex-column">
		<div class="guest-mobile-top d-flex align-items-center py-3">
			<!-- <div class="guest-filter-close" ng-click="reset_filters('guests')">
				<i class="close" aria-hidden="true"></i>
			</div> -->
			<div class="ml-auto d-flex align-items-center text-right">
				<span>
					{{ trans_choice('messages.home.guest',2) }}
				</span>
				<a class="cancel-link green-link ml-5" href="javascript:void(0)" ng-click="removeActive('guests')">
					{{ trans('messages.your_reservations.cancel') }}
				</a>
			</div>
		</div>
		<div class="guest-mobile-info d-flex align-items-center">
			<label>
				{{ trans_choice('messages.home.guest',1) }}
			</label>
			<div class="value-changer d-flex ml-auto align-items-center" ng-init="search_guest={{$guest}}">
				<button ng-disabled="search_guest==1" class="value-button" id="decrease" ng-click="search_guest=search_guest-1" value="Decrease Value">-</button>
				<input type="text" class="guest-input mx-2" ng-value="search_guest+'+'" readonly="" />
				<button ng-disabled="search_guest == 16" class="value-button" id="increase" ng-click="search_guest=search_guest-0+1" value="Increase Value">+</button>
			</div>
		</div>
		<div class="mt-auto py-4 text-center filter-btn">
			<a href="javascript:void(0)" ng-click="apply_filters('guests')" class="btn btn-primary seehome">
				{{ trans('messages.wishlist.see_homes') }}
			</a>
		</div>
	</div>

	<div class="date-mobile-drop">
		<div class="date-mobile-top d-flex align-items-center py-3">
			<!-- <div class="date-filter-close" ng-click="reset_filters('guests')">
				<i class="close" aria-hidden="true"></i>
			</div> -->
			<div class="ml-auto d-flex align-items-center text-right">
				<span>
					{{trans('messages.header.when')}}
				</span>
				<a class="green-link ml-4 mobile_date_clear" href="javascript:void(0)">
					{{ trans('messages.payments.clear') }}
				</a>				
				<a class="cancel-link green-link ml-4" href="javascript:void(0)" ng-click="reset_filters('dates')">
					{{ trans('messages.your_reservations.cancel') }}
				</a>
			</div>
		</div>
		<div class="date-mobile-info">
			<div class="custom-datepicker" id="daterangepicker_modal_div"></div>
		</div>
		<div class="mt-auto py-4 text-center filter-btn">
			<a href="javascript:void(0)" ng-click="apply_filters('dates')" class="btn btn-primary seehome">
				{{ trans('messages.wishlist.see_homes') }}
			</a>
		</div>
	</div>

	<!--Wishlist Modal -->
	<div class="wishlist-popup modal fade" id="wishlist-modal" tabindex="-1" role="dialog" aria-labelledby="Wishlist-ModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header border-0 p-0">
					<button type="button" class="close wl-modal-close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body p-0">
					<div class="d-md-flex">
						<div class="col-12 col-md-7 background-listing-img d-flex" style="background-image:url();">
							<div class="mt-auto mb-3 d-flex align-items-center">
								<div class="profile-img mr-3">
									<img class="host-profile-img" src="">
								</div>
								<div class="profile-info">
									<h4 class="wl-modal-listing-name">
									</h4>
									<span class="wl-modal-listing-address">
									</span>
								</div>
							</div>
						</div>
						<div class="add-wishlist d-flex flex-column col-12 col-md-5">
							<div class="wish-title pt-5 pb-3">
								<h3>
									{{ trans('messages.wishlist.save_to_wishlist') }}
								</h3>
							</div>
							<div class="wl-modal-wishlists d-flex flex-grow-1 flex-column">
								<ul class="mb-auto">
									<li class="d-flex align-items-center justify-content-between" ng-repeat="item in wishlist_list" ng-class="(item.saved_id) ? 'active' : ''" ng-click="wishlist_row_select($index)" id="wishlist_row_@{{ $index }}">
										<span class="d-inline-block text-truncate">@{{ item.name }}</span>
										<div class="wl-icons ml-2">
											<i class="icon icon-heart-alt" ng-hide="item.saved_id"></i>
											<i class="icon icon-heart" ng-show="item.saved_id"></i>
										</div>
									</li>
								</ul>
								<div class="wl-modal-footer my-3 pt-3">
									<form class="wl-modal-form d-none">
										<div class="d-flex align-items-center">
											<input type="text" class="wl-modal-input flex-grow-1 border-0" autocomplete="off" id="wish_list_text" value="" placeholder="Name Your Wish List" required>
											<button id="wish_list_btn" class="btn btn-contrast ml-3">
												{{ trans('messages.wishlist.create') }}
											</button>
										</div>
									</form>
									<div class="create-wl">
										<a href="javascript:void(0)">
											{{ trans('messages.wishlist.create_new_wishlist') }}
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop
@push('scripts')
<script type="text/javascript">
	var min_slider_price = {!! $default_min_price !!};
	var max_slider_price = {!! $default_max_price !!};
	var min_slider_price_value = {!! $min_price !!};
	var max_slider_price_value = {!! $max_price !!};
	$(document).ready(function() {
		$("#wish_list_text").keyup(function(){
			$('#wish_list_btn').prop('disabled', true);
			var v_value =  $(this).val();
			var len =v_value.trim().length;
			if (len == 0) {
				$('#wish_list_btn').prop('disabled', true);
			}
			else {
				$('#wish_list_btn').prop('disabled', false);
			}
		});
	});
	var APPLY_LANG = "@lang('messages.payments.apply')";
	var CLEAR_LANG = "@lang('messages.payments.clear')";
</script>
<script src="{{url('js/search.js?v='.$version)}}"></script>
@endpush
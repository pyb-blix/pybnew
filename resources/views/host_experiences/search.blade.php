@extends('template')
@section('main') 
<main ng-controller="search-page" ng-init="setParams()">
  <div class="search_filter" ng-init="review_text='{{ trans_choice("messages.header.review",1) }}'; reviews_text='{{ trans_choice("messages.header.review",2) }}';">
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
                <button ng-disabled="search_guest == 10" class="value-button" id="increase" ng-click="search_guest=search_guest-0+1" value="Increase Value">+</button>
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
        <li class="nav-item dropdown keep-open category-filter-btn">
          <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target-filter="category_types" ng-class="filter_active('category_types')" ng-click="update_opened_filter('category_types')">
            <span>
              {{ trans('messages.home.category') }}
            </span>
            <span ng-if="is_filter_active('category_types')">
              @{{filter_btn_text('category_types')}}
            </span>
          </button>
          <div class="host-category-dropdown dropdown-menu">
            <ul class="d-flex flex-wrap">
              @foreach($host_experience_categories as $row)
              <li>
                <label class="checkbox d-inline-block text-truncate">
                  <input class="room-type host_experience_category" id="category_{{$row->id}}" type="checkbox" value="{{ $row->id }}" {{in_array($row->id, $cat_type_selected) ? 'checked' : '' }} >
                  <span>
                    {{ $row->name }}
                  </span>
                </label>
              </li>
              @endforeach
            </ul>
            <div class="my-4 d-flex align-items-center justify-content-between filter-btn">
              <a href="javascript:void(0)" class="cancel-filter" ng-click="reset_filters('category_types')">
                {{ trans('messages.your_reservations.cancel') }}
              </a>
              <a href="javascript:void(0)" class="apply-filter" ng-click="apply_filters('category_types')">
                {{ trans('messages.payments.apply') }}
              </a>
            </div>
          </div>
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

  <div id="mobslider" class="price-range-slider p2-slider-new d-none"></div>

  <input class="d-none" id="checkin" ng-model="checkin" readonly="readonly" autocomplete="off" type="text" ng-change="search_result();" placeholder="{{ trans('messages.home.checkin') }}" ng-init="checkin = '{{ $checkin }}'">

  <input class="d-none" ng-model="checkout" id="checkout" readonly="readonly" autocomplete="off" type="text" ng-change="search_result();" placeholder="{{ trans('messages.home.checkout') }}" ng-init="checkout = '{{ $checkout }}'">

  <div class="search-content d-flex" ng-init='per_guest_text = "{{ trans("messages.wishlist.per_guest")}}"'>
    <div class="col-12 col-lg-8 search-content-filters">
      <div class="search-wrap d-md-flex flex-wrap row">
        <div class="search-list col-12 col-md-6 col-lg-4" ng-repeat="rooms in room_result.data" ng-cloak>
          <div ng-mouseover="on_mouse($index);" ng-mouseleave="out_mouse($index);">
            <div class="search-img">  
              <div id="search-img-slide" class="search-img-slide owl-carousel">   
                <a href="@{{ rooms.link }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" ng-repeat="photo in rooms.all_photos" target="listing_@{{ rooms.id }}" id="rooms_image_@{{ rooms.id}}">
                  <img ng-src="@{{ photo.image_url }}" alt="@{{ photo.name }}">
                </a>
              </div>
              <div class="search-wishlist">
                <input type="checkbox" id="wishlist-widget-@{{ rooms.id }}" name="wishlist-widget-@{{ rooms.id }}" data-for-hosting="@{{ rooms.id }}" ng-checked="rooms.saved_wishlists">
                <label for="wishlist-widget-@{{ rooms.id }}" ng-init="current_refinement='{{ $current_refinement }}';">
                  <i class="icon icon-heart"></i>
                  <i class="icon icon-heart-alt" @if(Auth::user()) data-toggle="modal"@endif data-target="#wishlist-modal" data-what="{{$current_refinement}}" id="wishlist-widget-icon-@{{ rooms.id }}" ng-click="saveWishlist(rooms)"></i>
                </label>
              </div>
            </div>

            <div class="search-info">
              <h4 class="text-truncate">
                <span> @{{ rooms.category_details.name }} </span>
                <span>·</span>
                <span> @{{ rooms.host_experience_location.city }} </span>
              </h4>
              <a href="@{{ rooms.link }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}" target="listing_@{{ rooms.id }}" class="text-truncate" title="@{{ rooms.title }}"> 
                @{{ rooms.title }}
              </a>
              <p class="search-price">    
                <span ng-bind-html="rooms.currency.symbol"></span>
                @{{ rooms.session_price }} 
                {{ trans("messages.wishlist.per_guest") }}
              </p>
              <div class="search-ratings">
                <a href="@{{ rooms.link }}?checkin=@{{ checkin }}&checkout=@{{ checkout }}&guests=@{{ guests }}">
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
     <h2 ng-hide="room_result.data.length" class="no-results text-center" id="no_results" ng-cloak>
      {{ trans('messages.search.no_results_found') }}
    </h2>
    <div class="results-pagination mb-4">
      <div class="pagination-container" ng-cloak>
        <div class="results-count">
          <p>
            <span ng-if="room_result.to != 0">
              @{{ room_result.from }} – 
            </span>
            @{{ room_result.to }} {{ trans('messages.search.of') }} @{{ room_result.total }} {{ trans('messages.search.experience') }}
          </p>
        </div>
        <posts-pagination ng-if="room_result.total != 0"></posts-pagination>
      </div>
    </div>
  </div>

  <div class="col-lg-4 search-map">
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
<input type="hidden" id="current_language" value= "{{ trans('messages.search.search_name') }} ">
<input type="hidden" id="redo_search_value" value= "{{ trans('messages.search.redo_search_name') }} ">
<!-- Pagination next prev used-->
<input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }} ">
<input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }} ">
<input type="hidden" id="viewport" value='{!! json_encode($viewport) !!}' ng-model="viewport">

<div class="category-mobile-drop">
 <div class="category-mobile-top d-flex align-items-center py-3">
  <!-- <div class="category-filter-close" ng-click="reset_filters('category_types')">
   <i class="close" aria-hidden="true"></i>
 </div> -->
 <div class="d-flex align-items-center text-right ml-auto">
  All Filters<span>(@{{filter_btn_text('filters_count')}})</span>
  <a href="javascript:void(0)" ng-click="reset_filters('category_types')" class="cancel-link green-link ml-4">
    {{ trans('messages.your_reservations.cancel') }}
  </a>
</div>
</div>
<div class="mobile-category-wrap">
  <h4>
    {{ trans('messages.home.category') }}
  </h4>
  <ul>
    @foreach($host_experience_categories as $row)
    <li>
      <label class="checkbox">
        <input class="room-type host_experience_category" type="checkbox" id="mob_category_{{$row->id}}" value="{{ $row->id }}" {{in_array($row->id, $cat_type_selected) ? 'checked' : ''}} >
        <span>
          {{ $row->name }}
        </span>
      </label>
    </li>
    @endforeach
  </ul>
</div>
<div class="mt-auto py-4 text-center filter-btn">
  <a href="javascript:void(0)" ng-click="apply_filters('category_types')" class="btn btn-primary seehome">
    {{ trans('messages.wishlist.see_result') }}
  </a>
</div>
</div>

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
<script src="{{ asset('js/host_experiences/search_new.js?v='.$version) }}"></script>
@endpush
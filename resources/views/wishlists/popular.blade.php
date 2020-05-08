@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">
  @include('common.wishlists_subheader')
  <div class="popular-list">
    <div class="container">
      <div data-feed-container="" class="list-view">
        <div data-infinity-pageid="4">
          @if($result->count() == 0)
          <h2 class="no-results my-4">
            {{ trans('messages.search.no_results_found') }}!
          </h2>
          @endif
          <div class="row">
            <ul class="wishlists-wrap d-md-flex flex-wrap mb-5">
              @foreach($result as $row)
              <li class="col-12 col-md-6">
                <div class="panel-image">
                  <a href="{{ url('rooms/'.$row->id) }}" class="wishlist-bg-img" target="_blank">
                    <img src="{{ $row->photo_name }}">
                  </a>
                  <span class="price-amount">
                    <sup>
                      {{ html_string($row->rooms_price->currency->symbol) }}
                    </sup>
                    {{ $row->rooms_price->night }}
                  </span>
                  <div class="wishlist-icon">
                    <span class="rich-toggle wish_list_button wishlist-button not_saved" data-hosting_id="{{ $row->id }}" data-img="{{ $row->photo_name }}" data-name="{{ $row->name }}" data-address="{{ $row->rooms_address->city }}" title="Save to Wish List">
                      <input type="checkbox" id="wishlist-widget-{{ $row->id }}" name="wishlist-widget-{{ $row->id }}" data-for-hosting="{{ $row->id }}" ng-checked="{{$row->saved_wishlists}}">
                      <label for="wishlist-widget-{{ $row->id }}">
                        <i class="icon icon-heart icon-rausch icon-size-2 rich-toggle-checked"></i>
                        <i class="icon icon-heart-alt" id="wishlist-widget-icon-{{ $row->id }}" data-toggle="modal" data-target="#wishlist-modal" data-room_id="{{ $row->id }}" data-img="{{ $row->photo_name }}" data-name="{{ $row->name }}" data-address="{{ $row->rooms_address->city }}" data-host_img="{{ $row->users->profile_picture->src }}"></i>
                      </label>
                    </span>
                  </div>
                  <a href="{{ url('users/show/'.$row->user_id) }}" class="profile-img" title="{{ $row->users->first_name }}">
                    <img src="{{ $row->users->profile_picture->src }}" height="60" width="60" alt="{{ $row->users->first_name }}">
                  </a>
                </div>
                <div class="panel-body">
                  <h4 class="listing-name" title="{{ $row->name }}">
                    <a class="normal-link" href="{{ url('rooms/'.$row->id) }}" target="_blank">
                      {{ $row->name }}
                    </a>
                  </h4>
                  <h5 class="listing-location">
                    <a href="{{ url('s/?location='.$row->rooms_address->city) }}">
                      {{ $row->rooms_address->city }}, {{ $row->rooms_address->country_name }}
                    </a>
                  </h5>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
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
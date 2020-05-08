@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists" ng-cloak>
  <input type="hidden" value="{{ $wl_id }}" id="wl_id">
  @include('common.wishlists_subheader')
  <div class="wishlist-detail my-4 my-md-5">  
    <div class="container">
      <div class="show_view" ng-show="show_edit != true">
        @if($owner)
        <div class="social-share-alert alert alert-info fade show text-center mb-4">
          <h5>
            {{ trans('messages.rooms.share') }} ‘{{ $result[0]->name }}’ {{ trans('messages.wishlist.with_friends') }}!
            <div class="social-share-widget">
              <span class="share-title">
                {{ trans('messages.rooms.share') }}:
              </span>
              <span class="share-triggers">
                <a class="share-btn share-facebook-btn link-icon" target="_blank" href="http://www.facebook.com/sharer.php?u={{ url('wishlists/'.$result[0]->id) }}">
                  <i class="icon icon-facebook"></i>
                </a>
                <a class="share-btn share-twitter-btn link-icon" target="_blank"  href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=Wow.+I+love+this+Wish+List+on+%40{{ $site_name }}+%23lovemywishlist&amp;url={{ url('wishlists/'.$result[0]->id) }}&amp;original_referer={{ url('wishlists/'.$result[0]->id) }}">
                  <i class="icon icon-twitter"></i>
                </a>
                <a href="javascript:void();" class="share-btn share-envelope-btn link-icon" data-toggle="modal" data-target="#share_wishlist-popup">
                  <i class="icon icon-envelope"></i>
                </a>
              </span>
            </div>
          </h5>
        </div>
        @endif
        <div class="wishlist-homes card">
          {{--HostExperienceBladeCommentStart--}}
          <ul class="card-header tabs tabs-header">
            @if($result[0]->rooms_count > 0)
            <li class="d-inline-flex">
              <button ng-click="setwishlisttype('homes')" ng-class="{'btn-secondary' : active_wishlist === 'homes'}" class="tab-btn">
                {{ trans('messages.header.homes') }}
              </button>
            </li>
            @else
            <span ng-init="active_wishlist='experience'"></span>
            @endif
            @if($result[0]->host_experience_count > 0)
            <li class="d-inline-flex">
              <button ng-click="setwishlisttype('experience')" ng-class="{'btn-secondary' : active_wishlist === 'experience'}" class="tab-btn">
                {{ trans('messages.header.experiences') }}
              </button>
            </li>
            @endif
          </ul>
          {{--HostExperienceBladeCommentEnd--}}
          <div class="card-body">
            <div class="top-bar row wishlists-user">
              <div class="col-12 col-md-6 d-flex">
                <a href="{{ url('/') }}/users/show/{{ $result[0]->user_id }}" class="profile-img" title="{{ $result[0]->users->first_name }}">
                  <img src="{{ $result[0]->profile_picture->src }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
                </a>
                <div class="ml-3 wishlist-header-text">
                  <a class="theme-link" href="{{ url('/') }}/users/{{ $result[0]->user_id }}/wishlists">
                    {{ $result[0]->users->first_name }}’s {{ trans_choice('messages.header.wishlist', 2) }}
                  </a>
                  <div>
                    <span>
                      {{ $result[0]->name }}:
                    </span>
                    <strong>
                      @{{ wishlist_count }}
                    </strong>
                    @if($owner)
                    <a class="theme-link" href="javascript:void(0);" ng-click="show_edit = true;">
                      {{ trans('messages.reviews.edit') }}
                    </a>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 text-center text-md-right my-4 my-md-0">
                <div class="btn-group view-btn-group">
                  <button class="btn" data-view="list" disabled="disabled" id="list">
                    {{ trans('messages.wishlist.list_view') }}
                  </button>
                  <button class="btn" data-view="map" id="map">
                    {{ trans('messages.wishlist.map_view') }}
                  </button>
                </div>
              </div>
            </div>

            <ul class="results-list">
              <li class="row listing" ng-if="common_loading">
                <div style="margin-bottom: 100px;" class="loading w-100"></div>
              </li>
              <li ng-repeat="wl_home in wishlists_homes" class="row listing" id="li_@{{ wl_home.room_id }}">
                <label class="d-md-flex w-100" for="hosting_id_@{{ wl_home.room_id }}">
                  <div class="col-12 col-md-4 col-lg-3 slideshow-container">
                    <div class="listing_slideshow_thumb_view">
                      <div class="listing-slideshow">
                        <img class="w-100 img-fluid" src="@{{ wl_home.photo_name }}" id="wishlist_image_@{{ wl_home.room_id }}" rooms_image="">
                      </div>
                      <a href="javascript:void();" data-prevent-default="" class="photo-paginate target-next prev marker_slider" data-room_id="@{{ wl_home.room_id }}"><i></i></a>
                      <a href="javascript:void();" data-prevent-default="" class="photo-paginate target-next next marker_slider" data-room_id="@{{ wl_home.room_id }}"><i></i></a>
                    </div>
                  </div>
                  <div class="wl-room-info col-12 col-md-8 col-lg-9 mt-3 mt-md-0">
                    <div class="row">
                      <div class="col-12 col-md-9">
                        <h2>
                          <a class="theme-link" href="{{ url('/') }}/rooms/@{{ wl_home.room_id }}">
                            @{{ wl_home.rooms.name }}
                          </a>
                        </h2>
                        <p>
                          @{{ wl_home.rooms_address.city }}
                        </p>
                        <ul class="d-flex">
                          <li>
                            @{{ wl_home.rooms.room_type_name }}
                          </li>
                          <li>
                            @{{ wl_home.rooms.bed_type_name }}
                          </li>
                          <li>
                            Sleeps @{{ wl_home.rooms.accommodates }}
                          </li>
                        </ul>
                      </div>

                      <div class="col-12 col-md-3 wl-room-price text-md-right my-2 my-md-0">
                        <h4 class="price-amount">
                          <sup ng-bind-html="wl_home.rooms_price.currency.symbol"></sup>            
                          @{{ wl_home.rooms_price.night }}
                        </h4>                    
                        <span class="d-block">
                          {{ trans('messages.wishlist.per_night') }}
                        </span>
                      </div>
                    </div>

                    @if($owner)
                    <div class="row mt-3">
                      <div class="col-8 d-flex">
                        <div class="profile-img">
                          <img src="@{{ wl_home.profile_picture.header_src }}" alt="@{{ wl_home.users.first_name }}"  width="40" height="40">
                        </div>
                        <form id="add-note-form" class="note-container ml-3 flex-grow-1" data-room_id="@{{ wl_home.room_id }}">
                          <textarea name="note" id="note_@{{ wl_home.room_id }}" placeholder="{{ trans('messages.wishlist.add_note') }}">@{{ wl_home.note }}</textarea>
                          <div class="mt-3 text-right add-note-wrap">
                            <button type="button" ng-click="add_home_note(wl_home.room_id,$index)" class="btn add-note">
                              {{ trans('messages.wishlist.save_note') }}
                            </button>
                            <div id="noteloader_@{{$index}}" class="text-center save-note-loading" style="display:none">
                              <img class="wish_dot_load" src="{{ url('/') }}/images/loading.svg">
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="col-4 text-right wl-remove pl-0">
                        @if($owner)
                        <a class="btn remove_listing_button" ng-click="delete_wishlist_home($index,wishlists_homes)" data-room_id="@{{ wl_home.room_id }}">
                          <span class="icon icon-remove mr-1 mr-md-2"></span>
                          {{ trans('messages.account.remove') }}
                        </a>
                        @endif
                      </div>
                    </div>
                    @endif
                  </div>
                </label>
              </li>

              <li ng-repeat="we_home in wishlists_experience" class="row listing" id="li_@{{ we_home.room_id }}">
                <label class="d-md-flex w-100" for="hosting_id_@{{ we_home.room_id }}">
                  <div class="col-12 col-md-4 col-lg-3 slideshow-container">
                    <div class="listing_slideshow_thumb_view">
                      <div class="listing-slideshow">
                        <img class="w-100 img-fluid" src="@{{ we_home.photo_name }}" id="experience_wishlist_image_@{{ we_home.room_id }}" rooms_image="">
                      </div>
                      <a href="javascript:void();" data-prevent-default="" class="photo-paginate target-next prev experience_marker_slider" data-room_id="@{{ we_home.room_id }}"><i></i></a>
                      <a href="javascript:void();" data-prevent-default="" class="photo-paginate target-next next experience_marker_slider" data-room_id="@{{ we_home.room_id }}"><i></i></a>
                    </div>
                  </div>
                  <div class="wl-room-info col-12 col-md-8 col-lg-9 mt-3 mt-md-0">
                    <div class="row">
                      <div class="col-12 col-md-9">
                        <h2>
                          <a class="theme-link" href="{{ url('/') }}/experiences/@{{ we_home.room_id }}">
                            @{{ we_home.host_experiences.title }}
                          </a>
                        </h2>
                        <p>
                          @{{ we_home.host_experiences.host_experience_location.city }}
                        </p>
                      </div>
                      <div class="col-12 col-md-3 wl-room-price text-md-right my-2 my-md-0">
                        <h4 class="price-amount">
                          <sup ng-bind-html="we_home.host_experiences.currency.symbol"></sup>
                          @{{ we_home.host_experiences.session_price }}
                        </h4>
                        <span class="d-block">
                          {{ trans('messages.wishlist.per_guest') }}
                        </span>
                      </div>
                    </div>
                    @if($owner)
                    <div class="row mt-3">
                      <div class="col-8 d-flex">
                        <div class="profile-img">
                          <img src="@{{ we_home.profile_picture.header_src }}" alt="@{{ we_home.users.first_name }}" class="profile-img" width="40" height="40">
                        </div>
                        <form id="add-note-form" class="note-container ml-3 flex-grow-1" data-room_id="@{{ we_home.room_id }}">
                          <textarea name="note" id="note_@{{ we_home.room_id }}" placeholder="{{ trans('messages.wishlist.add_note') }}">@{{ we_home.note }}</textarea>
                          <div class="mt-3 text-right add-note-wrap">
                            <button type="button" ng-click="add_home_note(we_home.room_id,$index)" class="btn add-note">
                              {{ trans('messages.wishlist.save_note') }}
                            </button>
                            <div id="noteloader_@{{$index}}" class="text-center save-note-loading" style="display:none">
                              <img class="wish_dot_load" src="{{ url('/') }}/images/loading.svg">
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="col-4 text-right wl-remove pl-0">
                        @if($owner)
                        <a class="btn remove_listing_button" ng-click="delete_experience_home($index,wishlists_experience)" data-room_id="@{{ we_home.room_id }}">
                          <span class="icon icon-remove mr-1 mr-md-2"></span>
                          {{ trans('messages.account.remove') }}
                        </a>
                        @endif
                      </div>
                    </div>
                    @endif
                  </div>
                </label>
              </li>
            </ul>
          </div>
          <div data-map-container="" class="results-map" id="results_map"></div>
        </div>
      </div>

      <!-- Edit -->
      <div class="edit_view" ng-show="show_edit == true">
        <div class="media align-items-center">
          <a href="{{ url('/') }}/users/{{ $result[0]->user_id }}/wishlists" class="profile-img" title="{{ $result[0]->users->first_name }}">
            <img src="{{ url($result[0]->users->profile_picture->src) }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
          </a>
          <div class="media-body pl-2">
            <div class="wishlist-header-text">
              <span>
                {{ trans('messages.reviews.edit') }} {{ trans_choice('messages.header.wishlist', 1) }}:
              </span>
              <strong></strong>
            </div>
          </div>
        </div>

        <div class="wishlists-body mt-4 row align-items-start">
          <div class="col-12 col-md-4">
            <div class="card">
              <a href="{{ url('/') }}/wishlists/{{ $result[0]->id }}" class="wishlist-bg-img" style="background-image:url('{{ @$result[0]->saved_wishlists[0]->photo_name }}');">
                <div class="card-body wishlist-card-info d-flex align-items-center justify-content-center flex-column">
                  @if($result[0]->privacy)
                  <i class="icon icon-lock"></i>
                  @endif
                  <h2 class="text-truncate">
                    {{ $result[0]->name }}
                  </h2>
                  <div class="btn btn-guest">
                    <span class="d-inline-block">
                      {{ $result[0]->rooms_count }} {{ trans_choice('messages.wishlist.listing', $result[0]->rooms_count) }}
                    {{--HostExperienceBladeCommentStart--}}</span>, <span class="d-inline-block">{{ $result[0]->host_experience_count }} {{ trans_choice('messages.home.experience',$result[0]->host_experience_count) }}</span>
                    {{--HostExperienceBladeCommentEnd--}}
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-12 col-md-8 mt-4 mt-md-0">
            <form action="{{ url('edit_wishlist/'.$result[0]->id) }}" method="post">
              {!! Form::token() !!}
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-8">
                      <label for="wish-list-name">
                        {{ trans('messages.wishlist.title') }}
                      </label>
                      <input id="wish-list-name" name="name" value="{{ $result[0]->name }}" type="text">
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                      <label for="wishlist-edit-privacy-setting">
                        {{ trans('messages.wishlist.privacy_settings') }}
                      </label>
                      <div class="media">
                        <div class="media-body">
                          <div class="select select-block" id="wishlist-edit-privacy-setting">
                            <select name="privacy">
                              <option value="0" {{ ($result[0]->privacy == 0) ? 'selected' : '' }}>
                                {{ trans('messages.wishlist.everyone') }}
                              </option>
                              <option value="1" {{ ($result[0]->privacy == 1) ? 'selected' : '' }}>
                                {{ trans('messages.wishlist.only_me') }}
                              </option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="wish-btn d-flex align-items-center mt-3">
                <button type="submit" class="btn btn-primary">
                  {{ trans('messages.wishlist.save_changes') }}
                </button>
                <button class="btn cancel mx-2 mx-md-3" ng-click="show_edit = false">
                  {{ trans('messages.your_reservations.cancel') }}
                </button>
                <a href="javascript:void();" class="delete theme-link ml-auto" data-toggle="modal" data-target="#wishlist-delete_popup">
                  {{ trans('messages.lys.delete') }} {{ trans_choice('messages.header.wishlist', 1) }}
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="wl-preload" style="display: none;">
      <div class="page-container">
        <div class="row">
          <div class="col-12 col-md-12">
            <p class="wl-loading">{{ trans('messages.wishlist.loading') }}…</p>
          </div>
        </div>
      </div>
    </div>
    <div class="loading-indicator wishlist-loading-indicator loading d-none"></div>
  </div>

  <div class="modal fade" id="share_wishlist-popup">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-center"> {{ trans('messages.rooms.share') }} {{ trans_choice('messages.header.wishlist', 1) }} </h5>
          <button type="button" class="close icon" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="log-form">
            {!! Form::open(['url' => route('wishlist_share.email',['id' => $result[0]->id]), 'method' => 'POST', 'class' => 'email-collaborators']) !!}
            <div class="control-group">
              <div class="input-group">
                <label for="email">{{ trans('messages.wishlist.send_to') }}:</label>
                <input class="share_email_list" type="text" name="email" placeholder="{{ trans('messages.wishlist.email_address_placeholder') }}" spellcheck="false" autocomplete="false" required>
                <p class="email_error text-danger" style="display: none">{{ trans('messages.account.valid_email') }}</p>
              </div>
            </div>
            <div class="control-group">
              <div class="input-group">
                <label for="message">{{ trans('messages.wishlist.write_message') }}:</label>
                <textarea name="message" rows="3">{{ trans('messages.wishlist.checkout_places') }} {{ $site_name }}!</textarea>
              </div>
            </div>
            <div class="mt-4 text-right">
              <button type="submit" class="btn btn-primary wishlist_share_submit">{{ trans('messages.wishlist.send_email') }}</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Wishlist popup -->
  <div id="wishlist-delete_popup" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5> @lang('messages.lys.delete') {{ trans_choice('messages.wishlist.wishlist',1) }} </h5>
          <button type="button" class="close" data-dismiss="modal"></button>
        </div>
        <div class="modal-body py-4">
          <p>
            @lang('messages.lys.remove_calc_confirm_message')
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn">
            {{ trans('messages.home.close') }}
          </button>
          <a class="btn btn-primary wishlist-delete_confirm" href=""> {{ trans('messages.lys.delete') }} </a>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
@push('scripts')
<script type="text/javascript">
  var locations = {!! $result[0]->saved_wishlists !!};
  var wishlist_id = {!! $result[0]->id !!}
</script>
@endpush
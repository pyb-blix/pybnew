@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="host_experience_details">
  @section('available_date_item')
  <div class="update col-8 p-0">
    <p ng-if="data.is_reserved" class="badge-spots-left">
      @{{data.spots_left}} {{strtoupper(trans('experiences.details.spots_left'))}}
    </p>
    <div class="monthup">
      @{{format_date(data.date, "ddd, Do MMM")}}
    </div>
    <div class="timing">
      @{{format_time(data.start_time, "HH:mm")}} - @{{format_time(data.end_time, "HH:mm")}}. @{{data.currency_symbol}}@{{data.price}}
      <span>
        {{trans('experiences.details.per_person')}}
      </span>
    </div>
  </div>
  <div class="choose-btn col-4 p-0">
    @if(@Auth::user()->id != $host_experience->user_id)
    <a href="javascript:void(0);" id="js_choose_btn_@{{$index}}" class="btn experience-btn host-primary js-choose-booking-date" ng-click="start_booking($index, data.date, '{{@Auth::user()->id}}')" data-date="@{{data.date}}">
      {{trans('experiences.details.choose')}}
    </a>
    @endif
  </div>
  @endsection
  <div class="experience-whole-wrap" ng-cloak>
    <div class="container">
      <div class="experience-detail-wrap row d-flex flex-md-row-reverse flex-wrap">
        <div class="col-12 col-md-5 py-4 host-exp-slider ml-auto">
          <div class="host_experience_photos_slider" id="host_experience_photos_slider">
            <div class="whish_list_exp d-block d-md-none">
              <span class="wishlist_save save" ng-init="wishlisted={{ (@$is_wishlist > 0 ) ? '1' : '0' }}" data-toggle="modal" data-target="#wishlist-modal">
                <span ng-if="wishlisted==1" class="rich-toggle-checked">
                  <i class="icon icon-heart icon-rausch"></i>
                </span>
                <span ng-if="wishlisted==0" class="rich-toggle-unchecked">
                  <i class="icon icon-heart-alt icon-light-gray"></i>
                </span>
              </span>
            </div>
            @if($host_experience->host_experience_photos->count()>1)
            <button class="more_photo" type="button">
              More photos
            </button>
            @endif
            <div ng-class="{{($host_experience->host_experience_photos->count()<2) ? 'no_slide' : ' '}}"></div>
            <ul id="imageGallery">
              @foreach($host_experience->host_experience_photos as $photo)
              <li data-thumb="{{$photo->image_url}}" data-src="{{$photo->image_url}}">
                <img src="{{$photo->image_url}}"/>
              </li>
              @endforeach
            </ul>
          </div>

          <div class="select-date-wrap d-flex">
            <div class="price-review-info">
              <h4>
                {{html_string($host_experience->currency->symbol)}}{{$host_experience->session_price}}
                <span>
                  {{trans('experiences.details.per_person')}}
                </span>
              </h4>
              <div class="star1 all_reviews_popup_btn">
                @if($result->reviews->count())
                @if($result->reviews->count()>1)
                <a href="#all_review_popup" data-effect="mfp-zoom-in">
                  {!! $result->overall_star_rating !!}
                </a>
                @else
                {!! $result->overall_star_rating !!}
                @endif
                @endif
                <span>
                  @if(!$result->reviews->count())
                  {{ trans('messages.rooms.no_reviews_yet') }}
                  @else
                  {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
                  @endif
                </span>
              </div>
            </div>
            <div class="available_dates_popup_btn ml-auto border-0">
              <a href="#available_dates_popup" data-effect="mfp-zoom-in">
                <button class="btn btn-primary">
                  {{trans('experiences.details.see_dates')}}
                </button>
              </a>
            </div>
          </div>

          <div class="share-wishlist-wrap d-none d-md-flex align-items-center" ng-init="link_copied=0">
            <div class="share-icons d-flex align-items-center">
              <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Facebook</span>
                <i class="icon icon-facebook social-icon-size"></i>
              </a>
              <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/home?status=Love this! {{ $result->name }} {{ "@".$site_name}} Travel {{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Twitter</span>
                <i class="icon icon-twitter social-icon-size"></i>
              </a>
              <div class="d-inline-block" id="share-popups" ng-click="link_copied=0">
                <a href="#share-popup" data-effect="mfp-zoom-in">
                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>
              </div>
            </div>
            <div class="wishlist_save save ml-auto" ng-init="wishlisted={{ (@$is_wishlist > 0 ) ? '1' : '0' }}" data-toggle="modal" data-target="#wishlist-modal">
              <span ng-if="wishlisted==1" class="rich-toggle-checked d-flex align-items-center">
                {{ trans('messages.wishlist.save_to_wishlist') }}
                <i class="icon icon-heart icon-rausch ml-2"></i>
              </span>
              <span ng-if="wishlisted==0" class="rich-toggle-unchecked d-flex align-items-center">
                {{ trans('messages.wishlist.save_to_wishlist') }}
                <i class="icon icon-heart-alt icon-light-gray ml-2"></i>
              </span>
            </div>
          </div>
          <div class="price-vary-info d-none d-md-block mt-3">
            {{trans('experiences.details.price_may_vary_depending_upon_the_date_select')}}
          </div>
        </div>

        <div class="col-12 col-md-7 host-exp-info mb-4 mt-md-4">
          <div class="exp-host-top" ng-init="host_experience_id= '{{$host_experience->id}}'; available_dates = [];">
            <h1>
              {{$host_experience->title}}
            </h1>
            <div class="row">
              <div class="col-10 exp-info">
                <h3>
                  {{$host_experience->category_details->name}} {{trans('experiences.details.experience')}}
                </h3>
                <div class="host">
                  {{trans('experiences.details.hosted_by')}} 
                  <a target="_blank" href="{{url('users/show/'.$host_experience->user_id)}}"> 
                    {{$host_experience->user->first_name}}
                  </a>
                </div>
              </div>
              <div class="col-2 pro-img">
                <a target="_blank" href="{{url('users/show/'.$host_experience->user_id)}}">
                  <img class="img-fluid" src="{{$host_experience->user->profile_picture->header_src510}}">
                </a>
              </div>
            </div>
          </div>

          <div class="expr-offer">
            <ul class="timer my-4 py-4">
              <li class="d-flex align-items-center">
                <i class="icon icon3-clock mr-2" aria-hidden="true"></i>
                {{$host_experience->total_hours}} {{trans('experiences.manage.hours_total')}}
              </li>
              @if($host_experience->host_experience_provides->count() > 0)
              <li class="d-flex align-items-center">
                <i class="icon icon3-invoice mr-2" aria-hidden="true"></i>
                @foreach($host_experience->host_experience_provides as $k => $provide)
                {{@$provide->provide_item->name}} 
                @if($k+2  == $host_experience->host_experience_provides->count())
                {{trans('experiences.details.and')}}
                @elseif($k+1  != $host_experience->host_experience_provides->count())
                ,
                @endif
                @endforeach
              </li>
              @endif
              <li class="d-flex align-items-center">
                <i class="icon icon3-chat-oval-filled-speech-bubbles mr-2" aria-hidden="true"></i>
                {{trans('experiences.details.offered_in')}} {{$host_experience->language_details->name}}
              </li>
            </ul>
          </div>

          <div class="mobile-social-media share-wishlist-wrap d-md-none">
            <div class="share-icons d-flex align-items-center justify-content-center mb-2">
              <a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Facebook</span>
                <i class="icon icon-facebook social-icon-size"></i>
              </a>
              <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/home?status=Love this! {{ $result->name }} {{ "@".$site_name}} Travel {{ Request::url() }}" target="_blank">
                <span class="screen-reader-only">Twitter</span>
                <i class="icon icon-twitter social-icon-size"></i>
              </a>
              <div class="d-inline-block" id="share-popups" ng-click="link_copied=0">
                <a href="#share-popup" data-effect="mfp-zoom-in">
                  <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>
              </div>
            </div>
            <div class="price text-center">
              {{trans('experiences.details.price_may_vary_depending_upon_the_date_select')}}
            </div>
          </div>

          <div class="pep d-none">
            <div class="pep1">
              <h3>
                People are eyeing this experience. Over 10,600 people have viewed it this week.
              </h3>
            </div>
            <div class="forpep">
              <img src="{{url('images/host_experiences/glass.gif')}}">
            </div>
          </div>

          <ul class="host-about-wrap pt-4 pt-md-0">
            <li>
              <h4>
                {{trans('experiences.manage.about_your_host')}}, {{$host_experience->user->first_name}}
              </h4>
              <div class="forpro">
                <p>
                  {{$host_experience->about_you}}
                </p>
              </div>
            </li>
            <li>
              <h4>
                {{trans('experiences.manage.what_will_do')}}
              </h4>
              <div class="forpro">
                <p>
                  {{$host_experience->what_will_do}}
                </p>
              </div>
            </li>
            @if($host_experience->host_experience_provides->count() > 0)
            <li>
              <h4>
                {{trans('experiences.manage.what_will_provide')}}
              </h4>
              @foreach($host_experience->host_experience_provides as $provide)
              <p class="provider-info">
                {{$provide->name}}
                <img src="{{$provide->provide_item->image_url}}" class="provide_icon">
              </p>
              <p>
                {{$provide->additional_details}}
              </p>
              @endforeach
            </li>
            @endif
            @if($host_experience->guest_requirements->minimum_age)
            <li>
              <h4>
                {{trans('experiences.manage.who_can_come')}}
              </h4>
              <p>
                {{trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $host_experience->guest_requirements->minimum_age])}}
              </p>
            </li>
            @endif
            @if($host_experience->notes != '')
            <li>
              <h4>
                {{trans('experiences.manage.notes')}}
              </h4>
              <div class="forpro">
                <p>
                  {{$host_experience->notes}}
                </p>
              </div>
            </li>
            @endif
            <li>
              <h4>
                {{trans('experiences.manage.where_will_be')}}
              </h4>
              <p>
                {{$host_experience->where_will_be}}
              </p>
            </li>
          </ul>

          <div class="map host-map" id="host_experience_details_map_popup_btn" ng-init="host_experience_location = {{json_encode($host_experience->host_experience_location)}}">
            <a href="#host_experience_map_popup" data-effect="mfp-zoom-in" class="d-block">  
              <div class="bg-white mobile_location_area">
                <h3>
                  {{trans('experiences.manage.where_will_meet')}}
                </h3>
                <p>
                  @{{host_experience_location.location_name ? host_experience_location.location_name+' - ': ''}} {{$host_experience->host_experience_location->city}}
                </p>
              </div>
              <div id="host_experience_details_map" style="width: 100%; height: 350px;"></div>
            </a>
          </div>

          <div class="upcoming-availability mt-4" ng-class="available_dates_page <= 1 ? 'dot-loading' : ''">
            <h4> 
              {{trans('experiences.details.upcoming_availability')}}
            </h4>
            <ul>
              <li class="month d-flex align-items-center" ng-repeat="data in available_dates" ng-if="$index < 3">
                @yield('available_date_item')
              </li>
            </ul>
          </div>

          <div class="forsee available_dates_popup_btn py-4 d-flex align-items-center justify-content-between" id="newshare">
            <a href="#available_dates_popup" data-effect="mfp-zoom-in" class="contlink">
              {{trans('experiences.details.see_all_available_dates')}}  
            </a>
            @if(Auth::user() && @Auth::user()->id != $host_experience->user_id)
            <a href="#available_dates_popup1" data-effect="mfp-zoom-in" class="conlink1">
              {{trans('messages.rooms.contact_host')}}
            </a>
            @endif
          </div>

          <div class="host-review-wrap py-4">
            <h2>
              {{trans_choice('messages.header.review',2)}} 
            </h2>
            @if(@$result->reviews->count())
            @php $row_inc = 1 @endphp
            @foreach($result->reviews as $row_review)
            @if($row_inc <= 3)
            <div class="review1">
              <div class="fordiv">
                <a href="{{ url('users/show/'.$row_review->user_from) }}" class="profile-img">
                  <img title="{{ $row_review->users_from->first_name }}" src="{{ $row_review->users_from->profile_picture->src }}">
                </a>
              </div>
              <div class="reviewpro">
                <a href="{{ url('users/show/'.$row_review->user_from) }}">
                  {{ $row_review->users_from->first_name }}
                </a>
                <div class="reviewmonth">
                  {{ $row_review->date_fy }}
                </div>
              </div>
              <p style="word-break: break-all;">
                {{ $row_review->comments }}
              </p>
            </div>
            @endif
            @php $row_inc++ @endphp
            @endforeach
            @endif 

            <div class="forsee all_reviews_popup_btn">
              @if(!$result->reviews->count())
              {{ trans('messages.rooms.no_reviews_yet') }}
              @else
              @if($result->reviews->count()>3)
              <a href="#all_review_popup" data-effect="mfp-zoom-in">
                <div class="read">
                  Read all 
                  {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
                </div>
              </a>
              @else
              <div class="reviews_count">
                {{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
              </div>
              @endif
              <span class="starright review_stars">
                {!! $result->overall_star_rating !!}
              </span>
              @endif              
            </div>
          </div>

          <ul class="host-about-wrap">
            <li>
              <h4>
                {{trans('experiences.manage.group_size')}}
              </h4>
              <h5>
                {{trans('experiences.details.there_are_spots_available_in_this_experience', ['count' => $host_experience->number_of_guests])}}
              </h5>
              <p>
                {{trans('experiences.details.you_dont_fill_all_experience_are_social')}}
              </p>
            </li>

            <li>
              <h4>
                {{trans('experiences.manage.guest_requirements')}}
              </h4>
              @if($host_experience->guest_requirements->minimum_age < 18)
              <h5>
                {{trans('experiences.details.bringing_guests_under_18')}}
              </h5>
              <p>
                {{trans('experiences.details.bring_guest_under_18_your_responsibility')}}
              </p>
              @endif
            </li>

            @if($host_experience->guest_requirements->includes_alcohol == 'Yes')
            <li>
              <h4>
                {{trans('experiences.manage.alcohol')}}
              </h4>
              <p>
                {{trans('experiences.details.this_alcohol_includes_only_for_legal_age')}}
              </p>
            </li>
            @endif

            @if($host_experience->guest_requirements->special_certifications || $host_experience->guest_requirements->additional_requirements)
            <li>
              <h4>
                {{trans('experiences.details.from_the_host')}}
              </h4>
              <div class="forpro">
                <p>
                  {!! $host_experience->guest_requirements->special_certifications ? $host_experience->guest_requirements->special_certifications.'<br>' : '' !!}
                  {{$host_experience->guest_requirements->additional_requirements}}
                </p>
              </div>
            </li>
            @endif

            <li>
              <h4>
                {{trans('experiences.manage.who_can_come')}}
              </h4>
              <p>
                {{trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $host_experience->guest_requirements->minimum_age])}}
                @if($host_experience->is_free_under_2=="Yes")
                <span>
                  {{trans('experiences.manage.free_for_under_2')}}
                </span>
                @endif
              </p>
            </li>
            @if($host_experience->host_experience_packing_lists->count() > 0)
            <li>
              <h4>
                {{trans('experiences.manage.packing_list')}}
              </h4>
              @foreach($host_experience->host_experience_packing_lists as $package)
              <p>
                {{ $package->item }}
              </p>
              @endforeach
            </li>
            @endif
          </ul>

          <div class="host-cancellation-policy py-4">
            <h4>
              {{trans('experiences.details.flexible_cancellation_policy')}}
            </h4>
            <p>
              {{trans('experiences.details.flexible_cancellation_policy_desc')}}
              <a target="_blank" href="{{url('hosts_experience_cancellation_policy')}}">
                {{trans('experiences.details.see_cancellation_policy')}}
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="available_dates_popup" class="available_dates_popup white-popup mfp-with-anim mfp-hide see_popp" ng-init="available_dates_page = 1;">
    <h2> 
      {{trans('experiences.details.when_do_you_want_to_go')}}
    </h2>
    <div class="month d-flex align-items-center pop_see" ng-repeat="data in available_dates">
      @yield('available_date_item')
    </div>
    <div class="dot-loading height-limited" id="available_dates_next_page" ng-class="available_dates_loading ? 'dot-loading' : '';"></div>
  </div>

  <div id="host_experience_map_popup" class="host_experience_map_popup white-popup mfp-with-anim mfp-hide">
    <div class="map card">
      <div id="host_experience_details_popup_map" class="host_experience_map"></div>
    </div>
  </div>

  <div id="all_review_popup" class="all-review-popup white-popup mfp-with-anim mfp-hide" ng-init="all_review_page = 1;">
    <h2> 
      {{trans_choice('messages.header.review',2)}} 
    </h2>
    <ul class="host-review-list">
      <li class="month" ng-repeat="review_data in all_reviews">
        <div class="fordiv">
          <a href="{{ url('users/show') }}/@{{ review_data.users_from.id }}" class="profile-img">
            <img ng-src="@{{ review_data.users_from.profile_picture.src }}">
          </a>
        </div>
        <div class="reviewpro">
          <a href="{{ url('users/show/') }}/@{{ review_data.users_from.id }}">
            @{{ review_data.users_from.first_name }}
          </a>
          <div class="reviewmonth">
            @{{ review_data.date_fy }}
          </div>
        </div>
        <p>
          @{{ review_data.comments }}
        </p>
      </li>
    </ul>
    <div class="dot-loading height-limited" id="all_review_next_page" ng-class="all_review_loading ? 'dot-loading' : '';"></div>
  </div>

  @if($similar_items->count() > 0)
  <div class="similar-listings my-4 my-md-5">
    <div class="container">
      <h4 class="title-sm mb-3">
        {{trans('experiences.details.similar_items_in_city', ['city' => $host_experience->city_details->name])}}
      </h4>
      <div id="similar-slider" class="owl-carousel">
        @foreach($similar_items as $item)
        <div class="listing list_view">
          <div class="pro-img">
            <a href="{{url('experiences/'.$item->id)}}" target="_blank" class="media-photo media-cover">
              <img src="{{@$item->host_experience_photos[0]->image_url}}" alt="image">
            </a>
          </div>
          <div class="pro-info">
            <h4 class="text-truncate">
              <span>
                {{$item->category_details->name}}
              </span> 
              <span>·</span>
              <span>
                {{$item->host_experience_location->city}}
              </span>
            </h4>
            <a href="{{url('experiences/'.$item->id)}}" target="_blank">
              <h5 class="text-truncate"> 
                {{$item->title}}
              </h5>
            </a>
            <p class="price"> 
              {{html_string($item->currency->symbol)}} {{$item->session_price}}
              {{trans('experiences.details.per_person')}}
            </p>
            <div class="d-flex align-items-center">
              @if($item->reviews->count())
              {!! $item->overall_star_rating !!}
              @endif
              <span class="review-count mx-2">
                @if(!$item->reviews->count())

                @else
                {{ $item->reviews->count() }} {{ trans_choice('messages.header.review',$item->reviews->count()) }}
                @endif
              </span>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  <div id="share-popup" class="white-popup mfp-with-anim sharepop mfp-hide">
    <h4>
      {{ trans('messages.rooms.share') }}
    </h4>
    <label class="share-popup-title">
      {{ @$result->title }}
    </label>
    <ul class="share-popup-ul mt-4">
      <li>
        <a class="share-btn link-icon" data-email-share-link="" data-network="email" rel="nofollow" title="{{ trans('messages.login.email') }}" href="mailto:?subject={{ $result->title }}&amp;body=Check out {{ @$host_experience->city_details->name }} - {{ $result->title }}  - {{ Request::url() }}">
          <i class="share-popup-icon icon icon-envelope social-icon-size"></i>
          <label class="share-popup-label">Email</label>
        </a>
      </li>
      <li>
        <a class="share-btn link-icon" data-network="pinterest" rel="nofollow" title="Pinterest" href="https://www.pinterest.co.uk/pin/create/button/?url={{ Request::url() }}&media={{ $result->host_experience_photos[0]->image_url }}&description={{ $result->title }}" target="_blank">
          <i class="share-popup-icon icon icon-pinterest social-icon-size"></i>
          <label class="share-popup-label">Pinterest</label>
        </a>
      </li>
      <li>
        <div id="copy_div"></div>
        <i class="fa fa-clipboard copy-label-i" aria-hidden="true"></i>
        <label data-copy="{{ Request::url() }}" class="share-popup-label share-copy-label">
          <span ng-if="!link_copied">Copy Link</span>
          <span ng-if="link_copied">Link Copied</span>
        </label>
      </li>
    </ul>
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
            <div class="col-12 col-md-7 background-listing-img d-flex" style="background-image:url({{ $result->photo_name }});">
              <div class="mt-auto mb-3 d-flex align-items-center">
                <div class="profile-img mr-3">            
                  <img src="{{ $result->users->profile_picture->src }}">
                </div>
                <div class="profile-info">
                  <h4>
                    {{ $result->name }}
                  </h4>
                  <span>
                    {{ $result->rooms_address->city }}
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
                      <i class="icon icon-heart-alt icon-light-gray wl-modal-wishlist-row__icon-heart-alt" ng-hide="item.saved_id"></i>
                      <i class="icon icon-heart icon-rausch wl-modal-wishlist-row__icon-heart" ng-show="item.saved_id"></i>
                    </div>
                  </li>
                </ul>
                <div class="wl-modal-footer my-3 pt-3">
                  <form class="wl-modal-form wl-modal-footer__form d-none">
                    <div class="d-flex align-items-center">
                      <input type="text" class="wl-modal-input wl-modal-footer__input flex-grow-1 border-0" autocomplete="off" id="wish_list_text" value="{{ $result->rooms_address->city }}" placeholder="Name Your Wish List" required>
                      <button id="wish_list_btn" class="btn btn-contrast ml-3 wish_list_create_btn">
                        {{ trans('messages.wishlist.create') }}
                      </button>
                    </div>
                  </form>
                  <span class="create-wl wl-modal-footer__text">
                    <a href="javascript:void(0)">
                      {{ trans('messages.wishlist.create_new_wishlist') }}
                    </a>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="available_dates_popup1" class="white-popup mfp-with-anim needmr mfp-hide">
    <form name="host_experience_contact_host_form" id="host_experience_contact_host_form" method="post" action="{{url('experiences/'.$host_experience->id.'/contact_host')}}">
      <div class="d-flex mb-4">
        <div class="guest-popup-info">
          <h4>
            {{trans('experiences.details.need_more_info_about_name', ['name' => $host_experience->title])}}
          </h4>
          <p>
            {!! trans('experiences.details.if_you_have_general_faq_link', ['link' => '<a href="'.url('help').'">'.trans('experiences.details.visit_our_faq').'</a>']) !!}
          </p>
        </div>
        <div class="guest-info-img" ng-init="contact_host_message = ''">
          <a href="{{url('users/show/'.$host_experience->user_id)}}" target="_blank">
            <img src="{{@$host_experience->user->profile_picture->header_src510}}">
          </a>
        </div>
      </div>
      <textarea class="guest-pop-area neddtext" id="contact_host_message" name="contact_host_message" ng-model="contact_host_message"></textarea>
      <p class="text-danger" id="contact_host_message_error" style="display: none;">
        {{trans_choice('messages.dashboard.message', 1)}} {{trans('messages.login.field_is_required')}}
      </p>
      <a href="javascript:void(0)" class="guest-pop-send mt-4 sendmr" id="contact_host_form_submit">
        {{trans('messages.your_reservations.send_message')}}
      </a>
    </form>
  </div>

  <div class="dot-loading d-none">
  </div>

</main>
@stop
@push('scripts')
<script src="{{url('js/host_experiences/magnify.js')}}">
</script>
<link rel="stylesheet" type="text/css" href="{{url('css/host_experiences/magnify.css')}}"> 
@endpush

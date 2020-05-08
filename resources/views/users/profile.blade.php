@extends('template')
@section('main')
<main id="site-content" role="main">
  <div class="profile-content py-4 py-md-5">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4 col-lg-3 profile-view-left">
          <div id="user" class="profile-img text-center">
            <img alt="{{ $result->first_name }}" class="img-fluid d-inline-block" src="{{ $result->profile_picture->src }}" title="{{ $result->first_name }}" width="225" height="225">
          </div>

          @if($result->school || $result->work || $result->languages_name)
          <div class="card my-4 d-none d-md-block">
            <div class="card-header">
              <h3>
                {{ trans('messages.profile.about_me') }}
              </h3>
            </div>
            <div class="card-body">
              <dl>
                @if($result->school)
                <dt>{{ trans('messages.profile.school') }}</dt>
                <dd>{{ $result->school }}</dd>
                @endif
                @if($result->work)
                <dt>{{ trans('messages.profile.work') }}</dt>
                <dd>{{ $result->work }}</dd>
                @endif
                @if($result->languages_name)
                <dt>{{ trans('messages.profile.languages') }}</dt>
                <dd style="word-wrap: break-word;">{{ $result->languages_name }}</dd>
                @endif
              </dl>
            </div>
          </div>
          @endif

          @if($result->users_verification->show() || $result->verification_status == 'Verified')
          <div class="card mt-4 verification-panel d-none d-md-block">
            <div class="card-header">
              {{ trans('messages.dashboard.verifications') }}
            </div>
            <div class="card-body">
              <ul>
                @if($result->verification_status == 'Verified')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      {{ trans('messages.dashboard.id_verification') }}
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.verified') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->email == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      {{ trans('messages.dashboard.email_address') }}
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.verified') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->phone_number == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      {{ trans('messages.profile.phone_number') }}
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.verified') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->facebook == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      Facebook
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->google == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      Google
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->linkedin == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      LinkedIn
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
              </ul>
            </div>
          </div>
          @endif
        </div>

        <div class="col-12 col-md-8 col-lg-9 profile-view-right mt-4 mt-md-0">
          <h3 class="text-center text-md-left">
            {{ trans('messages.profile.hey_iam',['first_name'=>$result->first_name]) }}!
          </h3>
          <h5 class="text-center text-md-left">
            @if($result->live)
            <a class="d-inline-block theme-link" href="{{ url('s?location='.$result->live) }}">
              {{ $result->live }}
            </a>·
            @endif
            <span>
              {{ trans('messages.profile.member_since') }} {{ $result->since }}
            </span>
          </h5>
          <div class="flag_controls d-none mt-3"></div>
          @if(Auth::check() && Auth::user()->id == $result->id)
          <div class="edit_profile_container mt-3 text-center text-md-left">
            <a class="theme-link" href="{{ url('users/edit') }}">
              {{ trans('messages.header.edit_profile') }}
            </a>
          </div>
          @endif

          @if($result->school || $result->work || $result->languages)
          <div class="card mt-4 d-block d-md-none">
            <div class="card-header">
              {{ trans('messages.profile.about_me') }}
            </div>
            <div class="card-body">
              <dl>
                @if($result->school)
                <dt>{{ trans('messages.profile.school') }}</dt>
                <dd>{{ $result->school }}</dd>
                @endif
                @if($result->work)
                <dt>{{ trans('messages.profile.work') }}</dt>
                <dd>{{ $result->work }}</dd>
                @endif
                @if($result->languages)
                <dt>{{ trans('messages.profile.languages') }}</dt>
                <dd style="word-wrap: break-word;">{{ $result->languages_name }}</dd>
                @endif
              </dl>
            </div>
          </div>
          @endif

          @if($result->users_verification->show())
          <div class="card mt-4 verification-panel d-block d-md-none">
            <div class="card-header">
              <h3>
                {{ trans('messages.dashboard.verifications') }}
              </h3>
            </div>
            <div class="card-body">
              <ul>
                @if($result->users_verification->email == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      {{ trans('messages.dashboard.email_address') }}
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.verified') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->phone_number == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      {{ trans('messages.profile.phone_number') }}
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.verified') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->facebook == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      Facebook
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->google == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      Google
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
                @if($result->users_verification->linkedin == 'yes')
                <li>
                  <i class="icon icon-ok mr-2"></i>
                  <div class="media-body">
                    <h5>
                      LinkedIn
                    </h5>
                    <p>
                      {{ trans('messages.dashboard.validated') }}
                    </p>
                  </div>
                </li>
                @endif
              </ul>
            </div>
          </div>
          @endif

          <div class="mt-3">
            <p>{{ $result->about }}</p>
          </div>

          <div class="mt-3">
            @if($reviews_count > 0)
            <div class="col-4 col-md-3">
              <a href="#reviews" rel="nofollow" class="link-reset">
                <div class="text-center text-wrap toms">
                  <div class="badge-pill h3">
                    <span class="badge-pill-count">
                      {{ $reviews_count }}
                    </span>
                  </div>
                  <div class="mt-2">
                    {{ trans_choice('messages.header.review',1) }}
                  </div>
                </div>
              </a>
            </div>
            @endif
          </div>

          <!-- Start User Rooms & Experience Details -->
          <div class="profile-room-slides">
            @if(!$rooms->isEmpty() )
            <div class="my-4" ng-init="rooms_data={{$rooms}}">
              <h2 class="title-sm">
                {{ trans('messages.header.homes') }}
              </h2>

              <ul class="profile-slider owl-carousel">
                <li ng-repeat="room in rooms_data">
                  <div class="pro-img">
                    <a href="@{{ room.link }}">
                      <img src="@{{ room.photo_name }}" />
                    </a>
                  </div>
                  <div class="pro-info">
                    <h4 class="text-truncate">
                      <span>@{{ room.room_type_name }}</span>
                      <span>·</span>
                      <span>@{{ room.beds }} @{{ room.bed_lang }} </span>
                    </h4>
                    <a href="@{{ room.link }}" title="@{{ room.name }}">
                      <h5 class="text-truncate"> @{{ room.name }} </h5>
                    </a>
                    <p class="price">             
                      <span ng-bind-html="room.rooms_price.currency.symbol"></span>@{{ room.rooms_price.night }}
                      {{ trans("messages.rooms.per_night") }}
                      <span ng-if="room.booking_type == 'instant_book'"> 
                        <i class="icon icon-instant-book"></i>
                      </span>
                    </p>
                    <div class="d-flex align-items-center">                                              
                      <span ng-bind-html="room.overall_star_rating"> </span>
                      <span class="review-count mx-2" ng-if="room.reviews_count > 0">
                        @{{ room.reviews_count }}
                      </span>
                      <span class="review-label" ng-if="room.overall_star_rating">
                        @{{ room.reviews_count_lang }}
                      </span>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            @endif

            {{--HostExperienceBladeCommentStart--}}
            @if(!$host_experiences->isEmpty() )
            <div class="my-4" ng-init="host_experiences={{ $host_experiences }}">
              <h2 class="title-sm">
                @lang('experiences.home.experiences')
              </h2>

              <ul class="profile-slider owl-carousel">
                <li ng-repeat="host_experience in host_experiences">
                  <div class="pro-img">
                    <a href="@{{ host_experience.link }}">
                      <img src="@{{ host_experience.photo_resize_name }}" />
                    </a>
                  </div>
                  <div class="pro-info">
                    <h4 class="text-truncate">
                      <span>@{{host_experience.category_name}}</span>
                      <span>·</span>
                      <span>@{{host_experience.city_name}}</span>
                    </h4>
                    <a href="@{{ host_experience.link }}" title="@{{ host_experience.title }}">
                      <h5 class="text-truncate">          
                        @{{ host_experience.title }}
                      </h5>
                    </a>
                    <div class="exp_price" >
                      <span ng-bind-html="host_experience.currency.symbol"></span> 
                      @{{ host_experience.session_price }} {{ trans("messages.wishlist.per_guest") }}
                    </div>
                    <div class="star-rating-wrapper">
                      <div class="category_city" ng-if="host_experience.overall_star_rating != '' ">
                        <span ng-bind-html="host_experience.overall_star_rating"></span>
                        <span>@{{ host_experience.reviews_count }} @{{ host_experience.reviews_count_lang }}</span>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            @endif
            {{--HostExperienceBladeCommentEnd--}}
          </div>
          <!-- End User Rooms & Experience Details -->

          @if($wishlists->count())
          <div class="profile-wishlists">
            <h5>
              {{ trans_choice('messages.header.wishlist',$wishlists->count()) }}
              <small>
                ({{ $wishlists->count() }})
              </small>
            </h5>
            <div class="row">
              <ul class="wishlists-wrap d-md-flex flex-wrap">
                @foreach($wishlists as $row)
                <li class="col-12 col-md-4">
                  <a href="{{ url('wishlists/'.$row->id) }}" class="wishlist-bg-img" style="background-image:url('@if($row->saved_wishlists->count() > 0){{ $row->saved_wishlists[0]->photo_name }} @endif');">
                    <div class="count-section mt-auto mb-3 col-12">
                      <h4>
                        {{ $row->name }}
                      </h4>
                      <span>
                        @if($row->rooms_count > 0)
                        {{ $row->rooms_count }} {{ trans('messages.header.home') }}
                        @endif
                        @if($row->rooms_count > 0 && $row->host_experience_count > 0)
                        .
                        @endif
                        @if($row->host_experience_count > 0)
                        {{ $row->host_experience_count }} {{ trans_choice('messages.home.experience',$row->host_experience_count) }}
                        @endif
                      </span>
                    </div>
                  </a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          @endif

          @if($reviews_count > 0)
          <div class="profile-reviews mt-4" id="reviews">
            <h5>
              {{ trans_choice('messages.header.review',2) }}
              <small>
                ({{ $reviews_count }})
              </small>
            </h5>
            @if($reviews_from_hosts->count() > 0)
            <div class="review-section">
              <h4>
                {{ trans('messages.profile.reviews_from_hosts') }}
              </h4>
              <ul class="reviews mt-3">
                @foreach($reviews_from_hosts->get() as $row_host)
                <li class="d-flex flex-wrap text-center text-md-left" id="review-{{ $row_host->id }}">
                  <div class="col-12 col-md-2 p-0 pr-md-1">
                    <a href="{{ url('/') }}/users/show/{{ $row_host->user_from }}" class="normal-link text-center">
                      <div class="mb-2 profile-img">
                        <img width="68" height="68" title="{{ $row_host->users_from->first_name }}" src="{{ $row_host->users_from->profile_picture->src }}" class="img-fluid d-inline-block" alt="{{ $row_host->users_from->first_name }}">
                      </div>
                      <div class="profile-name">
                        {{ $row_host->users_from->first_name }}
                      </div>
                    </a> 
                    <div class="date d-md-none">
                      {{ $row_host->date_fy }}
                    </div>  
                  </div>
                  <div class="col-12 col-md-10 pl-0">
                    <div class="comment-container expandable expandable-trigger-more expanded">
                      <div class="expandable-content">
                        <p>{{ $row_host->comments }}</p>
                        <!-- <div class="expandable-indicator"></div> -->
                      </div>
                     <!--  <a href="{{ url('/') }}/users/show/{{$row_host->users_from->id}}" class="expandable-trigger-more text-muted">
                        <strong>+ {{ trans('messages.profile.more') }}</strong>
                      </a> -->
                    </div>
                    <div class="date d-none d-md-block">
                      @if($row_host->users_from->live)
                      {{ trans('messages.profile.from') }} 
                      <a class="theme-link" href="{{ url('/') }}/s?location={{ $row_host->users_from->live }}">
                        {{ $row_host->users_from->live }}
                      </a>·
                      @endif
                      <span class="d-block">
                        {{ $row_host->date_fy }}
                      </span>
                    </div>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
            @if($reviews_from_guests->count() > 0)
            <div class="review-section">
              <h4>
                {{ trans('messages.profile.reviews_from_guests') }}
              </h4>
              <ul class="reviews mt-3">
                @foreach($reviews_from_guests->get() as $row_guest)
                <li class="d-flex flex-wrap text-center text-md-left" id="review-{{ $row_guest->id }}">
                  <div class="col-12 col-md-2 p-0">
                    <a class="normal-link" href="{{ url('/') }}/users/show/{{ $row_guest->user_from }}">
                      <div class="mb-2 profile-img text-center">
                        <img width="68" height="68" title="{{ $row_guest->users_from->first_name }}" src="{{ $row_guest->users_from->profile_picture->src }}" class="d-inline-block" alt="{{ $row_guest->users_from->first_name }}">
                      </div>
                      <div class="text-center profile-name text-wrap">
                        {{ $row_guest->users_from->first_name }}
                      </div>
                    </a>          
                    <div class="date d-md-none">
                      {{ $row_guest->date_fy }}
                    </div>
                  </div>
                  <div class="col-12 col-md-10">
                    <div class="comment-container expandable expandable-trigger-more expanded">
                      <div class="expandable-content">
                        <p>
                          {{ $row_guest->comments }}
                        </p>
                        <!-- <div class="expandable-indicator"></div> -->
                      </div>
                      <!-- <a href="{{ url('/') }}/users/show/{{$row_guest->users_from->id}}" class="expandable-trigger-more">
                        <strong>
                          + {{ trans('messages.profile.more') }}
                        </strong>
                      </a> -->
                    </div>
                    <div class="date d-none d-md-block">
                      @if($row_guest->users_from->live)
                      {{ trans('messages.profile.from') }} 
                      <a class="theme-link" href="{{ url('/') }}/s?location={{ $row_guest->users_from->live }}">
                        {{ $row_guest->users_from->live }}
                      </a>·
                      @endif
                      <span class="d-block">
                        {{ $row_guest->date_fy }}
                      </span>
                    </div>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
    <div id="staged-photos"></div>
  </main>
  @stop
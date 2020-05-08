<header>
	@php
	if ((request()->get('device') && request()->get('device') == 'mobile') || Session::get('get_token')!=null) {
	$view_device = 'mobile';
}
@endphp
@if(!isset($view_device))
<div class="header">
	<nav class="navbar navbar-expand-lg align-items-center flex-nowrap justify-content-start">
		<div class="logo d-none d-lg-block">
			<a class="navbar-brand" href="{{url('/')}}">
				<img src="{{ url(LOGO_URL) }}" />
			</a>
		</div>

		<div class="logo d-block d-lg-none cls_navhide" class="navbar-toggler" data-toggle="collapse" data-target='#navbarSupportedContent' aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<a class="navbar-brand" href="javascript:void(0);">
				<img src="{{ url(LOGO_URL) }}" />
			</a>
		</div>

		<button class="navbar-toggler cls_navhide" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fa fa-angle-down"></i>
		</button>

		@if(request()->segment(1) != 'help' && request()->segment(1) != '')
		<div class="search-bar-wrapper">
			<form action="{{ url('/') }}/s" class="search-form {{ (request()->segment(1) != 's') ? 'header_search_form' : 'search_header_form' }}">
				<div class="search-bar">
					<i class="icon icon-search icon-gray"></i>
					<input id="header-search-form" type="text" name="" class="location d-none d-lg-block" placeholder="{{ trans('messages.header.where_are_you_going') }}" />
					<button data-toggle="modal" data-target="#search-modal-sm" class="location search-modal-trigger d-block d-lg-none text-left">
						{{ trans('messages.header.where_are_you_going') }}
					</button>
				</div>

				<div id="header-search-settings" class="search-settings">
					@if(Route::currentRouteName() == 'search_page')
					{{--HostExperienceBladeCommentStart--}}
					<div class="search-page-filter">
						<strong>
							{{ trans('messages.referrals.explore') }}
						</strong>
						<ul class="header_refinement_ul mt-2">
							<input type="hidden" name="header_refinement" class="header_refinement_input" value="Homes">
							<li>
								<button class="header_refinement in_form active" data="Homes" type="button" id="home-refinement">
									{{ trans('messages.header.homes') }}
								</button>
							</li>
							<li>
								<button class="header_refinement in_form" data="Experiences" type="button" id="experience-refinement">
									{{ trans_choice('messages.home.experience',1) }}
								</button>
							</li>
						</ul>
					</div>
					{{--HostExperienceBladeCommentEnd--}}
					@else
					<div class="common-page-filter">
						<div class="row">
							<div class="col-md-4 pr-0">
								<label for="header-search-checkin" class="field-label">
									<strong>
										{{ trans('messages.home.checkin') }}
									</strong>
								</label>
								<input type="text" readonly="readonly" autocomplete="off" id="header-search-checkin" data-field-name="check_in_dates" class="checkin ui-datepicker-target" onfocus="this.blur()" placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}">
								<input type="hidden" name="checkin">
							</div>

							<div class="col-md-4 pr-0">
								<label for="header-search-checkout" class="field-label">
									<strong>
										{{ trans('messages.home.checkout') }}
									</strong>
								</label>
								<input type="text" readonly="readonly" autocomplete="off" id="header-search-checkout" data-field-name="check_out_dates" class="checkout ui-datepicker-target" onfocus="this.blur()"  placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}">
								<input type="hidden" name="checkout">
							</div>

							<div class="col-md-4">
								<label for="header-search-guests" class="field-label">
									<strong>
										{{ trans_choice('messages.home.guest', 2) }}
									</strong>
								</label>
								<div class="select select-block">
									<select id="header-search-guests" data-field-name="number_of_guests" name="guests">
										@for($i=1;$i<=16;$i++)
										<option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ ' : $i }} </option>
										@endfor
									</select>
								</div>
							</div>
						</div>

						{{--HostExperienceBladeCommentStart--}}
						<div class="my-4">
							<strong>{{ trans('messages.referrals.explore') }}</strong>
							<ul class="header_refinement_ul mt-2">
								<input type="hidden" name="current_refinement" class="header_refinement_input" value="Homes">
								<li>
									<button class="header_refinement active" data="Homes" type="button" id="home-refinement">
										{{ trans('messages.header.homes') }}
									</button>
								</li>
								<li>
									<button class="header_refinement" data="Experiences" type="button" id="experience-refinement">
										{{ trans_choice('messages.home.experience',1) }}
									</button>
								</li>
							</ul>
						</div>
						{{--HostExperienceBladeCommentEnd--}}

						<div class="explore_list">
							<div class="home_pro">
								<strong>
									{{ trans('messages.header.room_type') }}
								</strong>
								<div class="check_list">
									@foreach($header_room_type as $row_room)
									<div class="explore_check d-flex align-items-center">
										<input name="room_type" type="checkbox" value="{{ @$row_room->id }}" id="room-type-{{ @$row_room->id }}" class="head_room_type" {{@in_array($row_room->id, @$room_type_selected) ? 'checked' : ''}} />
										<i class="icon-activities">
											<img src="{{ $row_room->image_name }}">
										</i>
										<label class="search_check_label" for="room-type-{{ @$row_room->id }}">
											{{ @$row_room->name }}
										</label>
									</div>
									@endforeach
								</div>
							</div>
							{{--HostExperienceBladeCommentStart--}}
							<div class="exp_cat" style="display:none">
								<strong>{{ trans('messages.home.category') }}</strong>
								<div class="check_list">
									@foreach($host_experience_category as $row_cat)
									<div class="explore_check d-flex align-items-center">
										<input name="host_experience_category" type="checkbox" id="cat-type-{{ @$row_cat->id }}" value="{{ @$row_cat->id }}" class="head_cat_type" {{@in_array($row_cat->id, @$cat_type_selected) ? 'checked' : ''}} />
										<label class="search_check_label" for="cat-type-{{ @$row_cat->id }}">
											{{ @$row_cat->name }}
										</label>
									</div>
									@endforeach
								</div>
							</div>
							{{--HostExperienceBladeCommentEnd--}}
						</div>
						<div class="mt-3">
							<button type="submit" class="btn btn-primary btn-block">
								<i class="icon icon-search"></i>
								<span>
									{{ trans('messages.header.find_place') }}
								</span>
							</button>
						</div>
					</div>
					@endif
				</div>
			</form>
		</div>
		@endif

		<div class="main-menu collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav align-items-lg-center ml-auto d-none d-lg-flex">					
				{{--HostExperienceBladeCommentStart--}}
				@if(!Auth::check())
				<li class="nav-item dropdown">
					<a class="nav-link {{ Auth::check() ? '' : 'login_popup_open' }}" href="javascript:void(0)" role="button">
						{{ trans('messages.header.list_your_space') }}
					</a>
				</li>
				@endif

				@if(Auth::check())
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle {{ Auth::check() ? '' : 'login_popup_open' }}" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{{ trans('messages.header.list_your_space') }}
					</a>
					<ul class="dropdown-menu become-host-dropdown" aria-labelledby="navbarDropdown">
						<li class="dropdown-item">
							<a href="{{ url('rooms/new') }}" class="dropdown-link">
								{{ trans('messages.header.head_homes') }}
							</a>
						</li>
						<li class="dropdown-item">
							<a href="{{ url('host/experiences') }}" class="dropdown-link">
								{{ trans('messages.header.head_experience') }}
							</a>
						</li>
					</ul>
				</li>
				@endif
				{{--HostExperienceBladeCommentEnd--}}

				{{--HostExperienceBladeUnCommentStart
					<li class="nav-item">
						<a class="nav-link {{ Auth::check() ? '' : 'login_popup_open' }}" href="{{ url('rooms/new') }}">
							{{ trans('messages.header.list_your_space') }}
						</a>
					</li>
					HostExperienceBladeUnCommentEnd--}}

					@if(!Auth::check())
					<li class="nav-item">
						<a class="nav-link" href="{{ url('help') }}">
							{{ trans('messages.header.help') }}
						</a>
					</li>
					@endif

					@if(!Auth::check())
					<li class="nav-item">
						<a class="nav-link signup_popup_head" href="{{ url('signup_login') }}" data-toggle="modal" data-target="#signup-popup">
							{{ trans('messages.header.signup') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link login_popup_head" href="{{ url('login') }}" data-toggle="modal" data-target="#login-popup">
							{{ trans('messages.header.login') }}
						</a>
					</li>
					@endif

					@if(Auth::check())
					<li class="nav-item">
						<a class="nav-link" href="{{ url('trips/current') }}">
							<span class="trip-pos"> {{ trans('messages.header.Trips') }}</span>
							<i class="trips-icon">
								<i class="alert-count fade">0</i>
							</i>
						</a>
						<div class="panel drop-down-menu-trip d-none js-become-a-host-dropdown">
							<div class="trip-width">
								<div class="panel-header no-border section-header-home"><strong><span>Trips</span></strong><a href="{{ url('trips/current') }}" class="link-reset view-trips pull-right"><strong><span>View Trips</span></strong></a></div>
								<div class="pull-left" style="width:100%;padding:15px 20px;">
									<div class="pull-left" style="padding:15px 20px 0px;">
										<strong>No upcoming trips</strong>
										<p>continue searching in paris</p>
									</div>
									<div class="pull-right suitcase-icon">
										<i class="icon icon-suitcase"></i>
									</div>
								</div>
								<div class="panel-header no-border section-header-home pull-left" style="width:100%;" ><strong><span>Wishlist</span></strong><a href="{{ url('wishlists/my') }}" class="link-reset view-trips pull-right"><strong><span>View Wishlists</span></strong></a></div>
								<div class="pull-left" style="width:100%;padding:15px 20px;">
									<div class="pull-left" style="padding:15px 20px 0px;">
										<strong>No wish list created</strong>
										<p>create a wish list</p>
									</div>
									<div class="pull-right suitcase-icon">
										<i class="icon icon-heart-alt"></i>
									</div>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item" id="inbox-item">
						<a class="nav-link" href="{{ route('inbox') }}">
							<span class="position-relative" ng-init="inbox_count='{{ @Auth::user()->inbox_count()}}'"> 
								{{ trans_choice('messages.dashboard.message', 2) }}
								<i class="alert-count text-center" ng-class="inbox_count != '0' ? '' : 'fade'" ng-cloak> 
									@{{ inbox_count }} 
								</i>
							</span>
						</a>
						<div class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown
						notifications-dropdown d-none"></div>
						<div class="panel drop-down-menu-msg d-none js-become-a-host-dropdown">
							<div class="trip-width">
								<div class="panel-header no-border section-header-home">
									<strong>
										<span>
											Messages
										</span>
									</strong>
									<a href="{{ url('inbox') }}" class="link-reset view-trips pull-right">
										<strong>
											<span>
												View Inbox
											</span>
										</strong>
									</a>
								</div>
								<div class="panel-header no-border section-header-home w-100">
									<strong>
										<span>
											Notifications
										</span>
									</strong>
									<a href="{{ url('dashboard') }}" class="link-reset view-trips pull-right">
										<strong>
											<span>
												View Dashboard
											</span>
										</strong>
									</a>
								</div>
								<div class="pull-left" style="width:100%;padding:15px 20px;">
									<p style="margin:0px;padding-top:10px !important;"> 
										There are 3 notifications waiting for you in your 
										<a style="color:#333;text-decoration:underline;" href="{{ url('dashboard') }}">  
											{{ trans('messages.header.dashboard') }} 
										</a>.
									</p>
								</div>
							</div>
						</div>
					</li>

					<li class="nav-item" id="header-help-menu">
						<a class="nav-link" href="{{ url('help') }}">
							<span class="help-pos">
								{{ trans('messages.header.help') }}
							</span>
							<i class="help-icon"></i>
						</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle menu-droplist align-items-center d-flex" id="navbarDropdown" href="{{ url('login') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="value_name mr-2 d-none">
								{{ Auth::user()->first_name }}
							</span>
							<img src="{{ Auth::user()->profile_picture->header_src }}" />
						</a>
						
						<ul class="dropdown-menu custom-arrow top-right" aria-labelledby="navbarDropdown">
							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('dashboard') }}">
									{{ trans('messages.header.dashboard') }}
								</a>
							</li>

							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('rooms') }}">
									{{ trans_choice('messages.header.your_listing',2) }}
								</a>
							</li>

							<li class="dropdown-item reservations d-none">
								<a class="dropdown-link" href="{{ url('my_reservations') }}">
									{{ trans('messages.header.your_reservations') }}
								</a>
							</li>

							<li class="dropdown-item d-none">
								<a class="dropdown-link" href="{{ url('trips/current') }}">
									{{ trans('messages.header.your_trips') }}
								</a>
							</li>

							@if(Auth::user()->saved_wishlists)
							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('wishlists/my') }}">
									{{ trans_choice('messages.header.wishlist',2) }}
								</a>
							</li>
							@endif

							<li class="dropdown-item d-none">
								<a class="dropdown-link" href="{{ url('groups') }}">
									{{ trans('messages.header.groups') }}
								</a>
							</li>

							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('invite') }}">
									{{ trans('messages.referrals.travel_credit') }}
									<span class="label label-pink label-new">
									</span>
								</a>
							</li>

							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('users/edit') }}">
									{{ trans('messages.header.edit_profile') }}
								</a>
							</li>

							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('account') }}">
									{{ trans('messages.header.account') }}
								</a>
							</li>

							<li class="dropdown-item business-travel d-none">
								<a class="dropdown-link" href="{{ url('business') }}">
									{{ trans('messages.header.business_travel') }}
								</a>
							</li>

							<li class="dropdown-item">
								<a class="dropdown-link" href="{{ url('logout') }}">
									{{ trans('messages.header.logout') }}
								</a>
							</li>
						</ul>
					</li>
					@endif
				</ul>

				<ul class="navbar-nav align-items-lg-center ml-auto d-flex d-lg-none pt-2">	
					<li class="profile-link nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link d-flex align-items-center py-2 profile-img" href="{{ url('/') }}/users/show/{{ (Auth::user()) ? Auth::user()->id : '0' }}">
							<img src="{{(Auth::user()) ? Auth::user()->profile_picture->header_src : '' }}" />
							<span class="text-truncate">
								{{ (Auth::user()) ? Auth::user()->first_name : 'User' }}
							</span>
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="{{ url('/') }}">
							{{ trans('messages.header.home') }}
						</a>
					</li>

					<li>
						<hr/>
					</li>

					<li class="nav-item {{ (Auth::user()) ? 'd-none' : '' }}">
						<a class="nav-link" href="{{ url('rooms/new') }}">
							{{ trans('messages.header.head_homes') }}
						</a>
					</li>

					{{--HostExperienceBladeCommentStart--}}
					<li class="nav-item {{ (Auth::user()) ? 'd-none' : '' }}">
						<a class="nav-link" href="{{ url('host/experiences') }}">
							{{ trans('messages.header.head_experience') }}
						</a>
					</li>
					{{--HostExperienceBladeCommentEnd--}}

					<li class="nav-item {{ (Auth::user()) ? 'd-none' : '' }}">
						<a class="nav-link" href="{{ url('/') }}/signup_login">
							{{ trans('messages.header.signup') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? 'd-none' : '' }}">
						<a class="nav-link" href="{{ url('/') }}/login">
							{{ trans('messages.header.login') }}
						</a>
					</li>

					<li class="{{ (Auth::user()) ? 'd-none' : '' }}">
						<hr/>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('dashboard') }}">
							{{ trans('messages.header.dashboard') }}
						</a>
					</li>
					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('users/edit') }}">
							{{ trans('messages.header.profile') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('account') }}">
							{{ trans('messages.header.account') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('/') }}/trips/current">
							{{ trans('messages.header.Trips') }}
						</a>
					</li>

					@Auth
					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}" ng-init="inbox_count='{{ @Auth::user()->inbox_count()}}'">
						<a class="nav-link position-relative" href="{{ route('inbox') }}">
							{{ trans_choice('messages.dashboard.message', 2) }}
							<i class="alert-count text-center" ng-class="inbox_count != '0' ? '' : 'fade'" ng-cloak> @{{ inbox_count }} </i>
						</a>
					</li>
					@endauth

					@if(@Auth::user()->saved_wishlists)
					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('wishlists/my') }}">
							{{ trans_choice('messages.header.wishlist',2) }}
						</a>
					</li>
					@endif

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('rooms') }}">
							{{ trans_choice('messages.header.your_listing',2) }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('disputes') }}">
							{{ trans('messages.disputes.disputes') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<hr/>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('rooms/new') }}">
							{{ trans('messages.header.head_homes') }}
						</a>
					</li>

					{{--HostExperienceBladeCommentStart--}}
					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('host/experiences') }}">
							{{ trans('messages.header.head_experience') }}
						</a>
					</li>
					{{--HostExperienceBladeCommentEnd--}}

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<hr/>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="{{ url('/') }}/help">
							{{ trans('messages.header.help') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('/') }}/invite">
							{{ trans('messages.header.invite_friends') }}
						</a>
					</li>

					<li class="nav-item {{ (Auth::user()) ? '' : 'd-none' }}">
						<a class="nav-link" href="{{ url('/') }}/logout">
							{{ trans('messages.header.logout') }}
						</a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
	@endif
</header>

<div class="flash-container">
	@if(Session::has('message') && !isset($exception))
	@if((!Auth::check() || Route::current()->uri() == 'rooms/{id}' || Route::current()->uri() == 'payments/book/{id?}') || Route::current()->uri() == 'host/experiences')
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
		{{ Session::get('message') }}
	</div>
	@endif
	@endif
</div>
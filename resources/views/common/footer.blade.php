@php
  if ((request()->get('device') && request()->get('device') == 'mobile') || Session::get('get_token')!=null) {
    $view_device = 'mobile';
  }
@endphp
@if(!isset($view_device))
	<footer ng-controller="footer">
		<div class="container">
			<div class="footer-wrap row justify-content-between pb-4">
				<div class="col-md-3 col-lg-2">
					<h2>{{ trans('messages.footer.company') }}</h2>
					<ul class="list-layout">
						@foreach($company_pages as $company_page)
						<li>
							<a href="{{ url($company_page->url) }}" class="link-contrast">
								{{ $company_page->name }}
							</a>
						</li>
						@endforeach
						<li> 
							<a href="{{ url('contact') }}" class="link-contrast">
								{{ trans('messages.contactus.contactus') }}
							</a>
						</li>
					</ul>
				</div>

				<div class="col-md-3 col-lg-2 d-none d-md-block">
					<h2>{{ trans('messages.footer.discover') }}</h2>
					<ul class="list-layout">
						<li>
							<a href="{{ url('invite') }}" class="link-contrast">
								{{ trans('messages.referrals.travel_credit') }}
							</a>
						</li>
						@foreach($discover_pages as $discover_page)
						<li>
							<a href="{{ url($discover_page->url) }}" class="link-contrast">
								{{ $discover_page->name }}
							</a>
						</li>
						@endforeach
					</ul>
				</div>

				<div class="col-md-3 col-lg-2 d-none d-md-block">
					<h2>{{ trans('messages.footer.hosting') }}</h2>
					<ul class="list-layout">
						@foreach($hosting_pages as $hosting_page)
						<li>
							<a href="{{ url($hosting_page->url) }}" class="link-contrast">
								{{ $hosting_page->name }}
							</a>
						</li>
						@endforeach
					</ul>
				</div>

				<div class="col-md-3">
					<div class="social-links mt-3 mt-md-0">
						<ul>
							@for($i=0; $i < count($join_us); $i++)
							@if($join_us[$i]->value)
							<li>
								<a href="{{ $join_us[$i]->value }}" class="link-contrast footer-icon-container" target="_blank" title="{{ ucfirst($join_us[$i]->name) }}">
									<span class="screen-reader-only">
										{{ ucfirst($join_us[$i]->name) }}
									</span>
									<i class="icon footer-icon icon-{{ str_replace('_','-',$join_us[$i]->name) }}"></i> 
								</a>        
							</li>
							@endif
							@endfor
						</ul>
					</div>
					<ul class="app-link my-3 d-flex align-items-center justify-content-center justify-content-md-between">
						@if($play_store_link != '')
						<li>
							<a class="play_store_link" href="{{ $play_store_link }}" target="_blank">
								<img src="{{url('/')}}/images/play_store.png">
							</a>
						</li>
						@endif
						@if($app_store_link != '')
						<li>
							<a class="app_store_link" href="{{ $app_store_link }}" target="_blank">
								<img src="{{url('/')}}/images/app_store.png">
							</a>
						</li>
						@endif
					</ul>
				</div>
			</div>

			<div class="copyright d-md-flex justify-content-between align-items-center text-center text-md-left pt-3">
				<div class="company-info">
					<p>© {{ $site_name }}, Inc.</p>
				</div>
				<div class="lang-currency-wrap d-flex align-items-center justify-content-center mt-3 mt-md-0">
					<div class="language-selector">
						{!! Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector footer-select', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']) !!}
					</div>
					<div class="currency-selector ml-3">
						{!! Form::select('currency',$currency, (Session::get('currency')) ? Session::get('currency') : $default_currency[0]->code, ['class' => 'currency-selector footer-select', 'aria-labelledby' => 'currency-selector-label', 'id' => 'currency_footer']) !!}
					</div>
				</div>
			</div>
		</div>
	</footer>
@endif
<div class="search-mobile-modal modal fade" role="dialog" id="search-modal-sm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
				</button>
				<h1 class="modal-title">@lang('messages.home.search')</h1>
			</div>
			<div class="modal-body">
				<input type="hidden" name="latitude" class="home_latitude">
				<input type="hidden" name="longitude" class="home_longitude">
				<input type="hidden" name="source" value="mob">
				<div class="col-md-12 p-0">
					<span class="search_location_error set_location d-none"> @lang('messages.home.please_set_location') </span>
					<span class="search_location_error invalid_location d-none"> @lang('messages.home.search_validation') </span>
					<label class="d-block" for="header-location-sm">
						<span class="screen-reader-only">
							@lang('messages.header.where_are_you_going')
						</span>
						<input type="text" placeholder="@lang('messages.header.where_are_you_going')" autocomplete="off" name="location" id="header-search-form-mob" class="location input-large" value="{{ @$location }}">
					</label>
				</div>
				@if(Request::segment(1) != 's')
				<div class="row">
					<div class="col-6 pr-1 checkin_div">
						<label class="checkin d-block">
							<span class="screen-reader-only">@lang('messages.home.checkin')</span>
							<input type="text" readonly="readonly" onfocus="this.blur()" autocomplete="off" name="checkin" id="modal_checkin" class="checkin input-large ui-datepicker-target" placeholder="@lang('messages.home.checkin')" value="{{ @$checkin }}">
						</label>
					</div>
					<div class="col-6 pl-1 checkout_div">
						<label class="checkout d-block">
							<span class="screen-reader-only">@lang('messages.home.checkout')</span>
							<input type="text" readonly="readonly" onfocus="this.blur()" autocomplete="off" name="checkout" id="modal_checkout" class="checkout input-large ui-datepicker-target" placeholder="@lang('messages.home.checkout')" value="{{ @$checkout }}">
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<label for="header-search-guests" class="screen-reader-only">
							@lang('messages.home.no_of_guests')
						</label>
						<div class="select select-block select-large mb-0">
							<select id="modal_guests" name="guests--sm">
								@for($i=1;$i<=16;$i++)
								<option value="{{ $i }}" {{ ($i == @$guest) ? 'selected' : '' }}>{{ $i }} guest{{ ($i>1) ? 's' : '' }}</option>
								@endfor
							</select>
						</div>
					</div>
				</div>
				@endif
				{{--HostExperienceBladeCommentStart--}}
				@if(Request::segment(1) != 's')
				<div class="my-4">
					<strong>{{ trans('messages.referrals.explore') }}</strong>
					<ul class="header_refinement_ul mt-2">
						<input type="hidden" name="header_refinement" class="header_refinement_input" value="Homes">
						<li><button class="header_refinement in_form active" data="Homes" type="button" id="home-refinement">{{ trans('messages.header.homes') }}</button></li>
						<li><button class="header_refinement in_form" data="Experiences" type="button" id="experience-refinement">{{ trans_choice('messages.home.experience',1) }}</button></li>
					</ul>
				</div>
				@else
				<div class="my-4">
					<strong>{{ trans('messages.referrals.explore') }}</strong>
					<ul class="header_refinement_ul mt-2">
						<input type="hidden" name="header_refinement" class="header_refinement_modal_input" value="Homes">
						<li><button class="header_refinement_modal in_form active" data="Homes" type="button" id="home-refinement">{{ trans('messages.header.homes') }}</button></li>
						<li><button class="header_refinement_modal in_form" data="Experiences" type="button" id="experience-refinement">{{ trans_choice('messages.home.experience',1) }}</button></li>
					</ul>
				</div>
				@endif
				{{--HostExperienceBladeCommentEnd--}}
				@if(Request::segment(1) != 's')
				<div class="explore_list">
					<div class="home_pro">
						<strong>{{ trans('messages.header.room_type') }}</strong>
						<div class="check_list">
							@foreach($header_room_type as $row_room)
							<div class="explore_check d-flex align-items-center">
								<input type="checkbox" value="{{ @$row_room->id }}" id="room-type-{{ @$row_room->id }}" class="mob_room_type" {{@in_array($row_room->id, @$room_type_selected) ? 'checked' : ''}} />
								@if($row_room->id == 1)
								<i class="icon icon-entire-place h5"></i>
								@endif
								@if($row_room->id == 2)
								<i class="icon icon-private-room h5"></i>
								@endif
								@if($row_room->id == 3)
								<i class="icon icon-shared-room h5"></i>
								@endif
								@if($row_room->id >= 4)
								<i class="icon icon1-home-building-outline-symbol2 h5"></i>
								@endif
								<label class="search_check_label" for="room-type-{{ @$row_room->id }}">{{ @$row_room->name }}</label>
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
								<input type="checkbox" id="cat-type-{{ @$row_cat->id }}" value="{{ @$row_cat->id }}" class="mob_cat_type" {{@in_array($row_cat->id, @$cat_type_selected) ? 'checked' : ''}} />
								<label class="search_check_label" for="cat-type-{{ @$row_cat->id }}">{{ @$row_cat->name }}</label>
							</div>
							@endforeach
						</div>
					</div>
					{{--HostExperienceBladeCommentEnd--}}
				</div>
				@endif
			</div>
			@if(Request::segment(1) != 's')
				<div class="modal-footer justify-content-center mt-3">
					<button type="submit" id="search-form-sm-btn" class="btn btn-primary d-flex align-items-center">
						<i class="icon icon-search mr-2"></i>
						<span>{{ trans('messages.header.find_place') }}</span>
					</button>
				</div>
			@endif
		</div>
	</div>
</div>


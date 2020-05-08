@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="rooms_detail">
	<div class="detail-sticky">
		<div class="container">
			<ul>
				<li>
					<a href="#detail-gallery">
						{{ trans_choice('messages.header.photo',2) }}
					</a>
				</li>
				<li>
					<a href="#about-scroll">
						{{ trans('messages.rooms.about_this_listing') }}
					</a>
				</li>
				<li>
					<a href="#review-info">
						{{ trans_choice('messages.header.review',2) }}
					</a>
				</li>
				<li>
					<a href="#host-profile">
						{{ trans('messages.rooms.the_host') }}
					</a>
				</li>
				<li>
					<a href="#detail-map">
						{{ trans('messages.your_trips.location') }}
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="detail-banner">
		<ul id="detail-gallery" class="detail-slider scroll-section">
			@foreach($rooms_photos as $row_photos)
			<li data-thumb="{{ $row_photos->slider_image_name }}" data-src="{{ $row_photos->slider_image_name }}" data-sub-html=".caption_{{ $row_photos->id }}">
				<img src="{{ $row_photos->slider_image_name }}" title="{{ $row_photos->highlights }}">
				<div class="caption_{{ $row_photos->id }}">
					<p> {{ $row_photos->highlights }} </p>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
	<div class="detail-content">
		<div class="container">
			<div class="detail-wrap row">
				<div class="col-12 col-lg-8 content-wrap">
					<div class="user-wrap pt-4 pb-3 d-md-flex">
						<div class="user-img text-center">
							<a href="{{ url('users/show/'.$result->user_id) }}">
								<img alt="User Profile Image" class="profile-image" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
								<h4 class="text-truncate">
									{{ $result->users->first_name }}
								</h4>
							</a>
						</div>

						<div class="user-info pl-md-5 flex-grow-1">
							<h3>
								{{ $result->name }}
							</h3>
							<p href="javascript:void(0)" class="room-place mr-2">
								{{$result->rooms_address->city}}@if($result->rooms_address->city !=''), 
								@endif 
								{{$result->rooms_address->state}}@if($result->rooms_address->state !=''), 
								@endif
								{{$result->rooms_address->country_name}}
							</p>

							@if($result->overall_star_rating)
							<a href="#reviews" class="review_link">
								<div class="star-rating-wrapper d-flex align-items-center">
									{!! $result->overall_star_rating !!}
									<span class="ml-2">({{ $result->reviews->count() }})</span>
								</div>
							</a>
							@endif					

							<div class="room-type row mt-3 text-center">
								<div class="col-4 room-icon">
									<i class="room_list_img_icon">
										<img src="{{ url($result->room_type_data->image_name) }}" alt="{{ $result->room_type_name }}">
									</i>
									<div class="numfel"> {{ $result->room_type_name }}
										@if($result->is_shared == 'Yes')
										<p class="mt-1 mb-0">
											<em class="h6">
												{{trans('messages.shared_rooms.shared_room_notes')}}
											</em>
										</p>
										@endif
									</div>
								</div>
								<div class="col-4 room-icon">
									<i class="icon icon-group"></i>
									<div class="numfel">
										{{ $result->accommodates }} {{ trans_choice('messages.home.guest',2) }}
									</div>
								</div>
								<div class="col-4 room-icon">
									<i class="icon icon-double-bed"></i>
									<div class="numfel">
										{{ $result->beds }} {{ trans_choice('messages.lys.bed',$result->beds) }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="detail-info">
						<div id="about-scroll" class="about-listing scroll-section py-4 text-center text-md-left">
							<h4 class="title">
								{{ trans('messages.rooms.about_this_listing') }}
							</h4>
							<p>
								{!! nl2br($result->summary) !!}
							</p>
							@if(Auth::check())
							@if(Auth::user()->id != $result->user_id)
							<a id="contact-host-link" href="javascript:void(0);" class="mt-3 theme-link" data-toggle="modal" data-target="#contact-modal">
								<strong>
									{{ trans('messages.rooms.contact_host') }}
								</strong>
							</a>
							@endif
							@endif
						</div>

						<div class="space-info">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.lys.the_space') }}
									</label>
								</div>
								<div class="col-md-9 col-sm-12">
									<div class="row">
										<div class="col-md-6">

											<div>{{ trans('messages.rooms.property_type') }}: 
												<strong>{{ $result->property_type_name }}</strong>
											</div>

											<div>
												{{ trans('messages.lys.accommodates') }}: 
												<strong>{{ $result->accommodates }}</strong>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												{{ trans('messages.lys.bedrooms') }}: 
												<strong>{{ $result->bedrooms }}</strong>
											</div>
											<div>
												{{ trans('messages.lys.beds') }}: 
												<strong>{{ $result->beds }}</strong>
											</div>
											<div>
												{{ trans('messages.lys.bathrooms') }}: 
												<strong>
													{{ $result->bathrooms == null?'0':$result->bathrooms }} 
													@if($result->bathrooms != null && $result->bathrooms !=0)
													/ {{$result->bathroom_shared=='No'?trans('messages.lys.private'):trans('messages.lys.shared_bath')}}
													@endif
												</strong>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						@if(count($amenities) !=0)
						<div class="amenities-info">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.lys.amenities') }}
									</label>
								</div>

								<div class="col-md-9 expandable expandable-trigger-more">
									<div class="expandable-content-summary">
										<div class="row rooms_amenities_before">
											@php $i = 1 @endphp

											@php $count = round(count($amenities)/2) @endphp

											@foreach($amenities as $all_amenities)
											@if($i < 6)
											<div class="col-md-6 amenities-icon mb-3 d-flex align-items-center {{ $all_amenities->status != null ? '' : 'text-muted' }}">
												<img class="icon-img" src="{{ $all_amenities->image_name}}"/>
												<span class="js-present-safety-feature future_basics text-truncate">
													<strong>
														@if($all_amenities->status == null)
														<del> 
															@endif

															@if(Session::get('language')=='en')
															{{ $all_amenities->name }}
															@elseif($all_amenities->namelang == null)
															{{ $all_amenities->name }}
															@else
															{{ $all_amenities->namelang }}
															@endif

															@if($all_amenities->status == null)
														</del> 
														@endif
													</strong>
												</span>
											</div>
											@endif
											@php $i++ @endphp
											@endforeach
											@if(count($amenities)>5)
											<div class="col-md-6">
												<a href="javascript:void(0)" class="expandable-trigger-more amenities_trigger theme-link">
													<strong>+ {{ trans('messages.profile.more') }}</strong>
												</a>
											</div>
											@endif
										</div>

										<div class="row rooms_amenities_after" style="display:none;">
											@php $i = 1 @endphp

											@php $count = round(count($amenities)/2) @endphp

											@foreach($amenities as $all_amenities)

											<div class="col-md-6 amenities-icon mb-3 d-flex align-items-center new_id {{ $all_amenities->status != null ? '' : 'text-muted' }}">
												<p hidden="hidden" class="get_type" data-id="{{ $all_amenities->type_id }}">
													{{ $all_amenities->type_id}}
												</p>
												<img class="icon-img" src="{{ $all_amenities->image_name}}"/>
												<span class="js-present-safety-feature future_basics text-truncate">
													<strong>
														@if($all_amenities->status == null)
														<del> 
															@endif

															@if(Session::get('language')=='en')
															{{ $all_amenities->name }}
															@elseif($all_amenities->namelang == null)
															{{ $all_amenities->name }}
															@else
															{{ $all_amenities->namelang }}
															@endif

															@if($all_amenities->status == null)
														</del> 
														@endif
													</strong>
												</span>
											</div>
											@php $i++ @endphp
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
						@endif

						<div class="price-info">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.rooms.prices') }}
									</label>
								</div>
								<div class="col-md-9 col-sm-12">
									<div class="row">
										<div class="col-md-6">
											<div>
												{{ trans('messages.rooms.extra_people') }}: 
												<strong> 
													@if($result->rooms_price->guests !=0 && $result->rooms_price->additional_guest!=0)

													<span> 
														{{ $currency_symbol }} {{ $result->rooms_price->additional_guest }}   / {{ trans('messages.rooms.night_after_guest',['count'=>$result->rooms_price->guests]) }}
													</span>

													@else
													<span>
														{{ trans('messages.rooms.no_charge') }}
													</span>
													@endif
												</strong>
											</div>

											<!-- weekend price -->
											@if($result->rooms_price['original_weekend'] != 0)
											<div>
												{{ trans('messages.lys.weekend_pricing') }}:                 
												<strong>
													<span id="weekly_price_string">
														{{ $currency_symbol }} {{ number_format($result->rooms_price->weekend) }}
													</span> /{{ trans('messages.lys.weekend') }}
												</strong> 
											</div>
											@endif
										</div>
										<div class="col-md-6">
											{{ trans('messages.your_reservations.cancellation') }}:
											<a href="{{ url('/home/cancellation_policies#'.$result->cancel_policy) }}" id="cancellation-policy" class="theme-link">
												<strong>
													{{trans('messages.cancellation_policy.'.strtolower($result->cancel_policy))}}
												</strong>
											</a>
										</div>
									</div>
									@if($result->length_of_stay_rules->count() > 0)
									<div class="row mt-3">
										<div class="col-md-12">
											<h6 class="text-black">
												<strong>
													{{trans('messages.lys.length_of_stay_discounts')}}
												</strong>
											</h6>
										</div>
										@foreach($result->length_of_stay_rules->splice(0,2) as $i => $rule)
										@if(@$rule['period'])
										<div class="col-md-6">
											@if($rule['period'] == 7)
											{{trans('messages.lys.weekly')}}
											@elseif($rule['period'] == 28)
											{{trans('messages.lys.monthly')}}
											@else
											{{$rule['period']}} {{trans('messages.lys.nights')}}
											@endif
										</div>
										<div class="col-md-6">
											{{$rule['discount']}}%
										</div>
										@endif
										@endforeach 
										@if($result->length_of_stay_rules->count() > 0)
										<div class="col-md-12">
											<a class="expandable-trigger-more theme-link" href="javascript:void(0)" onclick="$(this).hide(); $('.expand_data_length_of_stay').show();">
												<strong>+ {{trans('messages.profile.more')}}</strong>
											</a>
										</div>
										@foreach($result->length_of_stay_rules as $i => $rule)
										@if(@$rule['period'])
										<div class="col-md-6 expand_data_length_of_stay" style="display: none;">
											@if($rule['period'] == 7)
											{{trans('messages.lys.weekly')}}
											@elseif($rule['period'] == 28)
											{{trans('messages.lys.monthly')}}
											@else
											{{$rule['period']}} {{trans('messages.lys.nights')}}
											@endif
										</div>
										<div class="col-md-6 expand_data_length_of_stay" style="display: none;">
											{{$rule['discount']}}%
										</div>
										@endif
										@endforeach 
										@endif
									</div>
									@endif
									@if($result->early_bird_rules->count() > 0)
									<div class="row mt-3">
										<div class="col-md-12">
											<h6 class="text-black">
												<strong>
													{{trans('messages.lys.early_bird_discounts')}}
												</strong>
											</h6>
										</div>
										@foreach($result->early_bird_rules->splice(0,2) as $rule)
										<div class="col-md-6">
											{{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
										</div>
										<div class="col-md-6">
											{{$rule['discount']}}%
										</div>
										@endforeach 
										@if($result->early_bird_rules->count() > 0)
										<div class="col-md-12">
											<a class="expandable-trigger-more theme-link" href="javascript:void(0)" onclick="$(this).hide(); $('.expand_data_early_bird').show();">
												<strong>+ {{trans('messages.profile.more')}}</strong>
											</a>
										</div>
										@foreach($result->early_bird_rules as $rule)
										<div class="col-md-6 expand_data_early_bird" style="display: none;">
											{{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
										</div>
										<div class="col-md-6 expand_data_early_bird" style="display: none;">
											{{$rule['discount']}}%
										</div>
										@endforeach
										@endif
									</div>
									@endif
									@if($result->last_min_rules->count() > 0)
									<div class="row mt-3">
										<div class="col-md-12">
											<h6 class="text-black">
												<strong>
													{{trans('messages.lys.last_min_discounts')}}
												</strong>
											</h6>
										</div>
										@foreach($result->last_min_rules->splice(0,2) as $rule)
										<div class="col-md-6">
											{{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
										</div>
										<div class="col-md-6">
											{{$rule['discount']}}%
										</div>
										@endforeach
										@if($result->last_min_rules->count() > 0)
										<div class="col-md-12">
											<a class="expandable-trigger-more theme-link" href="javascript:void(0)" onclick="$(this).hide(); $('.expand_data_last_min').show();">
												<strong>+ {{trans('messages.profile.more')}}</strong>
											</a>
										</div>
										@foreach($result->last_min_rules as $rule)
										<div class="col-md-6 expand_data_last_min" style="display: none">
											{{$rule['period']}} {{trans_choice('messages.reviews.day', 2)}}
										</div>
										<div class="col-md-6 expand_data_last_min" style="display: none">
											{{$rule['discount']}}%
										</div>
										@endforeach
										@endif
									</div>
									@endif 
								</div>
							</div>
						</div>

						@if($result->rooms_description->space !='' || $result->rooms_description->access !='' || $result->rooms_description->interaction !='' || $result->rooms_description->neighborhood_overview !='' || $result->rooms_description->transit || $result->rooms_description->notes || $result->rooms_description->house_rules) 
						@php 
						$res =$result->rooms_description->toArray();
						$res = array_filter($res);
						@endphp
						
						<div class="description-info">
							<div class="py-4 row d-md-flex description">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.lys.description') }}
									</label>
								</div>

								<div class="col-md-9 expandable expandable-trigger-more all_description">
									@foreach (array_slice($res, 1, 2) as $key => $value)
									@if($key == 'space')
									<p><strong>{{ trans('messages.lys.the_space') }}</strong></p>
									<p>{!! nl2br($result->rooms_description->space) !!}</p>
									@endif

									@if($key == 'access')
									<p><strong>{{ trans('messages.lys.guest_access') }}</strong></p>
									<p>{!! nl2br($result->rooms_description->access) !!} </p>
									@endif

									@if($key == 'interaction')
									<p><strong>{{ trans('messages.lys.interaction_with_guests') }}</strong></p>
									<p> {!! nl2br($result->rooms_description->interaction) !!}</p>
									@endif

									@if($key == 'notes')
									<p><strong>{{ trans('messages.lys.other_things_note') }}</strong></p>
									<p> {!! nl2br($result->rooms_description->notes) !!}</p>
									@endif

									@if($key == 'neighborhood_overview')
									<p><strong>{{ trans('messages.lys.the_neighborhood') }}</strong></p>
									<p> {!! nl2br($result->rooms_description->neighborhood_overview) !!}</p>
									@endif

									@if($key == 'transit')
									<p><strong>{{ trans('messages.lys.getting_around') }}</strong></p>
									<p>{!! nl2br($result->rooms_description->transit) !!}</p>
									@endif
									@endforeach

									<div class="expandable-content" id="des_content" ng-show="show_more_desc">
										@php
										$count = 0;
										@endphp
										@foreach (array_slice($res, 3, count($res)) as $key => $value)
										@php
										$count ++;
										@endphp
										@if($key == 'space')
										<p><strong>{{ trans('messages.lys.the_space') }}</strong></p>
										<p>{!! nl2br($result->rooms_description->space) !!}</p>
										@endif

										@if($key == 'access')
										<p><strong>{{ trans('messages.lys.guest_access') }}</strong></p>
										<p>{!! nl2br($result->rooms_description->access) !!} </p>
										@endif

										@if($key == 'interaction')
										<p><strong>{{ trans('messages.lys.interaction_with_guests') }}</strong></p>
										<p> {!! nl2br($result->rooms_description->interaction) !!}</p>
										@endif
										@if($key == 'notes')
										<p><strong>{{ trans('messages.lys.other_things_note') }}</strong></p>
										<p> {!! nl2br($result->rooms_description->notes) !!}</p>
										@endif
										@if($key == 'neighborhood_overview')
										<p><strong>{{ trans('messages.lys.the_neighborhood') }}</strong></p>
										<p> {!! nl2br($result->rooms_description->neighborhood_overview) !!}</p>
										@endif

										@if($key == 'transit')
										<p><strong>{{ trans('messages.lys.getting_around') }}</strong></p>
										<p>{!! nl2br($result->rooms_description->transit) !!}</p>
										@endif
										@endforeach
									</div>

									@if ($count > 3)
									<a class="expandable-trigger-more desc theme-link" id="desc" href="" ng-show="show_more_desc != true;" ng-click="show_more_desc=true;">
										<strong>+ {{ trans('messages.profile.more') }}</strong>
									</a>
									@endif
								</div>
							</div>
						</div>
						@endif

						@if($result->rooms_description->house_rules !='')
						<div class="house-rules">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.lys.house_rules') }}
									</label>
								</div>
								<div class="col-md-9 expandable expandable-trigger-more expanded col-sm-12">
									<div class="expandable-content">
										<p>{!! nl2br($result->rooms_description->house_rules) !!}</p>
										<div class="expandable-indicator"></div>
									</div>
									<!-- <a href="javascript:void(0)" class="expandable-trigger-more theme-link">
										<strong>+ {{ trans('messages.profile.more') }}</strong>
									</a> -->
								</div>
							</div>
						</div>
						@endif

						@if(count($safety_amenities) !=0)
						<div class="safety-features amenities-info">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.rooms.safety_features') }}
									</label>
								</div>
								<div class="col-md-9 col-sm-12">
									<div class="js-no-safety-features-text d-none">
										{{ trans('messages.account.none') }}
									</div>
									<div class="d-md-flex row flex-wrap">
										@php $i = 1 @endphp

										@php $count = round(count($safety_amenities)/2) @endphp

										@foreach($safety_amenities as $row_safety)

										<div class="col-md-6 amenities-icon mb-3 d-flex align-items-center {{ $row_safety->status != null ? '' :'text-muted'}}">
											<img class="icon-img" src="{{$row_safety->image_name}}"/>
											<span class="js-present-safety-feature cut-span">
												<strong>
													@if($row_safety->status == null)
													<del> 
														@endif

														@if(Session::get('language')=='en')
														{{ $row_safety->name }}
														@elseif($row_safety->namelang == null)
														{{ $row_safety->name }}
														@else
														{{ $row_safety->namelang }}
														@endif

														@if($row_safety->status == null)
													</del> 
													@endif
												</strong>
											</span>
										</div>
										@php $i++ @endphp
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endif

						<!-- Bed details -->
						@if($result->rooms_beds_count)
						<div class="room-detail-sleep py-3">
							<h4>
								{{trans('messages.rooms.sleeping_arrangements')}}
							</h4>
							<ul class="bed-type-slider mt-3 mt-md-4 p-0 owl-carousel">
								@foreach($result->get_first_bed_type as $room_no => $bed)
								@if($result->searcharray('1','count', $bed) > 0)
								<li class="bed-type">
									<div class="bed-type-img d-flex flex-wrap">
										@foreach($bed as $bed_name)
										@if($bed_name['count'] > 0)
										@for($f=0;$f<$bed_name['count'];$f++)
										<img src="{{$bed_name['icon']}}"/>
										@endfor
										@endif
										@endforeach
									</div>
									<div class="bed-type-info">
										<h5>
											{{trans('messages.rooms.bedroom')}} {{$room_no}}
										</h5>
										@php $first=1; @endphp
										@foreach($bed as $bed_name)
										@if($bed_name['count'] > 0)
										<span>
											@if($first==0) , @endif
											{{$bed_name['count']}} {{$bed_name['name']}}
											@php $first=0; @endphp 
										</span> 
										@endif
										@endforeach
									</div>
								</li>
								@endif
								@endforeach

								@if($result->searcharray('1','count', $result->get_common_bed_type) > 0)
								<li class="bed-type">
									<div class="bed-type-img d-flex flex-wrap">
										@foreach($result->get_common_bed_type as $bed_name)
										@if($bed_name['count'] > 0)
										@for($f=0;$f<$bed_name['count'];$f++)
										<img src="{{$bed_name['icon']}}"/>
										@endfor
										@endif
										@endforeach
									</div>
									<div class="bed-type-info">
										<h5>
											{{trans('messages.rooms.common_space')}}
										</h5>
										@php $first=1; @endphp
										@foreach($result->get_common_bed_type as $bed_name)
										@if($bed_name['count'] > 0)
										<span>
											@if($first==0) <span>,</span> @endif
											{{$bed_name['count']}} {{$bed_name['name']}} 
											@php $first=0; @endphp
										</span> 
										@endif
										@endforeach
									</div>
								</li>
								@endif
							</ul>
						</div>
						<hr>
						@endif

						<div class="availability-info">
							<div class="py-4 row d-md-flex">
								<div class="col-12 col-md-3">
									<label>
										{{ trans('messages.rooms.availability') }}
									</label>
								</div>
								<div class="col-md-9 col-sm-12">
									@if($result->rooms_price->minimum_stay || $result->rooms_price->maximum_stay)
									<div class="row">
										@if($result->rooms_price->minimum_stay)
										<div class="col-md-6">
											<span>{{trans('messages.lys.minimum_stay')}}:</span>
											<strong>{{$result->rooms_price->minimum_stay}}</strong>
										</div>
										@endif
										@if($result->rooms_price->maximum_stay)
										<div class="col-md-6">
											<span>{{trans('messages.lys.maximum_stay')}}:</span>
											<strong>{{$result->rooms_price->maximum_stay}}</strong>
										</div>
										@endif
									</div>
									@endif

									@if($result->availability_rules->count() > 0)
									<div class="row flex-wrap mt-2">
										@foreach($result->availability_rules->splice(0, 2) as $rule)
										<div class="col-md-12 my-2">
											{{trans('messages.lys.during')}} {{$rule->during}}
											@if($rule->minimum_stay)
											<p class="m-0" style="text-transform: capitalize;">
												{{trans('messages.lys.guest_stay_for_minimum')}} {{$rule->minimum_stay}} {{trans('messages.lys.nights')}}
											</p>
											@endif
											@if($rule->maximum_stay)
											<p class="m-0" style="text-transform: capitalize;">
												{{trans('messages.lys.guest_stay_for_maximum')}} {{$rule->maximum_stay}} {{trans('messages.lys.nights')}}
											</p>
											@endif
										</div>
										@endforeach 

										@if($result->availability_rules->count() > 0)
										<div class="col-md-12">
											<a class="expandable-trigger-more theme-link" href="javascript:void(0)" onclick="$(this).hide(); $('#expand_data_availability_rules').show();">
												<strong>+ {{trans('messages.profile.more')}}</strong>
											</a>
										</div>

										<div id="expand_data_availability_rules" style="display: none;">@foreach($result->availability_rules as $rule)
											<div class="col-md-12 my-2">
												{{trans('messages.lys.during')}} {{$rule->during}}
												@if($rule->minimum_stay)
												<p class="m-0" style="text-transform: capitalize;">
													{{trans('messages.lys.guest_stay_for_minimum')}} {{$rule->minimum_stay}} {{trans('messages.lys.nights')}}
												</p>
												@endif
												@if($rule->maximum_stay)
												<p class="m-0" style="text-transform: capitalize;">
													{{trans('messages.lys.guest_stay_for_maximum')}} {{$rule->maximum_stay}} {{trans('messages.lys.nights')}}
												</p>
												@endif
											</div>
											@endforeach 
										</div>
										@endif
									</div>
									@endif
									<div class="row">
										<div class="col-md-6 lang-chang-label col-sm-6">
											<a href="javascript:void(0);" id="view-calendar" class="theme-link">
												<strong>{{ trans('messages.rooms.view_calendar') }}</strong>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						@if($result->video != '' || $rooms_photos->count() > 0)
						<div class="gallery-wrap border-0">
							<div class="photo-grid flex-wrap d-none d-md-flex justify-content-between">
								@php $i = 1 @endphp
								@foreach($rooms_photos->sortByDesc('featured') as $row_photos)
								@if(count($rooms_photos) == 1)
								<div class="lg-gallery mb-2">
									<a class="open-gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="javascript:void(0)">
									</a>
								</div>
								@else

								@if($i == 1)
								<div class="lg-gallery mb-2">
									<a class="open-gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="javascript:void(0)">
									</a>
								</div>
								@endif

								@if($i==2 && $i >1)               
								<div class="sm-gallery"> 
									<a class="open-gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="javascript:void(0)">
									</a> 
								</div>
								@endif

								@if($i==3 && $i >2)
								<div class="sm-gallery">
									<a class="open-gallery" style="background-image: url({{ $row_photos->slider_image_name }})" href="javascript:void(0)">
									</a>
									<a class="open-gallery more-gallery-btn d-flex align-items-center justify-content-center" href="javascript:void(0)">
										<h5>
											{{ trans('messages.rooms.see_all') }} {{ round(count($rooms_photos))}} {{ trans_choice('messages.header.photo',2) }}
										</h5>
									</a>
								</div>
								@endif
								@endif
								@php $i++ @endphp
								@endforeach
							</div>

							@if($result->video)
							<div class="detail-video mt-3">
								<iframe width="100%" height="300" src="{{ $result->video }}" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>
							</div>
							@endif
						</div>
						@endif

						<div id="review-info" class="review-section scroll-section border-0">
							@if(!$result->reviews->count())
							<div class="review-content mt-3">
								<div class="panel-body">
									<h5>
										{{ trans('messages.rooms.no_reviews_yet') }}
									</h5>
									@if($result->users->reviews->count())
									<p>
										{{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count(), ['count'=>$result->users->reviews->count()]) }}
									</p>
									<a href="{{ url('users/show/'.$result->user_id) }}" class="btn btn-secondary mt-2">
										{{ trans('messages.rooms.view_other_reviews') }}
									</a>
									@endif
								</div>
							</div>
							@else

							<div class="review-wrapper">
								<div class="review-count d-flex pt-4 mt-4 align-items-center">
									<h5>
										{{ $result->reviews->count() }} {{ trans_choice('messages.header.review',$result->reviews->count()) }}
									</h5>
									<div class="ml-3 star-rating-wrapper">
										{!! $result->overall_star_rating !!}
									</div>
								</div>

								<div class="review-main pt-2 mt-4">
									<div class="review-inner my-3">
										<div class="row">
											<div class="col-lg-3">
												<span class="text-muted">
													{{ trans('messages.lys.summary') }}
												</span>
											</div>
											<div class="col-lg-9">
												<div class="row">
													<div class="col-lg-6 summary_details">
														<div class="d-flex justify-content-between">
															<strong>
																{{ trans('messages.reviews.accuracy') }}
															</strong>
															<div class="star-rating-wrapper">
																{!! $result->accuracy_star_rating !!}
															</div>
														</div>
														<div class="d-flex justify-content-between">
															<strong>
																{{ trans('messages.reviews.communication') }}
															</strong>
															<div class="star-rating-wrapper">
																{!! $result->communication_star_rating !!}
															</div>
														</div>
														<div class="d-flex justify-content-between">
															<strong>
																{{ trans('messages.reviews.cleanliness') }}
															</strong>
															<div class="star-rating-wrapper">
																{!! $result->cleanliness_star_rating !!}
															</div>
														</div>
													</div>
													<div class="col-lg-6 summary_details">
														<div class="d-flex justify-content-between">
															<strong>{{ trans('messages.reviews.location') }}</strong>
															<div class="star-rating-wrapper">
																{!! $result->location_star_rating !!}
															</div>
														</div>
														<div class="d-flex justify-content-between">
															<strong>{{ trans('messages.home.checkin') }}</strong>
															<div class="star-rating-wrapper">
																{!! $result->checkin_star_rating !!}
															</div>
														</div>
														<div class="d-flex justify-content-between">
															<strong>{{ trans('messages.reviews.value') }}</strong>
															<div class="star-rating-wrapper">
																{!! $result->value_star_rating !!}
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="review-content">
										<div class="panel-body">
											@foreach($result->reviews as $row_review)
											<div class="d-flex row">
												<div class="col-12 col-md-3 my-2">
													<div class="profile-img d-inline-block text-center">
														<a class="media-photo media-round align-top" href="{{ url('users/show/'.$row_review->user_from) }}">
															<img title="{{ $row_review->users_from->first_name }}" src="{{ $row_review->users_from->profile_picture->src }}" data-pin-nopin="true" alt="shared.user_profile_image">
															<h5 class="text-truncate">
																{{ $row_review->users_from->first_name }}
															</h5>
														</a>
													</div>
												</div>
												<div class="col-12 col-md-9 mb-2 mt-md-2">
													<div class="review-text" data-review-id="{{ $row_review->id }}">
														<div class="expandable-content" tabindex="-1">
															<p>{{ $row_review->comments }}</p>
														</div>
													</div>
													<div class="review-subtext">
														<span class="date d-inline-block">
															{{ $row_review->date_fy }}
														</span>
													</div>
												</div>
											</div>
											@endforeach
											@if($result->users->reviews->count() - $result->reviews->count())
											<div class="total-reviews mt-3 pt-3">
												<p>
													{{ trans_choice('messages.rooms.review_other_properties', $result->users->reviews->count() - $result->reviews->count(), ['count'=>$result->users->reviews->count() - $result->reviews->count()]) }}
												</p>
												<a target="blank" class="btn btn-secondary" href="{{ url('users/show/'.$result->user_id) }}">
													<span>
														{{ trans('messages.rooms.view_other_reviews') }}
													</span>
												</a>
											</div>
											@endif
										</div>
									</div>
								</div>
							</div>
							@endif
						</div>

						<div id="host-profile" class="host-profile-section scroll-section pt-4 mt-4">
							<h4 class="mb-4">
								{{ trans('messages.rooms.about_host') }}, {{ $result->users->first_name }}
							</h4>
							<div class="row align-items-center">
								<div class="col-12 col-md-3 text-center">
									<a href="{{ url('users/show/'.$result->user_id) }}" class="profile-img">
										<img alt="{{ $result->users->first_name }}" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
									</a>
								</div>
								<div class="col-12 col-md-9 text-center text-md-left mt-3 mt-md-0">
									@if($result->users->live)
									<span>
										{{ $result->users->live }}
									</span>
									@endif
									<span>
										{{ trans('messages.profile.member_since') }} 
										{{ $result->users->since }}
									</span>
									@if(Auth::check())
									@if(Auth::user()->id != $result->user_id)
									<div id="contact_wrapper">
										<button id="host-profile-contact-btn" class="btn btn-primary mt-2" data-toggle="modal" data-target="#contact-modal">
											{{ trans('messages.rooms.contact_host') }}
										</button>
									</div>
									@endif
									@endif
								</div>
							</div>

							<div class="trust-info mt-4 pt-4 d-flex align-items-center">
								<div class="col-md-3 p-0 d-none">
									<label>
										{{ trans('messages.rooms.trust') }}
									</label>
								</div>
								<div class="col-md-9">
									<div class="badge-pill d-inline-block text-center">
										<a rel="nofollow" href="{{ url('users/show/'.$result->user_id) }}#reviews">
											<span class="badge-pill-count">
												{{ $result->users->reviews->count() }}
											</span>
											<h5>
												{{ trans_choice('messages.header.review',2) }}
											</h5>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 booking-form position-sticky">
					<form accept-charset="UTF-8" action="{{ url('payments/book/'.$room_id) }}" id="book_it_form" method="post">
						{!! Form::token() !!}
						<h4 class="screen-reader-only">
							{{ trans('messages.rooms.request_to_book') }}
						</h4>
						<div id="pricing" class="price-label d-flex align-items-center" itemprop="offers">
							<div id="price_amount" class="book-it-price-amount">
								<span>
									{{ $currency_symbol }}
								</span>
								<span id="rooms_price_amount">
									{{ $result->rooms_price->night }}
								</span>
								@if($result->booking_type == 'instant_book')
								<span aria-label="Book Instantly">
									<i class="icon icon-instant-book icon-flush-sides tool-amenity1"></i>
									<div class="tooltip-amenity" role="tooltip" data-sticky="true" aria-hidden="true">
										<ul class="panel-body">
											<li>
												<strong>
													Instant Book
												</strong>
											</li>
											<li>
												Book without waiting for the host to respond
											</li>
										</ul>
									</div>
								</span>
								@endif
							</div>
							<div id="payment-period-container" class="book-pay-type ml-auto">
								<span id="per_night" class="per-night">
									{{ trans('messages.rooms.per_night') }}
								</span>
								<span id="per_month" class="per-month d-none">
									{{ trans('messages.rooms.per_month') }}
									<i id="price-info-tooltip" class="icon icon-question d-none" data-behavior="tooltip"></i>
								</span>
							</div>
						</div>

						<div id="book_it" class="display-subtotal">
							<div class="book-it-panel">
								<div class="panel-body">
									<div class="form-fields">
										<div class="row">
											<div class="book-form-dates col-9 d-flex align-items-end p-0">
												<div class="col-md-6 pr-0">
													<label for="checkin">
														{{ trans('messages.home.checkin') }}
													</label>
													<input readonly="readonly" class="checkin ui-datepicker-target" onfocus="this.blur()" autocomplete="off" id="list_checkin"  placeholder="{{ strtolower(DISPLAY_DATE_FORMAT) }}" type="text">
													<input type="hidden" name="checkin" value="{{ $formatted_checkin }}" class="formatted_checkin">
												</div>

												<input readonly="readonly" type="hidden" ng-model="room_id" ng-init="room_id = {{ $room_id }}">
												<input type="hidden" id="room_blocked_dates" value="" >
												<input type="hidden" id="calendar_available_price" value="" >
												<input type="hidden" id="room_available_price" value="" >
												<input type="hidden" id="price_tooltip" value="">
												<input type="hidden" id="weekend_price_tooltip" value="" >
												<input type="hidden" id="url_checkin" value="{{ $checkin }}" >
												<input type="hidden" id="url_checkout" value="{{ $checkout }}" >
												<input type="hidden" id="url_guests" value="{{ $guests }}" >
												<input type="hidden" name="booking_type" id="booking_type" value="{{ $result->booking_type }}">
												<input type="hidden" name="cancellation" id="cancellation" value="{{ $result->cancel_policy }}" >

												<div class="col-md-6 pr-0">
													<label for="checkout">
														{{ trans('messages.home.checkout') }}
													</label>
													<input readonly="readonly" class="checkout ui-datepicker-target" onfocus="this.blur()" autocomplete="off" id="list_checkout" placeholder="{{ strtolower(DISPLAY_DATE_FORMAT) }}" type="text">
													<input type="hidden" name="checkout" value="{{ $formatted_checkout }}" class="formatted_checkout">
												</div>
											</div>
											<div class="col-3">
												<label for="number_of_guests">
													{{ trans_choice('messages.home.guest',2) }}
												</label>
												<div class="select select-block">
													<select id="number_of_guests" name="number_of_guests">
														@for($i=1;$i<= $result->accommodates;$i++)
														<option value="{{ $i }}"> {{ $i }}</option>
														@endfor
													</select>
												</div>
											</div>
										</div>

										<div id="guest_error" class="simple-dates-message-container" style="display:none">
											<div class="media text-kazan space-top-2 space-2">
												<div class="pull-left message-icon">
													<i class="icon icon-currency-inr"></i>
												</div>
												<div class="media-body">
													<strong>
														{{ trans('messages.search.enter_guest') }}
													</strong>
												</div>
											</div>
										</div>

										<div class="simple-dates-message-container d-none">
											<div class="media my-2">
												<span class="message-icon">
													<i class="icon icon-currency-inr"></i>
												</span>
												<div class="media-body">
													<strong>
														{{ trans('messages.search.enter_dates') }}
													</strong>
												</div>
											</div>
										</div>
									</div>

									<div class="js-book-it-status">
										<div class="js-book-it-enabled clearfix">
											<div class="js-subtotal-container book-it__subtotal panel-padding-fit mt-3" style="display:none;">
												<table class="table table-bordered price_table" >
													<tbody>
														<tr>
															<td class="pos-rel room-night">
																<span class="lang-chang-label"> 
																	{{ $currency_symbol }}
																</span>  
																<span id="rooms_price_amount_1" value="">
																	{{ $result->rooms_price->night }}
																</span> 
																<span class="lang-chang-label">  x </span>
																<span id="total_night_count" value="">0</span> 
																{{ trans_choice('messages.rooms.night',1) }}
																<i id="service-fee-tooltip" rel="tooltip" class="icon icon-question" title="{{ trans('messages.rooms.avg_night_rate') }}"></i>
															</td>
															<td>
																<span class="lang-chang-label">
																	{{ $currency_symbol }}
																</span>
																<span id="total_night_price" value="">0</span>
															</td>
														</tr>
														<tr class="early_bird booking_period" style="color: #f05056;"> 
															<td>
																<span class="booked_period_discount" value="">0</span>% {{ trans('messages.rooms.early_bird_price_discount') }}
															</td>
															<td>
																-{{ $currency_symbol }}
																<span class="booked_period_discount_price" value="">0</span>
															</td>
														</tr>
														<tr class="last_min booking_period" style="color: #f05056;"><td>
															<span class="booked_period_discount" value="">0</span>% {{ trans('messages.rooms.last_min_price_discount') }}
														</td>
														<td>
															-{{ $currency_symbol }}
															<span class="booked_period_discount_price" value="">0</span>
														</td>
													</tr>
													<tr class="weekly"> 
														<td>
															<span id="weekly_discount" value="">0</span>% {{ trans('messages.rooms.weekly_price_discount') }}
														</td>
														<td>
															-{{ $currency_symbol }}
															<span id="weekly_discount_price" value="">
																0
															</span>
														</td>
													</tr>
													<tr class="monthly"> 
														<td>
															<span id="monthly_discount" value="">0</span>% {{ trans('messages.rooms.monthly_price_discount') }}
														</td>
														<td>-{{ $currency_symbol }}<span id="monthly_discount_price" value="">0</span>
														</td>
													</tr>
													<tr class="long_term" style="color: #f05056;"> 
														<td>
															<span id="long_term_discount" value="">0</span>% {{ trans('messages.rooms.long_term_price_discount') }}
														</td>
														<td>-{{ $currency_symbol }}<span  id="long_term_discount_price" value="">0</span></td>
													</tr>
													<tr>
														<td class="room-ser-fee">
															{{ trans('messages.rooms.service_fee') }}
															<i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.rooms.24_7_help') }}"></i>
														</td>
														<td>
															<span class="lang-chang-label">
																{{ $currency_symbol }}
															</span>
															<span id="service_fee" value="">0</span>
														</td>
													</tr>

													<tr class="additional_price"> 
														<td>
															{{ trans('messages.rooms.addtional_guest_fee') }}
														</td>
														<td>{{ $currency_symbol }}<span id="additional_guest" value="">0</span></td>
													</tr>

													<tr class="cleaning_price"> 
														<td>
															{{ trans('messages.rooms.cleaning_fee') }}
														</td>
														<td>
															{{ $currency_symbol }}
															<span id="cleaning_fee" value="">0</span>
														</td>
													</tr>

													<tr>
														<td>{{ trans('messages.rooms.total') }}</td>
														<td>
															<span class="lang-chang-label">
																{{ $currency_symbol }}
															</span>
															<span id="total" value="">0</span>
														</td>
													</tr>
													<tr class="security_price"> 
														<td>
															{{ trans('messages.rooms.security_fee') }} <i id="service-fee-tooltip" rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}"></i>
														</td>
														<td>
															{{ $currency_symbol }}
															<span id="security_fee" value="">0</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div id="book_it_disabled" class="text-center" style="display:none;">
											<p id="book_it_disabled_message" class="icon-rausch book_it_disabled_msg">
												{{ trans('messages.rooms.dates_not_available') }}
											</p>
											<p id="book_it_error_message" class="text-danger book_it_disabled_msg">
											</p>
											<a href="{{URL::to('/')}}/s?location={{$result->rooms_address->city }}" class="btn btn-large btn-block" id="view_other_listings_button">
												{{ trans('messages.rooms.view_other_listings') }}
											</a>
										</div>          
										<div class="js-book-it-btn-container mt-3 {{ ($result->user_id == @Auth::user()->id) ? 'd-none' : '' }}">
											<button type="submit" class="js-book-it-btn btn btn-block btn-primary">
												<span class="{{ ($result->booking_type != 'instant_book') ? '' : 'd-none' }}">
													{{ trans('messages.rooms.request_to_book') }}
												</span>
												<span class="{{ ($result->booking_type == 'instant_book') ? '' : 'd-none' }}">
													<i class="icon icon-bolt book-instant-icon"></i>
													{{ trans('messages.lys.instant_book') }}
												</span>
											</button> 
											<input type="hidden" name="instant_book" value="{{ $result->booking_type }}">
										</div>
										<p class="text-muted mt-3 mb-0 text-center {{ ($result->user_id == @Auth::user()->id) ? 'd-none' : '' }}">
											<small>
												{{ trans('messages.rooms.review_before_paying') }}
											</small>
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card wishlist-panel mt-3">
							<div class="card-body">
								@if(Auth::check())
								<div class="wishlist-wrapper">
									<div class="rich-toggle wish_list_button not_saved" data-hosting_id="{{ $result->id }}">
										<input type="checkbox" name="wishlist-button" id="wishlist-button" @if(@$is_wishlist > 0 ) checked @endif >
										<label for="wishlist-button" class="btn btn-block" data-toggle="modal" data-target="#wishlist-modal">
											<span class="rich-toggle-checked">
												<i class="icon icon-heart mr-2"></i>
												Saved to Wish List
											</span>
											<span class="rich-toggle-unchecked">
												<i class="icon icon-heart-alt mr-2"></i>
												{{ trans('messages.wishlist.save_to_wishlist') }}
											</span>
										</label>
									</div>
								</div>
								@endif
								<div class="social-share-widget d-flex align-items-center justify-content-center mt-3 text-center">
									<span class="share-title mr-2">
										{{ trans('messages.rooms.share') }}:
									</span>
									<ul class="share-triggers">
										<li>
											<a class="share-btn link-icon" data-email-share-link="" data-network="email" rel="nofollow" title="{{ trans('messages.login.email') }}" href="mailto:?subject=I love this room&amp;body=Check out this {{ Request::url() }}">
												<span class="screen-reader-only">
													{{ trans('messages.login.email') }}
												</span>
												<i class="icon icon-envelope social-icon-size"></i>
											</a>
										</li>
										<li>
											<a class="share-btn link-icon" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
												<span class="screen-reader-only">Facebook</span>
												<i class="icon icon-facebook social-icon-size"></i>
											</a>
										</li>
										<li>
											<a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="http://twitter.com/intent/tweet?text=Love this! {{ $result->name }} - {{ $result->property_type_name }} for Rent - {{ "@".$site_name}} Travel&url={{ Request::url() }}" target="_blank">
												<span class="screen-reader-only">Twitter</span>
												<i class="icon icon-twitter social-icon-size"></i>
											</a>  
										</li>
										<li>
											<a class="share-btn link-icon" data-network="pinterest" rel="nofollow" title="Pinterest" href="http://pinterest.com/pin/create/button/?url={{ Request::url() }}&media={{ $result->photo_name }}&description={{ $result->summary }}" target="_blank">
												<span class="screen-reader-only">Pinterest</span>
												<i class="icon icon-pinterest social-icon-size"></i>
											</a> 
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<input id="hosting_id" name="hosting_id" type="hidden" value="{{ $result->id }}">
					<input id="room_types" name="room_types" type="hidden" value="{{ $room_types }}">
				</form>
			</div>
		</div>

		<div id="detail-map" class="room-map scroll-section my-4 my-md-5" data-reactid=".2">
			<div id="map" data-lat="{{ $result->rooms_address->latitude }}" data-lng="{{ $result->rooms_address->longitude }}"></div>
			<div class="hover-card text-center d-none d-md-block">
				<h4>
					{{ trans('messages.rooms.listing_location') }}
				</h4>
				<a href="">
					<span>{{$result->rooms_address->state}},</span>
				</a>
				<a href="">
					<span>{{$result->rooms_address->country_name}}</span>
				</a>
			</div>
		</div>

		@if(count($similar)!= 0)
		<div class="similar-listings mb-4 mb-md-5">
			<h4 class="title-sm mb-3">
				{{ trans('messages.rooms.similar_listings') }}
			</h4>
			<div id="similar-slider" class="owl-carousel" item-length="{{count($similar)}}">
				@foreach($similar as $rooms)
				<div class="listing list_view">
					<div class="pro-img">
						<a href="{{$rooms->link}}" target="listing_{{$rooms->id}}" class="media-photo media-cover">
							<div class="listing-img-container media-cover text-center">
								<img id="rooms_image_{{$rooms->id}}" src="{{ $rooms->photo_name }}" class="img-responsive-height" alt="{{$rooms->name}}">
							</div>
						</a>
					</div>
					<div class="pro-info">
						<h4 class="text-truncate">
							<span>
								{{ $rooms->room_type_name }}
							</span>
							<span>·</span>
							<span>
								{{ $rooms->beds }} {{ trans_choice('messages.lys.bed',$rooms->beds) }}
							</span>
						</h4>
						<a href="{{$rooms->link}}" target="listing_{{$rooms->id}}">
							<h5 class="text-truncate"> 
								{{$rooms->name}}
							</h5>
						</a>
						<p class="price">	
							{{ $currency_symbol }}
							{{ $rooms->rooms_price->night }}
							{{ trans("messages.rooms.per_night") }}
							@if($rooms->booking_type == 'instant_book')
							<span> 
								<i class="icon icon-instant-book">
								</i>
							</span>
							@endif
						</p>
						<div class="d-flex align-items-center">   
							@if($rooms->overall_star_rating)
							{!!$rooms->overall_star_rating!!}
							@endif

							@if($rooms->reviews_count)										
							<span class="review-count mx-2">
								· {{$rooms->reviews_count}}
							</span>
							<span class="review-label">
								{{ trans_choice('messages.header.review', $rooms->reviews_count) }}
							</span>
							@endif
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@endif
	</div>
</div>

<!--Contact Host Modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header border-0 p-0">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body d-md-flex p-0">
				<div class="host-info col-md-4">
					<div class="profile-img mb-4">
						<a href="{{ url('/') }}/users/show/{{ $result->user_id }}" class="media-photo media-round">
							<img alt="shared.user_profile_image" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
						</a>
					</div>

					<h5>
						{{ trans('messages.rooms.send_a_message',['first_name'=>$result->users->first_name]) }}
					</h5>
					<p>
						{{ trans('messages.rooms.share_following') }}:
					</p>

					<ul>
						<li>
							<span>
								{{ trans('messages.rooms.tell_about_yourself',['first_name'=>$result->users->first_name]) }}
							</span>
						</li>
						<li>
							<span>
								{{ trans('messages.rooms.what_brings_you',['city'=>$result->rooms_address->city]) }}?
							</span>
						</li>
						<li>
							<span>
								{{ trans('messages.rooms.love_about_listing') }}!
							</span>
						</li>
					</ul>
				</div>
				<div class="compose-info col-md-8">
					<form id="message_form" class="contact-host-panel m-0" action="{{ url('/') }}/users/ask_question/{{ $result->id }}?src_url=rooms/{{ $result->id }}" method="POST">
						{!! Form::token() !!}
						<h5>
							{{ trans('messages.rooms.when_you_traveling') }}?
						</h5>
						<div class="mt-3 d-flex clearfix">
							<div class="col-4 p-0 checkin_input">
								<label class="screen-reader-only">{{ trans('messages.home.checkin') }}</label>
								<input value="" readonly="readonly" id="message_checkin" onfocus="this.blur()" class="checkin text-center ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" type="text" required />
								<input type="hidden" name="message_checkin">
								<span hidden="hidden" id="room_id">{{ $result->id }}</span>
							</div>
							<div class="col-4 p-0">
								<label class="screen-reader-only">{{ trans('messages.home.checkout') }}</label>
								<input value="" readonly="readonly" id="message_checkout" onfocus="this.blur()" class="checkout text-center ui-datepicker-target border-left-0 border-right-0" placeholder="{{ trans('messages.home.checkout') }}" type="text" required />
								<input type="hidden" name="message_checkout">
							</div>
							<div class="col-4 p-0">
								<div class="select select-block">
									<select class="text-center" name="message_guests" id="message_guests">
										@for($i=1;$i<= $result->accommodates;$i++)
										<option value="{{ $i }}">{{ $i }} {{ trans_choice('messages.home.guest',$i) }}</option>
										@endfor
									</select>
								</div>
							</div>
						</div>
						<span id="errors" class="error-msg mt-1 d-none">
							Please Fill the details
						</span>
						<div class="message-panel tooltip-fixed tooltip-bottom-left my-3">
							<div class="panel-body">
								<textarea class="focus-on-active" name="question" placeholder="{{ trans('messages.rooms.start_your_msg') }}..."></textarea>
							</div>
						</div>
						<input name="message_save" value="1" type="hidden">
						<div class="send-user mt-4 d-flex align-items-center justify-content-between">
							<div class="profile-img">
								<a href="{{ url('/') }}/users/show/{{ (Auth::check()) ? Auth::user()->id : '' }}" class="media-photo media-round">
									<img alt="shared.user_profile_image" data-pin-nopin="true" src="{{ (Auth::check()) ? Auth::user()->profile_picture->src : '' }}" title="{{ (Auth::check()) ? Auth::user()->first_name : '' }}">
								</a>
							</div>
							<button id="contact_message_send" type="submit" class="btn btn-primary">
								{{ trans('messages.your_reservations.send_message') }}
							</button>
						</div>
					</form>
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
								<form class="wl-modal-form d-none">
									<div class="d-flex align-items-center">
										<input type="text" class="wl-modal-input flex-grow-1 border-0" autocomplete="off" id="wish_list_text" value="{{ $result->rooms_address->city }}" placeholder="Name Your Wish List" required>
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
	$(document).ready(function() {
		function booking_form() {
			var header_height = $("header").outerHeight();
			var detail_banner = $('.detail-banner').position().top + $('.detail-banner').outerHeight();
			var book_it = $('#book_it').outerWidth();
			var pricing = $('#pricing').outerHeight();
			$('.booking-form').css({"top": header_height + "px"});

			if ($(window).scrollTop() >= (detail_banner - header_height)) {
				$('.booking-form').addClass('active');
				$('.detail-sticky').addClass('active');
				$('.booking-form #pricing').css({"width": book_it + "px"});
				$('.booking-form #pricing').css({"top": header_height + "px"});
				$('#book_it').css({"margin-top": pricing + "px"});
			} 
			else {
				$('.booking-form').removeClass('active');
				$('.detail-sticky').removeClass('active');
				$('#book_it').css({"margin-top": 0 + "px"});
			}
		}

		booking_form();

		$(window).scroll(function() {
			booking_form();
		});

		$(window).resize(function() {
			booking_form();
		});
	});
</script>
@endpush
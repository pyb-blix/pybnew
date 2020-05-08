@extends('template')
@section('main')
<main role="main" id="site-content" ng-cloak>
	<div class="shared-itinerary py-4 py-md-5">
		<div class="container">		
			<div class="itinerary-info">
				<h2>
					{{ trans('messages.your_trips.you_are_gonna') }}
					@if($reservation_details->rooms->rooms_address->city != '')
					{{ $reservation_details->rooms->rooms_address->city }}!
					@else
					{{ $reservation_details->rooms->rooms_address->state }}!
					@endif
				</h2>
				<div class="mt-2">
					<label class="d-block mb-1">
						{{ trans('messages.your_trips.reservation_code') }}:							
						<span>
							{{ $reservation_details->code }}
						</span>
					</label>
					<span class="d-print-none">
						<a class="normal-link" href="{{ url('/') }}/reservation/receipt?code={{ $reservation_details->code }}">
							<span>
								{{ trans('messages.your_trips.view_receipt') }}
							</span>
						</a>
					</span>
				</div>

				<div class="row mt-4">
					<div class="col-md-6 itinerary-img d-print-none order-md-2">
						<img src="{{ $reservation_details->rooms->photo_name }}" class="img-fluid w-100">
					</div>

					<div class="col-md-6 itinerary-card order-md-1">
						<div class="card">
							<div class="card-body">
								<div class="row itinerary_checkin">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.home.checkin') }}
										</strong>
									</div>
									<div class="col-md-8">
										<span>
											{{ $reservation_details->checkin_dmd }}
										</span>
										@if($reservation_details->list_type == 'Rooms')
										<span class="d-block">
											{{ trans('messages.your_reservations.flexible_checkin_time') }}
										</span>
										@endif
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row itinerary_checkout">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.home.checkout') }}
										</strong>
									</div>
									<div class="col-md-8">
										<span>
											{{ $reservation_details->checkout_dmd }}
										</span>
										@if($reservation_details->list_type == 'Rooms')
										<span class="d-block">
											{{ trans('messages.your_reservations.flexible_checkout_time') }}
										</span>
										@endif
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row itinerary_checkout">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.your_trips.guest_count') }}
										</strong>
									</div>
									<div class="col-md-8">
										<span>
											{{ $reservation_details->number_of_guests }}
										</span>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.account.address') }}
										</strong>
									</div>
									<div class="col-md-8">
										<span>
											@if($reservation_details->rooms->rooms_address->address_line_1 != '')
											{{ $reservation_details->rooms->rooms_address->address_line_1 }}<br>
											@endif
											@if($reservation_details->rooms->rooms_address->city != '')
											{{ $reservation_details->rooms->rooms_address->city }},
											@endif
											@if($reservation_details->rooms->rooms_address->state != '')
											{{ $reservation_details->rooms->rooms_address->state }} 
											@endif
											@if($reservation_details->rooms->rooms_address->postal_code != '')
											{{ $reservation_details->rooms->rooms_address->postal_code }}<br>
											@endif
											@if($reservation_details->rooms->rooms_address->country_name != '')
											{{ $reservation_details->rooms->rooms_address->country_name }}<br>
											@endif
										</span>
										<a class="d-print-none theme-link" target="_blank" href="http://google.com/maps/place/{{ str_replace(' ','+',$reservation_details->rooms->rooms_address->address_line_1.' '.$reservation_details->rooms->rooms_address->city.', '.$reservation_details->rooms->rooms_address->state.' '.$reservation_details->rooms->rooms_address->postal_code.' '.$reservation_details->rooms->rooms_address->country_name) }}">
											{{ trans('messages.your_trips.get_directions') }}
										</a>
										<a class="theme-link d-block" href="{{ $reservation_details->rooms->link }}">
											@if($reservation_details->list_type == 'Experiences')
											{{ trans('experiences.details.view_experience') }}
											@else
											{{ trans('messages.your_trips.view_listing') }}
											@endif
										</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.your_trips.host') }}
										</strong>
									</div>
									<div class="col-md-2 profile-image my-3 my-md-0 px-5 pl-md-3 pr-md-0">
										<a class="d-block" href="{{ url('/') }}/users/show/{{ $reservation_details->host_id }}">
											<img class="img-fluid w-100" src="{{ $reservation_details->rooms->users->profile_picture->header_src }}" alt="{{ $reservation_details->rooms->users->full_name }}">
										</a>
									</div>
									<div class="col-md-6">
										<span>
											{{ $reservation_details->rooms->users->full_name }}
										</span>
										<div class="d-print-none">
											<a class="theme-link" href="{{ url('/') }}/z/q/{{ $reservation_details->id }}">
												{{ trans('messages.your_trips.msg_host') }}
											</a>
											@If($reservation_details->status == 'Accepted')
											<a class="theme-link d-block" href="mailto:{{$reservation_details->host_users->email}}">
												{{ trans('messages.your_reservations.contact_by_email') }}
											</a>
											{{$reservation_details->host_users->primary_phone_number}}
											@endif
										</div>
										<div class="d-print-block">
											{{ $reservation_details->rooms->users->email }}
										</div>
									</div>
								</div>
							</div>
							<div class="card-body billing-table">
								<div class="row">
									<div class="col-md-4">
										<strong>
											{{ trans('messages.your_trips.billing') }}
										</strong>
									</div>
									<div class="col-md-8">
										<div class="row d-flex flex-wrap">
											<div class="col-6">
												{{ $reservation_details->duration_text }} 
											</div>
											<div class="col-6">
												{{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->total }}
											</div>
										</div>
										<div class="d-print-none w-100">
											<a class="theme-link" href="{{ url('/') }}/reservation/receipt?code={{ $reservation_details->code }}">
												{{ trans('messages.your_trips.detailed_receipt') }}
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
	</div>
</main>
<script>
	function print_itinerary()
	{
		window.print();
	}
</script>
@stop
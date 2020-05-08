<div class="review-guest review-guest1 mt-md-4">
	<h1>
		{{trans('experiences.payment.who_is_coming?')}}
	</h1>
	<div class="review-guest-user d-flex justify-content-between my-4 py-4">
		<div class="user-info">
			<h4>
				{{$user->first_name}}
			</h4>
		</div>
		<div class="user-img">
			<img class="w-100" src="{{$user->profile_picture->header_src510}}">
		</div>
	</div>
	<div class="add-review-guest">
		<div ng-repeat="guest_data in payment_data.guest_details">
			<div class="guest-added-wrap my-4 d-flex align-items-center">
				<span class="guest-added">
					{{trans_choice('experiences.payment.guest_s', 1)}}
					<span>
						@{{$index+2}}
					</span>
				</span>
				<span class="remover ml-auto">
					<a href="javascript:void(0);" ng-click="remove_guest($index)">
						{{trans('experiences.manage.remove')}}
					</a>
				</span>
			</div>
			<div class="formfill">
				<p class="d-none">
					{{trans('experiences.payment.keep_your_guests_in_the_loop_add_email_to_send_itinerary')}}
				</p>
				<ul class="formlist d-md-flex flex-wrap justify-content-between">
					<li>
						<label>
							{{trans('messages.login.first_name')}}
						</label>
						<input type="text" name="guest_details[@{{$index}}][first_name]" ng-model="payment_data.guest_details[$index].first_name" class="required">
					</li>
					<li>
						<label>
							{{trans('messages.login.last_name')}}
						</label>
						<input type="text" name="guest_details[@{{$index}}][last_name]" ng-model="payment_data.guest_details[$index].last_name" class="required">
					</li>
					<li>
						<label>
							{{trans('messages.login.email')}} ({{trans('experiences.payment.optional')}})
						</label>
						<input type="text" name="guest_details[@{{$index}}][email]" ng-model="payment_data.guest_details[$index].email" class="email1">
					</li>
				</ul>
			</div>
		</div>
		<a href="javascript:void(0)" class="add-more-wrap d-flex justify-content-between align-items-center" ng-click="add_guest();" ng-show="(payment_data.guest_details.length+1) < payment_data.spots_left">
			<h4>
				{{trans('experiences.payment.add_another_guest')}}
			</h4>
			<span class="add-more-btn">
				+
			</span>
		</a>
		<p class="mt-3" ng-hide="(payment_data.guest_details.length+1) < payment_data.spots_left">
			{{trans('experiences.payment.there_are_only_spots_left_book_soon', ['count' => $payment_data['spots_left']])}}
		</p>
	</div>
</div>
<div class="whocan review-guest">
	<div class="mt-4">
		<button class="host-payment-btn" type="button" ng-click="next_step()">
			{{trans('experiences.manage.next')}}
		</button>
	</div>
</div>

@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="payment">
	<div class="container">
		{{ Form::open(array('url' => $form_url, 'method' => 'post','id'=>'checkout-form')) }}
			<input name="room_id" type="hidden" value="{{ $room_id }}">
			<input name="checkin" type="hidden" value="{{ $checkin }}">
			<input name="special_offer_id" type="hidden" value="{{ $special_offer_id }}">
			<input name="checkout" type="hidden" value="{{ $checkout }}">
			<input name="number_of_guests" type="hidden" value="{{ $number_of_guests }}">
			<input name="nights" type="hidden" value="{{ $nights }}">    
			<input type="hidden" name="payment_intent_id" id="payment_intent_id">    
			<input name="cancellation" type="hidden" value="{{ $cancellation }}">
			<input name="currency" type="hidden" value="{{ $result->rooms_price->code }}">
			<input name="session_key" type="hidden" value="{{ $s_key }}">
			<input name="guest_token" type="hidden" value="{{ Session::get('get_token') }}">
			<div class="payment-wrap py-4 py-md-5 d-flex flex-wrap flex-column-reverse flex-md-row">
				<div class="col-md-7 mt-4 mt-md-0 position-sticky">
					<div class="alert alert-with-icon alert-error alert-block d-none" id="form-errors">
						<i class="icon alert-icon icon-alert-alt"></i>
						<div class="error-header">
							{{ trans('messages.payments.almost_done') }}!
						</div>
					</div>
					<div class="alert alert-with-icon alert-error alert-block d-none" id="server-error">
						<i class="icon alert-icon icon-alert-alt"></i>
						{{ trans('messages.payments.connection_timed_out',['site_name'=>$site_name]) }}
					</div>
					<div class="alert alert-with-icon alert-error alert-block d-none" id="verification-error">
						<i class="icon alert-icon icon-alert-alt"></i>
						{{ trans('messages.payments.card_not_verified') }}
					</div>
					@if(($reservation_id != '' || $booking_type == 'instant_book') && $price_list->total != '0')
					<div id="payment" class="payment-section">
						<div class="payment-type-wrap">
							<div class="row">
								<h2 class="col-12">
									{{ trans('messages.payments.payment') }}
								</h2>
								<div class="col-12 col-lg-6">
									<label for="country-select">
										{{ trans('messages.account.country') }}
									</label>
									<div class="select">
										@if(Session::get('payment_country')) 
											{!! Form::select('payment_country', $country, Session::get('mobile_payment_counry_code'), ['id' => 'country-select']) !!}
										@else
											{!! Form::select('payment_country', $country, $default_country_code, ['id' => 'country-select']) !!}
										@endif
									</div>
								</div>
								<div class="w-100 d-block d-md-flex flex-wrap">
									<div class="col-md-12 col-lg-6 mb-2 mb-md-0">
										<label for="payment-method-select">
											{{ trans('messages.payments.payment_type') }}
										</label> 
										<div class="select">
											<select name="payment_type" id="payment-method-select">
												<!--change for Api payment_type-->
												<option value="cc" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
													{{ trans('messages.payments.credit_card') }}
												</option>
												<option value="paypal" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="" {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'selected':''}} >
													PayPal
												</option>
											</select>
										</div>
									</div>
									<div class="col-md-12 col-lg-6 d-flex mt-3 mb-2 my-lg-0">
										<ul class="payment-method cc mt-auto" style={{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'display:none;':'display:block'}}  >
											<li class="payment-logo unionpay d-none"></li>
											<li class="payment-logo visa">{{ trans('messages.payments.credit_card') }}</li>
											<li class="payment-logo master"></li>
											<li class="payment-logo american_express"></li>
											<li class="payment-logo discover"></li>
											<li class="payment-logo jcb d-none"></li>
											<li class="payment-logo postepay d-none"></li>
											<i class="icon icon-lock"></i>
											<div class="cc-data d-none">
												<div class="cc-info">
													{{ trans('messages.payments.name') }}: 
													<span id="selected-cc-name"></span>
												</div>
												<div class="cc-info">
													{{ trans('messages.payments.expires') }}: 
													<span id="selected-cc-expires"></span>
												</div>
											</div>
										</ul>
										<div class="payment-method mt-auto grouped-field digital_river_cc d-none">
											<div class="payment-logo visa"></div>
											<div class="payment-logo master"></div>
											<div class="payment-logo american_express"></div>
											<div class="payment-logo hipercard"></div>
											<div class="payment-logo elo"></div>
											<div class="payment-logo aura"></div>
											<i class="icon icon-lock icon-light-gray"></i>
										</div>
										<div class="payment-method mt-auto grouped-field paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? '':'d-none'}} ">
											<div class="payment-logo paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? '':'d-none'}} ">
												PayPal
											</div>
										</div>
									</div>
									<div class="control-group cc-zip col-md-6 cc-zip-retry d-none">
										<label for="credit-card-zip">
											{{ trans('messages.payments.postal_code') }}
										</label>
										<input type="text" class="cc-zip-text cc-short cc-short-half" name="zip_retry" id="credit-card-zip-retry">
										<div class="label label-warning d-none"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-type-wrap row">
							<div class="payment-method col-12 cc active" id="payment-method-cc">
								<input type="hidden" name="payment_method_nonce" id="payment_method_nonce">
								<div class="new-card cc-details">
									<div class="row">
										<div class="control-group cc-type col-md-6">
											<label class="control-label" for="credit-card-type">
												{{ trans('messages.payments.card_type') }}
											</label>
											<div class="select">
												<select id="credit-card-type" class="cc-med" name="cc_type">
													<option value="visa" selected="selected">
														Visa
													</option>
													<option value="master">
														MasterCard
													</option>
													<option value="american_express">
														American Express
													</option>
													<option value="discover">
														Discover
													</option>
												</select>
											</div>
										</div>
										<div class="control-group cc-number col-md-6">
											<label for="credit-card-number">
												{{ trans('messages.payments.card_number') }}
											</label>
											{!! Form::text('cc_number', '', ['class' => 'cc-med', 'id' => 'credit-card-number', 'autocomplete' => 'off']) !!}
											@if ($errors->has('cc_number')) 
											<div class="label label-warning">
												{{ $errors->first('cc_number') }}
											</div> 
											@endif
										</div>
										<div class="control-group cc-expiration col-md-6">
											<label aria-hidden="true">
												{{ trans('messages.payments.expires_on') }}
											</label>
											<div class="row">
												<div class="col-md-6 pr-md-2">
													<div class="select">
														<label for="credit-card-expire-month" class="screen-reader-only">
															{{ trans('messages.login.month') }}
														</label>
														{!! Form::selectRangeWithDefault('cc_expire_month', 1, 12, null, 'mm', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-month']) !!}
													</div>
												</div>
												<div class="col-md-6 pl-md-2 mt-3 mt-md-0">
													<div class="select">
														<label for="credit-card-expire-year" class="screen-reader-only">
															{{ trans('messages.login.year') }}
														</label>
														{!! Form::selectRangeWithDefault('cc_expire_year', date('Y'), date('Y')+30, null, 'yyyy', [ 'class' => 'cc-short', 'id' => 'credit-card-expire-year']) !!}
													</div>
												</div>
											</div>
											@if ($errors->has('cc_expire_month') || $errors->has('cc_expire_year'))
											<div class="label label-warning">
												@if ($errors->has('cc_expire_month'))
												{{ $errors->first('cc_expire_month') }}
												@endif
												@if ($errors->has('cc_expire_month') == '')
												{{ $errors->first('cc_expire_year') }}
												@endif
											</div> 
											@endif
										</div>
										<div class="control-group cc-security-code col-md-6">
											<label class="control-label" for="credit-card-security-code">
												{{ trans('messages.payments.security_code') }}
											</label>
											{!! Form::text('cc_security_code', '', ['class' => 'cc-short', 'id' => 'credit-card-security-code', 'autocomplete' => 'off']) !!}

											@if ($errors->has('cc_security_code')) 
											<div class="label label-warning">
												{{ $errors->first('cc_security_code') }}
											</div> @endif
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="bill-info-wrap">
							<div class="row">
								<h2 class="col-12">
									{{ trans('messages.payments.billing_info') }}
								</h2>
								<div class="control-group cc-first-name col-md-6">
									<label class="control-label" for="credit-card-first-name">
										{{ trans('messages.login.first_name') }}
									</label>

									{!! Form::text('first_name', '', ['id' => 'credit-card-first-name']) !!}

									@if ($errors->has('first_name')) 
									<div class="label label-warning">
										{{ $errors->first('first_name') }}
									</div> 
									@endif
								</div>

								<div class="control-group cc-last-name col-md-6">
									<label class="control-label" for="credit-card-last-name">
										{{ trans('messages.login.last_name') }}
									</label>

									{!! Form::text('last_name', '', ['id' => 'credit-card-last-name']) !!}

									@if ($errors->has('last_name')) 
									<div class="label label-warning">
										{{ $errors->first('last_name') }}
									</div> 
									@endif
								</div>
								<div class="control-group cc-address1 col-md-6 d-none">
									<label class="control-label" for="credit-card-address1">
										{{ trans('messages.payments.street_address') }}
									</label>

									<input type="text" name="address1" id="credit-card-address1" disabled="">
									@if ($errors->has('address1')) 
									<div class="label label-warning">
										{{ $errors->first('address1') }}
									</div> 
									@endif
								</div>

								<div class="control-group col-md-6 d-none">
									<label for="credit-card-address2">
										{{ trans('messages.payments.apt') }} #
									</label>

									<input type="text" class="cc-short" name="address2" id="credit-card-address2" disabled="">
								</div>

								<div class="control-group cc-city col-md-6 d-none">
									<label for="credit-card-city">
										{{ trans('messages.account.city') }}
									</label>

									<input type="text" name="city" id="credit-card-city" disabled="">
									@if ($errors->has('city')) 
									<div class="label label-warning">
										{{ $errors->first('city') }}
									</div> 
									@endif
								</div>

								<div class="cc-state col-md-6 d-none">
									<label for="credit-card-state">
										{{ trans('messages.account.state') }}
									</label>

									<input type="text" class="cc-short" name="state" id="credit-card-state" disabled="">
								</div>

								<div class="control-group cc-zip cc-zip-new col-md-6">
									<label for="credit-card-zip">
										{{ trans('messages.payments.postal_code') }}
									</label>

									{!! Form::text('zip', '', ['id' => 'credit-card-zip', 'class' => 'cc-short cc-zip-text']) !!}

									@if ($errors->has('zip')) 
									<div class="label label-warning">
										{{ $errors->first('zip') }}
									</div> 
									@endif
								</div>

								<div class="col-md-6 d-flex">
									<div class="help-inline credit-card-country-name mt-auto">
										<strong id="billing-country"></strong>
									</div>
								</div>
							</div>
						</div>

						<div class="payment-method paypal {{ (@Session::get('payment')[$s_key]['payment_card_type']=='PayPal') ? 'active':'d-none'}}" id="payment-method-paypal">
							<div class="paypal-instructions mt-2">
								<p>
									{{ trans('messages.payments.redirected_to_paypal') }}
								</p>
							</div>
						</div>

						<input name="payment_method" type="hidden" value="{{ (@Session::get('payment')[$s_key]['payment_card_type']!='PayPal') ? 'cc':''}}">
						<input name="country" type="hidden" value="">
						<input name="digital_river[country]" type="hidden" value="">
					</div>
					@endif

					@if($price_list->total == '0' && $price_list->coupon_code != '')
						<div class="payment-section">
							<div class="row">
								<div class="col-lg-6">
									<label for="country-select">
										{{ trans('messages.account.country') }}
									</label>

									<div class="select select-block">
										@if(Session::get('payment_country')) 
										{!! Form::select('payment_country', $country, Session::get('mobile_payment_counry_code'), ['id' => 'country-select']) !!}
										@else
										{!! Form::select('payment_country', $country, $default_country_code, ['id' => 'country-select']) !!}
										@endif
									</div>
								</div>
							</div>
		        		</div>
			        @endif
					<div class="checkout-info">
						<h2>
							{{ trans('messages.payments.tell_about_your_trip',['first_name'=>$result->users->first_name]) }}
						</h2>
						<p>
							{{ trans('messages.payments.helful_trips') }}:
						</p>
						<ul class="my-3 disc-type">
							<li>
								{{ trans('messages.rooms.what_brings_you',['city'=>$result->rooms_address->city]) }}
							</li>
							<li>
								{{ trans('messages.payments.checkin_plans') }}
							</li>
							<li>
								{{ trans('messages.payments.ask_recommendations') }}
							</li>
						</ul>

						<div class="chat-container mt-4">
							<div class="chat-row d-flex">
								<div class="user-img">
									@if(Session::get('get_token')=='')
									<a href="{{ url('users/show/'.$result->user_id) }}">
										<img alt="User Profile Image" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
									</a>
									@else
									<a href="javascript:void(0);">
										<img alt="User Profile Image" data-pin-nopin="true" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
									</a>
									@endif
								</div>
								<div class="chat-box ml-4 flex-grow-1 panel-dark arrow-left">
									<p>
										@if($result->booking_message)
											{{ $result->booking_message }}
										@else
											{{ trans('messages.payments.welcome_to_city',['city'=>$result->rooms_address->city]) }}
										@endif
									</p>
								</div>
							</div>

							<div class="chat-row d-flex">
								<div class="user-img">
									@if(Session::get('get_token')!='')
									<a href="javascript:void(0);">
										<img alt="User Profile Image" class="" data-pin-nopin="true" src="{{ @Session::get('payment')[$s_key]['mobile_user_image'] }}" title="">
									</a>
									@else
									<a href="{{ url('users/show/'.Auth::user()->id) }}">
										<img alt="User Profile Image" class="" data-pin-nopin="true" src="{{ Auth::user()->profile_picture->src }}" title="{{ Auth::user()->first_name }}">
									</a>
									@endif
								</div>
								<div class="chat-box ml-4 flex-grow-1 arrow-left">
									<label for="message-to-host-input" class="screen-reader-only">
										{{ trans('messages.payments.message_your_host') }}...
									</label>
									<!--payment_message_to_host set for Api start -->
									<textarea id="message-to-host-input" name="message_to_host" rows="3" class="message-to-host-quote-input" placeholder="{{ trans('messages.payments.message_your_host') }}...">@if(@Session::get('payment')[$s_key]['payment_message_to_host']){{ @Session::get('payment')[$s_key]['payment_message_to_host'] }} @endif</textarea>
									<!--payment_message_to_host set for Api stop -->
								</div>
							</div>
						</div>

						<div id="house-rules-agreement" class="checkout-rule-info">
							<h2>
								{{ trans('messages.lys.house_rules') }}
							</h2>
							<p>
								{{ trans('messages.payments.by_booking_this_space',['first_name'=>$result->users->first_name]) }}.
							</p>
							<div class="row-space-2">
							    <div class="expandable expandable-trigger-more house-rules-panel-body expanded">
							      	<div class="expandable-content" data-threshold="50">
							        	<p>{{ $result->rooms_description->house_rules }}</p>
							        	<div class="expandable-indicator"></div>
							      	</div>
							    </div>
							</div>
						</div>

						<div id="policies" class="policies">
							<label for="agrees-to-terms">
								{{ trans('messages.payments.by_clicking',['booking_type'=>($booking_type == 'instant_book') ? ($price_list->total == '0') ? trans('messages.lys.continue') : trans('messages.payments.book_now') : trans('messages.lys.continue')]) }}
								@foreach($company_pages as $company_page)
					                @if($company_page->name=='Terms of Service')
					                	<a href="{{ url('terms_of_service') }}" class="terms_link theme-link" target="_blank">{{ trans('messages.login.terms_service') }}</a>,
					                @endif
					                @if($company_page->name=='Guest Refund')
					                	<a href="{{ url('guest_refund') }}" class="refund_policy_link theme-link" target="_blank">{{ trans('messages.login.guest_policy') }}</a>,
					                @endif
					             @endforeach
								<a href="#house-rules-agreement" class="house-rules-link theme-link">
									{{ trans('messages.lys.house_rules') }}
								</a>, 
								<a href="{{ url('home/cancellation_policies#flexible') }}" class="cancel-policy-link theme-link" target="_blank">
									{{ trans('messages.payments.cancellation_policy') }}
								</a>
							</label>
						</div>
						<a href="javascript:void(0)" id="payment-form-submit" class="btn btn-large btn-primary" ng-click="disableButton()">
				        	{{ ($booking_type == 'instant_book') ? ($price_list->total == '0') ? trans('messages.lys.continue') : trans('messages.payments.book_now') : trans('messages.lys.continue') }}
				       </a>
						<p class="book-now-explanation default"></p>
						<p class="book-now-explanation immediate_charge d-none">
							{{ trans('messages.payments.clicking') }} 
							<strong>
								{{ trans('messages.lys.continue') }}
							</strong> 
							{{ trans('messages.payments.charge_your_payment') }}
						</p>
						<p class="book-now-explanation deferred_payment d-none">
							{{ trans('messages.payments.host_will_reply') }}
						</p>
					</div>
				</div>

				<div class="col-md-5 position-sticky">
					<div class="payment_list_right">
						<div class="payments-listing-image">
							{!! Html::image($result->photo_name, $result->name, ['class' => 'img-fluid']) !!}
						</div>
						<div id="your-trip" class="hosting-info">
							<div class="payments-listing-name">
								<h3>
									{{ $result->name }}
								</h3>
								<p>
									@if($result->rooms_address->city !='') {{ $result->rooms_address->city }} , @endif 
									@if($result->rooms_address->state !=''){{ $result->rooms_address->state }} @endif 
									@if($result->rooms_address->country_name !='') , {{  $result->rooms_address->country_name }} @endif 
								</p>
							</div>
							<div class="room-info mt-3 pt-3">
								<p>
									<strong>        
										{{ $result->room_type_name }}                   
									</strong> 
									{{ trans('messages.payments.for') }} 
									<strong>
										{{ $number_of_guests }} {{ trans_choice('messages.home.guest',$number_of_guests) }}
									</strong>
								</p>
								<p> 
									<strong>
										{{ date($php_format_date, strtotime($checkin)) }}
									</strong> 
									{{ trans('messages.payments.to') }} 
									<strong>
										{{ date($php_format_date, strtotime($checkout)) }}
									</strong>
								</p>
							</div>

							<div class="reso-info-table mt-3 pt-3">
								<ul class="row">
									<li>
										<div class="col-6">
											{{ trans('messages.payments.cancellation_policy') }}
										</div>
										<div class="col-6">
											@if($reservation_id!='')
											<a href="{{ url('home/cancellation_policies#').strtolower($cancellation) }}" class="theme-link" target="_blank">
												{{trans('messages.cancellation_policy.'.strtolower($cancellation))}}
											</a>
											@else
											<a href="{{ url('home/cancellation_policies#').strtolower($result->cancel_policy) }}" class="theme-link" target="_blank">
												{{trans('messages.cancellation_policy.'.strtolower($result->cancel_policy))}} 
											</a>
											@endif
										</div>
									</li>
									<li>
										<div class="col-6">
											{{ trans('messages.lys.house_rules') }}
										</div>
										<div class="col-6">
											<a href="#house-rules-agreement" class="theme-link">
												{{ trans('messages.payments.read_policy') }}
											</a>
										</div>
									</li>
									<li>
										<div class="col-6">
											{{ ucfirst(trans_choice('messages.rooms.night',2)) }}
										</div>
										<div class="col-6">
											{{ $nights }}
										</div>
									</li>
								</ul>
							</div>
							<div id="billing-summary" class="billing-summary mt-3 pt-3">
								<div class="tooltip tooltip-top-middle taxes-breakdown" role="tooltip" data-sticky="true" data-trigger="#tax-tooltip" aria-hidden="true">
									<div class="panel-body">
										<ul>
											<li>
												<td colspan="2"></td>
											</li>
										</ul>
									</div>  
								</div>
								<div class="tooltip tooltip-top-middle makent-credit-breakdown" role="tooltip" data-sticky="true" data-trigger="#makent-credit-tooltip" aria-hidden="true">
									<div class="panel-body">
										<div class="makent-credit-breakdown">
										</div>
									</div>
								</div>
								<div id="billing-table" class="reso-info-table billing-table">
									<ul class="row">
										<li class="base-price">
											<div class="col-7">
												<span>  
													@if(Session::get('get_token')!='')
														{{ html_string(Session::get('currency_symbol')) }}
													@else
														{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												{{ $price_list->base_rooms_price }}  x {{ $nights }} {{ trans_choice('messages.rooms.night',$nights) }}
												<i id="service-fee-tooltip" rel="tooltip" title="{{ trans('messages.rooms.avg_night_rate') }}">
												</i>
											</div>
											<div class="col-5 text-right">
												<span> 
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}

													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->total_night_price }}
												</span>
											</div>
										</li>
										@if($price_list->booked_period_type != '')
										<li class="booked_period">
											<div class="col-7">
												{{$price_list->booked_period_discount}}% 
												@if($price_list->booked_period_type == 'early_bird')
												{{ trans('messages.rooms.early_bird_price_discount') }} 

												@elseif($price_list->booked_period_type == 'last_min')
												{{ trans('messages.rooms.last_min_price_discount') }}
												@endif
											</div>
											<div class="col-5 text-right">
												-
												<span> 
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}
													@else

													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->booked_period_discount_price }}
												</span>
											</div>
										</li>
										@endif

										@if($price_list->length_of_stay_type != '')
										<li class="length_of_stay">
											<div class="col-7">
												{{$price_list->length_of_stay_discount}}% 
												@if($price_list->length_of_stay_type == 'weekly')
												{{ trans('messages.rooms.weekly_price_discount') }} 

												@elseif($price_list->length_of_stay_type == 'monthly')
												{{ trans('messages.rooms.monthly_price_discount') }}

												@elseif($price_list->length_of_stay_type == 'custom') 
												{{ trans('messages.rooms.long_term_price_discount') }}
												@endif
											</div>
											<div class="col-5 text-right">
												-
												<span> 
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}

													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->length_of_stay_discount_price }}
												</span>
											</div>
										</li>
										@endif

										@if($price_list->service_fee)
										<li class="service-fee">
											<div class="col-7">
												{{ trans('messages.rooms.service_fee') }}
												<i id="service-fee-tooltip" class="icon icon-question" rel="tooltip" title="{{ trans('messages.rooms.24_7_help') }}">
												</i>
											</div>
											<div class="col-5 text-right">
												<span> 
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}
													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->service_fee }}
												</span>
											</div>
										</li>
										@endif

										@if($price_list->additional_guest)
										@if(@$special_offer_id == '' || @$special_offer_type == 'pre-approval' )
										<li class="additional_price"> 
											<div class="col-7">
												{{ trans('messages.rooms.addtional_guest_fee') }}
											</div>
											<div class="col-5 text-right">
												<span>
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}
													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->additional_guest }}
												</span>
											</div>
										</li>
										@endif
										@endif

										@if($price_list->cleaning_fee)
										@if(@$special_offer_id =='' || @$special_offer_type == 'pre-approval')
										<li class="cleaning_price"> 
											<div class="col-7">
												{{ trans('messages.lys.cleaning') }}
											</div>
											<div class="col-5 text-right">
												<span>
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}
													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span>
												<span>
													{{ $price_list->cleaning_fee }}
												</span>
											</div>
										</li>
										@endif
										@endif

										<li class="editable-fields col-12 flex-wrap" id="after_apply">
											<div class="coupon-input mt-2 d-none w-100">
												<input class="flex-grow-1 coupon-code-field" autocomplete="off" name="coupon_code" type="text" value="">
												<a href="javascript:void(0);" id="apply-coupon" class="btn btn-sm btn-primary apply-coupon ml-3">
													{{ trans('messages.payments.apply') }}
												</a>
											</div>

											<div id="coupon_disabled_message" class="icon-rausch w-100 text-danger">
											</div>
											<div class="cancel-coupon">
												<a href="javascript:void(0);" class="theme-link">
													{{ trans('messages.your_reservations.cancel') }}
												</a>
											</div>
										</li>

										@if($reservation_id!='' || $booking_type == 'instant_book')
										<li class="coupon">
											<div class="col-7">
												<span class="without-applied-coupon">
													<span class="coupon-section-link" id="after_apply_coupon" style="{{ (Session::has('coupon_amount')) ? 'display:block;' : 'display:none;' }}"> 
														@if($travel_credit !=0 && Session::get('coupon_code') == 'Travel_Credit') 
														{{ trans('messages.referrals.travel_credit') }}
														@else
														{{ trans('messages.payments.coupon') }} 
														@endif
													</span>
												</span>
												<span class="without-applied-coupon" id="restrict_apply">
													<a href="javascript:;" class="open-coupon-section-link theme-link" style="{{ (Session::has('coupon_amount')) ? 'display:none;' : 'display:block;' }}">
														{{ trans('messages.payments.coupon_code') }}
													</a>
												</span>
											</div>
											<div class="col-5 text-right">
												<div class="without-applied-coupon label label-success" id="after_apply_amount" style="{{ (Session::has('coupon_amount')) ? 'display:block;' : 'display:none;' }}">
													-{{ html_string($result->rooms_price->currency->symbol) }}
													<span id="applied_coupen_amount">
														{{ $price_list->coupon_amount }}
													</span>
												</div>
											</div>
										</li>

										<li id="after_apply_remove" style="{{ (Session::has('coupon_amount')) ? '' : 'display:none;' }}">
											<div class="col-12">
												<a data-prevent-default="true" href="javascript:void(0);" id="remove_coupon" class="theme-link">
													<span>
														@if($travel_credit !=0  && Session::get('coupon_code') == 'Travel_Credit')
														{{ trans('messages.referrals.remove_travel_credit') }}
														@else
														{{ trans('messages.payments.remove_coupon') }}
														@endif
													</span>
												</a>
											</div>
										</li>
										@endif
									</ul>
								</div>

								<div id="payment-total-table" class="reso-info-table billing-table mt-3 pt-3 border-top">
									<ul class="row">
										<li class="total">
											<div class="col-7">
												<span>
													{{ trans('messages.rooms.total') }}
												</span>
											</div>
											<div class="col-5 text-right">
												<span>
													@if(Session::get('get_token')!='')
													{{ html_string(Session::get('currency_symbol')) }}
													@else
													{{ html_string($result->rooms_price->currency->symbol) }}
													@endif
												</span> 
												<span id="payment_total">
													{{ $price_list->total }}
												</span>
											</div>
										</li>
										@if($price_list->security_fee)
											@if(@$special_offer_id =='' || @$special_offer_type == 'pre-approval')
												<li class="security_price"> 
													<div class="col-7">
														{{ trans('messages.payments.security_deposit') }}
														<i id="service-fee-tooltip"  rel="tooltip" class="icon icon-question" title="{{ trans('messages.disputes.security_deposit_will_not_charge') }}">
														</i>
													</div>
													<div class="col-5 text-right">
														<span>
															@if(Session::get('get_token')!='')
																{{ html_string(Session::get('currency_symbol')) }}
															@else
																{{ html_string($result->rooms_price->currency->symbol) }}
															@endif
														</span>
														<span> {{ $price_list->security_fee }} </span>
													</div>
												</li>
											@endif
										@endif
									</ul>
								</div>

								@if($price_list->total == '0')
								<div class="panel-travel_credit-full">
									<span class="label label-success">
										@if(Session::get('coupon_code') == 'Travel_Credit')
								        	{{ trans('messages.payments.continue_with_travel_credit') }}
								      	@else
									        {{ trans('messages.payments.continue_with_coupon_code') }}
								      	@endif
									</span>
								</div>
								@endif
							</div>

							<div class="panel-total-charge mt-3 pt-3">
								<span id="currency-total-charge" class="">
									{{ trans('messages.payments.you_are_paying_in') }}
									<strong>
										<span id="payment-currency">
											{{html_string(PAYPAL_CURRENCY_SYMBOL)}}{{PAYPAL_CURRENCY_CODE}}
										</span>
									</strong>.
									{{ trans('messages.payments.total_charge_is') }}
									<strong>
										<span id="payment-total-charge">
											{{html_string(PAYPAL_CURRENCY_SYMBOL)}}{{ $paypal_price }}
										</span>
									</strong>.
								</span>
								<span id="fx-messaging">
									{!! trans('messages.payments.exchange_rate_booking',['symbol'=>html_string(PAYPAL_CURRENCY_SYMBOL)]) !!} {{ html_string($result->rooms_price->currency->original_symbol) }}{{ $paypal_price_rate }} {{ $result->rooms_price->currency_code }} ({{ trans('messages.payments.host_listing_currency') }}).
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		{!! Form::close() !!}
	</div>
</main>
@stop
@push('scripts')
	@if(Request::offsetGet('s_key') == '')
		<script type="text/javascript">
			url = window.location.href;
			url += (url.match(/\?/) ? '&' : '?') + "s_key={{$s_key}}";
			history.replaceState(null, null, url);
		</script>
	@endif
	<script type="text/javascript">
		//credit card number inputs allow only digits validation
		$("#credit-card-number").keypress(function (e) {
		//if the letter is not digit then display error and don't type anything
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			//display error message
			$("#errmsg").html("Digits Only").show().fadeOut("slow");
				return false;
		}
	});
	</script>
	<script src="https://js.stripe.com/v3/"></script>

	<script type="text/javascript">
		var payment_intent_client_secret  = "{!! session('payment.'.$s_key.'.payment_intent_client_secret') ? session('payment.'.$s_key.'.payment_intent_client_secret') : ''  !!}";
	</script>
@endpush
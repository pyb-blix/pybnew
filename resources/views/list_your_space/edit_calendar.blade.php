<!-- Center Part Starting  -->
<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
	<div class="manage-listing-content col-12" id="js-manage-listing-content">
		<div class="content-heading my-4">
			<h3>
				@lang('messages.lys.calendar_title') 
			</h3>
			<p>
				@lang('messages.lys.calendar_desc',['site_name'=>$site_name]) 
			</p>
		</div>
		<div class="row">
			<div class="calendar-setting col-12 col-lg-8 my-3 text-right">
				<ul class="float-left d-flex mr-3">
					<li>
						<span>
							@lang('messages.lys.mobile_select_desc') 
						</span>
					</li>
				</ul>
				<ul class="float-left d-flex">
					<li class="ml-2">
						<div class="d-inline-flex" style="background-color: #46A4A7;height: 10px; width: 10px;"></div> 
						<span> 
							@lang('messages.lys.today') 
						</span> 
					</li>
					<li class="ml-2"> 
						<div class="d-inline-flex" style="background-color: #fff3df;height: 10px; width: 10px;"></div> 
						<span> 
							@lang('messages.lys.Blocked') 
						</span> 
					</li>
					<li class="ml-2"> 
						<div class="d-inline-flex" style="background-color: #ffdadc;height: 10px; width: 10px;"></div> 
						<span> 
							@lang('messages.inbox.reservations') 
						</span> 
					</li>
				</ul>
				<a class="d-inline-flex align-items-center" id="js-calendar-settings-btn" href="javascript:void(0)" data-prevent-default="true">
					<i class="icon icon-cog mr-1"></i>
					<span>
						@lang('messages.header.settings')
					</span>
				</a>
			</div>
			<div id="calendar" class="calendar col-12 col-lg-8 mt-4 mb-1" ng-init="calendar_data={{ json_encode($calendar) }};minimum_amount='{{ $minimum_amount}}';spots_left_text='{{ __('messages.shared_rooms.spots_left') }}'";></div>
			<div class="calendar-side-option col-12 col-lg-4 pt-4 pt-lg-5" ng-show="showUpdateForm">
				<form name="calendar-edit-form" class="ng-pristine ng-valid">
					<div class="panel-header text-center" ng-init="segment_status = 'Available'">
						<div class="segmented-control d-md-flex">
							<label id="avi" class="segmented-control-option segmented-option-selected" ng-class="(segment_status == 'Available') ? 'segmented-option-selected' : '' ">
								<span>
									@lang('messages.lys.Available')
								</span>
								<input type="radio" id="available_check" ng-checked="segment_status == 'Available'" name="radio" ng-model="segment_status" value="Available" class="segmented-control-input ng-pristine ng-untouched ng-valid" checked="checked">
							</label>
							<label id="unavi" class="segmented-control-option" ng-class="(segment_status == 'Not available') ? 'segmented-option-selected' : ''">
								<span>
									@lang('messages.lys.Blocked')
								</span>
								<input type="radio" id="notavailable_check" ng-checked="segment_status == 'Not available'" name="radio" value="Not available" ng-model="segment_status" class="segmented-control-input ng-pristine ng-untouched ng-valid">
							</label>
						</div>
					</div>
					<div class="panel-body text-center">
						<div class="d-flex">
							<div class="col-6">
								<label> 
									@lang('messages.lys.start_date') 
								</label>
								<input type="text" id="calendar-edit-start" ng-model="calendar_edit_start_date" readonly="readonly">
								<input type="hidden" id="calendar-start">
							</div>
							<div class="col-6">
								<label> 
									@lang('messages.lys.end_date') 
								</label>
								<input type="text" id="calendar-edit-end" ng-model="calendar_edit_end_date" readonly="readonly">
								<input type="hidden" id="calendar-end">
							</div>
						</div>
						<div class="price-changer my-3">
							<label> 
								@lang('messages.lys.price_each_night') 
							</label>
							<div class="input-wrap d-flex align-items-center mx-auto">
								<span>
									{{ html_string($rooms_price->currency->original_symbol) }}
								</span>
								<input type="number" limit-to="9" class="sidebar-price get_price" id="calendar_edit_price" ng-model="calendar_edit_price">
							</div>
							<p ng-show="calendar_edit_price < minimum_amount" class="text-danger">
								@lang('validation.min.numeric', ['attribute' => __('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount])
							</p>
						</div>
						<div class="notes-wrap">
							<a data-prevent-default="true" href="{{url('manage-listing/'.$room_id.'/calendar')}}" class="link-icon alg_1" onclick="return false;" ng-click="isAddNote = !isAddNote">
								<span class="link-icon__text">
									@lang('messages.lys.add_note')
								</span>
								<i class="icon icon-caret-down"></i>
							</a>
							<textarea ng-model="notes" ng-show="isAddNote" class="mt-3"></textarea>
						</div>
					</div>
					<div class="panel-footer d-flex align-items-center justify-content-center pt-0">
						<button class="btn btn-default" ng-click="full_calendar();">
							@lang('messages.your_reservations.cancel')
						</button>
						<button type="submit" class="btn btn-host" ng-disabled="calendar_edit_price < minimum_amount" ng-click="calendar_edit_submit()">
							@lang('messages.wishlist.save_changes')
						</button>
					</div>
				</form>
			</div>
		</div>
		<ul class="my-4 calendar-footer-button">
			<li>
				<a href="javascript:void(0)" id="import_button" data-toggle="modal" data-target="#import_popup">
					@lang('messages.lys.import_calc')
				</a>
			</li>
			<li>
				<a href="{{ url('calendar/sync/'.$result->id) }}" class="js-calendar-sync" data-prevent-default="true">
					@lang('messages.lys.sync_other_calc')
				</a>
			</li>
			<li>
				<a href="javascript:void(0)" id="export_button" data-toggle="modal" data-target="#export_popup">
					@lang('messages.lys.export_calc')
				</a>
			</li>
			<li>
				<a href="javascript:void(0)" class="remove_sync_button" id="remove_sync_button" data-toggle="modal" data-target="#remove_sync_popup">
					@lang('messages.lys.remove_calc')
				</a>
			</li>
		</ul>

		<div id="calendar-rules" class="sidebar-overlay" ng-init="rs_errors = []">
			<div class="sidebar-overlay-inner js-section">
				<h3 class="sidebar-overlay-heading">
					{{ trans('messages.lys.reservation_settings') }}
				</h3>
				<button type="button" id="js-close-calendar-settings-btn" class="close" data-dismiss="modal"></button>
				<div class="js-saving-progress reservation_settings-saving saving-progress" style="display: none;">
					<h5>
						{{ trans('messages.lys.saving') }}...
					</h5>
				</div>
				<div class="clearfix"></div>
				<div data-hook="min_max_nights" class="row my-3">
					<div class="col-6">
						<label>
							{{ trans('messages.lys.minimum_stay') }}
						</label>
						<div class="input-addon">
							<input name="minimum_stay" id="min-nights" value="{{$result->rooms_price->minimum_stay}}" type="text" class="input-stem reservation_settings_inputs">
							<span class="input-suffix">
								{{ trans('messages.lys.nights') }}
							</span>
						</div>
						<p class="ml-error">
							@{{rs_errors['minimum_stay'][0]}}
						</p>
					</div>
					<div class="col-6">
						<label>
							{{ trans('messages.lys.maximum_stay') }}
						</label>
						<div class="input-addon">
							<input name="maximum_stay" id="max-nights" value="{{$result->rooms_price->maximum_stay}}" type="text" class="input-stem reservation_settings_inputs">
							<span class="input-suffix">
								{{ trans('messages.lys.nights') }}
							</span>
						</div>
						<p class="ml-error">
							@{{rs_errors['maximum_stay'][0]}}
						</p>
					</div>
					<p id="min-max-error" class="ml-error" style="display:none;"></p>
				</div>
				<div class="seasonal-info-wrap" data-hook="seasonal_min_max_overview" ng-init="availability_rules = {{json_encode($result->availability_rules->count() ? $result->availability_rules : [] )}};">
					<div class="col-12 p-0" id="availability_rules_wrapper">
						<div class="row my-3" ng-repeat="item in availability_rules">
							<div class="col-md-9 small edit_arb">
								<p>
									{{trans('messages.lys.during')}} @{{item.during}},
								</p>
								<p ng-if="item.minimum_stay">
									{{trans('messages.lys.guest_stay_for_minimum')}} @{{item.minimum_stay}} {{trans('messages.lys.nights')}}
								</p>
								<p ng-if="item.maximum_stay">
									{{trans('messages.lys.guest_stay_for_maximum')}} @{{item.maximum_stay}} {{trans('messages.lys.nights')}}
								</p>
							</div>
							<div class="col-md-3 seasonal-option edit_arb1 text-right">
								<a href="javascript:void(0)" class="link-icon" ng-click="remove_availability_rule($index)">
									<span class="fa fa-trash"></span>
								</a>
								<a href="javascript:void(0)" class="link-icon" ng-click="edit_availability_rule($index)">
									<span class="fa fa-edit"></span>
								</a>
							</div>
						</div>
					</div>
					<div class="add-available-rule">
						<a href="javascript:void(0)" id="js-add-availability-rule-link" class="text-decoration" data-prevent-default="true">
							{{ trans('messages.lys.add_requirement_seasons') }}
						</a>
					</div>
				</div>
				<div class="js-calendar-sync-section sidebar-overlay-highlight-section d-none">
					<div></div>
					<h3 id="calendar_sync_heading" data-hook="calendar_sync_heading" class="row-space-4 sidebar-overlay-heading">
						{{ trans('messages.lys.sync_calc') }}
					</h3>
					<div data-hook="calendar_sync">
						<div class="space-2">
							<div class="row row-condensed">
								<div class="col-sm-12">
									<ul class="list-unstyled">
										<li class="space-1">
											<a href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="true" class="text-muted link-icon">
												<i name="download" class="icon icon-download"></i>
												<span>
													{{ trans('messages.lys.import_calc') }}
												</span>
											</a>
										</li>
										<li>
											<a href="{{ url('manage-listing/'.$room_id.'/calendar') }}" data-prevent-default="true" class="text-muted link-icon">
												<i name="share" class="icon icon-share"></i>
												<span>
													{{ trans('messages.lys.export_calc') }}
												</span>
											</a>
										</li>
									</ul>
									<p class="get_n_day" hidden="hidden">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="calendar-rules-overlay" id="calendar-rules-custom" ng-init="availability_rule_item = {type: 'month'}; ar_errors=[];">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header pt-2 pb-0 border-0">
						<button type="button" class="close" id="js-close-availability-rule-btn" data-dismiss="modal"></button>
					</div>					
					<div class="modal-body col-lg-11 ed_calbox" id="availability_rule_item_wrapper">
						<div class="form-group d-md-flex">
							<label class="control-label mb-md-0 col-md-4">
								{{trans('messages.lys.select_dates')}}
							</label>
							<div class="col-md-8" ng-init="availability_rule_item.type = availability_rule_item.id ? 'prev' : ''">
								<div class="select">
									<select name="availability_rule_item[type]" class="form-control" id="availability_rule_item_type" ng-model="availability_rule_item.type" ng-click="availability_rules_type_change();" >
										<option value="" ng-disabled="availability_rule_item.type != ''" ng-if="!availability_rule_item.id">
											{{trans('messages.lys.select_dates')}}
										</option>
										<option value="prev" data-start_date="@{{availability_rule_item.start_date_formatted}}" data-end_date="@{{availability_rule_item.end_date_formatted}}" ng-if="availability_rule_item.id">
											@{{availability_rule_item.during}}
										</option>
										@foreach($availability_rules_months_options as $date => $option)
										<option value="month" data-start_date="{{$option['start_date']}}" data-end_date="{{$option['end_date']}}">
											{{$option['text']}}
										</option>
										@endforeach
										<option value="custom">
											{{trans('messages.lys.custom')}}
										</option>
									</select>
								</div>
								<p class="ml-error">
									@{{ar_errors['type'][0]}}
								</p>
							</div>
						</div>
						<div class="form-group d-md-flex" ng-show="availability_rule_item['type'] == 'custom'">
							<label class="col-md-4 mb-md-0 control-label">
								{{trans('messages.lys.custom_dates')}}
							</label>
							<div class="col-12 col-md-4 intbut" ng-init="availability_rule_item.start_date = availability_rule_item.start_date_formatted">
								<input type="text" readonly name="availability_rule_item[start_date]" class="form-control" id="availability_rules_start_date" placeholder="{{trans('messages.lys.start_date')}}" ng-model="availability_rule_item.start_date">
								<p class="ml-error">
									@{{ar_errors['start_date'][0]}}
								</p>
							</div>
							<div class="col-12 col-md-4 intbut mt-3 mt-md-0" ng-init="availability_rule_item.end_date = availability_rule_item.end_date_formatted">
								<input type="text" readonly name="availability_rule_item[end_date]" class="form-control" id="availability_rules_end_date" placeholder="{{trans('messages.lys.end_date')}}" ng-model="availability_rule_item.end_date">
								<p class="ml-error">
									@{{ar_errors['end_date'][0]}}
								</p>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<label class="control-label mb-md-0 col-md-4">
								{{trans('messages.lys.minimum_stay')}}
							</label>
							<div class="col-md-8">
								<div class="input-addon">
									<input type="text" name="availability_rule_item[minimum_stay]" class="form-control availability_minimum_stay" id="availability_rules_minimum_stay" placeholder="{{trans('messages.lys.minimum_stay')}}" ng-model="availability_rule_item.minimum_stay">
									<span class="input-suffix">
										{{trans('messages.lys.nights')}}
									</span>
								</div>
								<p class="ml-error">
									@{{ar_errors['minimum_stay'][0]}}
								</p>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<label class="control-label mb-md-0 col-md-4">
								{{trans('messages.lys.maximum_stay')}}
							</label>
							<div class="col-md-8">
								<div class="input-addon">
									<input type="text" name="availability_rule_item[maximum_stay]" class="form-control availability_maximum_stay" id="availability_rules_maximum_stay" data-minimum_stay="#availability_rules_minimum_stay" placeholder="{{trans('messages.lys.maximum_stay')}}" ng-model="availability_rule_item.maximum_stay">
									<span class="input-suffix">
										{{trans('messages.lys.nights')}}
									</span>
								</div>
								<p class="ml-error">
									@{{ar_errors['maximum_stay'][0]}}
								</p>
							</div>
						</div>
						<div class="-example-image-container mt-4 d-none"></div>
						<div class="-rule-caption"></div>
						<div class="-jump-to-month mt-3 d-none"></div>
					</div>
					<div class="modal-footer">
						<a class="btn" data-prevent-default="true" href="javascript:void(0)" id="js-cancel-availability-rule-btn">
							{{trans('messages.your_reservations.cancel')}}
						</a>
						<button type="button" class="btn btn-host" id="js-save-calendar-rule-btn" ng-click="update_availability_rule()">
							{{trans('messages.wishlist.save_changes')}}
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Center Part Ending -->
<style>
	.hiddenEvent {
		display: none;
	}
	.status-r {
		background: #E2B4B6;
	}
</style>
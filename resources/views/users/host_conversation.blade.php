@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="conversation">
  @include('common.subheader')
  <div class="conversation-content" ng-cloak>
    <div class="container">
      <div class="pt-4 mb-3 conversation-head">
        <h1>
          {{ trans('messages.inbox.conversation_with') }} {{ $messages[0]->reservation->users->first_name }}
        </h1>
      </div>
      @if($messages[0]->reservation->status == 'Accepted')
      <div class="col-12 accepted-alert text-left alert alert-success alert-block p-3 mb-4">
        <div class="d-flex">
          <i class="icon icon-star-circled mr-3"></i>
          <p>
            <strong>
              {{ trans('messages.inbox.accepted') }}
            </strong>
            {{ trans('messages.inbox.you_have_accepted_reservation',['site_name'=>$site_name, 'first_name'=>$messages[0]->reservation->users->first_name]) }} 
            <a class="theme-link" href="mailto:{{ $messages[0]->reservation->users->email }}">
              {{ trans('messages.inbox.email') }}
            </a>
            @if($messages[0]->reservation->users->primary_phone_number != ''){{trans('messages.login.or')}} {{strtolower(trans('messages.profile.phone_number'))}} ({{$messages[0]->reservation->users->primary_phone_number}}) @endif 
          </p>
        </div>
        <div class="mt-2">
          <a class="theme-link" href="{{ url('/') }}/reservation/itinerary?code={{ $messages[0]->reservation->code }}">
            {{ trans('messages.your_trips.view_itinerary') }}
          </a>
        </div>
      </div>
      @endif

      <div class="conversation-wrap d-md-flex row">
        <div class="col-12 col-md-7 col-lg-8 conversation-left">
          <ul>
            <li id="message_friction_react"></li>
            <li id="post_message_box">
              <form id="non_special_offer_form" data-key="non_special_offer_form" class="message_form">
                <input type="hidden" value="{{ $messages[0]->reservation_id }}" name="inquiry_post_id" id="reservation_id">
                <input type="hidden" value="{{ $messages[0]->reservation->room_id }}" name="room_id" id="room_id">
                <input type="hidden" value="" name="template">
                <textarea placeholder="{{ trans('messages.inbox.add_personal_msg') }}" name="message" id="message_text"></textarea>
                <div class="my-4 text-right">
                  @if($status == 'Expired' && $messages[0]->reservation->list_type == 'Rooms')
                  <button type="button" class="btn btn-primary w-auto ml-2" ng-click="reply_message('non_special_offer_form')">
                    @lang('messages.your_reservations.send_message')
                  </button>
                  @else
                  @if($messages[0]->reservation->type != 'contact' && $messages[0]->reservation->list_type == 'Rooms')
                  <a class="btn attach-offer" href="javascript:void(0);">
                    @lang('messages.inbox.attach_special_offer')
                  </a>
                  @endif
                  @if($messages[0]->reservation->type == 'contact' && $messages[0]->reservation->list_type == 'Rooms')
                  <a id="pre_approve_button" class="btn pre-approve" href="javascript:void(0);">
                    @lang('messages.inbox.pre_approve') / @lang('messages.your_reservations.decline')
                  </a>
                  @endif
                  <button type="button" class="btn btn-primary w-auto ml-2" ng-click="reply_message('non_special_offer_form')">
                    @lang('messages.your_reservations.send_message')
                  </button>
                  @endif
                </div>
              </form>

              <div class="card inquiry-form-fields d-none">
                <div class="card-header">
                  <div class="row">
                    <div class="col-12 col-md-8 text-center text-md-left">
                      <h4>
                        {{ $messages[0]->reservation->rooms->name }}
                      </h4>
                      <p>
                        {{ $messages[0]->reservation->dates }} ({{ $messages[0]->reservation->nights }} {{ trans_choice('messages.rooms.night',1) }}{{ ($messages[0]->reservation->nights > 1) ? 's' : '' }})
                        ·
                        {{ $messages[0]->reservation->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[0]->reservation->number_of_guests) }}
                      </p>
                    </div>
                    <div class="price-info col-12 col-md-4 mt-3 mt-md-0 text-center text-md-right">
                      <h2>
                        <sup class="h5">
                          {{ html_string($messages[0]->reservation->currency->symbol) }}
                        </sup>
                        {{ $messages[0]->reservation->subtotal - $messages[0]->reservation->host_fee }}
                      </h2>
                    </div>
                  </div>
                </div>

                <div class="card-body host-decide">
                  <ul class="option-list" ng-init="last_message_id='{{$messages[0]->id}}'">
                    <li data-tracking-section="accept" class="positive">
                      <a class="option-link theme-link" href="javascript:void(0);">
                        {{ trans('messages.inbox.allow_guest_book') }}
                      </a>
                      <form class="message_form positive" id="allow_guest">
                        <input type="hidden" value="{{ $messages[0]->reservation_id }}" name="inquiry_post_id">
                        <ul class="mb-4 d-none">
                          @if(@$messages[0]->reservation->booked_reservation)
                          <li data-key="pre-approve" class="mb-2">
                            <hr>
                            <label class="d-flex align-items-center">
                              <input type="radio" value="1" name="template">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.pre_approve_book',['first_name'=>$messages[0]->reservation->users->first_name]) }}
                              </strong>
                            </label>
                            <div class="textarea-field mt-2">
                              <div class="drawer d-none">
                                <p class="description mb-3">
                                  {{ trans('messages.inbox.pre_approve_desc',['first_name'=>$messages[0]->reservation->users->first_name]) }}
                                </p>
                                <textarea placeholder="{{ trans('messages.inbox.include_msg',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                                <div class="mt-2 text-right">
                                  <input type="submit" value="{{ trans('messages.inbox.pre_approve') }}" class="btn btn-primary w-auto" ng-click="reply_message('pre-approve')">
                                </div>
                              </div>
                            </div>
                          </li>
                          @endif

                          <li data-key="special_offer" class="active">
                            <hr>
                            <label>
                              <input type="radio" value="2" name="template">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.send_a_special_offer',['first_name'=>$messages[0]->reservation->users->first_name]) }}
                              </strong>
                            </label>

                            <div class="textarea-field">
                              <div class="drawer d-none">
                                <p class="description mb-3">
                                  {{ trans('messages.inbox.special_offer_desc',['first_name'=>$messages[0]->reservation->users->first_name]) }}
                                </p>

                                <fieldset class="available-special-offer my-3">
                                  <label for="pricing_room_id">
                                    {{ trans('messages.lys.listing') }}
                                  </label>
                                  <div class="select mt-2">
                                    {!! Form::select('pricing[hosting_id]', $rooms, $messages[0]->reservation->room_id, ['id'=>'pricing_room_id']); !!}
                                  </div>

                                  <div class="special-offer-date-fields my-3">
                                    <div class="row">
                                      <div class="col-4 price-details-conversation">
                                        <label for="pricing_start_date">
                                          {{ trans('messages.your_reservations.checkin') }}
                                        </label>
                                        <input type="text" value="" readonly="readonly" onfocus="this.blur()" id="pricing_start_date" class="checkin ui-datepicker-target" placeholder="{{ DISPLAY_DATE_FORMAT }}">
                                        <input type="hidden" name="pricing[start_date]">
                                      </div>
                                      <div class="col-4 price-details-conversation">
                                        <label for="pricing_end_date">
                                          {{ trans('messages.your_reservations.checkout') }}
                                        </label>
                                        <input type="text" value="" readonly="readonly" onfocus="this.blur()" id="pricing_end_date" class="checkout ui-datepicker-target" placeholder="{{ DISPLAY_DATE_FORMAT }}">
                                        <input type="hidden" name="pricing[end_date]">
                                      </div>
                                      <div class="col-4 price-details-conversation">
                                        <label for="pricing_guests">
                                          {{ trans_choice('messages.home.guest',2) }}
                                        </label>
                                        <div class="select">
                                          <select name="pricing[guests]" id="pricing_guests">
                                            <option value="@{{i}}" ng-repeat="i in range(1,accomodates)">@{{i}}</option>
                                          </select>
                                        </div>
                                        <input type="hidden" value="nightly" name="pricing[unit]" id="pricing_unit">
                                      </div>
                                    </div>
                                  </div>
                                  <input type="hidden" name="pricing[status]" id="availability_status" value="Available" />
                                  <div id="availability_warning" class="alert alert-info d-none">
                                    <i class="icon alert-icon icon-comment"></i>
                                    <span id="not_available">
                                      {{ trans('messages.inbox.already_marked_dates') }}
                                    </span>
                                    <span id="error"></span>
                                  </div>
                                  <input type="hidden" name="currency" value="{!! Session::get('currency') !!}">   
                                  <div class="row">
                                    <div class="col-4 price-details-conversation">
                                      <label for="pricing_price">
                                        {{ trans('messages.inbox.price') }}
                                      </label>
                                      <div class="input-group flex-nowrap pricing-field">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            {{ html_string($messages[0]->reservation->currency->symbol) }}
                                          </span>
                                        </div>
                                        <input type="number" min="0" name="pricing[price]" id="pricing_price" class="input-stem">
                                      </div>
                                      <span class="text-danger">
                                        {{ $errors->first('pricing_price') }}
                                      </span>
                                    </div>

                                    <div class="col-4 d-none">
                                      <label for="pricing_price_type">&nbsp;</label>
                                      <div class="select d-none">
                                        <select name="pricing[price_type]" id="pricing_price_type" disabled="">
                                          <option value="total">{{ trans('messages.inbox.subtotal_price') }}</option>
                                          <option value="per_unit">{{ trans('messages.rooms.per_month') }}</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <input type="hidden" name="currency1" value="{!! Session::get('currency') !!}">   
                                  <div id="availability_warning1" class="alert alert-with-icon alert-info  row-space-top-2 d-none">
                                    <i class="icon alert-icon icon-comment"></i>
                                    Please Enter Amount
                                  </div>
                                  <p data-error="price" class="ml-error"></p>
                                  <div class="my-2">
                                    {{ trans('messages.inbox.price_include_additional_fees') }}
                                  </div>
                                  <div class="my-2" id="price-breakdown"></div>
                                </fieldset>
                                <textarea placeholder="{{ trans('messages.inbox.include_msg',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                                <div class="mt-2 text-right">
                                  <input type="submit" value="{{ trans('messages.inbox.send_offer') }}" class="btn btn-primary w-auto" ng-click="reply_message('special_offer')">
                                </div>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </form>       
                    </li>

                    @if($messages[0]->reservation->status != 'Accepted' && $messages[0]->reservation->status != 'Declined' && $messages[0]->reservation->status != 'Cancelled')
                    <li data-tracking-section="decline" class="negative">
                      <a class="option-link theme-link" href="javascript:void(0);">
                        {{ trans('messages.inbox.tell_listing_unavailable') }}
                      </a>
                      <form class="message_form negative" id="decline">
                        <input type="hidden" value="" name="inquiry_post_id">
                        <ul class="d-none">
                          <li>
                            <br>
                            <p class="font-weight-bold green-color">
                              {{trans('messages.inbox.host_msg_note',['site_name'=>SITE_NAME])}}
                            </p>
                          </li>
                          <li data-key="dates_not_available">
                            <hr>
                            <label>
                              <input type="radio" value="NOT_AVAILABLE" name="template" data-message="Dates are not available">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.dates_not_available_block',['dates'=>$messages[0]->reservation->dates]) }}
                              </strong>
                            </label>
                            <div class="textarea-field">
                              <div class="drawer d-none">
                                <p class="description mb-3">
                                  {{ trans('messages.inbox.calc_marked_unavailable',['dates'=>$messages[0]->reservation->dates]) }}
                                </p>
                                <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                                <p class="text-danger message_error_box d-none">
                                {{trans('messages.reviews.this_field_is_required')}}</p>
                                <div class="mt-2 text-right">
                                  <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('dates_not_available')">
                                </div>
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="not_comfortable">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="I do not feel comfortable with this guest">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.donot_feel_comfortable') }}
                              </strong>
                            </label>
                            <div class="textarea-field">
                              <div class="drawer d-none">
                                <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                                <p class="text-danger message_error_box d-none">
                                {{trans('messages.reviews.this_field_is_required')}}</p>
                                <div class="mt-2 text-right">
                                  <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('not_comfortable')">
                                </div>
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="not_a_good_fit" class="template_9">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="My listing is not a good fit for the guest’s needs (children, pets, etc.)">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.listing_not_good_fit') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                              <p class="text-danger message_error_box d-none">
                                {{trans('messages.reviews.this_field_is_required')}}
                              </p>
                              <div class="mt-2 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('not_a_good_fit')">
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="waiting_for_better_reservation" class="template_9">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="I’m waiting for a more attractive reservation">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.waiting_attractive_reservation') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                              <p class="text-danger message_error_box d-none">
                              {{trans('messages.reviews.this_field_is_required')}}</p>
                              <div class="mt-2 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('waiting_for_better_reservation')">
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="different_dates_than_selected" class="template_9">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="The guest is asking for different dates than the ones selected in this request">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.guest_asking_different_dates') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                              <p class="text-danger message_error_box d-none">
                              {{trans('messages.reviews.this_field_is_required')}}</p>
                              <div class="mt-2 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('different_dates_than_selected')">
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="spam" class="template_9">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="This message is Spam">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.msg_is_spam') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                              <p class="text-danger message_error_box d-none">
                              {{trans('messages.reviews.this_field_is_required')}}</p>
                              <div class="mt-3 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('spam')">
                              </div>
                            </div>
                          </li>
                          <!-- 9 -->
                          <li data-key="other" class="template_9">
                            <hr>
                            <label>
                              <input type="radio" value="9" name="template" data-message="Other">                                    
                              <strong class="d-inline-block">
                                {{ trans('messages.profile.other') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea placeholder="{{ trans('messages.inbox.send_msg_user',['first_name'=>$messages[0]->reservation->users->first_name]) }}" name="message"></textarea>
                              <p class="text-danger message_error_box d-none">
                              {{trans('messages.reviews.this_field_is_required')}}</p>
                              <div class="mt-3 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('other')">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </form>        
                    </li>
                    @endif
                    <li data-tracking-section="discussion" class="neutral d-none">
                      <a class="option-link theme-link" href="javascript:void(0);">
                        {{ trans('messages.inbox.write_back_to_learn') }}
                      </a>
                      <form class="message_form neutral" id="discussion">
                        <input type="hidden" value="" name="inquiry_post_id">
                        <ul class="d-none">
                          <!-- 7 -->
                          <li data-key="discussion" class="template_7" data-message="Dates are not available">
                            <hr>
                            <label>
                              <input type="radio" value="7" name="template">
                              <strong class="d-inline-block">
                                {{ trans('messages.inbox.need_answer_question') }}
                              </strong>
                            </label>
                            <div class="textarea-field drawer d-none">
                              <textarea class="required" placeholder="{{ trans('messages.inbox.only_guest_see_msg') }}" name="message"></textarea>
                              <div class="mt-3 text-right">
                                <input type="submit" value="{{ trans('messages.inbox.send') }}" class="btn btn-primary w-auto" ng-click="reply_message('discussion')">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </form>       
                    </li>
                  </ul>
                </div>
              </div>
            </li>

            @for($i=0; $i < count($messages); $i++)
            @if($messages[$i]->user_from == Auth::user()->id)
            <li id="question2_post_{{ $messages[$i]->id }}">
              @if($messages[$i]->message_type == 7)
              <div class="card my-4">
                <div class="card-header">
                  <span class="label label-info">
                    {{ trans('messages.inbox.special_offer') }}
                  </span>
                  <h5>
                    {{ $messages[$i]->reservation->users->first_name }} {{ trans('messages.inbox.pre_approved_stay_at') }} 
                    <a href="{{ url('rooms/'.$messages[$i]->special_offer->room_id) }}">
                      {{ $messages[$i]->special_offer->rooms->name }}
                    </a>
                  </h5>
                  <p class="m-0">
                    {{ $messages[$i]->special_offer->dates }}
                    <span class="ml-2">
                      ·
                      {{ $messages[$i]->special_offer->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[$i]->special_offer->number_of_guests) }}
                    </span>
                    <br>
                    <strong>
                      {{ trans('messages.inbox.you_could_earn') }} {{ html_entity_decode($messages[$i]->special_offer->currency->symbol).$messages[$i]->special_offer->price }} {{ $messages[$i]->special_offer->currency->session_code }}
                    </strong> 
                    ({{ trans('messages.inbox.once_reservation_made') }})
                  </p>
                </div>
                @if(@$messages[$i]->special_offer->is_booked)
                <div class="card-body">
                  <a href="{{ url('/') }}/messaging/remove_special_offer/{{ $messages[$i]->special_offer_id }}" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">
                    {{ trans('messages.inbox.remove_special_offer') }}
                  </a>
                </div>
                @endif
              </div>
              @endif

              @if($messages[$i]->message_type == 6)
              <div class="card my-4">
                <div class="card-header">
                  <h5>
                    {{ $messages[$i]->reservation->users->first_name }} {{ trans('messages.inbox.pre_approved_stay_at') }} 
                    <a href="{{ url('rooms/'.$messages[$i]->reservation->room_id) }}">
                      {{ $messages[$i]->special_offer->rooms->name }}
                    </a>
                  </h5>
                  <p class="m-0">
                    {{ $messages[$i]->special_offer->dates }}
                    <span class="mx-2">
                      ·
                      {{ $messages[$i]->special_offer->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[$i]->special_offer->number_of_guests) }}
                      ·
                    </span>
                    {{ html_entity_decode($messages[$i]->special_offer->currency->symbol).($messages[$i]->special_offer->price - $messages[$i]->reservation->host_fee) }} {{ $messages[$i]->special_offer->currency->session_code }}
                  </p>
                </div>
                @if(@$messages[$i]->special_offer->is_booked)
                <div class="card-body">
                  <a href="{{ url('/') }}/messaging/remove_special_offer/{{ $messages[$i]->special_offer_id }}" class="btn" data-confirm="Are you sure?" data-method="post" rel="nofollow">
                    {{ trans('messages.inbox.remove_pre_approval') }}
                  </a>
                </div>
                @endif
              </div>
              @endif

              <div class="row my-4">
                <div class="col-3 col-md-2 pr-0 text-center">
                  <a aria-label="{{ $messages[$i]->reservation->rooms->users->first_name }}" data-behavior="tooltip" href="{{ url('/') }}/users/show/{{ $messages[$i]->reservation->host_id }}">
                    <img title="{{ $messages[$i]->reservation->rooms->users->first_name }}" src="{{ $messages[$i]->reservation->rooms->users->profile_picture->src }}" alt="{{ $messages[$i]->reservation->rooms->users->first_name }}">
                  </a>
                </div>
                <div class="col-9 col-md-10">
                  <div class="card custom-arrow left">
                    <div class="card-body p-3">
                      <p>
                        {{ $messages[$i]->message }}
                      </p>
                    </div>
                  </div>
                  <div class="time-container">
                    <small title="{{ $messages[$i]->created_at }}" class="time">
                      {{ $messages[$i]->created_time }}
                    </small>
                    <small class="exact-time d-none">
                      {{ $messages[$i]->created_at }}
                    </small>
                  </div>
                </div>
              </div>
            </li>
            @endif

            @if($messages[$i]->user_from != Auth::user()->id)
            <li id="question2_post_{{ $messages[$i]->id }}">
              @if(($messages[$i]->message_type == 1 || $messages[$i]->message_type == 9) && $messages[$i]->reservation->list_type != 'Experiences')
              <div class="card">
                <div class="card-header">
                  <h5>
                    {{ trans('messages.inbox.inquiry_about') }} 
                    <a locale="en" data-popup="true" href="{{ url('/') }}/rooms/{{ $messages[$i]->reservation->room_id }}" class="theme-link">
                      {{ $messages[$i]->reservation->rooms->name }}
                    </a>
                  </h5>
                  <p class="m-0">
                    {{ $messages[$i]->reservation->dates }}
                    <span class="ml-2">
                      ·
                      {{ $messages[$i]->reservation->number_of_guests }} {{ trans_choice('messages.home.guest',$messages[$i]->reservation->number_of_guests) }}
                    </span>
                    <br>
                    {{ trans('messages.inbox.you_will_earn') }} {{ html_entity_decode($messages[$i]->reservation->currency->symbol).$messages[$i]->reservation->host_payout }} {{ $messages[$i]->reservation->currency->code }}
                  </p>
                </div>
              </div>
              @endif
              @if($messages[$i]->message_type == 10)
              <div class="inline-status">
                <div class="horizontal-rule-text">
                  <span class="horizontal-rule-wrapper">
                    <span>
                      {{ trans('messages.inbox.reservation_declined') }} 
                    </span>
                    <span>
                      {{ $messages[$i]->created_time }}
                    </span>
                  </span>
                </div>
              </div>
              @endif

              <div class="row my-4">
                <div class="col-9 col-md-10">
                  <div class="card custom-arrow right">
                    <div class="card-body p-3">
                      <p>
                        {{ $messages[$i]->message }}
                      </p>
                    </div>
                  </div>
                  <div class="time-container text-right">
                    <small title="{{ $messages[$i]->created_at }}" class="time">
                      {{ $messages[$i]->created_time }}
                    </small>
                    <small class="exact-time d-none">
                      {{ $messages[$i]->created_at }}
                    </small>
                  </div>
                </div>

                <div class="col-3 col-md-2 pl-0 text-center">
                  <a aria-label="{{ $messages[$i]->reservation->users->first_name }}" data-behavior="tooltip" href="{{ url('/') }}/users/show/{{ $messages[$i]->reservation->user_id }}">
                    <img title="{{ $messages[$i]->reservation->users->first_name }}" src="{{ $messages[$i]->reservation->users->profile_picture->src }}" alt="{{ $messages[$i]->reservation->users->first_name }}">
                  </a>
                </div>
              </div>
            </li>
            @endif
            @endfor
          </ul>
        </div>

        <div class="col-12 col-md-5 col-lg-4 coversation-right">
          <div class="card">
            <div class="mini-profile d-flex">
              <div class="profile-img col-4 p-0">
                <a href="{{ url('/') }}/users/show/{{ $messages[0]->reservation->user_id }}">
                  <img alt="{{ $messages[0]->reservation->users->first_name }}" src="{{ $messages[0]->reservation->users->profile_picture->src }}">
                </a>
              </div>

              <div class="mini-profile-info col-8 my-2">
                <h4 class="text-truncate">
                  <a href="{{ url('/') }}/users/show/{{ $messages[0]->reservation->user_id }}">
                    {{ $messages[0]->reservation->users->first_name }}
                  </a>
                </h4>
                <span>
                  {{ $messages[0]->reservation->users->live }}
                </span>
                <span>
                  {{ trans('messages.profile.member_since') }} {{ @$messages[0]->reservation->users->since }}
                </span>
              </div>
            </div>

            @if($messages[0]->reservation->users->users_verification->show() || $messages[0]->reservation->users->verification_status == 'Verified')
            <div class="verification-panel">
              <div class="card-header">
                {{ trans('messages.dashboard.verifications') }}
              </div>
              <div class="card-body">
                <ul>
                  @if($messages[0]->reservation->users->verification_status == 'Verified')
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
                  @if($messages[0]->reservation->users->users_verification->email == 'yes')
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
                  @if($messages[0]->reservation->users->users_verification->phone_number == 'yes')
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
                  @if($messages[0]->reservation->users->users_verification->facebook == 'yes')
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
                  @if($messages[0]->reservation->users->users_verification->google == 'yes')
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
                  @if($messages[0]->reservation->users->users_verification->linkedin == 'yes')
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

          <div class="select my-3">
            {!! Form::select('hosting', $rooms, $messages[0]->reservation->room_id, ['id'=>'hosting']); !!}
          </div>

          <div id="calendar-container" class="small-calendar my-2">
            {!! $calendar !!}
          </div>

          <a class="theme-link" href="{{ $edit_calendar_link }}" id="edit_calendar_url" data-type="{{$messages[0]->reservation->list_type}}">
            {{ trans('messages.inbox.full_calc_edit') }}
          </a>

          <div class="contact-info card my-4">
            <div class="card-header">
              <h5>
                {{ trans('messages.inbox.contact_info') }}
              </h5>
            </div>
            <div class="card-body">
              <p>
                {{ trans('messages.inbox.contact_info_desc') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
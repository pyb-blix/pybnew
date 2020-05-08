@extends('template')
@section('main')
<main id="site-content" role="main">    
  @include('common.subheader')  
  <div class="reservation-content my-4 my-md-5" ng-cloak>
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 side-nav">
          @include('common.sidenav')
        </div>

        <div class="col-12 col-md-9">       
          <div class="card">
            <div class="card-header d-md-flex text-center justify-content-between">
              <h6 class="m-0">
                {{ trans('messages.your_reservations.requested_reservation') }}
              </h6>
              @if($result->status == 'Pending')
              <div class="reserve-left mt-2 mt-md-0">
                <span class="label label-info green-color d-inline-flex align-items-center">
                  <i class="icon icon-time mr-2"></i>
                  {{ trans('messages.your_reservations.expires_in') }}
                  <span class="countdown_timer hasCountdown">
                    <span class="countdown_row countdown_amount ml-1" id="countdown_1"></span>
                  </span>
                </span>
              </div>
              @endif
            </div>

            <div id="accept_decline" class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>{{ trans('messages.your_reservations.property') }}</td>
                      <td>
                        {{ $result->rooms->name }}
                        <a class="theme-link table-link" href="{{ url('/') }}/rooms/{{ $result->room_id }}" data-popup="true">
                          {{ trans('messages.your_reservations.view_property') }}
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('messages.your_reservations.checkin') }}</td>
                      <td>
                        {{ $result->checkin_mdy }}
                        <a class="theme-link table-link" href="{{ url('/') }}/manage-listing/{{ $result->room_id }}/calendar" data-popup="true">
                          {{ trans('messages.your_reservations.view_calendar') }}
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('messages.your_reservations.checkout') }}</td>
                      <td>
                        {{ $result->checkout_mdy }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ ucfirst(trans_choice('messages.rooms.night',2)) }}
                      </td>
                      <td>
                        {{ $result->nights }}
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans_choice('messages.home.guest',2) }}</td>
                      <td>
                        {{ $result->number_of_guests }}
                      </td>
                    </tr>
                    <tr>
                      <td>{{ trans('messages.your_reservations.cancellation') }}</td>
                      <td>
                        {{trans('messages.cancellation_policy.'.strtolower($result->cancellation))}}
                        <a class="theme-link table-link" href="{{ url('/') }}/home/cancellation_policies#{{ $result->cancellation }}" id="cancel-policy-modal-trigger">
                          {{ trans('messages.your_reservations.view_policy') }}
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.rate_per_night') }}
                        <sup class="h6">
                          <i class="icon icon-question tool-amenity2" title="{{ trans('messages.your_reservations.different_rates') }}" rel="tooltip"></i>
                        </sup>
                      </td>
                      <td>
                        <span>
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->base_per_night }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span> 
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->base_per_night }} x {{$result->nights}}   
                      </td>
                      <td>
                        <span>
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->base_per_night * $result->nights}}
                      </td>
                    </tr>
                    @foreach($result->discounts_list as $list) 
                    <tr class="green-color">
                      <td>
                        {{$list['text']}}
                      </td>
                      <td>
                        -<span>
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $list['price'] }}
                      </td>
                    </tr>
                    @endforeach
                    @if($result->cleaning != 0)
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.cleaning_fee') }}
                      </td>
                      <td>
                        <span>
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->cleaning }}
                      </td>
                    </tr>
                    @endif
                    @if($result->additional_guest != 0)
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.additional_guest_fee') }}
                      </td>
                      <td>
                        <span>
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->additional_guest }}
                      </td>
                    </tr>
                    @endif
                    
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.subtotal') }}
                      </td>
                      <td>
                        <span>  
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->subtotal }}
                      </td>
                    </tr>
                    @if($result->host_fee) 
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.host_fee') }}
                        <i class="icon icon-question icon-question-sign" title="{{ trans('messages.your_reservations.host_fee_desc',['site_name'=>$site_name]) }}" rel="tooltip"></i>
                      </td>
                      <td>
                        -<span>  
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->host_fee }}
                      </td>
                    </tr>
                    @endif
                    @if($result->security != 0)
                    <tr>
                      <td>
                        {{ trans('messages.your_reservations.security_fee') }}
                      </td>
                      <td>
                        <span>  
                          {{ html_string($result->currency->symbol) }}
                        </span>
                        {{ $result->security }}
                      </td>
                    </tr>
                    @endif
                    <tr id="total">
                      <td>
                        {{ trans('messages.your_reservations.total_payout') }}
                      </td>
                      <td>
                        <strong>
                          <span> 
                            {{ html_string($result->currency->symbol) }}
                          </span>
                          {{ @$result->host_payout }}
                        </strong>
                      </td>
                    </tr>
                  </tbody>
                </table> 
              </div>
            </div>
            <div class="card-header">
              {{ trans('messages.your_reservations.about_guest') }}
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-4 profile-img user-reserve text-center text-md-left">
                  <img class="mb-4" alt="{{ $result->users->first_name }}" src="{{ $result->users->profile_picture->src }}" title="{{ $result->users->first_name }}">
                  <h4>
                    <a href="{{ url('/') }}/users/show/{{ $result->users->id }}" class="verification_user_name theme-link">
                      {{ $result->users->first_name }}
                    </a>
                  </h4>
                  <span class="h5">
                    {{ $result->users->live }}
                  </span>
                  <p>
                    {{ trans('messages.profile.member_since') }} {{ $result->users->since }}
                  </p>
                  @if($result->users->age)
                  <dl class="mt-2">
                    <dt style="width:100%;">
                      {{ trans('messages.your_reservations.age') }}
                    </dt>
                    <dd style="width:100%;">
                      {{ $result->users->age }}
                    </dd>
                  </dl>
                  @endif
                  @if($result->users->users_verification->show())
                  <div class="card verification-panel">
                    <div class="card-header">
                      {{ trans('messages.dashboard.verifications') }}
                    </div>
                    <div class="card-body">
                      <ul>
                        @if($result->users->verification_status == 'Verified')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                {{ trans('messages.dashboard.id_verification') }}
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.verified') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                        @if($result->users->users_verification->email == 'yes')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                {{ trans('messages.dashboard.email_address') }}
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.verified') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                        @if($result->users->users_verification->phone_number == 'yes')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                {{ trans('messages.profile.phone_number') }}
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.verified') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                        @if($result->users->users_verification->facebook == 'yes')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                Facebook
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.validated') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                        @if($result->users->users_verification->google == 'yes')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                Google
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.validated') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                        @if($result->users->users_verification->linkedin == 'yes')
                        <li>
                          <div class="media">
                            <i class="icon icon-ok mr-2"></i>
                            <div class="media-body">
                              <h5>
                                LinkedIn
                              </h5>
                              <p>
                                {{ trans('messages.dashboard.validated') }}
                              </p>
                            </div>
                          </div>
                        </li>
                        @endif
                      </ul>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="col-12 col-md-8 mt-2 mt-md-0">
                  @if($result->messages->message != '')
                  <div class="card">
                    <div class="card-body custom-arrow left">
                      <p>
                        {{ $result->messages->message }}
                      </p>
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>

            @if($result->status == 'Pending')
            <div class="card-header">
              <div class="col-12 text-center d-md-flex p-0">
                <span>
                  {{ trans('messages.your_reservations.accept_request') }}?
                </span>
                <div class="mt-2 mt-md-0 ml-md-auto timer">
                  <span class="label label-info green-color d-inline-flex align-items-center">
                    <i class="icon icon-time mr-2"></i>
                    {{ trans('messages.your_reservations.expires_in') }}
                    <span class="countdown_timer hasCountdown">
                      <span class="countdown_row countdown_amount ml-1" id="countdown_2"></span>
                    </span>
                  </span>
                </div>
              </div>
            </div>

            <div class="py-3 px-4">
              <p class="m-0">
                {{ trans('messages.your_reservations.penalized_if_expires') }}
              </p>
            </div>

            <div class="card-footer text-right">
              <button class="js-host-action btn btn-primary" data-toggle="modal" data-target="#accept-modal">
                {{ trans('messages.inbox.pre_accept') }}
              </button>
              <button class="js-host-action btn" data-toggle="modal" data-target="#decline-modal">
                {{ trans('messages.your_reservations.decline') }}
              </button>
              <button class="js-host-action btn" data-toggle="modal" data-target="#discuss-modal">
                {{ trans('messages.your_reservations.discuss') }}
              </button>
            </div>
            @else
            <div class="card-header text-center">
              <span class="label-{{ $result->status_color }}">
                @if(@$result->status == 'Pre-Accepted')
                {{ trans('messages.inbox.pre_accepted') }}
                @endif
                @if(@$result->status == 'Expired')  
                {{ trans('messages.dashboard.Expired') }}    
                @endif
              </span>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" role="dialog" id="accept-modal" aria-hidden="true" tabindex="-1">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ trans('messages.your_reservations.accept_this_request') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="accept-modal-alert" class="card-header alert alert-header alert-info d-none">
        <i class="icon icon-comment alert-icon"></i>
        <span id="alert-content"></span>
      </div>
      <form accept-charset="UTF-8" action="{{ url('reservation/accept/'.$reservation_id) }}" id="accept_reservation_form" method="post" name="accept_reservation_form">
        {!! Form::token() !!}
        <div class="modal-body">
          <label for="accept_message">
            {{ trans('messages.your_reservations.type_msg_to_guest') }}...
          </label>
          <textarea cols="40" id="accept_message" name="message" rows="10"></textarea>
          <div class="d-flex mt-2">
            <input class="mt-1" id="tos_confirm" name="tos_confirm" type="checkbox" value="0">
            <label class="label-inline" for="tos_confirm">
              {{ trans('messages.your_reservations.by_checking_box') }} 
              @foreach($company_pages as $company_page)
              @if($company_page->name=='Terms of Service')
              <a class="d-inline-block theme-link" href="{{ url('/') }}/terms_of_service" target="_blank"> @lang('messages.login.terms_service') </a>,
              @endif
              @if($company_page->name=='Host Guarantee')
              <a class="d-inline-block theme-link" href="{{ url('/') }}/host_guarantee" target="_blank"> @lang('messages.your_reservations.host_terms_conditions') </a>,
              @endif
              @if($company_page->name=='Guest Refund')
              and <a class="d-inline-block theme-link" href="{{ url('/') }}/guest_refund" target="_blank"> @lang('messages.your_reservations.guest_refund_terms') </a>.
              @endif
              @endforeach
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="decision" value="accept">
          <input class="btn btn-primary w-auto" id="accept_submit" name="commit" type="submit" value="{{ trans('messages.inbox.pre_accept') }}">
          <button class="btn" data-dismiss="modal" aria-label="Close">
            {{ trans('messages.home.close') }}
          </button>
        </div>
      </form>    
    </div>
  </div>
</div>

<div class="modal" role="dialog" id="decline-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form accept-charset="UTF-8" action="{{ url('reservation/decline/'.$reservation_id) }}" id="decline_reservation_form" method="post" name="decline_reservation_form">
        {!! Form::token() !!}
        <div class="modal-header">
          <h5 class="modal-title">
            {{ trans('messages.your_reservations.decline_this_request') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="decline_reason_container">
            <p>
              {{ trans('messages.your_reservations.reason_declining') }}?
            </p>
            <p>
              <strong>
                {{ trans('messages.your_reservations.not_shared_with_guest') }}
              </strong>
            </p>
            <div class="select" ng-init="decline_reason ='';decline_message = '';decline_reason_other='';">
              <select id="decline_reason" name="decline_reason" ng-model="decline_reason">
                <option value="">{{trans('messages.host_decline.why_are__you_declining')}}</option>
                <option value="dates_not_available">{{trans('messages.host_decline.dates_not_available')}}</option>
                <option value="not_comfortable">{{trans('messages.host_decline.not_comfortable')}}</option>
                <option value="not_a_good_fit">{{trans('messages.host_decline.not_a_good_fit')}}</option>
                <option value="waiting_for_better_reservation">{{trans('messages.host_decline.waiting_for_better_reservation')}}</option>
                <option value="different_dates_than_selected">{{trans('messages.host_decline.different_dates_than_selected')}}</option>
                <option value="spam">{{trans('messages.host_decline.spam')}}</option>
                <option value="other">{{trans('messages.your_reservations.other')}}</option>
              </select>
            </div>
            <div id="decline_reason_other_div" class="mt-3" ng-show="decline_reason == 'other'">
              <label for="decline_reason_other">
                {{ trans('messages.your_reservations.why_declining') }}?
              </label>
              <textarea id="decline_reason_other" name="decline_reason_other" rows="4" ng-model="decline_reason_other"></textarea>
            </div>
          </div>
          <label for="decline_message" class="mt-3">
            {{ trans('messages.your_reservations.type_msg_to_guest') }}...
          </label>
          <textarea cols="40" id="decline_message" name="message" rows="10" ng-model="decline_message"></textarea>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="decision" value="decline">
          <input class="btn btn-primary w-auto" id="decline_submit" name="commit" type="submit" value="{{trans('messages.your_reservations.decline')}}" ng-disabled="(decline_reason =='' && decline_message=='') || (decline_reason == 'other' && decline_reason_other == '')">
          <button class="btn" data-dismiss="modal" aria-label="Close">
            {{ trans('messages.home.close') }}
          </button>
        </div>
      </form>      
    </div>
  </div>
</div>

<div class="modal" role="dialog" data-trigger="#discuss-modal" id="discuss-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          {{ trans('messages.your_reservations.discuss_this_request') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>
          {{ trans('messages.your_reservations.before_accept_decline') }}:
        </p>
        <ul>
          <li>
            <a class="theme-link mb-2" href="{{ url('/') }}/messaging/qt_with/{{ $result->id }}" id="other_reservation_send_message">
              {{ trans('messages.your_reservations.send_msg_to') }} {{ $result->users->first_name }}
            </a>
          </li>
        </ul>
        <p>
          {{ trans('messages.your_reservations.after_msg_accept') }}
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">
          {{ trans('messages.home.close') }}
        </button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="expired_at" value="{{ $result->created_at_timer }}">
<input type="hidden" id="reservation_id" value="{{ $reservation_id }}">
</main>
@stop
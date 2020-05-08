@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="cancel_reservation">
  @include('common.subheader')  
  <div class="my-reservation my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 side-nav">
          @include('common.sidenav')
        </div>
        <div class="col-12 col-md-9 upcoming-reservations mt-3 mt-md-0">
          <div class="card" id="print_area">
            @if($reservations->count() == 0 && $code != 1 && $reservation_count >= 1)
            <div class="card-body">
              <p>
                {{ trans('messages.your_reservations.no_upcoming_reservations') }}
              </p>
              <a class="theme-link" href="{{ url('/') }}/my_reservations?all=1">
                {{ trans('messages.your_reservations.view_past_reservation_history') }}
              </a>
            </div>
            @elseif(($reservations->count() == 0 && $code == 1) || $reservation_count < 1)
            <div class="card-body">
              <p>
                {{ trans('messages.your_reservations.no_reservations') }}
              </p>
              <a href="{{ url('/') }}/rooms/new" class="btn list-your-space-btn" id="list-your-space">
                {{ trans('messages.your_listing.add_new_listings') }}
              </a>
            </div>
            @else
            <div class="card-header d-md-flex text-center text-md-left align-items-center border-bottom-0">
              <h3>
                {{ ($code == 1) ? trans('messages.your_reservations.all') : trans('messages.your_reservations.upcoming') }} {{ trans('messages.inbox.reservations') }}
              </h3>
              <a class="btn print-btn mt-3 mt-md-0 ml-auto d-flex align-items-center justify-content-center" href="{{ url('/') }}/my_reservations?all={{ $code }}&print={{ $code }}">
                <span>
                  {{ trans('messages.your_reservations.print_this_page') }}
                </span>
                <i class="icon icon-description ml-2"></i>
              </a>   
            </div>
            <div class="table-responsive">
              <table class="table card-body">
                <tbody>
                  <tr>
                    <th>
                      {{ trans('messages.your_reservations.status') }}
                    </th>
                    <th>
                      {{ trans('messages.your_reservations.dates_location') }}
                    </th>
                    <th>
                      {{ trans_choice('messages.home.guest',1) }}
                    </th>
                    <th>
                      {{ trans('messages.your_reservations.details') }}
                    </th>
                  </tr>
                  @foreach($reservations as $row)
                  <tr data-reservation-id="{{ $row->id }}" class="reservation">
                    <td>
                      @if($row->status == 'Pre-Accepted' || $row->status == 'Inquiry')
                      @if($row->checkin >= date("Y-m-d"))
                      <span class="label label-{{ $row->status_color }} text-nowrap">
                        {{ trans('messages.dashboard.'.$row->status) }}
                      </span>
                      @else
                      <span class="label label-info text-nowrap">
                        {{trans('messages.dashboard.Expired')}}
                      </span>
                      @endif
                      @else
                      <span class="label label-{{ $row->status_color }} text-nowrap">
                        {{ trans('messages.dashboard.'.$row->status) }}
                      </span>
                      @endif
                    </td>
                    <td>
                      {{ $row->dates }}
                      <br>
                      <a locale="en" href="{{$row->rooms->link}}" class="{{$row->title_class}} theme-link text-capitalize">
                        {{ $row->rooms->name }}
                      </a>
                      <br>
                      {{ $row->rooms->rooms_address->address_line_1 }}
                      <br>
                      @if($row->rooms->rooms_address->city !='') {{ $row->rooms->rooms_address->city }},@endif
                      @if($row->rooms->rooms_address->state !='') {{ $row->rooms->rooms_address->state }}@endif
                      @if($row->rooms->rooms_address->postal_code !='') {{ $row->rooms->rooms_address->postal_code }}@endif
                      <br>
                    </td>
                    <td>
                      <div class="guest-reserve d-flex">
                        <a class="profile-img" href="{{ url('/') }}/users/show/{{ $row->users->id }}">
                          <img title="{{ $row->users->first_name }}" src="{{ $row->users->profile_picture->src }}" alt="{{ $row->users->first_name }}">
                        </a>     
                        <div class="pl-2">
                          <a class="normal-link" href="{{ url('/') }}/users/show/{{ $row->users->id }}">
                            {{ $row->users->full_name }}
                          </a>
                          @if($row->status == 'Accepted')
                          <a class="normal-link d-flex text-nowrap" href="{{ url('/') }}/messaging/qt_with/{{ $row->id }}">
                            <i class="icon icon-envelope mr-2"></i>
                            {{ trans('messages.your_reservations.send_message') }}
                          </a>
                          <a class="theme-link" href="mailto:{{ $row->users->email }}">
                            {{ trans('messages.your_reservations.contact_by_email') }} 
                          </a>
                          @if($row->users->primary_phone_number != '')
                          <br>
                          {{ $row->users->primary_phone_number }}
                          @endif
                          @endif
                          <br>
                        </div>
                      </div>
                    </td>
                    <td>
                      {{html_string($row->currency->symbol)}}{{$row->subtotal - $row->host_fee}} {{ trans('messages.your_reservations.total') }}
                      <ul>
                        <li>
                          <a class="theme-link text-nowrap" href="{{ url('/') }}/messaging/qt_with/{{ $row->id }}">
                            {{ trans('messages.your_reservations.message_history') }}
                          </a>
                        </li>
                        @if($row->status == "Pre-Accepted")
                        <li>
                          <a class="theme-link" data-toggle="modal" data-target="#cancel-modal" href="javascript:void(0)" id="{{$row->id}}-trigger">
                            {{ trans('messages.your_reservations.cancel') }}
                          </a>
                        </li>
                        @endif
                        @if($row->status == "Accepted")
                        <li>
                          <a class="theme-link text-nowrap" target="_blank" href="{{ url('/') }}/reservation/itinerary?code={{ $row->code }}">
                            {{ trans('messages.your_reservations.print_confirmation') }}
                          </a>
                        </li>
                        @if(!$row->checkout_cross)
                        <li>
                          <a class="theme-link" data-toggle="modal" data-target="#cancel-modal" href="javascript:void(0)" id="{{$row->id}}-trigger">
                            {{ trans('messages.your_reservations.cancel') }}
                          </a>
                        </li>
                        @endif
                        @endif
                      </ul>
                      @if($row->can_apply_for_dispute && $row->paymode != '')
                      <button class="btn btn-primary" type="button" id="js_dispute_btn" ng-click="trigger_create_dispute({{collect(['id' => $row->id, 'currency_code' => $row->currency_code, 'currency_symbol' => html_entity_decode($row->currency->symbol)])->toJson()}})">
                        {{trans('messages.disputes.dispute')}}
                      </button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @if($code == '0' || $code == '')
            <div class="card-body border-top">
              <a class="theme-link" href="{{ url('/') }}/my_reservations?all=1">
                {{ trans('messages.your_reservations.view_all_reservation_history') }}
              </a>
            </div>
            @else
            <div class="card-body">
              <a class="theme-link" href="{{ url('/') }}/my_reservations?all=0">
                {{ trans('messages.your_reservations.view_upcoming_reservations') }}
              </a>
            </div>
            @endif
            @endif
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" value="{{ $print }}" id="print">

    <div class="modal" role="dialog" id="cancel-modal" aria-hidden="true" tabindex="-1">
     <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form accept-charset="UTF-8" action="{{ url('reservation/host_cancel_reservation') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
          {!! Form::token() !!}
          <div class="modal-header">
            <h5 class="modal-title">
              {{ trans('messages.your_reservations.cancel_this_reservation') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="decline_reason_container">
              <p>
                {{ trans('messages.your_reservations.reason_cancel_reservation') }}
              </p>
              <div class="select">
                <select id="cancel_reason" name="cancel_reason">
                  <option value="">
                    {{trans('messages.host_cancel.why_are__you_cancelling')}}
                  </option>
                  <option value="no_longer_available">
                    {{trans('messages.host_cancel.no_longer_available')}}
                  </option>
                  <option value="offer_a_different_listing">{{trans('messages.host_cancel.offer_a_different_listing')}}
                  </option>
                  <option value="need_maintenance">
                    {{trans('messages.host_cancel.need_maintenance')}}
                  </option>
                  <option value="I_have_an_extenuating_circumstance">
                    {{trans('messages.host_cancel.I_have_an_extenuating_circumstance')}}
                  </option>
                  <option value="my_guest_needs_to_cancel">
                    {{trans('messages.host_cancel.my_guest_needs_to_cancel')}}
                  </option>
                  <option value="other">
                    {{trans('messages.your_reservations.other')}}
                  </option>
                </select>
              </div>
            </div>
            <label for="cancel_message" class="mt-3">
              {{ trans('messages.your_reservations.type_msg_guest') }}...
            </label>
            <textarea cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
            <input type="hidden" name="id" id="reserve_id" value="">
          </div>
          <div class="modal-footer text-right">
            <input type="hidden" name="decision" value="decline">
            <input class="btn btn-primary w-auto" id="cancel_submit" name="commit" type="submit" value="Cancel My Reservation">
            <button type="button" class="btn ml-2" data-dismiss="modal" aria-label="Close">
              {{ trans('messages.home.close') }}
            </button>
          </div>
        </form>      
      </div>
    </div>
  </div>
</div>
@include('trips/dispute_modal')
</main>
<script>
  if(document.getElementById('print').value >= '0')
  {
    window.print();
    window.onfocus=function() {
      window.location.href=APP_URL+'/my_reservations';
    }
  }
</script>
@if($print >= '0')
<style>
  body * {
    visibility: hidden;
  }
  #print_area, #print_area * {
    visibility: visible;
  }
  #print_area {
    position: fixed;
    left: 0px;
    top: 0px;
  }
  a[href]:after {
    content: none !important;
  }
</style>
@endif
@stop
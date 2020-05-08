@extends('template')
@section('main')
<main id="site-content" role="main">      
  <div class="reservation-print py-4 py-md-5">
    <div class="container">
      <div class="card p-4">
        <div class="d-flex row">
          <div class="col-12 col-md-6 text-center text-md-left">
            <h3>
              {{ trans('messages.your_reservations.itinerary') }}
            </h3>
            <label>
              {{ trans('messages.your_reservations.confirmation_code') }}: 
              <span>
                {{ $reservation_details->code }}
              </span>
            </label>
          </div>

          <div class="col-12 col-md-6 text-center text-md-right d-print-none print-itinerary">
            <a class="text-nowrap theme-link" onclick="print_itinerary()" href="javascript:void(0)">
              {{ trans('messages.your_reservations.print_itinerary') }}
            </a>
          </div>
        </div>

        <div class="card-body mt-4 mb-2 my-md-3 p-0">
          <div class="row">
            <div class="col-12 col-md-4">
              <strong>
                {{ trans('messages.home.checkin') }}
              </strong>
              <h4>
                {{ $reservation_details->checkin_dmy }}
              </h4>
              <p>
                @if($reservation_details->list_type == 'Rooms')
                {{ trans('messages.your_reservations.flexible_checkin_time') }}
                @endif
              </p>
            </div>

            <div class="col-12 col-md-4">
              <strong>
                {{ trans('messages.home.checkout') }}
              </strong>
              <h4>
                {{ $reservation_details->checkout_dmy }}
              </h4>
              <p>
                @if($reservation_details->list_type == 'Rooms')
                {{ trans('messages.your_reservations.flexible_checkout_time') }}
                @endif
              </p>
            </div>

            <div class="col-12 col-md-2 d-flex d-md-block">
              <h4>
                {{ $reservation_details->duration }}
              </h4>
              <p class="ml-2 ml-md-0">
                {{ ucfirst($reservation_details->duration_type_text) }}
              </p>
            </div>

            <div class="col-12 col-md-2 d-flex d-md-block">
              <h4>
                {{ $reservation_details->number_of_guests }}
              </h4>
              <p class="ml-2 ml-md-0">
                {{ trans_choice('messages.home.guest',1) }}
              </p>
            </div>
          </div>
        </div>

        <div class="card-body my-md-3 p-0">
          <div class="row">
            <div class="col-12 col-md-6 flex-column">
              <h4 class="mb-1 text-capitalize">
                <a href="{{ $reservation_details->rooms->link }}" class="{{$reservation_details->title_class}}">
                  {{ $reservation_details->rooms->name }}
                </a>
              </h4>

              @if($reservation_details->rooms->rooms_address->address_line_1  != '')
              <label>
                {{ $reservation_details->rooms->rooms_address->address_line_1 }}
                <br>
                @endif
                @if($reservation_details->rooms->rooms_address->city  != '')
                {{ $reservation_details->rooms->rooms_address->city }} , @endif
                @if($reservation_details->rooms->rooms_address->state  != '')
                {{ $reservation_details->rooms->rooms_address->state }} <br> @endif
                @if($reservation_details->rooms->rooms_address->country_name  != '')
                {{ $reservation_details->rooms->rooms_address->country_name }} <br> @endif 
              </label>
            </div>

            <div class="col-12 col-md-6 d-flex align-items-center">
              <a href="{{ url('/') }}/users/show/{{ $reservation_details->user_id }}" class="profile-img">
                <img alt="{{ $reservation_details->users->first_name }}" src="{{ $reservation_details->users->profile_picture->src }}" title="{{ $reservation_details->users->first_name }}">
              </a>
              <div class="card-body">
                <strong class="d-block">
                  <a class="normal-link" href="{{ url('/') }}/users/show/{{ $reservation_details->user_id }}">
                    {{ $reservation_details->users->full_name }}
                  </a>
                </strong>
                @if($reservation_details->status == 'Accepted')
                <p>
                  <a class="text-nowrap theme-link" href="mailto:{{$reservation_details->users->email}}">
                    {{ trans('messages.your_reservations.contact_by_email') }}
                  </a>
                </p>
                <p>
                  {{$reservation_details->users->primary_phone_number}}
                </p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex row">
          <div class="col-12 col-md-6">
            <div class="mt-4">
              <h4>
                {{ trans('messages.payments.payment') }}
              </h4>
              {{ trans('messages.your_reservations.see_transaction_history') }}
            </div>

            <div class="mt-4">
              <h4>
                {{ trans('messages.payments.cancellation_policy') }}
              </h4>
              @if($reservation_details->cancellation == 'Flexible')
              {{ trans('messages.your_reservations.flexible_desc') }}
              @elseif($reservation_details->cancellation == 'Moderate')
              {{ trans('messages.your_reservations.moderate_desc') }}
              @elseif($reservation_details->cancellation == 'Strict')
              {{ trans('messages.your_reservations.Strict_desc') }}
              @endif
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="mt-4">
              <h4>
                {{ trans('messages.account.payout') }}
              </h4>
              <table class="table payment-table mt-3">
                <tbody> 
                  <tr>
                    <th class="receipt-label">
                      {{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->base_per_night }} x 
                      {{ $reservation_details->subtotal_multiply_text }}
                    </th>
                    <td class="receipt-amount">
                      {{ html_string($reservation_details->currency->symbol) }}{{ ($reservation_details->base_per_night*($reservation_details->list_type == 'Experiences' ? $reservation_details->number_of_guests : $reservation_details->nights)) }}
                    </td>
                  </tr>
                  @if($reservation_details->special_offer_id == '' || @$reservation_details->special_offer_details->type == 'pre-approval')
                  @foreach($reservation_details->discounts_list as $list)
                  <tr>
                    <th class="receipt-label">
                      {{ @$list['text'] }}
                    </th>
                    <td class="receipt-amount">
                      -{{  html_string($reservation_details->currency->symbol) }}{{@$list['price'] }}
                    </td>
                  </tr>
                  @endforeach
                  @if($reservation_details->additional_guest != 0)
                  <tr>
                    <th class="receipt-label">
                      {{ trans('messages.your_reservations.additional_guest') }}
                    </th>
                    <td class="receipt-amount">
                      {{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->additional_guest }}
                    </td>
                  </tr>
                  @endif
                  @if($reservation_details->cleaning != 0)
                  <tr>
                    <th class="receipt-label">
                      {{ trans('messages.your_reservations.cleaning_fee') }}
                    </th>
                    <td class="receipt-amount">
                      {{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->cleaning }}
                    </td>
                  </tr>
                  @endif
                  @endif
                  @if($reservation_details->host_fee != 0)
                  <tr>
                    <th class="receipt-label">
                      {{ $site_name }} {{ trans('messages.your_reservations.host_fee') }}
                    </th>
                    <td class="receipt-amount">
                      ({{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->host_fee }})
                    </td>
                  </tr>
                  @endif
                  @if($penalty != '')
                  <tr>
                    <th class="receipt-label">
                      {{ trans('messages.your_reservations.penalty_amount') }}
                    </th>
                    <td class="receipt-amount">
                      ({{ html_string($reservation_details->currency->symbol) }}{{$penalty}})
                    </td>
                  </tr>
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th class="receipt-label">
                      {{ trans('messages.your_reservations.total_payout') }}
                    </th>
                    <td class="receipt-amount">
                      {{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->host_payout }}
                    </td>
                  </tr>
                  @if($reservation_details->security > 0)
                  <tr>
                    <th class="receipt-label">
                      {{ trans('messages.your_reservations.security_fee') }}
                    </th>
                    <td class="receipt-amount">
                      {{  html_string($reservation_details->currency->symbol) }}{{$reservation_details->security }}
                    </td>
                  </tr>
                  @endif
                </tfoot>
              </table>
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
@extends('template')
@section('main')
<main role="main" id="site-content">
  <div class="receipt-content py-4 py-md-5">
    <div class="container">
      <div class="mb-3" id="receipt-id">
        <ul class="receipt-info">
          <li>
            {{ @$reservation_details->receipt_date }}
          </li>
          <li>
            {{ trans('messages.your_trips.receipt') }} # {{ $reservation_details->id }}
          </li>
        </ul>
      </div>
      <div class="card">
        <div class="card-body d-md-flex">
          <div class="customer-receipt">
            <h2>
              {{ trans('messages.your_trips.customer_receipt') }}
            </h2>
            <label>
              {{ trans('messages.your_reservations.confirmation_code') }}
            </label>
            <h4>
              {{ $reservation_details->code }}
            </h4>
          </div>
          <div class="mt-3 mt-md-0 ml-auto d-print-none">
            <a id="print_receipt" onclick="print_receipt()" class="btn" href="#">
              {{ trans('messages.your_trips.print') }}
            </a>
          </div>
        </div>
        <div class="card-body border-top border-bottom">
          <div class="row mb-4">
            <div class="col-md-3">
              <label>
                {{ trans('messages.payments.name') }}
              </label>
              <p>
                {{ $reservation_details->users->full_name }}
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.payments.payment') }} {{ trans('messages.account.type') }}
              </label>
              <p>
                {{ $reservation_details->formatted_paymode }}
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.your_trips.travel_destination') }}
              </label>
              <p>
                @if($reservation_details->rooms->rooms_address->city != "")
                {{ $reservation_details->rooms->rooms_address->city }}
                @else
                {{ $reservation_details->rooms->rooms_address->state }}
                @endif
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.your_trips.duration') }}
              </label>
              <p>
                {{ $reservation_details->duration_text }}
              </p>
            </div>
            <div class="col-md-3">
              @if($reservation_details->list_type == 'Experiences')
              <label>
                {{ trans('experiences.manage.category') }}
              </label>
              <p>
                {{ $reservation_details->rooms->category_details->name }}
              </p>
              @else
              <label>
                {{ trans('messages.your_trips.accommodation_type') }}
              </label>
              <p>
                {{ $reservation_details->rooms->room_type_name }}
              </p>
              @endif
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.your_trips.guest_count') }}
              </label>
              <p>
                {{ $reservation_details->number_of_guests }}
              </p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <label>
                {{ trans('messages.your_trips.accommodation_address') }}
              </label>
              <p>
                {{ $reservation_details->rooms->name }}
              </p>
              <p>
                @if($reservation_details->rooms->rooms_address->address_line_1 !='')
                {{ $reservation_details->rooms->rooms_address->address_line_1 }}<br>
                @endif
                @if($reservation_details->rooms->rooms_address->city !='')
                {{ $reservation_details->rooms->rooms_address->city }}, 
                @endif
                @if($reservation_details->rooms->rooms_address->state !='')
                {{ $reservation_details->rooms->rooms_address->state }}
                @endif
                @if($reservation_details->rooms->rooms_address->postal_code !='')
                {{ $reservation_details->rooms->rooms_address->postal_code }}<br>
                @endif
                @if($reservation_details->rooms->rooms_address->country_name !='')
                {{ $reservation_details->rooms->rooms_address->country_name }}<br>
                @endif
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.your_trips.accommodation_host') }}
              </label>
              <p>
                {{ $reservation_details->rooms->users->full_name }}
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.home.checkin') }}
              </label>
              <p>             
                {{ $reservation_details->checkin_dmy }}<br>
                @if($reservation_details->list_type == 'Rooms')
                {{ trans('messages.your_reservations.flexible_checkin_time') }}
                @endif
              </p>
            </div>
            <div class="col-md-3">
              <label>
                {{ trans('messages.home.checkout') }}
              </label>
              <p>
                {{ $reservation_details->checkout_dmy }}<br>
                @if($reservation_details->list_type == 'Rooms')
                {{ trans('messages.your_reservations.flexible_checkout_time') }}
                @endif
              </p>
            </div>
          </div>
        </div>
        <div class="card-body reservation-charges">
          <h2>
            {{ trans('messages.your_trips.reservation_charges') }}
          </h2>
          <table class="table table-bordered payment-table">
            <tbody>
              <tr>
                <th class="receipt-label">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->base_per_night }} x 
                  {{ $reservation_details->subtotal_multiply_text }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ ($reservation_details->base_per_night*($reservation_details->list_type == 'Experiences' ? $reservation_details->number_of_guests : $reservation_details->nights)) }}
                </td>
              </tr>
              @if(@$reservation_details->special_offer_id == '' || @$reservation_details->special_offer_details->type == 'pre-approval')
              @foreach($reservation_details->discounts_list as $list)
              <tr class="green-color">
                <th class="receipt-label">
                  {{ @$list['text'] }}
                </th>
                <td class="receipt-amount">
                  -{{ html_string($reservation_details->currency->symbol) }}{{ @$list['price'] }}
                </td>
              </tr>
              @endforeach
              @if($reservation_details->additional_guest)
              <tr>
                <th class="receipt-label">
                  {{ trans('messages.rooms.addtional_guest_fee') }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->additional_guest }}
                </td>
              </tr>
              @endif
              @if($reservation_details->cleaning)
              <tr>
                <th class="receipt-label">
                  {{ trans('messages.your_reservations.cleaning_fee') }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->cleaning }}
                </td>
              </tr>
              @endif
              
              @endif
              @if($reservation_details->coupon_amount)
              <tr>
                <th class="receipt-label">
                  @if($reservation_details->coupon_code == 'Travel_Credit')
                  {{ trans('messages.referrals.travel_credit') }}
                  @else
                  {{ trans('messages.payments.coupon_amount') }}
                  @endif          
                </th>
                <td class="receipt-amount">
                  -{{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->coupon_amount }}
                </td>
              </tr>
              @endif
              <tr>
                <th class="receipt-label">
                  {{ $site_name }} {{ trans('messages.your_reservations.service_fee') }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->service }}
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th class="receipt-label">
                  {{ trans('messages.rooms.total') }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->total }}
                </td>
              </tr>
            </tfoot>
          </table>
          <table class="table table-bordered payment-table">
            <tbody>
              <tr>
                <th class="receipt-label">
                  {{ trans('messages.your_trips.payment_received') }}:
                  {{ $reservation_details->receipt_date }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->total }}
                </td>
              </tr>
              @if($reservation_details->security)
              <tr>
                <th class="receipt-label">
                  {{ trans('messages.your_reservations.security_fee') }}
                </th>
                <td class="receipt-amount">
                  {{ html_string($reservation_details->currency->symbol) }}{{ $reservation_details->security }}
                  <small>
                    ({{trans('messages.disputes.security_deposit_will_not_charge')}})
                  </small>
                </td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-4" id="legal-disclaimer">
        <p>
          {{ trans('messages.your_trips.authorized_to_accept',['site_name'=>$site_name]) }}
        </p>
      </div>
    </div>
  </div>
</main>
<script>
  function print_receipt()
  {
    window.print();
  }
</script>
@stop
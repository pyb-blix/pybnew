<!-- Center Part Starting  -->
<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-md-9" id="js-manage-listing-content">
    <div class="ib-better-off-state {{($result->booking_type == NULL) ? '' : 'd-none'}}" id="before_select">    
      <div class="content-heading my-4">
        <h3>
          {{ trans('messages.lys.booking_title') }}
        </h3>
        <p>
          {{ trans('messages.lys.booking_desc') }}
        </p>
      </div>
      <div class="row">
        <div class="col-md-6 booking_option">
          <div class="card h-100">
            <div class="card-body d-flex flex-column">
              <div class="booking-option-wrap d-flex align-items-center">
                <h4>
                  {{ trans('messages.lys.review_each_request') }}
                </h4>
                <img class="img-fluid ml-auto" src="{{url('/')}}/images/request-book-img.png">
              </div>
              <ul class="disc-type mt-2 mb-5">
                <li>
                  <span>
                    {{ trans('messages.lys.guests_send_booking_requests') }}
                  </span>
                </li>
                <li>
                  <span>
                    {{ trans('messages.lys.approve_decline_within_24hrs') }}
                  </span>
                </li>
              </ul>
              <div class="mt-auto">
                <button class="btn btn-block booking_but" ng-click="booking_select('request_to_book')">
                  {{ trans('messages.lys.select') }}
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 booking_option mt-4 mt-md-0">
          <div class="card h-100">
            <div class="card-body d-flex flex-column">
              <div class="booking-option-wrap d-flex align-items-center">
                <h4>
                  {{ trans('messages.lys.guest_book_instantly') }}
                </h4>
                <img class="img-fluid ml-auto" src="{{url('/')}}/images/instant-book-img.png">
              </div>
              <ul class="disc-type mt-2 mb-5">
                <li>
                  <span>
                    {{ trans('messages.lys.guests_book_without_needing_approval') }}
                  </span>
                </li>
              </ul>
              <div class="mt-auto">
                <button class="btn btn-block booking_but" ng-click="booking_select('instant_book')">
                  {{ trans('messages.lys.select') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="js-ib-state mt-4 instant-book-wrap {{ ($result->booking_type == 'request_to_book') ? '' : 'd-none' }}" id="request_to_book">
      <div data-placeholder="cya-header">
        <div class="js-section">
          <h3>
            {{ trans('messages.lys.guests_request_book') }}
          </h3>
          <p>
            <span>
              {{ trans('messages.lys.you_respond_request_within_24hrs') }}
            </span>
            <a class="theme-link" href="javascript:void(0);" ng-click="booking_change('request_to_book')">
              {{ trans('messages.lys.change') }}
            </a>
          </p>
        </div>
      </div>
      <div class="request-book-info text-center">
        <img src="{{ url('images/request-book-img.png') }}">      
        <h3>
          {{ trans('messages.lys.you_respond_request_within_24hrs') }}
        </h3>
        <p>
          {{ trans('messages.lys.request_book_with_confirmation') }}
        </p>
      </div>
    </div>
    <div class="ib-better-off-state mt-4 request-book-wrap {{ ($result->booking_type == 'instant_book') ? '' : 'd-none' }}" id="instant_book">
      <div class="js-section">
        <h3>
          {{ trans('messages.lys.instant_book') }}
        </h3>
        <p>
          <span>
            {{ trans('messages.lys.guests_book_without_sending_requests') }}
          </span>
          <a class="theme-link" href="javascript:void(0);" ng-click="booking_change('instant_book')">
            {{ trans('messages.lys.change') }}
          </a>
        </p>
        <div class="instant-book-info text-center">
          <img src="{{ url('images/instant-book-img.png') }}">
          <h3>
            {{ trans('messages.lys.more_reservations_less_work') }}
          </h3>
          <p>
            <span class="d-block">
              {{ trans('messages.lys.guests_book_with_click') }}
            </span>
            <span class="d-block">
              {{ trans('messages.lys.instant_book_often') }}
            </span>
          </p>
        </div>
        <div class="row text-center d-none">
          <div class="row">
            <strong>
              {{ trans('messages.lys.what_new_with_instant_book') }}
            </strong>
          </div>
          <div class="row">
            <div class="col-11">
              <div class="col-5 undefined">
                <div class="row">
                  <img src="{{ url('images/icon_safeguest@1x.png') }}">
                </div>
                <div class="row">
                  <p>
                    {{ trans('messages.lys.decide_who_stays') }}
                  </p>
                </div>
              </div>
              <div class="col-5">
                <div class="row">
                  <img src="{{ url('images/icon_houserules@1x.png') }}">
                </div>
                <div class="row">
                  <p>
                    {{ trans('messages.lys.agree_house_rules') }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-11">
              <div class="col-5 undefined">
                <div class="row">
                  <img src="{{ url('images/icon_clock@1x.png') }}">
                </div>
                <div class="row">
                  <p>
                    {{ trans('messages.lys.set_min_max_nights') }}
                  </p>
                </div>
              </div>
              <div class="col-5">
                <div class="row">
                  <img src="{{ url('images/icon_calendarsync@1x.png') }}">
                </div>
                <div class="row">
                  <p>
                    {{ trans('messages.lys.calendar_sync_fine_tuned') }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="js-ib-celebratory-msg-modal-container mt-4">
      <div class="row" ng-init='booking_message="{{ $result->booking_message }}"'>
        <div class="col-8">
          <h4>{{ trans('messages.payments.pre_booking_message') }}</h4>
        </div>
        <div class="js-saving-progress saving-progress booking-saving col-4 text-right" style="display: none;">
          <h5>{{ trans('messages.lys.saving') }}...</h5>
        </div>
      </div>
      <textarea class="overview-summary" name="booking_message" rows="4" placeholder="{{ trans('messages.payments.say_hi_guest') }}" ng-model="booking_message" data-saving="booking-saving"></textarea>
    </div>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        @if($result->status != NULL)
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="back-section-button">{{ trans('messages.lys.back') }}
        </a>
        @endif
      </div>
      <div class="next_step">
        <a class="btn btn-primary next-section-button" href="{{ url('manage-listing/'.$room_id.'/basics') }}" data-prevent-default="">
          {{ trans('messages.lys.next') }}
        </a>
      </div>
    </div>
    <div id="js-manage-listing-help" class="manage-listing-help d-none"></div>
  </div>
</div>
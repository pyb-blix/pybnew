@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="footer">

  <div class="referrals-wrap py-4">
    <div class="container">
      <h1>
        {{ trans('messages.referrals.earn_up_to') }} {{ html_string($result->value(5)) }}{{ $result->value(2) + $result->value(3) }} {{ trans('messages.referrals.everyone_invite') }}.
      </h1>
    </div>
  </div>

  <div class="referrals-info my-4">
    <div class="container p-0">
      <div class="credit-info col-12 text-center">
        <p>
          {{ trans('messages.referrals.send_friend') }} {{ html_string($result->value(5)) }}{{ $result->value(4) }} {{ $site_name }} {{ trans('messages.referrals.credit_you_will_get') }} {{ html_string($result->value(5)) }}{{ $result->value(2) }} {{ trans('messages.referrals.when_they_travel') }} {{ html_string($result->value(5)) }}{{ $result->value(3) }} {{ trans('messages.referrals.when_they_host') }}.
        </p>
      </div>

      <div id="share-container">
        <div id="email-entry" class="d-md-flex my-2">
          <div class="col-12 col-md-6">
            <div class="input-addon d-flex">
              <div class="referral-mail flex-grow-1">
                <input type="text" class="typeahead tt-hint" autocorrect="off" readonly="" autocomplete="off" spellcheck="false" tabindex="-1" dir="ltr">
                <input id="email-list" type="text" class="typeahead tt-input" autocorrect="off" placeholder="{{ trans('messages.referrals.add_friends_email') }}" autocomplete="off" spellcheck="false" dir="auto"
                >
              </div>
              <pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Circular, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></pre>
              <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
                <div class="tt-dataset tt-dataset-email-autocomplete"></div>
              </div>
              <a href="javascript:void(0)" class="btn btn-primary ml-2" id="send-email">
                {{ trans('messages.inbox.send') }}
              </a>
            </div>
            <p class="instructions-text">
              <span id="info_message" class="info-msg">
                {{ trans('messages.referrals.separate_email_commas') }}.
              </span>
              <span id="success_message" class="success-msg">
                {{ trans('messages.referrals.invitation_sent') }}!
              </span>
              <span id="error_message" class="error-msg">
                {{ trans('messages.referrals.emails_invalid') }}!
              </span>
            </p>
          </div>
          <div class="referral-link-share col-12 col-md-6 mt-3 mt-md-0">
            <div class="input-addon d-flex">
              <input id="share-link" type="text" value="{{ url('c/'.$username) }}" readonly="">
              <a class="btn btn-primary fb-btn ml-2 text-nowrap d-flex align-items-center" data-network="facebook" rel="nofollow" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ url('c/'.$username) }}" target="_blank">
                <i class="icon icon-facebook mr-1"></i>
                {{ trans('messages.inbox.facebook') }}
              </a>
            </div>
            <div class="instructions-text">
              <div class="social-share-widget invite_twitter">
                <span class="share-title">
                  {{ trans('messages.rooms.share') }}:
                </span>
                <span class="share-triggers">
                  <a class="share-btn link-icon" data-network="twitter" rel="nofollow" title="Twitter" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=Travel+on+{{ $site_name }}+and+get+{{ html_string($result->value(5)) }}{{ $result->value(4) }}+in+travel+credit!+{{ '@'.$site_name }}&amp;url={{ url('c/'.$username) }}" target="_blank">
                    <span class="screen-reader-only">Twitter</span>
                    <i class="icon icon-twitter social-icon-size"></i>
                  </a>
                  <span class="d-none more-btn">
                    ···
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="sent-referrals col-12 my-4">
        <h3 class="text-center">
          <span>
            {{ trans('messages.referrals.you_have_got') }} 
          </span>
          <span class="credit-amount">
            {{ html_string($result->value(5)) }}{{ $credited_amount }}
          </span>
          <span> 
            {{ trans('messages.referrals.travel_credit_spend') }}!
          </span>
        </h3>
        @if($result->value(1) <= $creditable_amount)
        <p class="text-center mt-2 error-msg">
          {{trans('messages.referrals.reached_max_amount', ['amount' => html_entity_decode($result->value(5)).$result->value(1)])}}
        </p>
        @endif

        @foreach($referrals as $row)
        <div class="card sent-referral border-0">
          <div class="card-body d-flex align-items-center py-2 py-md-4 px-0">
            <div class="profile-img">
              <a href="{{ url('users/show/'.$row->friend_users->id) }}">
                <img src="{{ $row->friend_users->profile_picture->header_src }}" alt="{{ $row->friend_users->first_name }}" class="img-fluid w-100">
              </a>
            </div>
            <div class="my-2 referred-row flex-grow-1">
              <div class="referred-row-body d-flex align-items-center w-100">
                <div class="col-7 col-md-9">
                  <span class="text-wrap">
                    <strong>
                      {{ $row->friend_users->first_name }}
                    </strong>
                  </span>
                </div>
                <div class="col-5 col-md-3 pending-price text-right">
                  @if($row->creditable_amount > 0) 
                  <span>
                    {{ html_string($row->currency->symbol) }}{{ $row->if_friend_guest_amount + $row->if_friend_host_amount }} {{ trans('messages.referrals.pending') }}
                  </span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</main>
@stop
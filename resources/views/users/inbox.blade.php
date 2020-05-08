@extends('template')
@section('main')
@section('inbox_address')
  <span class="inbox-msg" ng-class="(all.read == '1') ? '' : 'unread_message'"> @{{ all.message }} </span>
  <span ng-if="all.user_to != all.user_from">
    
    <span ng-if="all.list_type=='Rooms'">

      <span class="street-address" ng-if="all.rooms.rooms_address.address_line_1 || all.rooms.rooms_address.address_line_2">
      @{{ all.rooms.rooms_address.address_line_1 }} @{{ all.rooms.rooms_address.address_line_2 }},
      </span>
      <span class="locality" ng-if= "all.rooms.rooms_address.city">
        @{{ all.rooms.rooms_address.city }},
      </span>
      <span class="region">
        @{{ all.rooms.rooms_address.state }}
      </span>
      <span class="text-nowrap" ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">  
        (@{{ all.reservation.dates_subject  }})
      </span>
    </span>

    <span ng-if="all.list_type=='Experiences'">
      <span class="street-address" ng-if="all.host_experience.rooms_address.address_line_1 || all.host_experience.rooms_address.address_line_2">
      @{{ all.host_experience.rooms_address.address_line_1 }} @{{ all.host_experience.rooms_address.address_line_2 }},
      </span>
      <span class="locality" ng-if= "all.host_experience.rooms_address.city">
        @{{ all.host_experience.rooms_address.city }},
      </span>
      <span class="region">
        @{{ all.host_experience.rooms_address.state }}
      </span>
      <span class="text-nowrap" ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">  
        (@{{ all.reservation.dates_subject  }})
      </span>
    </span>

  </span>
  <span class="msg-count" ng-if="all.inbox_thread_count > 1">
    <i ng-cloak class="text-center inbox_message_count">@{{ all.inbox_thread_count }}</i>
  </span>
@endsection
<main id="site-content" role="main" ng-controller="inbox">
  @include('common.subheader')
  <div class="container">
    <div id="inbox" class="inbox-wrap mt-4" ng-cloak>
      <div class="card">
        <div class="card-header">
          <form accept-charset="UTF-8" action="" class="col-md-4 p-0" id="inbox_filter_form" method="get">
            <input name="utf8" type="hidden" value="✓">
            <div class="select">
              <select id="inbox_filter_select" name="filter" ng-cloak>
                <option value="all" selected="selected">
                  {{ trans('messages.dashboard.all_messages') }} (@{{message_count.all_message_count}})
                </option>
                <option value="starred">
                  {{ trans('messages.inbox.starred') }} (@{{message_count.stared_count}})
                </option>
                <option value="unread">
                  {{ trans('messages.inbox.unread') }} (@{{message_count.unread_count}})
                </option>
                <option value="reservations">
                  {{ trans('messages.inbox.reservations') }} (@{{message_count.reservation_count}})
                </option>
                <option value="pending_requests">
                  {{ trans('messages.inbox.pending_requests') }} (@{{message_count.pending_count}})
                </option>
                <option value="hidden">
                  {{ trans('messages.inbox.archived') }} (@{{message_count.archived_count}})
                </option>
                <option value="admin_messages">
                  {{ trans('messages.inbox.admin_messages') }} (@{{message_count.admin_messages}})
                </option>
              </select>
            </div>
            <input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }}">
            <input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }}">
          </form>
        </div>
      </div>
      <input type="hidden" ng-model="user_id" ng-init="user_id = {{ $user_id }};">
      <!-- Inbox Thread -->
      <ul class="inbox-list border-top-0 loading" style="height: 100px;">
        <li class="d-flex py-3 py-md-4" ng-repeat="all in message_result.data" ng-class="(all.read == '1') ? 'readed' : ''" ng-cloak>
          <!-- Profile Picture -->
          <div class="col-3 col-md-2 col-lg-1 list-img">
            <!-- Admin -->
            <a ng-if="all.user_to == all.user_from" href="#">
              <img height="50" width="50" title="@{{ all.admin_name }}" src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="media-round media-photo" alt="@{{ all.admin_name }}">
            </a>
            <!-- Admin -->
            <a ng-if="all.user_to != all.user_from" href="{{ url('users/show/')}}/@{{ all.user_details.id }}">
              <img title="@{{ all.user_details.first_name }}" ng-src="@{{all.user_details.profile_picture.src }}" alt="@{{ all.user_details.first_name }}">
            </a>
          </div>
          <!-- Profile Picture -->
          <div class="col-9 col-md-10 col-lg-11 p-0 d-md-flex mt-1">
            <!-- Name, Date And Time -->
            <div class="list-name pl-md-0 pl-lg-2 col-12 col-md-3">
              <h3 class="text-truncate">
              @{{ all.user_to == all.user_from ? all.admin_name : all.user_details.first_name }}
              </h3>
              <span class="list-date">
                @{{ all.created_time }}
              </span>
            </div>
            <!-- Name, Date And Time -->
            <!-- Conversation and Address Details -->
            <div class="reserve-link col-12 col-md-4 my-1 my-md-0" ng-if="all.user_from != all.user_to">
              @{{all.reservation.rooms}}
              <!-- Pending Inquires / Reservations -->
              <a ng-if="all.host_check == 1 && all.reservation.status == 'Pending'" href="{{ url('reservation')}}/@{{ all.reservation_id }}">
                @yield('inbox_address')
              </a>
              <!-- Host Messages -->
              <a ng-if="all.host_check == 1 && all.reservation.status != 'Pending'" href="{{ url('messaging/qt_with')}}/@{{ all.reservation_id }}">
                @yield('inbox_address')
              </a>

              <!-- Guest Messages -->
              <a ng-if="all.guest_check" href="{{ url('z/q')}}/@{{ all.reservation_id }}">
                @yield('inbox_address')
              </a>
            </div>

            <div class="reserve-link col-12 col-md-4" ng-if="all.user_from == all.user_to">
              @{{all.reservation.rooms}}

              <!-- Admin Room Resubmit Messages -->
              <a ng-if="all.room_id != 0" href="{{ url('messaging/admin')}}/@{{ all.reservation_id }}">
                @yield('inbox_address')
              </a>
              <!-- Admin Verification Messages -->
              <a ng-if="all.room_id == 0" href="{{ url('admin_messages')}}/@{{ all.user_to }}">
                @yield('inbox_address')
              </a>
            </div>
            <!-- Conversation and Address Details -->
            <!-- Message Status -->
            <div class="list-status col-12 col-md-3 my-1 my-md-0" ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">
              <span class="d-block label label label-info"
                ng-show="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}">
                <strong>
                {{trans('messages.dashboard.Expired')}}
                </strong>
              </span>
              <span class="d-block label label-@{{ all.reservation.status_color }}"
                ng-hide="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}">
                <strong>
                @{{ all.reservation.status_language }}
                </strong>
              </span>
              <span class=" price-breakdown-trigger" ng-if="all.reservation != null">
                <span ng-bind-html="all.reservation.currency.symbol"></span>
                <span ng-show="all.host_check"> @{{ all.reservation.subtotal - all.reservation.host_fee }} </span>
                <span ng-show="all.guest_check"> @{{ all.reservation.total }} </span>
              </span>
            </div>
            <!-- Message Status -->
            <!-- Message Actions -->
            <div class="col-md-2 pl-md-0 list-actions ml-auto">
              <ul>
                <li>
                  <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon d-flex align-items-center js-star-thread">
                    <i ng-show="all.star == 1" class="icon istar_@{{ all.user_from }} icon-star mr-2" ng-click="star($index, all.user_from,all.id,'Unstar')"></i>
                    <i ng-show="all.star == 0" class="icon iunstar_@{{ all.user_from }} icon-star-alt mr-2" ng-click="star($index, all.user_from,all.id,'Star')"></i>
                    <span ng-show="all.star == 1" class="thread-star link-icon__text star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Unstar')">
                      {{ trans('messages.inbox.unstar') }}
                    </span>
                    <span ng-show="all.star == 0" class="thread-star link-icon__text un_star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Star')">
                      {{ trans('messages.inbox.star') }}
                    </span>
                  </a>
                </li>
                <li>
                  <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon d-flex align-items-center js-archive-thread">
                    <i ng-show="all.archive == 1" class="icon icon-archive mr-2" ng-click="archive($index,all.user_from ,all.id,'Unarchive')"></i>
                    <i ng-show="all.archive == 0" class="icon icon-archive mr-2" ng-click="archive($index, all.user_from,all.id,'Archive')"></i>
                    <span ng-show="all.archive == 0" class="link-icon__text user_from_@{{all.user_from}}" ng-click="archive($index, all.user_from,all.id,'Archive')">
                      {{ trans('messages.inbox.archive') }}
                    </span>
                    <span ng-show="all.archive == 1" class="link-icon__text un_user_from_@{{all.user_from}}" ng-click="archive($index,all.user_from ,all.id,'Unarchive')">
                      {{ trans('messages.inbox.unarchive') }}
                    </span>
                  </a>
                </li>
              </ul>
            </div>
            <!-- Message Actions -->
          </div>
        </li>
      </ul>
      <!-- Inbox Thread -->
    </div>
    <div class="result-footer my-4">
      <div class="pagination-buttons-container" ng-cloak>
        <div class="text-right my-3">
          <p>
            <span> @{{ message_result.from }} – @{{ message_result.to }} </span>
            <span> of </span>
            <span> @{{ message_result.total }} </span>
            <span> {{ trans_choice('messages.dashboard.message',2) }} </span>
          </p>
        </div>
      <posts-pagination></posts-pagination>
    </div>
  </div>
</div>
</main>
@stop
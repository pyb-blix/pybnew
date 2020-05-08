@extends('template')
@section('main')
<main id="site-content" role="main">
  @include('common.subheader')
  <div class="guest-dashboard my-4" id="guest-dashboard-container">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-3">
          <div class="profile-img">
            <a href="{{ url('users/show/'.Auth::user()->id) }}" title="{{ trans('messages.dashboard.view_profile') }}">
              {!! Html::image(Auth::user()->profile_picture->src, Auth::user()->first_name, ['class'=>'img-fluid', 'width' => '', 'height'=>'', 'title' => Auth::user()->first_name]) !!}
            </a>          
            <a class="upload-profile-photo btn btn-contrast d-flex align-items-center justify-content-center" href="{{ url('users/edit/media') }}">
              <i class="icon icon-camera mr-2"></i>
              {{ trans('messages.dashboard.add_profile_photo') }}
            </a>   
          </div>
          <div class="profile-info p-4 border-top-0 text-center">
            <h2>
              {{ Auth::user()->first_name }}
            </h2>
            <a class="theme-link" href="{{ url('users/show/'.Auth::user()->id) }}">
              {{ trans('messages.dashboard.view_profile') }}
            </a>
            @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
            <a href="{{ url('users/edit') }}" class="btn btn-primary d-block mt-2" id="edit-profile">
              {{ trans('messages.dashboard.complete_profile') }}
            </a>
            @endif
          </div>
          @if(Auth::user()->users_verification->show() || Auth::user()->verification_status == 'Verified')
          <div class="card mt-4 verification-panel">
            <div class="card-header">
              {{ trans('messages.dashboard.verifications') }}
            </div>
            <div class="card-body">
              <ul>
                @if(Auth::user()->verification_status == 'Verified')
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
                @if(Auth::user()->users_verification->email == 'yes')
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
                @if(Auth::user()->users_verification->phone_number == 'yes')
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
                @if(Auth::user()->users_verification->facebook == 'yes')
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
                @if(Auth::user()->users_verification->google == 'yes')
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
                @if(Auth::user()->users_verification->linkedin == 'yes')
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

        <div class="col-md-8 col-lg-9 notify-msg">
          <div class="card mt-4 mt-md-0">
            <div class="card-header">
              <span>
                {{ trans('messages.dashboard.welcome') }} {{ $site_name }},
              </span> 
              <strong>
                {{Auth::user()->first_name }}!
              </strong>
            </div>
            <div class="card-body">
              <p>
                {{ trans('messages.dashboard.welcome_desc') }} @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '') {{ trans('messages.dashboard.welcome_ask_to_complete_profile') }} @endif
              </p>
              @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
              <div class="mt-3">
                @if(Auth::user()->profile_picture->src == '' || Auth::user()->about == '')
                <strong>
                  <a class="theme-link" href="{{ url('users/edit') }}">
                    {{ trans('messages.dashboard.complete_your_profile') }}
                  </a>
                </strong>
                <p>
                  {{ trans('messages.dashboard.complete_your_profile_desc') }}
                </p>
                @endif
              </div>
              @endif
            </div>
          </div>

          @if(Auth::user()->users_verification->email == 'no')
          <div class="card mt-4">
            <div class="card-header">
              {{ trans_choice('messages.header.notification',2) }}
            </div>
            <div class="card-body">
              @if(Auth::user()->users_verification->email == 'no')
              <p>
                {{ trans('messages.dashboard.confirm_your_email') }} 
                <a class="theme-link" href="{{ url('users/request_new_confirm_email') }}">
                  {{ trans('messages.dashboard.request_confirmation_email') }}
                </a> 
                {{ trans('messages.login.or') }} 
                <a class="theme-link" href="{{ url('users/edit') }}">
                  {{ trans('messages.dashboard.change_email_address') }}.
                </a>
              </p>
              @endif
            </div>
          </div>
          @endif

          <div class="card mt-4">
            <div class="card-header">
              {{ trans_choice('messages.dashboard.message',2) }} ({{$all_message->count()}} {{ trans('messages.dashboard.new') }})
            </div>
            <div class="card-body px-0">
              <ul class="col-12 list-layout">
                @foreach($all_message as $all)
                  @if($all->user_to == $all->user_from)
                    <li id="thread_{{ $all->id }}" class="d-flex">
                      <div class="col-3 col-md-2 col-lg-1 list-img text-center p-0">           
                        <a data-popup="true" href="#" class="profile-image">
                          <img title="{{ $all->admin_name }}" src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="media-round media-photo" alt="{{ $all->admin_name }}">
                        </a>
                      </div>
                      <div class="col-9 col-md-10 col-lg-11 p-0 d-md-flex mt-1 text-md-center">
                        <div class="list-name col-12 col-md-3">
                          <h3 class="text-truncate"> {{ $all->admin_name }} </h3>
                          <span class="list-date"> {{ $all->created_time }} </span>
                        </div>
                        <div class="reserve-link col-12 col-md-6">
                          <a href="{{ ($all->reservation == null && $all->room_id != 0) ? route('admin_resubmit_message',['id' => $all->reservation_id]) : route('admin_messages',['id' => $all->user_to])  }}">
                            <span class="list-subject unread-message ng-binding">
                              {{ @$all->message }}
                            </span>
                            @if(@$all->inbox_thread_count > 1)
                              <span class="msg-count">
                                <i class="alert-count1 text-center inbox_message_count">
                                  {{ $all->inbox_thread_count }}
                                </i>
                              </span>
                            @endif
                          </a>
                        </div>
                      </div>
                    </li>
                  @else
                    <li id="thread_{{ $all->id }}" class="d-flex">
                      <div class="col-3 col-md-2 col-lg-1 list-img text-center p-0">           
                        <a data-popup="true" href="#" class="profile-image">
                          <img title="{{ $all->user_details->first_name }}" src="{{ $all->user_details->profile_picture->src }}" class="media-round media-photo" alt="{{ $all->user_details->first_name }}">
                        </a>
                      </div>

                      <div class="col-9 col-md-10 col-lg-11 p-0 d-md-flex mt-1 text-md-center">
                        <div class="list-name col-12 col-md-3">
                          <h3 class="text-truncate">  {{ $all->user_details->first_name }} </h3>
                          <span class="list-date">  {{ $all->created_time }} </span>
                        </div>

                        <div class="reserve-link col-12 col-md-6">
                          @if($all->list_type=='Rooms')
                          <a href="{{ url('z/q')}}/{{ @$all->reservation_id }}">
                          @else
                          <a href="{{ url('messaging/qt_with/')}}/{{ @$all->reservation_id }}">
                          @endif

                            <span class="list-subject unread-message ng-binding">
                              {{ @$all->message }}
                            </span>

                            <span class="street-address">
                              {{ @$all->reservation->rooms->rooms_address->address_line_1 }} {{ @$all->reservation->rooms->rooms_address->address_line_2 }},
                            </span>
                            <span class="locality">{{ @$all->reservation->rooms->rooms_address->city }},</span> 
                            <span class="region">{{ @$all->reservation->rooms->rooms_address->state }}</span>

                            @if($all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
                            <span class="check-date d-inline-block"> 
                              ({{ $all->reservation->dates_subject }})
                            </span>
                            @endif
                            @if(@$all->inbox_thread_count > 1)
                              <span class="msg-count">
                                <i class="alert-count1 text-center inbox_message_count">
                                  {{ $all->inbox_thread_count }}
                                </i>
                              </span>
                            @endif
                          </a>
                        </div>

                        @if($all->reservation->list_type != 'Experiences' || $all->reservation->type != 'contact' )
                          <div class="list-status col-12 col-md-3">
                            <span class="d-block label label-{{ $all->reservation->status_color }}">
                              <strong>  {{ $all->reservation->status_language }} </strong>
                            </span>
                            <span> {{ html_string($all->reservation->currency->original_symbol) }} {{ $all->reservation->total }} </span>
                          </div>
                        @endif
                      </div>
                    </li>
                  @endif
                @endforeach
              </ul>
              <div class="text-center">
                <a class="theme-link" href="{{ url('inbox') }}">
                  {{ trans('messages.dashboard.all_messages') }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop    
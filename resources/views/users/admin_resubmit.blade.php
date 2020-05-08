@extends('template')
@section('main')
<main id="site-content" role="main">
  @include('common.subheader')  
  <div class="dispute-reason my-4 my-md-5">
    <div class="container">
      <h2 class="conversation_head">
        {{ trans('messages.inbox.resubmit_reasons') }} 
      </h2>
      <a class="theme-link" href="{{ url('manage-listing/'.$messages[0]->room_id.'/basics')}}">
        {{$messages[0]->rooms->name}} 
        @if(isset($messages[0]->rooms_address))
        (
        @if(isset($messages[0]->rooms_address->address_line_1))
        {{$messages[0]->rooms_address->address_line_1}} {{$messages[0]->rooms_address->address_line_2}}, 
        @endif
        @if(isset($messages[0]->rooms_address->city))
        {{$messages[0]->rooms_address->city}}, 
        @endif
        {{$messages[0]->rooms_address->state}}
        )
        @endif
      </a>
      <p class="resubmit-info mt-1">
        {{ trans('messages.inbox.resubmit_info') }} 
      </p>
      <div class="host-conversation mt-4 mt-md-5">
        <span class="thread-list-item" id="message_friction_react"></span>
        <ul class="host_ul" id="thread-list">
          @for($i=0; $i < count($messages); $i++)
          <li id="question2_post_{{ $messages[$i]->id }}" class="thread-list-item">
            <div class="row">
              <div class="col-3 col-md-2 profile-image text-center">
                <img title="Admin" class="media-photo media-round" src="{{ url('admin_assets/dist/img/avatar04.png') }}" alt="Admin">
                <p>
                  Admin
                </p>
              </div>
              <div class="col-9 col-md-10">
                <div class="card custom-arrow left">
                  <div class="card-body">
                    <p>
                      {{ $messages[$i]->message}}
                    </p>
                  </div>
                </div>
                <div class="time-container">
                  <small title="{{ $messages[$i]->created_at }}" class="time">
                    {{ $messages[$i]->created_time }}
                  </small>
                </div>
              </div>
            </div>
          </li>
          @endfor
        </ul>
      </div>
    </div>
  </div>
</main>
@stop
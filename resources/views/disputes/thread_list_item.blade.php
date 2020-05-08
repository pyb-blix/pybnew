<div class="row post">
    @if($message->sender_or_receiver == 'Sender')
    <div class="col-3 col-lg-2 text-center name_break user_msg_detail1">
        <div class="media-photo media-round">
            <img src="{{ $message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
        <p>
            {{$message->message_sender_details->get('name')}}
        </p>
    </div>
    <div class="col-9 col-lg-10 user_msg_detail">
        <div class="panel-quote-flush panel-quote panel panel-quote-left p-0">
            <div class="card-body">
                <div class="message-text">
                    <span>
                        {{$message->message}}
                    </span>
                </div>
                <div class="dispute-to-user text-right my-2">
                    <span>
                        -{{trans('messages.payments.to')}} {{$message->message_receiver_details->get('name')}}
                    </span>
                </div>
                @if($message->sub_text)
                <div class="inline-status text-branding mt-3 text-center">
                    <div class="horizontal-rule-text">
                        <span class="horizontal-rule-wrapper">
                            {{$message->sub_text}}                         
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="col-9 col-lg-10 user_msg_detail1">
        <div class="panel-quote-flush panel-quote panel panel-quote-right p-0">
            <div class="card-body">
                <div class="message-text">
                    <span>
                        {{$message->message}}
                    </span>
                </div>

                @if($message->sub_text)
                <div class="inline-status text-branding mt-3 text-center">
                    <div class="horizontal-rule-text">
                        <span class="horizontal-rule-wrapper">
                            {{$message->sub_text}}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-3 col-lg-2 text-center name_break user_msg_detail">
        <div class="media-photo media-round">
            <img src="{{$message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
        <p>
            {{$message->message_sender_details->get('name')}}
        </p>
    </div>
    @endif
</div>
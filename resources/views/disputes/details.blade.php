@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="dispute_details">
    @include('common.subheader')
    <div class="dispute-content py-4 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 dispute_leftside">
                    <h3>
                        {{$dispute->inbox_subject}}
                    </h3>
                    @if($dispute->can_dispute_accept_form_show())
                    <div class="dispute_amount_accept_panel my-2">
                        <label class="label">{{trans('messages.disputes.include_a_message_for_user', ['first_name' => $dispute->user_or_dispute_user == 'User' ? $dispute->dispute_user->first_name : $dispute->user->first_name])}}:</label>
                        <textarea class="form-control" name="message" ng-model="accept_amount_data.message"> </textarea>
                        <p  ng-cloak class="text-danger">@{{accept_amount_form_errors.message[0]}}</p>
                        @if($dispute->is_pay())
                        <input type="hidden" name="payment_type" ng-model="accept_amount_data.payment" ng-init="accept_amount_data.payment = 'Pay'">
                        <button class="btn btn-host-banner" ng-click="accept_amount();">{{trans('messages.disputes.pay')}} {{html_string($dispute->currency->symbol)}}{{$dispute->final_dispute_data->get('amount')}}</button>
                        @else
                        <input type="hidden" name="payment_type" ng-model="accept_amount_data.payment" ng-init="accept_amount_data.payment = 'Accept'">
                        <button class="btn btn-host-banner" ng-click="accept_amount();">{{trans('messages.disputes.accept')}} {{html_string($dispute->currency->symbol)}}{{$dispute->final_dispute_data->get('amount')}}</button>
                        @endif
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12 my-4">
                            <div class="row">
                                <div class="col-12 dispute_conversation_details">
                                    <div class="panel-default panel">
                                        <div class="panel-header">
                                            <ul class="tabs tabs-header">
                                                <li>
                                                    <a href="javascript:void(0)" data-target="keep_talking" class="tab-item" aria-selected="true">{{trans('messages.disputes.keep_talking')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" data-target="involve_site" class="tab-item">{{trans('messages.disputes.involve_site', ['site_name' => $site_name])}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="panel-body" ng-cloak id="dispute_controls_area">
                                            <input type="hidden" name="dispute_id" id="dispute_id" value="{{$dispute->id}}" >
                                            <div class="tabs-content">
                                                <div class="tab-panel" data-tab_content="keep_talking">
                                                    <div class="row">
                                                        @if(($dispute->status == 'Open' || $dispute->status == 'Processing') && $dispute->payment_status == null)
                                                        <div class="col-12 my-2">
                                                            <p class="space-1">{{trans('messages.disputes.offer_a_different_amount')}}:</p>
                                                            <div class="input-addon">
                                                                <span class="input-prefix">{{html_string($dispute->currency->symbol)}} </span>
                                                                <input type="text" name="amount" class="input-stem input-large form-control" ng-model="dispute_message.amount">
                                                            </div>
                                                            <p class="text-danger">@{{dispute_message_form_errors.amount[0]}}</p>
                                                        </div>
                                                        @endif
                                                        <div class="col-12 my-2">
                                                            <textarea class="form-control" name="message" ng-model="dispute_message.message"> </textarea>
                                                            <p class="text-danger">@{{dispute_message_form_errors.message[0]}}</p>
                                                        </div>
                                                        <div class="col-12 my-2">
                                                            <button class="btn btn-primary pull-right" type="button" ng-click="keep_talking()">{{trans('messages.disputes.keep_talking')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-panel" data-tab_content="close" aria-hidden="true"></div>
                                                <div class="tab-panel" data-tab_content="involve_site" aria-hidden="true">
                                                    <div class="row">
                                                        <div class="col-12 my-2">
                                                            <textarea class="form-control" name="message" ng-model="involve_site_data.message"> </textarea>
                                                            <p class="text-danger">@{{involve_site_form_errors.message[0]}}</p>
                                                        </div>
                                                        <div class="col-12 my-2">
                                                            <button class="btn btn-primary pull-right" type="button" ng-click="involve_site()">{{trans('messages.disputes.involve_site', ['site_name' => $site_name])}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 py-4" id="thread-list">
                            @foreach($dispute->dispute_messages->sortByDesc('id') as $message)
                            @include('disputes/thread_list_item', ['message' => $message])
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 dispute_rightside">
                    <div class="col-12 p-0 my-3">
                        <h5>
                            {{trans('messages.disputes.dispute_reason')}}
                        </h5>
                        <p>
                            {{$dispute->subject}}
                        </p>
                    </div>
                    <div class="col-12 p-0 my-3">
                        <h5>
                            {{trans('messages.disputes.reservation_information')}}
                        </h5>
                        <div class="listing cls_dis_rimg">
                            <div class="panel-image listing-img img-large">
                                <a href="{{ url('rooms/'.$dispute->reservation->room_id) }}" class="media-photo media-cover wishlist-bg-img" target="_blank">
                                    <img src="{{ $dispute->reservation->rooms->photo_name }}" width="639" height="426">
                                </a>
                            </div>
                            <div class="panel-body panel-card-section">
                                <div class="media">
                                    <a href="{{ url('users/show/'.$dispute->reservation->rooms->user_id) }}" target="_blank" class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" title="{{ $dispute->reservation->rooms->users->first_name }}">
                                        <img src="{{ $dispute->reservation->rooms->users->profile_picture->src }}" height="60" width="60" alt="{{ $dispute->reservation->rooms->users->first_name }}">
                                    </a>
                                    <div class="py-4" style="width: 100%">
                                        <a href="{{ url('rooms/'.$dispute->reservation->rooms->id) }}" class="text-normal" target="_blank">
                                            <div title="{{ $dispute->reservation->rooms->name }}" class="h5 listing-name text-truncate row-space-top-1">
                                                {{ $dispute->reservation->rooms->name }}
                                            </div>
                                        </a>
                                        <div class="">
                                            <span>{{$dispute->reservation->created_at_date}}</span>
                                            <span class="dot-cont">·</span>
                                            <span>{{$dispute->reservation->nights}} {{trans_choice('messages.rooms.night', $dispute->reservation->nights)}}</span>
                                            <span class="dot-cont">·</span>
                                            <span class="green-color">#{{$dispute->reservation->code}}</span>
                                            <br>
                                            <span>{{$dispute->reservation->guests_text}} </span>
                                            <span class="dot-cont">·</span>
                                            <span>{{html_string($dispute->currency->symbol)}}{{$dispute->reservation->total}}</span>
                                            <span class="dot-cont">·</span>
                                            <span class="green-color" class="label label-{{ $dispute->reservation->status_color }}">{{$dispute->reservation->status}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 p-0 my-3">
                        <h5>
                            {{trans('messages.disputes.attachments')}}
                        </h5>
                        <div class="mt-3">
                            {!! Form::open(['url' => route('upload_dispute_doc',['id' => $dispute->id]), 'id' => 'dispute_documents_form', 'files' => 'true']) !!}
                            <input type="file" name="documents[]" id="dispute_documents" class="form-control p-0" accept="image/*" multiple />
                            <p class="text-danger">
                                {{$errors->first('documents')}}
                            </p>
                            {!! Form::close() !!}
                        </div>
                        <div class="dispute-gallery">
                            <ul>
                                @foreach($dispute->dispute_documents as $dispute_doc)                            
                                <li>
                                    <div data-thumb="{{ $dispute_doc->file_url }}" data-src="{{ $dispute_doc->file_url }}" data-sub-html=".caption_{{ $dispute_doc->file_url }}">
                                        <img src="{{ $dispute_doc->file_url }}">
                                    </div>
                                    @if($dispute_doc->uploaded_by == @Auth::user()->id)
                                    <button type="button" id="{{ $dispute_doc->id}}" class="js-delete-photo-btn" data-toggle="modal" data-target="#delete_document-popup" ng-click="id='{{ $dispute_doc->id }}'">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($dispute->can_dispute_accept_form_show() && $dispute->is_pay())
    @include('disputes.payment_popup')
    @endif
    <div id="delete_document-popup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header h6 text-center">
                    @lang('messages.disputes.delete_document')
                </div>
                <div class="modal-body py-4">
                    <p> @lang('messages.disputes.delete_document_desc') </p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn"> @lang('messages.home.close') </button>
                    <button class="btn btn-primary" ng-click="delete_document()"> @lang('messages.lys.delete') </button>
                </div>
            </div>
        </div>
    </div>
</main>

@stop
@extends('template')
@section('main')
<main id="site-content" role="main" ng-cloak>
    @include('common.subheader')  
    <div class="listing-content my-4 my-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 side-nav">        
                    @include('common.sidenav')
                </div>
                <div class="col-12 col-md-9 listing-wrap mt-3 mt-md-0">
                    @if($listed_result->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3>
                                {{ trans('messages.your_listing.listed') }}
                            </h3>
                        </div>
                        <ul>
                            @foreach($listed_result as $row)
                            <li class="listing card-body">
                                <div class="row">
                                    <div class="col-5 col-md-3 col-lg-2 room-image pr-0">
                                        <a href="{{ url('rooms/'.$row->id) }}">
                                            {!! Html::image($row->photo_name, '', ['class' => 'img-fluid w-100']) !!}
                                        </a>
                                    </div>
                                    <div class="col-7 col-md-9 col-lg-10 p-0 d-md-flex room-info">
                                        <div class="col-12 col-md-7">
                                            <a href="{{ url('rooms/'.$row->id) }}">
                                                <h4>
                                                    {{ ($row->name == '') ? $row->sub_name : $row->name }}
                                                </h4>
                                            </a>
                                            <div class="actions mt-1">
                                                <a class="theme-link" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                                    {{ trans('messages.your_listing.manage_listing_calendar') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="list-btn-wrap col-12 col-md-5 mt-2 mt-md-0 d-lg-flex align-items-center">
                                            @if($row->steps_count == 0 && ( $row->status == NULL || $row->status == 'Pending'))
                                                <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                                    {{ trans('messages.your_listing.pending') }}
                                                </a>
                                            @elseif($row->steps_count == 0 && $row->status == 'Resubmit')
                                                <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                                    {{ trans('messages.profile.Resubmit') }}
                                                </a>
                                            @elseif($row->steps_count != 0)
                                                <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                                    {{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}
                                                </a>
                                            @else
                                            <div id="availability-dropdown" class="availability-dropdown-wrap d-flex align-items-center justify-content-md-end ml-auto mr-lg-2" data-room-id="div_{{ $row->id }}">
                                                <i class="dot mr-2 dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>
                                                <div class="select">
                                                    <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
                                                        <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>
                                                            {{ trans('messages.your_listing.listed') }}
                                                        </option>
                                                        <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>
                                                            {{ trans('messages.your_listing.unlisted') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <a class="btn btn-host step_count mt-2 mt-lg-0 disable_after_click" href="{{ url('listing/'.$row->id.'/duplicate') }}">
                                                {{trans('messages.rooms.duplicate')}}
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($unlisted_result->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                           <h3>
                            {{ trans('messages.your_listing.unlisted') }}
                        </h3>
                    </div>
                    <ul>
                        @foreach($unlisted_result as $row)
                        <li class="listing card-body">
                            <div class="row">
                             <div class="col-5 col-md-3 col-lg-2 room-image pr-0">
                                <a href="{{ url('rooms/'.$row->id) }}">
                                    {!! Html::image($row->photo_name, '', ['class' => 'img-fluid w-100']) !!}
                                </a>
                            </div>
                            <div class="col-7 col-md-9 col-lg-10 p-0 d-md-flex room-info">
                                <div class="col-12 col-md-7">
                                    <a href="{{ url('rooms/'.$row->id) }}">
                                        <h4>
                                            {{ ($row->name == '') ? $row->sub_name : $row->name }}
                                        </h4>
                                    </a>
                                    <div class="actions mt-1">
                                        <a class="theme-link" href="{{ url('manage-listing/'.$row->id.'/basics') }}">{{ trans('messages.your_listing.manage_listing_calendar') }}</a>
                                    </div>
                                </div>
                                <div class="list-btn-wrap col-12 col-md-5 mt-2 mt-md-0 d-lg-flex align-items-center">
                                    @if($row->steps_count == 0 && ($row->status == NULL || $row->status == 'Pending'))
                                    <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                        {{ trans('messages.your_listing.pending') }}
                                    </a>
                                    @elseif($row->steps_count == 0 && $row->status == 'Resubmit')
                                    <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                        {{ trans('messages.profile.Resubmit') }}
                                    </a>
                                    @elseif($row->steps_count != 0)
                                    <a class="btn ml-auto" href="{{ url('manage-listing/'.$row->id.'/basics') }}">
                                        {{ $row->steps_count }} {{ trans('messages.your_listing.steps_to_list') }}
                                    </a>
                                    @else
                                        <div id="availability-dropdown" class="availability-dropdown-wrap d-flex align-items-center justify-content-md-end ml-auto mr-lg-2" data-room-id="div_{{ $row->id }}">
                                        <i class="dot mr-2 dot-{{ ($row->status == 'Listed') ? 'success' : 'danger' }}"></i>
                                        <div class="select">
                                            <select class="room_status_dropdown" data-room-id="{{ $row->id }}">
                                                <option value="Listed" {{ ($row->status == 'Listed') ? 'selected' : '' }}>
                                                    {{ trans('messages.your_listing.listed') }}
                                                </option>
                                                <option value="Unlisted" {{ ($row->status == 'Unlisted') ? 'selected' : '' }}>{{ trans('messages.your_listing.unlisted') }}</option>
                                            </select>
                                        </div>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif  

                @if($unlisted_result->count() == 0 && $listed_result->count() == 0)
                <div class="card">
                    <div class="card-body">
                        <p>
                            {{ trans('messages.your_listing.no_listings') }}
                        </p>
                        <a href="{{ url('/') }}/rooms/new" class="btn list-your-space-btn becomebtn" id="list-your-space">
                            {{ trans('messages.your_listing.add_new_listings') }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div> 
</main>
@stop
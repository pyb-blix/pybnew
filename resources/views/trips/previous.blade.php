@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="cancel_reservation">
  @include('common.subheader')  
  <div class="previous-trip my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 side-nav">
          @include('common.sidenav')
        </div>
        <div class="col-12 col-md-9 previous-trip-info mt-3 mt-md-0">
          @if($previous_trips->count() == 0)
          <div class="card">
            <div class="card-header">
              <h3>
                {{ trans('messages.your_trips.previous_trips') }}
              </h3>
            </div>
            <div class="card-body">
              <p>
                {{ trans('messages.your_trips.no_previous_trips') }}
              </p>
              <form method="get" action="{{ url('/') }}/s" accept-charset="UTF-8">
                <div class="trip-search-bar d-flex">
                  <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" name="location" id="location" autocomplete="off" class="location">
                  <button id="submit_location" search_type="previous" class="btn btn-primary ml-3" type="submit">
                    {{ trans('messages.home.search') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
          @endif
          @if($previous_trips->count() > 0)
          <div class="card">
            <div class="card-header">
              <h3>
                {{ trans('messages.your_trips.previous_trips') }}
              </h3>
            </div>
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <th>
                      {{ trans('messages.your_reservations.status') }}
                    </th>
                    <th>
                      {{ trans('messages.your_trips.location') }}
                    </th>
                    <th>
                      {{ trans('messages.your_trips.host') }}
                    </th>
                    <th>
                      {{ trans('messages.your_trips.dates') }}
                    </th>
                    <th>
                      {{ trans('messages.your_trips.options') }}
                    </th>
                  </tr>
                  @foreach($previous_trips as $previous_trip)
                  @include('trips/trip_row', ['trip_row' => $previous_trip, 'trip_type' => 'Previous'])
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @include('trips/dispute_modal')
</main>
@stop
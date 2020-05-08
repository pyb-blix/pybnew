@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists">
  @include('common.wishlists_subheader')
  <div class="picks-list">
    <div class="container">
      @if($result->count() == 0)
      <h2 class="no-results my-4">
        {{ trans('messages.search.no_results_found') }}!
      </h2>
      @endif
      <div class="row">
        <ul class="wishlists-wrap d-md-flex flex-wrap mb-5">
          @foreach($result as $row)
          <li class="col-12 col-md-4">
            <a href="{{ url('wishlists/'.$row->id) }}" class="wishlist-bg-img" style="background-image:url('{{ @$row->saved_wishlists[0]->photo_name }}');">
              <div class="count-section mt-auto mb-3 col-12">
                @if($row->privacy)
                <i class="icon icon-lock"></i>
                @endif
                <h4>
                  {{ $row->name }}
                </h4>
                <span>
                  @if($row->rooms_count > 0)
                  {{ $row->rooms_count }} {{ trans('messages.header.home') }}
                  @endif
                  @if($row->rooms_count > 0 && $row->host_experience_count > 0)
                  .
                  @endif
                  @if($row->host_experience_count > 0)
                  {{ $row->host_experience_count }} {{ trans_choice('messages.home.experience',$row->host_experience_count) }}
                  @endif
                </span>
              </div>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</main>
@stop
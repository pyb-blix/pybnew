@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="wishlists" ng-cloak>
  @include('common.wishlists_subheader')
  <div class="wishlists-content my-4 my-md-5">
    <div class="container">
      <div class="d-md-flex row">
        <div class="col-12 col-md-6 whishlist-category d-flex">
          <a href="{{ url('/') }}/users/show/{{ $result[0]->user_id }}" class="profile-img" title="{{ $result[0]->users->first_name }}">
            <img src="{{ $result[0]->profile_picture->src }}" alt="{{ $result[0]->users->first_name }}" width="50" height="50">
          </a>
          <div class="wishlist-header-text pl-3">
            <h5>
              {{ $result[0]->users->first_name }}’s {{ trans_choice('messages.header.wishlist', 2) }}
            </h5>
            <span class="whishlist_name d-block">
              {{ trans_choice('messages.wishlist.wishlist', $result->count()) }}:
              <strong>
                {{ $result->count() }}
              </strong>
            </span>
          </div>
        </div>
        <div class="col-12 col-md-6 mt-3 mt-md-0 whishlist_button text-center text-md-right">
          @if($owner)
          <div class="btn-group social-share-widget-container hide"></div>
          <div class="btn-group">
            <button class="btn" data-toggle="modal" data-target="#create-wishlist-modal">
              {{ trans('messages.wishlist.create_new_wishlist') }}
            </button>
          </div>
          @endif
        </div>
      </div>

      <div class="row">
        <ul class="wishlists-wrap d-md-flex flex-wrap">
          @foreach($result as $row)
          @if(trim($row->name) !='')
          <li class="col-12 col-md-4">
            <a href="{{ url('wishlists/'.$row->id) }}" class="wishlist-bg-img" style="background-image:url('{{@$row->saved_wishlists[0]->photo_name }}');">
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
          @endif
          @endforeach
        </ul>
      </div>
      <div class="wl-preload" style="display: none;">
        <div class="page-container">
          <div class="row">
            <div class="col-12">
              <p class="wl-loading">
                {{ trans('messages.wishlist.loading') }}…
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="loading-indicator wishlist-loading-indicator loading d-none"></div>
    </div>
  </div>

  <!--Create Wishlist Modal -->
  <div class="create-wishlist-popup modal fade" id="create-wishlist-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ url('create_new_wishlist') }}" method="post">
          {!! Form::token() !!}
          <div class="modal-header">
            <h1 class="modal-title">
              {{ trans('messages.wishlist.create_new_wishlist') }}
            </h1>
            <button type="button" class="close wl-modal-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" ng-init="wishlistName='';">
            <label for="wishlistName">
              <span>
                {{ trans('messages.wishlist.wish_list_name') }}
              </span>
            </label>
            <input type="text" id="wishlistName" name="name" ng-model="wishlistName">
            <label class="mt-3">
              <span>
                {{ trans('messages.wishlist.privacy_settings') }}
              </span>
            </label>
            <div class="row">
              <div class="col-12 col-md-6 col-lg-4">
                <div class="select select-block" id="wishlist-edit-privacy-setting">
                  <select name="privacy">
                    <option selected="" value="0">
                      {{ trans('messages.wishlist.everyone') }}
                    </option>
                    <option value="1">
                      {{ trans('messages.wishlist.only_me') }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn save btn-primary disable_after_click" type="submit" ng-disabled="!wishlistName">
              <span>{{ trans('messages.profile.save') }}</span>
            </button>
            <button class="btn cancel" data-dismiss="modal">
              <span>{{ trans('messages.your_reservations.cancel') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@stop

<script type="text/javascript">
 $(document).ready(function() {
  var f=$('.get_text').val(); 
  if(f=''){ 
    $('.get_button').attr('disabled', true);
  }else
  {
    $('.get_button').attr('disabled', false);
  }
  $('#wishlistName').keyup(function(){
    var wishlistnamelength = $(this).val().trim().length;
    wishlistnamelength >= 1 ? $('.save').removeAttr('disabled') : $('.save').attr('disabled','disabled');
  });
});
</script>
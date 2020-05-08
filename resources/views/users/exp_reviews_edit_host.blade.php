@extends('template')
@section('main')
<main role="main" id="site-content" ng-controller="reviews_edit_host">
  <div class="container">
    <div class="review-wrap d-md-flex row py-4 py-md-5">
      <div class="review-user-info col-md-4">
        <h2>
          {{ trans('messages.reviews.rate_review') }}
        </h2>
        <div class="panel-image">
          <a title="{{ $result->users->full_name }}" class="media-photo media-photo-block media-cover text-center" alt="{{ $result->users->full_name }}" href="{{ url('users/show/'.$result->user_id) }}">
            <img title="{{ $result->users->first_name }}" src="{{ $result->users->profile_picture->src }}" alt="{{ $result->users->first_name }}">
            <div class="media-caption">
              <span>
                {{ $result->users->first_name }}
              </span>
            </div>
          </a>      
        </div>     
        <div class="panel-info">
          <a class="profile-image" href="{{ url('experiences/'.$result->room_id) }}">
            <img src="{{ $result->rooms->photo_name }}">
          </a>       
          <h4 class="text-truncate">
            {{ trans('messages.reviews.stayed_at') }}
            {{ $result->rooms->name }}
          </h4>
          <span>
            {{ $result->dates }}
          </span>
        </div>
      </div>

      <div class="review-user-area col-md-8 pl-md-5">
        <div class="review-container">
          <div class="review-facets panel-body">
            <div id="double-blind-copy" class="review-facet">
              <section class="mt-3 mt-md-5">
                <h3>
                  {{ trans('messages.reviews.write_review_for') }} {{ $result->users->first_name }}
                </h3>
                <p>
                  {{ trans('messages.reviews.write_review_host_desc1') }}
                  {{ trans('messages.reviews.write_review_host_desc2',['site_name'=>$site_name]) }}
                </p>
                <p>
                  {{ trans('messages.reviews.write_review_host_desc3') }}
                </p>
                <button class="btn btn-primary next-facet">
                  {{ trans('messages.reviews.write_a_review') }}
                </button>
              </section>
            </div>
            <input type="hidden" value="{{ $review_id }}" name="review_id" id="review_id">
            <input type="hidden" value="{{ $result->id }}" name="reservation_id" id="reservation_id">
            <div tabindex="-1" class="review-facet d-none" id="guest">
              <form id="guest-form" class="edit_review">
                <input type="hidden" value="guest" name="section" id="section">
                <section class="mt-3 mt-md-5">
                  <h3>
                    {{ trans('messages.reviews.describe_your_exp') }}
                    <small>({{ trans('messages.reviews.required') }})</small>
                  </h3>
                  <p>
                    {{ trans('messages.reviews.describe_your_exp_host_desc',['first_name'=>$result->users->first_name]) }}
                  </p>
                </section>
                <section class="my-3">
                  <label style="display: none;" class="invalid" generated="true" for="review_private_feedback">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                  <textarea rows="5" placeholder="{{ trans('messages.reviews.private_guest') }}" name="private_feedback" id="review_private_feedback" cols="40">{{ @$result->review_details($review_id)->comments }}</textarea>
                </section>
                <section class="my-3">
                  <h3>
                    {{ trans('messages.reviews.rate_review') }}
                  </h3>
                  <div class="star-rating">
                    <input type="radio" value="5" name="cleanliness" id="review_cleanliness_5" class="star-rating-input" {{ (@$result->review_details($review_id)->rating == 5) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_5" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="4" name="cleanliness" id="review_cleanliness_4" class="star-rating-input" {{ (@$result->review_details($review_id)->rating == 4) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_4" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="3" name="cleanliness" id="review_cleanliness_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->rating == 3) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_3" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="2" name="cleanliness" id="review_cleanliness_2" class="star-rating-input" {{ (@$result->review_details($review_id)->rating == 2) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_2" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="1" name="cleanliness" id="review_cleanliness_1" class="star-rating-input" {{ (@$result->review_details($review_id)->rating == 1) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_1" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                  </div>
                  <label style="display: none;" class="invalid" generated="true" for="review_rating">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                </section>
                <button data-track-submit="" class="btn btn-primary exp_review_submit" type="submit">
                  {{ trans('messages.account.submit') }}
                </button>
              </form>          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop
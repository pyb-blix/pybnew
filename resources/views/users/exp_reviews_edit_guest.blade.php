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
          <a title="{{ $result->rooms->title }}" class="media-photo media-cover text-center" alt="{{ $result->rooms->title }}" href="{{ url('/') }}/experiences/{{ $result->room_id }}">
            <img src="{{ $result->rooms->photo_name }}" alt="{{ $result->rooms->title }}">
          </a>    
        </div>
        <div class="panel-info">
          <a class="profile-image" href="{{ url('/') }}/users/show/{{ $result->rooms->users->id }}">
            <img title="{{ $result->rooms->users->first_name }}" src="{{ $result->rooms->users->profile_picture->src }}" alt="{{ $result->rooms->users->first_name }}">
          </a>
          <h4 class="text-truncate">
            {{ $result->rooms->title }}
          </h4>
          <span>
            {{ $result->rooms->rooms_address->city }}
          </span>
          <h4 class="text-truncate">
            {{ trans('messages.reviews.hosted_by') }}
            {{ $result->rooms->users->full_name }}
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
                  {{ trans('messages.reviews.write_review_for') }} {{ $result->rooms->users->first_name }}
                </h3>
                <p>
                  {{ trans('messages.reviews.write_review_guest_desc1') }}
                  {{ trans('messages.reviews.write_review_guest_desc2') }}
                </p>
                <p>
                  {{ trans('messages.reviews.write_review_guest_desc3') }}
                </p>
                <button class="btn btn-primary next-facet">
                  {{ trans('messages.reviews.write_a_review') }}
                </button>
              </section>
            </div>
            <input type="hidden" value="{{ $result->id }}" name="reservation_id" id="reservation_id">
            <input type="hidden" value="{{ $review_id }}" name="review_id" id="review_id">
            <div class="review-facet d-none" id="host-summary">
              <form id="host-summary-form">
                <input type="hidden" value="host_summary" name="section" id="section">
                <section class="mt-3 mt-md-5">
                  <h3>
                    {{ trans('messages.reviews.describe_your_exp') }}
                    <small>({{ trans('messages.reviews.required') }})</small>
                  </h3>
                  <p>
                    {{ trans('messages.reviews.describe_your_exp_guest_desc',['site_name'=>$site_name]) }}
                  </p>
                  <label style="display: none;" class="invalid" generated="true" for="review_comments">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                </section>

                <section class="my-4">
                  <label style="display: none;" class="invalid" generated="true" for="review_private_feedback">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                  <textarea rows="3" name="improve_comments" id="improve_comments" class="input-large valid">{{ @$result->review_details($review_id)->comments }}</textarea>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.overall_exp') }}
                    <small>({{ trans('messages.reviews.required') }})</small>
                  </h3>
                  <div class="star-rating">
                    <input type="radio" value="5" name="rating" id="review_rating_5" class="star-rating-input valid" {{ (@$result->review_details($review_id)->rating == 5) ? 'checked="true"' : '' }}>
                    <label for="review_rating_5" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="4" name="rating" id="review_rating_4" class="star-rating-input valid" {{ (@$result->review_details($review_id)->rating == 4) ? 'checked="true"' : '' }}>
                    <label for="review_rating_4" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="3" name="rating" id="review_rating_3" class="star-rating-input valid" {{ (@$result->review_details($review_id)->rating == 3) ? 'checked="true"' : '' }}>
                    <label for="review_rating_3" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="2" name="rating" id="review_rating_2" class="star-rating-input valid" {{ (@$result->review_details($review_id)->rating == 2) ? 'checked="true"' : '' }}>
                    <label for="review_rating_2" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="1" name="rating" id="review_rating_1" class="star-rating-input valid" {{ (@$result->review_details($review_id)->rating == 1) ? 'checked="true"' : '' }}>
                    <label for="review_rating_1" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                  </div>
                  <label style="display: none;" class="invalid" generated="true" for="review_rating">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                </section>
                <button class="btn btn-primary exp_review_submit" type="submit">
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
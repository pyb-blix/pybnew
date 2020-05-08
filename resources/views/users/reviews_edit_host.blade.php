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
          <a class="profile-image" href="{{ url('rooms/'.$result->room_id) }}">
            <img src="{{ $result->rooms->src }}">
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
                  <label style="display: none;" class="invalid" generated="true" for="review_comments">
                    {{ trans('messages.reviews.this_field_is_required') }}
                  </label>
                  <textarea maxlength="500" rows="5" placeholder="{{ trans('messages.reviews.host_descrip') }}" name="comments" id="review_comments" data-behavior="countable" cols="40" onkeyup="countChar(this)" onpaste="countChar(this)">{{ @$result->review_details($review_id)->comments }}</textarea>
                  <div data-behavior="counter" class="h6 pull-right mt-1">
                    <span id="charNum">{{500-strlen(@$result->review_details($review_id)->comments)}}</span>
                    <span> {{ trans('messages.reviews.500_words_left') }}</span>
                    <span class="reached-warning d-none">
                      {{ trans('messages.reviews.reached_500_words') }}
                    </span>
                  </div>
                  <br>
                  <P class="personal-info-warning">
                    {{ trans('messages.reviews.describe_your_exp_host_desc2') }}
                  </P>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.private_guest_feedback') }}
                  </h3>
                  <p>
                    {{ trans('messages.reviews.private_guest_feedback_desc') }}
                  </p>
                  <textarea rows="5" placeholder="{{ trans('messages.reviews.private_guest') }}" name="private_feedback" id="review_private_feedback" cols="40">{{ @$result->review_details($review_id)->private_feedback }}</textarea>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.cleanliness') }}
                  </h3>
                  <p>
                    {{ trans('messages.reviews.cleanliness_host_desc') }}
                  </p>
                  <div class="star-rating">
                    <input type="radio" value="5" name="cleanliness" id="review_cleanliness_5" class="star-rating-input" {{ (@$result->review_details($review_id)->cleanliness == 5) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_5" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="4" name="cleanliness" id="review_cleanliness_4" class="star-rating-input" {{ (@$result->review_details($review_id)->cleanliness == 4) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_4" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="3" name="cleanliness" id="review_cleanliness_3" class="star-rating-input" {{ (@$result->review_details($review_id)->cleanliness == 3) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_3" class="star-rating-star js-star-rating needsclick">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="2" name="cleanliness" id="review_cleanliness_2" class="star-rating-input" {{ (@$result->review_details($review_id)->cleanliness == 2) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_2" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="1" name="cleanliness" id="review_cleanliness_1" class="star-rating-input" {{ (@$result->review_details($review_id)->cleanliness == 1) ? 'checked="true"' : '' }}>
                    <label for="review_cleanliness_1" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                  </div>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.communication') }}
                  </h3>
                  <p>
                    {{ trans('messages.reviews.communication_host_desc') }}
                  </p>
                  <div class="star-rating">
                    <input type="radio" value="5" name="communication" id="review_communication_5" class="star-rating-input" {{ (@$result->review_details($review_id)->communication == 5) ? 'checked="true"' : '' }}>
                    <label for="review_communication_5" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="4" name="communication" id="review_communication_4" class="star-rating-input" {{ (@$result->review_details($review_id)->communication == 4) ? 'checked="true"' : '' }}>
                    <label for="review_communication_4" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="3" name="communication" id="review_communication_3" class="star-rating-input needsclick" {{ (@$result->review_details($review_id)->communication == 3) ? 'checked="true"' : '' }}>
                    <label for="review_communication_3" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="2" name="communication" id="review_communication_2" class="star-rating-input" {{ (@$result->review_details($review_id)->communication == 2) ? 'checked="true"' : '' }}>
                    <label for="review_communication_2" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="1" name="communication" id="review_communication_1" class="star-rating-input" {{ (@$result->review_details($review_id)->communication == 1) ? 'checked="true"' : '' }}>
                    <label for="review_communication_1" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                  </div>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.observance_house_rules') }}
                  </h3>
                  <p>
                    {{ trans('messages.reviews.observance_house_rules_desc') }}
                  </p>
                  <div class="star-rating">
                    <input type="radio" value="5" name="respect_house_rules" id="review_respect_house_rules_5" class="star-rating-input" {{ (@$result->review_details($review_id)->respect_house_rules == 5) ? 'checked="true"' : '' }}>
                    <label for="review_respect_house_rules_5" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="4" name="respect_house_rules" id="review_respect_house_rules_4" class="star-rating-input" {{ (@$result->review_details($review_id)->respect_house_rules == 4) ? 'checked="true"' : '' }}>
                    <label for="review_respect_house_rules_4" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="3" name="respect_house_rules" id="review_respect_house_rules_3" class="star-rating-input" {{ (@$result->review_details($review_id)->respect_house_rules == 3) ? 'checked="true"' : '' }}>
                    <label for="review_respect_house_rules_3" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="2" name="respect_house_rules" id="review_respect_house_rules_2" class="star-rating-input" {{ (@$result->review_details($review_id)->respect_house_rules == 2) ? 'checked="true"' : '' }}>
                    <label for="review_respect_house_rules_2" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                    <input type="radio" value="1" name="respect_house_rules" id="review_respect_house_rules_1" class="star-rating-input" {{ (@$result->review_details($review_id)->respect_house_rules == 1) ? 'checked="true"' : '' }}>
                    <label for="review_respect_house_rules_1" class="star-rating-star js-star-rating">
                      <i class="icon icon-star"></i>
                    </label>
                  </div>
                </section>

                <section class="my-4">
                  <h3>
                    {{ trans('messages.reviews.would_you_recommend') }}
                  </h3>
                  <P>
                    {{ trans('messages.reviews.would_you_recommend_host_desc') }}
                  </P>
                  <div class="thumbs-widget">
                    <input type="radio" value="0" name="recommend" id="review_recommend_0" {{( @$result->review_details($review_id) != null && @$result->review_details($review_id)->recommend == 0) ? 'checked="true"' : '' }}>
                    <label for="review_recommend_0">
                      <i class="icon icon-thumbs-down"></i>
                      {{ trans('messages.reviews.no') }}
                    </label>
                    <input type="radio" value="1" name="recommend" id="review_recommend_1" {{ (@$result->review_details($review_id) == null || @$result->review_details($review_id)->recommend == 1) ? 'checked="true"' : '' }}>
                    <label for="review_recommend_1">
                      <i class="icon icon-thumbs-up"></i>
                      {{ trans('messages.reviews.yes') }}!
                    </label>
                  </div>
                </section>
                <button data-track-submit="" class="btn btn-primary review_submit" type="submit">
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
<div ng-repeat="featured_category in featured_host_experience_categories" ng-if="featured_category.all_host_experiences_count > 0">
  <div class="my-4 d-flex justify-content-between align-items-center">
    <h2 class="title-sm">
      @{{ featured_category.name }}
    </h2>
  </div>
  <ul class="owl-carousel featured_category-slider">
    <li ng-repeat="host_experience in featured_category.host_experiences">
      <div class="pro-img">
        <a href="@{{ host_experience.link }}">
          <img src="@{{ host_experience.photo_resize_name }}" />
        </a>
      </div>
      <div class="pro-info">
        <h4 class="text-truncate">
          <span>@{{host_experience.category_name}}</span>
          <span>·</span>
          <span>@{{host_experience.city_name}}</span>
        </h4>
        <a href="@{{ host_experience.link }}" title="@{{ host_experience.title }}">
          <h5 class="text-truncate">
            @{{ host_experience.title }}
          </h5>
        </a>
        <div class="exp_price" >
          <span ng-bind-html="host_experience.currency.symbol"></span> @{{ host_experience.session_price }} {{ trans("messages.wishlist.per_guest") }}
        </div>
        <div class="star-rating-wrapper">
          <div class="category_city" ng-if="host_experience.overall_star_rating != '' ">
            <span ng-bind-html="host_experience.overall_star_rating"></span>
            <span>@{{ host_experience.reviews_count }} @{{ host_experience.reviews_count_lang }}</span>
          </div>
        </div>
      </div>
    </li>
  </ul>
  <div class="mt-3 mt-md-0 mb-5" ng-if="featured_category.all_host_experiences_count > 8">
    <a class="see-all-link d-md-inline-flex align-items-center" href="{{ url('s') }}">
      <span>
        {{ trans('messages.header.seeall') }}
      </span>
      <i class="icon icon-chevron-right ml-2"></i>
    </a>
  </div>
</div>
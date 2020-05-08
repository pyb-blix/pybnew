<div class="my-4 d-flex justify-content-between align-items-center" ng-if="host_experiences.length > 0">
	<h2 class="title-sm m-0">
		{{ $title_text }}
	</h2>
</div>
<ul id="experience-slider" class="experience-slider owl-carousel">
	<li ng-repeat="host_experience in host_experiences">
		<div class="pro-img">
			<a href="@{{ host_experience.link }}">
				<img class="owl-lazy" data-src="@{{ host_experience.photo_name }}"/>
			</a>
		</div>
		<div class="pro-info">
			<h4 class="text-truncate">
				<span>@{{host_experience.category_name}}</span>
				<span>·</span>
				<span>@{{host_experience.host_experience_location.city}}</span>
			</h4>
			<a href="@{{ host_experience.link }}" title="@{{ host_experience.title }}">
				<h5 class="text-truncate">
					@{{ host_experience.title }}
				</h5>
			</a>
			<div class="exp_price">
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

<div class="mt-3 mt-md-0 mb-5" ng-if="host_experiences.length > 4">
	<a class="see-all-link d-md-inline-flex align-items-center" href="{{ url('/s?current_refinement=Experiences') }}">
		<span>
			{{ trans('messages.header.seeall') }}
		</span>
		<i class="icon icon-chevron-right ml-2"></i>
	</a>
</div>
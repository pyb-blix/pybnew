<div class="main-wrap d-lg-flex" ng-cloak>
	<div class="save-info">
		@include('host_experiences.manage_experience.header')  
	</div>
	<div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
		<h3>
			{{trans('experiences.manage.hi_name', ['name' => @Auth::user()->first_name])}}
		</h3>
		<p>
			{{trans('experiences.manage.this_is_where_you_create_experience_4_steps')}}{{trans('experiences.manage.we_offer_tips_examples_along_way')}}
		</p>
		<p>
			{{trans('experiences.manage.hosting_experienceis_new_growing_community', ['site_name' => SITE_NAME])}} {{trans('experiences.manage.we_cant_wait_to_see')}}
		</p>
		<a class="btn experience-btn host-secondary next_step mt-3 mt-md-4" data-step-num="{{$step_num +1}}" href="javascript:void(0);">
			{{trans('experiences.manage.next')}}
		</a>
	</div>
	<div class="d-none d-lg-block main-wrap-img">
		<img class="img-fluid" src="{{url('images/host_experiences/host_exper.jpg')}}">
	</div>
</div>

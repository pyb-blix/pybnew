<div class="main-wrap d-lg-flex" ng-cloak>
	<div class="save-info">
		@include('host_experiences.manage_experience.header')  
	</div>
	<div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
		<h3>
			{{trans('experiences.manage.review_our_experience_standards')}}
		</h3>
		<p>
			{{trans('experiences.manage.your_experience_meet_our_standards', ['site_name' => SITE_NAME])}}
			<a href="javascript:void(0)" class="d-none">
				{{trans('experiences.manage.learn_more_about_them')}}
			</a> 
		</p>
		<p> 
			1. {{trans('experiences.manage.access')}}
		</p>
		<p>
			{{trans('experiences.manage.access_desc')}}
		</p>
		<p> 
			2. {{trans('experiences.manage.preparation')}}
		</p>
		<p>
			{{trans('experiences.manage.preparation_desc')}}
		</p>
		<p> 
			3. {{trans('experiences.manage.perspective')}}
		</p>
		<p>
			{{trans('experiences.manage.perspective_desc')}}
		</p>
		<div class="my-4">
			<label class="verify-check">
				<input type="checkbox" name="experience_standards_reviewed" ng-model="host_experience.experience_standards_reviewed" ng-true-value="'Yes'" ng-false-value="false" ng-checked="host_experience.experience_standards_reviewed == 'Yes'"> 
				<span>
					{{trans('experiences.manage.my_experience_meet_this_standards')}}
				</span>
				<p class="text-danger" ng-show="form_errors.experience_standards_reviewed.length">
					@{{form_errors.experience_standards_reviewed[0]}}
				</p>
			</label>
		</div>
		<a class="btn btn-host" data-step-num="{{$step_num +1}}" href="javascript:void(0);" ng-class="host_experience.experience_standards_reviewed == 'Yes' ? 'save_next_step' : ''" ng-disabled="host_experience.experience_standards_reviewed != 'Yes' ">
			{{trans('experiences.manage.next')}}
		</a>
	</div>
	<div class="d-none d-lg-block main-wrap-img">
		<img class="img-fluid" src="{{url('images/host_experiences/review.jpg')}}">
	</div>
</div>
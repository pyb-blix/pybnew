<div class="main-wrap d-lg-flex" ng-cloak>
	<div class="save-info">
		@include('host_experiences.manage_experience.header')  
	</div>
	<div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
		<h3>
			{{trans('experiences.manage.review_hosting_standards')}}
		</h3>
		<p>
			{{trans('experiences.manage.its_important_to_think_yourself_as_guide')}}
		</p>
		<p>
			{{trans('experiences.manage.make_sure_you_meet_this_qualities')}}
		</p>
		<p>
			1. {{trans('experiences.manage.credible')}}
		</p>
		<p>
			{{trans('experiences.manage.credible_desc')}}
		</p>
		<p>
			2. {{trans('experiences.manage.genuine')}}
		</p>
		<p>
			{{trans('experiences.manage.genuine_desc')}}
		</p>
		<p>
			3. {{trans('experiences.manage.empathetic')}}
		</p>
		<p>
			{{trans('experiences.manage.empathetic_desc')}}
		</p>
		<div class="my-4">
			<label class="verify-check">
				<input type="checkbox" name="hosting_standards_reviewed" ng-model="host_experience.hosting_standards_reviewed" ng-true-value="'Yes'" ng-false-value="false" ng-checked="host_experience.hosting_standards_reviewed == 'Yes'"> 
				<span>
					{{trans('experiences.manage.thats_me')}}
				</span>
				<p class="text-danger" ng-show="form_errors.hosting_standards_reviewed.length">
					@{{form_errors.hosting_standards_reviewed[0]}}
				</p>
			</label>
		</div>
		<a class="btn btn-host" data-step-num="{{$step_num +1}}" href="javascript:void(0);" ng-class="host_experience.hosting_standards_reviewed == 'Yes' ? 'save_next_step' : ''" ng-disabled="host_experience.hosting_standards_reviewed != 'Yes' ">
			{{trans('experiences.manage.next')}}
		</a>
	</div>
	<div class="d-none d-lg-block main-wrap-img">
		<img class="img-fluid" src="{{url('images/host_experiences/review2.jpg')}}">
	</div>
</div>

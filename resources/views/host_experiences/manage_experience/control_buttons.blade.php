<div class="check_detail1" ng-show="host_experience_steps[step_num].status == 0 || save_next_step">
	<button class="btn experience-btn" type="button" ng-class="steps_status[step] ? 'save_next_step' : ''" data-step-num="{{$step_num +1}}" ng-disabled="!steps_status[step] || save_in_progress"> 
		{{trans('experiences.manage.save_and_continue')}}
	</button>
</div>
<div class="check_detail1" ng-show="form_modified && host_experience_steps[step_num].status == 1 && !save_next_step">
	<button class="btn experience-btn" type="button" ng-class="steps_status[step] ? 'save_step' : ''" data-step-num="{{$step_num +1}}" ng-disabled="!steps_status[step] || save_in_progress"> 
		{{trans('experiences.manage.save')}}
	</button>
	<button class="btn experience-btn host-primary ml-2 undo_step" type="button" ng-show="step != 'photos'"> 
		{{trans('experiences.manage.undo')}}
	</button>
</div>
<div class="check_detail1-1" ng-show="!form_modified && host_experience_steps[step_num].status == 1 && !save_next_step">
	<button class="btn experience-btn host-primary next_step" data-step-num="{{$step_num +1}}" type="button"> 
		{{trans('experiences.manage.next')}}
	</button>
</div>
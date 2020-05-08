@foreach(@$host_experience_steps_group[""] as $k => $step)
<ul ng-class="host_experience_steps[{{@$step['step_num']}}].locked ? 'opacity-low' : ''">
  <li ng-if="host_experience_steps[{{@$step['step_num']}}].locked"> 
    <i class="icon icon-lock"></i> 
    <span>
      {{@$step['name']}}
    </span>
  </li>
  <li class="location-reload refresh_main_content" data-step-num="{{$step['step_num']}}" ng-if="!host_experience_steps[{{@$step['step_num']}}].locked" ng-class="{{@$step['step_num']}} == step_num ? 'active' : ''">
    <a href="javascript:void(0)" ng-if="host_experience_steps[{{@$step['step_num']}}].step == 'experience_page' && host_experience.title" class="text-truncate"> 
      @{{host_experience.title}}
    </a>
    <a href="javascript:void(0)" ng-if="host_experience_steps[{{@$step['step_num']}}].step != 'experience_page' || !host_experience.title" class="text-truncate"> 
      {{@$step['name']}}
    </a>
  </li>
  @if(@$host_experience_steps_group[$step['step']])
  @foreach(@$host_experience_steps_group[$step['step']] as $sub_step)
  <li class="location-reload refresh_main_content" data-step-num="{{$sub_step['step_num']}}" ng-if="!host_experience_steps[{{@$step['step_num']}}].locked" ng-class="({{@$sub_step['step_num']}} == step_num && host_experience.is_reviewed) ? 'active' : ''">
    <a href="javascript:void(0)" class="text-truncate"> 
      {{$sub_step['name']}}
    </a> 
    <i class="icon icon2-tick" ng-if="steps_status['{{@$sub_step['step']}}'] == true && host_experience.status == NULL"></i>
    <i class="icon icon-alert" ng-if="steps_status['{{@$sub_step['step']}}'] == false && host_experience.status != NULL"></i>
  </li>
  @endforeach
  @endif
</ul>
@endforeach

<div class="btn-sidebar d-block d-md-none">
  <i class="fa fa-bars side-menu-bar" style="cursor: pointer;"></i>
</div>
<div class="save-exit-btn">
  <ul class="d-flex align-items-center justify-content-end">
    <li class="d-none d-md-block save-loading opacity-low" ng-init="changes_saved_text = '{{$host_experience->changes_saved}}'">
      @{{changes_saved_text}}
    </li>
    <li>
      <a href="javascript:void(0)" class="@if(@$header_inverse) @endif save_exit"> 
        {{trans('experiences.manage.save_exit')}}
      </a>
    </li>
  </ul>        
</div>    
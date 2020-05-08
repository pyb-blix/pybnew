<div class="main-wrap bg-white" ng-init="check_single_packing_list();">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.create_a_packing_list')}}
    </h3>
    <p>
      {{trans('experiences.manage.lets_guests_know_what_to_bring')}}
    </p>
    <h5>
      @{{host_experience.title}}
    </h5>
    <p>
      {{trans('experiences.manage.what_should_your_guests_bring')}}
    </p>
    <div class="clone_elem package-input" ng-repeat="item in host_experience_packing_lists">
      <span class="icon icon2-cancel cursor" ng-click="remove_packing_list($index)"></span>
      <input type="hidden" name="packing_lists[][id]" id="packing_list_@{{$index}}_id" ng-model="host_experience_packing_lists[$index].id">
      <input type="text" class="input_new1 my-2" placeholder="{{trans('experiences.manage.enter_item_here')}}" name="packing_lists[][item]" id="packing_list_@{{$index}}_item" ng-model="host_experience_packing_lists[$index].item" ng-change="host_experience_packing_lists_changed(); need_packing_lists_change();">
    </div>
    <a href="javascript:void(0)" class="mt-3 host-add-item" ng-if="packing_list_can_add_more" ng-click="add_packing_list();"> 
      + {{trans('experiences.manage.add_an_item')}}
    </a>
    <div class="mt-4" id="need_packing_lists_part" test="@{{ host_experience_packing_lists }}" ng-show="host_experience_packing_lists.length == 0">
      <h5>
        {{trans('experiences.manage.do_guests_not_bringing_anything')}}
      </h5>
      <p>
        {{trans('experiences.manage.if_so_make_sure_you_provide_everything')}}
      </p>
      <div class="my-4">
        <label class="verify-check">
          <input type="checkbox" name="need_packing_lists" ng-model="host_experience.need_packing_lists" ng-true-value="'No'" ng-false-value="false" ng-checked="host_experience.need_packing_lists == 'No'"> 
          <span>
           {{trans('experiences.manage.my_guests_dont_need_to_bring_anything')}}
         </span>
         <p class="text-danger" ng-show="form_errors.need_packing_lists.length">
          @{{form_errors.need_packing_lists[0]}}
        </p>
      </label>
    </div>
  </div>
  <div class="mt-4">
    @include('host_experiences.manage_experience.control_buttons')
  </div>
</div>
</div>
<!--  main_bar end -->
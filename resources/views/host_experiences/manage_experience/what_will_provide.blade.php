<div class="main-wrap provide-wrap bg-white" ng-init="check_single_provide();">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.confirm_what_will_provide')}}
    </h3>
    <p>
      {{trans('experiences.manage.what_will_provide_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup1"> 
      <span class="icon icon2-light-bulb"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>    
    @{{$scope.removed_provides}}
    <div class="col-md-8 clone_sec_wrap provide-list-wrap mt-4 mt-md-5 p-0">
      <div class="clone_sec" ng-repeat="provide in host_experience_provides">
        <div class="provide-name-remove d-flex justify-content-between align-items-center mb-2">
          <label class="text-truncate pr-2">
            {{trans('experiences.manage.item')}} @{{$index+1}}
          </label>
          <a href="javascript:void(0)" ng-show="provide.host_experience_provide_item_id > 0" ng-click="remove_provide($index)">
            {{trans('experiences.manage.remove')}}
          </a>
        </div>
        <div class="select">
          <i class="icon-chevron-down"></i>
          <select id="host_experience_provide_@{{$index}}_host_experience_provide_item_id" name="provides[][host_experience_provide_item_id]" ng-model="host_experience_provides[$index].host_experience_provide_item_id" ng-change="host_experience_provides_changed(); need_provides_change();">
            <option ng-if="provide.host_experience_provide_item_id == 0" value="0">
              {{trans('experiences.manage.select_item')}}
            </option>
            <option ng-repeat="item in provide_items" ng-selected="provide.host_experience_provide_item_id == item.id" value="@{{item.id}}" ng-if="check_provide_item_available(item.id, $parent.$index)">
              @{{item.name}}
            </option>
          </select>            
        </div>
        <div class="focus_txt" ng-show="provide.host_experience_provide_item_id > 0">
          <input type="text" name="provides[][name]" class="input_new1  top_1px_adj mul_input" id="host_experience_provide_@{{$index}}_name" placeholder="{{trans('experiences.manage.name_item')}}" ng-model="host_experience_provides[$index].name" ng-change="host_experience_provides_changed();" ng-focus="show_element('#provide_name_tips_'+$index);" ng-blur="hide_element('#provide_name_tips_'+$index);" />
          <textarea name="provides[][additional_details]" class="input_new1 top_1px_adj  mul_textarea" rows="3" placeholder="{{trans('experiences.manage.add_additionale_detials_optional')}}" id="host_experience_provide_@{{$index}}_additional_details"  ng-model="host_experience_provides[$index].additional_details" ng-change="host_experience_provides_changed();" ng-focus="show_element('#provide_additional_details_tips_'+$index);" ng-blur="hide_element('#provide_additional_details_tips_'+$index);" ng-show="provide.name">
          </textarea>
        </div>
        <p class="mt-2" id="provide_name_tips_@{{$index}}" ng-class="character_length_class(1, 25, host_experience_provides[$index].name.length)" style="display: none;">
          @{{character_length_validation(1, 25, host_experience_provides[$index].name.length)}}
        </p>
        <p class="mt-2" id="provide_additional_details_tips_@{{$index}}" ng-class="character_length_class(1, 125, host_experience_provides[$index].additional_details.length)" style="display: none;">
          @{{character_length_validation(1, 125, host_experience_provides[$index].additional_details.length)}}
        </p>
      </div> 
      <div class="add-more-link mt-3">
        <a class="d-flex align-items-center" href="javascript:void(0)" ng-click="add_provide();" ng-show="provide_can_add_more && host_experience_provides.length < provide_items.length">
          <span class="icon-add mr-2"></span> 
          <span>
            {{trans('experiences.manage.add_another_item')}}
          </span> 
        </a>
      </div>
    </div>  
    <div id="need_provides_part" ng-show="host_experience_provides[0].host_experience_provide_item_id == 0">
      <h4>
        {{trans('experiences.manage.not_providing_anythig_for_guests')}}
      </h4>
      <div class="my-4">
        <label class="verify-check">
          <input type="checkbox" name="need_provides" ng-model="host_experience.need_provides" ng-true-value="'No'" ng-false-value="false" ng-checked="host_experience.need_provides == 'No'"> 
          <span> @lang('experiences.manage.i_am_not_providing_anything') </span>
        </label>
        <p class="text-danger" ng-show="form_errors.need_provides.length">
          @{{form_errors.need_provides[0]}}
        </p>
      </div>
    </div>
    <div class="mt-4 mt-md-5">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
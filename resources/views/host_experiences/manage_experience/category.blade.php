<div class="main-wrap bg-white" ng-init="categories = {{json_encode($categories)}}">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.what_type_of_experience_you_host')}}
    </h3>
    <p>
      {{trans('experiences.manage.category_step_desc')}}
    </p>
    <div class="select">
      <i class="icon-chevron-down"></i>
      <select class="light" id="host_experience_category" name="category" ng-model="host_experience.category" ng-init="host_experience.category = host_experience.category == null ? '' : host_experience.category;">
        <option ng-if="host_experience.category == ''" value=''>
          {{trans('experiences.manage.choose_a_category')}}
        </option>
        <option ng-repeat="category in categories" ng-selected="host_experience.category == category.id" value="@{{category.id}}">
          @{{category.name}}
        </option>
      </select>
      <p class="text-danger" ng-show="form_errors.category.length">
        @{{form_errors.category[0]}} 
      </p>
    </div>
    <div class="second-category-select mt-4" ng-init="is_secondary = (host_experience.secondary_category > 0)" ng-class="!is_secondary ? 'd-none' : ''">
      <label>
        {{trans('experiences.manage.second_category_optional')}}
        <a href="javascript:void(0);" ng-click="host_experience.secondary_category = ''; is_secondary = false">
          {{trans('experiences.manage.remove')}}
        </a>
      </label>
      <div class="select">
        <i class="icon-chevron-down"></i>
        <select id="host_experience_secondary_category" name="secondary_category" ng-model="host_experience.secondary_category" ng-init="host_experience.secondary_category = host_experience.secondary_category == null ? '' : host_experience.secondary_category;">
          <option ng-if="host_experience.secondary_category == ''" value=''>
            {{trans('experiences.manage.choose_a_category')}}
          </option>
          <option ng-repeat="category in categories" ng-selected="host_experience.secondary_category == category.id" ng-if="category.id != host_experience.category" value="@{{category.id}}">
            @{{category.name}}
          </option>
        </select>
        <p class="text-danger" ng-show="form_errors.secondary_category.length">
          @{{form_errors.secondary_category[0]}} 
        </p>
      </div>
    </div>
    <a href="javascript:void(0)" class="host-add-item mt-3" ng-show="host_experience.category" ng-class="is_secondary ? 'd-none' : ''" ng-click=" is_secondary = true;">
      + {{trans('experiences.manage.add_a_second_category_optional')}}
    </a>
    <div class="d-none">
      <p> 
        <i class="icon icon2-ribbon"></i>
        Is this a social impact experience?
      </p>
      <p>
        If you’re partnering with a nonprofit or a charitable organization, you can host a social impact experience. To host, you’ll have to validate your organization with our partner, TechSoup. 
        <a href="javascript:void(0)">
         Learn more
       </a>
     </p>
     <div class="my-4">
       <label class="verify-check">
        <input type="checkbox" name="" class="chekbox1 check_detail_tri"> 
        <span>
         Yes, this is a social impact experience
       </span>
     </label>
   </div>
   <div class="check_detail d-none">
    <p>
     Are you signed into the correct account?
   </p>
   <p>
    Your payout method must go to your nonprofit. It cannot be changed once this experience is published.
  </p>
  <div class="tabl">
    <div class="tb_cell">
      <div class="prof1" style="background-image: url('{{url('images/host_experiences/pro.jpg')}}');"></div>
    </div>
    <div class="tb_cell">
      <p> 
        Arun
      </p>
    </div>
  </div>
  <div class="my-4">
    <label class="verify-check">
      <input type="checkbox" name="" class="chekbox1 check_detail_tri1"> 
      <span>
       This is the correct account and not my personal Airbnb account
     </span>
   </label>
 </div>
</div>
</div>
<!-- check_detail end -->
<div class="mt-4 mt-md-5">
  @include('host_experiences.manage_experience.control_buttons')
</div>
</div>
</div>
<!--  main_bar end -->
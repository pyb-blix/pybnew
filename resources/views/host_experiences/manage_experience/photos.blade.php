<div class="main-wrap bg-white" ng-init="check_single_photo();">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.add_photos_for_your_experience')}}
    </h3>
    <p>
      {{trans('experiences.manage.photos_page_desc')}}
    </p>
    <a href="javascript:void(0)" class="pop_link d-none" data-id="popup2"> 
      <span class="icon icon2-light-bulb"></span> 
      {{trans('experiences.manage.tips_and_examples')}}
    </a>
    <ul class="photo-upload-wrap mt-4 mt-md-5">
      @{{photo_style}}
      <li ng-repeat="photos in host_experience_photos">
        <label class="browse" id="photo_div_@{{$index}}" ng-class="photos.name ? 'bg-cover' : ''" ng-init="photo_style_dis[$index] = photos.name ? {'background-image':'url('+photos.image_url+')'} : {}" ng-style="photo_style($index);">
          <a class="icon icon2-cancel close1" ng-if="photos.name" href="javascript:void(0)" ng-click="remove_photo($index)"></a> 
          <span class="icon-add"></span>
          <input type="file" id="host_experience_photo_@{{$index}}" class="host_experience_photos_element" name="host_experiences_photos[]" ng-disabled="photos.name" data-index="@{{$index}}">
        </label>
      </li>
    </ul>
    <div class="mt-4 mt-md-5">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
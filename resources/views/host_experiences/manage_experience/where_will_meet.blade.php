<div class="main-wrap meet-location-wrap bg-white" ng-init="countries = {{json_encode($countries)}};">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.add_a_meeting_location')}}
    </h3>
    <p>
      {{trans('experiences.manage.where_will_meet_page_desc')}}
    </p>
    <h4>
      {{trans('experiences.manage.step')}} 1: {{trans('experiences.manage.provide_an_address')}}
    </h4>
    <div class="loaction-input-wrap my-3 my-md-4">
      <label>
        {{trans('experiences.manage.location_name')}}
      </label>
      <input type="text" name="host_experience_location[location_name]" class="input_new1" id="host_experience_location_location_name" ng-model="host_experience.host_experience_location.location_name" />
      <p class="text-danger" ng-show="form_errors.host_experience.location_name.length">
        @{{form_errors.host_experience.location_name[0]}} 
      </p>  
    </div>
    <div class="loaction-input-wrap my-3 my-md-4">
      <label>
        {{trans('experiences.manage.country')}}
      </label>       
      <div class="select">
        <i class="icon-chevron-down"></i>
        <select name="host_experience_location[country]" id="host_experience_location_country" ng-model="host_experience.host_experience_location.country">
          <option ng-if="host_experience.host_experience_location.country == ''" value="">
            {{trans('experiences.manage.select_country')}}
          </option>
          @foreach($countries as $k => $v)
          <option value="{{$k}}">
            {{$v}}
          </option>
          @endforeach
        </select>           
      </div>
      <p class="text-danger" ng-show="form_errors.host_experience.country.length">
        @{{form_errors.host_experience.country[0]}} 
      </p>  
    </div>
    <div class="loaction-input-wrap my-3 my-md-4">
      <label>
        {{trans('experiences.manage.street_address')}}
      </label>
      <input type="text" autocomplete="off" name="host_experience_location[address_line_1]" class="input_new1" id="host_experience_location_address_line_1" ng-model="host_experience.host_experience_location.address_line_1" />
      <p class="text-danger" ng-show="form_errors.host_experience.address_line_1.length">
        @{{form_errors.host_experience.address_line_1[0]}} 
      </p>  
    </div>
    <div class="loaction-input-wrap my-3 my-md-4">
      <label>
        {{trans('experiences.manage.address_line_1_name')}}
      </label> 
      <input type="text" autocomplete="off" name="host_experience_location[address_line_2]" class="input_new1" id="host_experience_location_address_line_2" ng-model="host_experience.host_experience_location.address_line_2" />
      <div class="d-block d-md-flex my-4 row"> 
        <div class="col-md-6">
          <label>
            {{trans('experiences.manage.city')}}
          </label>
          <input type="text" name="host_experience_location[city]" class="input_new1" id="host_experience_location_city" ng-model="host_experience.host_experience_location.city" />
          <p class="text-danger" ng-show="form_errors.host_experience.city.length">
            @{{form_errors.host_experience.city[0]}} 
          </p>  
        </div>
        <div class="col-md-6 mt-4 mt-md-0">
          <label>
            {{trans('experiences.manage.state')}}
          </label>
          <input type="text" name="host_experience_location[state]" class="input_new1" id="host_experience_location_state" ng-model="host_experience.host_experience_location.state" />
        </div>
      </div>
    </div>
    <div class="loaction-input-wrap my-3 my-md-4">
      <div class="row"> 
        <div class="col-md-6">
          <label>
            {{trans('experiences.manage.zip_code')}}
          </label>     
          <input type="text" name="host_experience_location[postal_code]" class="input_new1" id="host_experience_location_postal_code" ng-model="host_experience.host_experience_location.postal_code" />
        </div>
      </div>
    </div>
    <div class="mt-4" ng-show="host_experience.host_experience_location.latitude && host_experience.host_experience_location.longitude">
      <h4>
        {{trans('experiences.manage.step')}} 2: {{trans('experiences.manage.drop_a_pin_on_the_map')}}
      </h4>   
      <div class="host-map-wrap my-4">
        <label>
          {{trans('experiences.manage.map_pin')}}
        </label>
        <p>
          {{trans('experiences.manage.drag_the_pin_on_the_map_for_exact_location')}}
        </p>
        <input type="hidden" name="host_experience_location[latitude]" class="input_new1" id="latitude" ng-model="host_experience.host_experience_location.latitude" />
        <input type="hidden" name="host_experience_location[longitude]" class="input_new1" id="host_experience_location_longitude" ng-model="host_experience.host_experience_location.longitude" />

        <div id="host_experience_location_map" style="width: 100%; height: 340px;"></div>
        <small>
          {{trans('experiences.manage.this_wont_share_with_guests_until_book')}}
        </small>
      </div>

      <label>
        {{trans('experiences.manage.directions_optional')}}
      </label>      
      <textarea class="input_new1" rows="3" id="host_experience_location_directions" name="host_experience_location[directions]" ng-model="host_experience.host_experience_location.directions">
        {{trans('experiences.manage.directions_example')}}
      </textarea>
    </div>
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img" ng-init="initialize_autocomplete(); initialize_map();">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
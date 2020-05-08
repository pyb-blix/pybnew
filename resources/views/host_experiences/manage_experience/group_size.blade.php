<div class="main-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.maximum_number_guests')}}
    </h3>
    <p>
      {{trans('experiences.manage.what_number_guests_you_can_accommodate')}}
    </p>
    <div class="row space-top-1">
      <div class="col-sm-8 dtner">
        <div class="select">
          <i class="icon-chevron-down"></i>
          <select class="" name="number_of_guests" id="host_experience_number_of_guests" ng-model="host_experience.number_of_guests">
            <option ng-if="host_experience.number_of_guests == ''" value="">
              {{trans('experiences.manage.choose_number_of_guests')}}
            </option>
            @foreach($host_experience->group_size_array as $size)
            <option value="{{$size}}">
              {{$size}}
            </option>
            @endforeach
          </select>      
        </div>      
        <p class="text-danger" ng-show="form_errors.number_of_guests.length">
          @{{form_errors.number_of_guests[0]}}
        </p>  
      </div>
    </div>
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    @include('host_experiences/manage_experience/mobile_preview')
  </div>
</div>
<!--  main_bar end -->
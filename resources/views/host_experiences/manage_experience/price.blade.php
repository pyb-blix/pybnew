<div class="main-wrap host-price-wrap bg-white">
  <div class="save-info">
    @include('host_experiences.manage_experience.header', ['header_inverse' => true])
  </div>
  <div class="col-12 col-lg-7 main-wrap-info mt-lg-4">
    <h3>
      {{trans('experiences.manage.set_price_per_guest')}}
    </h3>
    <p>
      {!! trans('experiences.manage.price_is_upto_you_play_with_calc_read_tips', ['read_up_link' => '<a href="javascript:void(0)">'.trans('experiences.manage.read_up').'</a>']) !!}
    </p>
    <div class="row space-top-6">
      <div class="col-sm-8">
        <div class="euro-input">
          <span class="currency-symbol">
            {{$host_experience->currency_code}}
          </span>
          <input type="text" class="input_new1 numeric-values" value="" name="price_per_guest" id="host_experience_price_per_guest" ng-model="host_experience.price_per_guest" placeholder="{!! $host_experience->currency->original_symbol !!}" max_vlaue={{$maximum_amount}} autocomplete="off">
        </div>
        <p class="text-danger" ng-show="form_errors.price_per_guest.length">
          @{{form_errors.price_per_guest[0]}}
        </p>
        <small>
          *{{trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => html_entity_decode($host_experience->currency->original_symbol).$host_experience->minimum_price])}}
        </small>
      </div>
    </div>

    <div class="my-4" ng-show="host_experience.guest_requirements.allowed_under_2 == 'Yes'">
      <label class="verify-check" for="check1">
        <input type="checkbox" class="chekbox1 mt-0" name="is_free_under_2" id="host_experience_is_free_under_2" ng-model="host_experience.is_free_under_2" ng-true-value="'Yes'" ng-false-value="'No'">
        {{trans('experiences.manage.free_for_under_2')}}
      </label>
    </div>   
    <div class="mt-4">
      @include('host_experiences.manage_experience.control_buttons')
    </div>
  </div>
  <div class="d-none d-lg-block main-wrap-img">
    <h3>
      {{trans('experiences.manage.pricing_calculator')}}
    </h3>
    <div class="row mt-4">
      <div class="col-md-6">
        <h4>
          {{trans('experiences.manage.price_per_guest')}}
        </h4>
      </div>
      <div class="col-md-6 text-right">
        <h4>
          {!! $host_experience->currency->original_symbol !!}@{{price_filter(host_experience.price_per_guest)}}
        </h4>
      </div>
    </div>
    <hr>
    <div class="row my-4">
      <div class="col-md-6">
        <h4>
          {{trans('experiences.manage.number_of_guests')}}
        </h4>
      </div>
      <div class="col-md-6" ng-init="price_calc_guest = 1">
        <input type="number" name="" value="4" class="wid1 pull-right" min="1" ng-model="price_calc_guest">
      </div>
    </div>
    <hr>
    <div class="row my-4">
      <div class="col-md-6">
        <h4>
          {{trans('experiences.manage.you_could_make')}}
        </h4>
      </div>
      <div class="col-md-6 text-right">
        <h4>
          {!! $host_experience->currency->original_symbol !!} @{{(price_calc_guest?price_calc_guest:0) * price_filter(host_experience.price_per_guest)}}
        </h4>
      </div>
    </div>   
    <small>
      {!! trans('experiences.manage.this_is_amount_after_service_fee_by_site', ['site_name' => SITE_NAME, 'service_fee_link' => trans('experiences.manage.service_fee'), 'service_fee' => $service_fee]) !!}        
    </small>
  </div>
</div>
<!--  main_bar end -->
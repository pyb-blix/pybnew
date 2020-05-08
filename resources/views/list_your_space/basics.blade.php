<!-- Center Part Starting  -->
<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.basics_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.basics_desc',['site_name'=>$site_name]) }}
      </p>
    </div>
    <div class="js-section" ng-init="bedrooms={{ $result->bedrooms ? $result->bedrooms : '0' }};beds='{{ $result->beds }}';bed_type='{{ $result->bed_type }}';all_bed_type={{$bed_type}};first_bed_type={{$first_bed_type}}; bed_types = [];get_single_bed_type = {{json_encode($get_single_bed_type)}};get_common_bed_type = {{json_encode($get_common_bed_type)}};bed_types_name ={{json_encode($first_bed_type1)}};firstbedtypeid={{$firstbedtypeid}};">
      <div class="js-saving-progress saving-progress basics1" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.rooms_beds') }}
      </h4>
      <div class="my-3 option-row">
        <div class="label-values d-flex align-items-center">
          <label class="label-name">
            {{ trans('messages.lys.bedrooms') }}
          </label>
          <div class="value-btn-wrap">
            <button class="value-button" ng-disabled="bedrooms == 0" ng-click="bedrooms = bedrooms-1;bedrooms_changes();update_bedrooms();" value="Decrease Value">
              -
            </button>
            <input type="text" name="bedrooms" id="basics-select-bedrooms" class="guest-select" ng-model="bedrooms" readonly="readonly" onfocus="this.blur()" />
            <button class="value-button" ng-disabled="bedrooms == 10" ng-click="bedrooms = bedrooms+1;bedrooms_changes();update_bedrooms();" value="Increase Value">
              +
            </button>
          </div>
        </div>
      </div>
      <h4> 
        {{ trans('messages.rooms.sleeping_arrangements')}} 
      </h4>
      <div class="bed_room_types" ng-if="bedrooms>0" ng-repeat="(room_no,all_beds) in bed_types_name | orderObjectBy:'room_no'" ng-init="show_bed_room[room_no]=0;" ng-hide="room_no==0">
        <div class="d-flex align-items-center">
          {{ trans('messages.rooms.bedroom')}} @{{room_no}}
          <button ng-click="show_bded_room(room_no);show_bed_icon(bed_types_name[room_no],room_no,'{{ trans('messages.rooms.bedroom')}}');" class="btn btn-primary ml-auto">
            <span ng-if="total_bed_count(bed_types_name[room_no]) > 0" ng-show="!show_bed_room[room_no]"> 
              {{ trans('messages.rooms.edit_beds')}} 
            </span>
            <span ng-if="total_bed_count(bed_types_name[room_no]) < 1" ng-show="!show_bed_room[room_no]"> 
              {{ trans('messages.rooms.add_beds')}} 
            </span>
            <span ng-show="show_bed_room[room_no]"> 
              {{ trans('messages.rooms.done')}} 
            </span>
          </button>
        </div>
        <div class="my-2">
          <p>
            @{{total_bed_count(bed_types_name[room_no])}}
            <span ng-if="total_bed_count(bed_types_name[room_no]) > 1">{{ trans_choice('messages.lys.bed',2)}}</span> 
            <span ng-if="total_bed_count(bed_types_name[room_no]) <= 1">{{ trans_choice('messages.lys.bed',1)}}</span> 
          </p>
          <p ng-show="!show_bed_room[room_no]">
            <span ng-repeat="(ids,bed_name) in bed_types_name[room_no] | nonZeroElem : 'count'">
              <span class="bed_count">
                @{{bed_name.count}} @{{bed_name.name}} 
              </span>
              <span ng-hide="$last ">,</span>
            </span>
          </p>
        </div>
        <div class="my-3 more-option" id="beds_show" ng-show="show_bed_room[room_no]">
          <div class="label-values d-flex align-items-center" ng-repeat="(key,beds_id) in all_beds | objToArray | toArray">
            <label class="label-name">
              @{{ beds_id.name}}
            </label>
            <div class="value-btn-wrap" data="@{{beds_id.id}}">
              <button ng-disabled="beds_id.count==0 || bed_change_disable==1" class="value-button" id="decrease" ng-click="beds_id.count=beds_id.count-1;save_room_types();show_bed_icon(bed_types_name[room_no],room_no,'{{ trans('messages.rooms.bedroom')}}');" value="Decrease Value">-</button>
              <input type="text" class="guest-select" ng-model="beds_id.count" readonly="readonly" onfocus="this.blur()"/>
              <button ng-disabled="beds_id.count == 5 || bed_change_disable==1" class="value-button" id="increase" ng-click="beds_id.count=beds_id.count-0+1;save_room_types();show_bed_icon(bed_types_name[room_no],room_no,'{{ trans('messages.rooms.bedroom')}}');" count="beds_id.count" value="Increase Value">+</button>
            </div>
          </div>
          <div id="bedtype-select" class="bed_drop_down_@{{room_no}} my-4 mb-md-5" ng-hide="all_bed_type.length - obj_size(all_beds) <= 0">
            <div class="select">
              <select ng-init="beds1[room_no]='';" ng-change="beds1[room_no] = add_bed_types(room_no,beds1[room_no],all_beds);show_bed_icon(bed_types_name[room_no],room_no,'{{ trans('messages.rooms.bedroom')}}');" ng-model="beds1[room_no]" id="1asfasdbasics-select-bed_type_@{{room_no}}" name="bed_type" data-saving="basics1">
                <option value="" selected="" disabled="">
                  {{ trans('messages.lys.select') }}...
                </option>
                <option ng-repeat="beds in all_bed_type track by $index" value="@{{beds.id }}" ng-if="bed_type_item_available(beds.id,all_beds)">
                  @{{ beds.name }}@{{key}}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="bed_room_types">
        <div class="d-flex align-items-center">
          {{ trans('messages.rooms.common_space')}}
          <button ng-click="show_common_bded_room();show_bed_icon(get_common_bed_type,'','{{ trans('messages.rooms.common_space_small')}}');" class="btn btn-primary ml-auto">
            <span ng-if="total_bed_count(get_common_bed_type) > 0" ng-show="!common_bed">
             {{ trans('messages.rooms.edit_beds')}}
           </span>
           <span ng-if="total_bed_count(get_common_bed_type) < 1" ng-show="!common_bed"> 
            {{ trans('messages.rooms.add_beds')}} 
          </span>
          <span ng-show="common_bed"> 
            {{ trans('messages.rooms.done')}}
          </span>
        </button>
      </div>
      <div class="my-2">
        <p>
          @{{total_bed_count(get_common_bed_type)}}
          <span ng-if="total_bed_count(get_common_bed_type) <= 1">
            {{trans_choice('messages.lys.bed',1)}}
          </span>
          <span ng-if="total_bed_count(get_common_bed_type) > 1">
            {{trans_choice('messages.lys.bed',2)}}
          </span>
        </p>
        <p ng-show="!common_bed">
          <span ng-repeat="bed_name in get_common_bed_type | filter:myFilters1 | nonZeroElem : 'count'" >
            <span ng-show="bed_name.count"> 
              @{{bed_name.count}} @{{bed_name.name}}
            </span>
            <span ng-show="bed_name.count">
              <span ng-hide="$last">,</span> 
            </span>
          </span>
        </p>
      </div>
      <div class="my-3 more-option" id="beds_show" ng-init="common_bed=0" ng-show="common_bed">
        <div class="label-values d-flex align-items-center common_bed_room_types" ng-repeat="(key,beds_id) in get_common_bed_type | toArray ">
          <label class="label-name">
            @{{ beds_id.name}}
          </label>
          <div class="value-btn-wrap" data="@{{beds_id.id}}">
            <button ng-disabled="beds_id.count==0 || bed_change_disable==1" class="value-button" id="decrease" ng-click="beds_id.count=beds_id.count-1;save_common_room_types();show_bed_icon(get_common_bed_type,'','{{ trans('messages.rooms.common_space_small')}} ');" value="Decrease Value">-</button>
            <input type="text" class="guest-select" ng-model="beds_id.count"  readonly="readonly" onfocus="this.blur()" />
            <button ng-disabled="beds_id.count == 5 || bed_change_disable==1" class="value-button" id="increase" ng-click="beds_id.count=beds_id.count-0+1;save_common_room_types();show_bed_icon(get_common_bed_type,'','{{ trans('messages.rooms.common_space_small')}} ');" value="Increase Value">+</button>
          </div>
        </div>
        <div id="bedtype-select" class="my-4 mb-md-5" ng-hide="all_bed_type.length-obj_size(get_common_bed_type) <= 0">
          <div class="select">
            <select ng-init="common_beds = '';" ng-change="common_beds = add_common_bed_types(common_beds);show_bed_icon(get_common_bed_type,'','{{ trans('messages.rooms.common_space_small')}}');" ng-model="common_beds" id="1asfasdbasics-select-bed_type" name="bed_typedd" data-saving="basics1">
              <option value="" selected="" disabled="">
                {{ trans('messages.lys.select') }}...
              </option>
              <option ng-repeat="beds in all_bed_type track by $index" value="@{{beds.id }}" ng-if="bed_type_item_available(beds.id,get_common_bed_type)">
                @{{ beds.name }}@{{key}}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="js-section">
    <div class="js-saving-progress saving-progress basics2" style="display: none;">
      <h5>
        {{ trans('messages.lys.select') }}...
      </h5>
    </div>
    <h4>
      {{ trans('messages.lys.how_many_bathrooms') }}
    </h4>
    <div class="label-values d-flex align-items-center">
      <label class="label-name">
        {{ trans('messages.lys.bathrooms') }}
      </label>
      <div class="value-btn-wrap" ng-init="bathrooms={{@$result->bathrooms==null?0:$result->bathrooms}};bathroom_shared='{{(@$result->bathroom_shared==null?'No':$result->bathroom_shared)}}'">
        <button class="value-button" ng-disabled="bathrooms == 0" ng-click="bathrooms = bathrooms-0.5;save_bathrooms();" value="Decrease Value">-</button>
        <input type="text" name="bathrooms" id="basics-select-bathrooms" class="guest-select" ng-model="bathrooms"  readonly="readonly" onfocus="this.blur()" ng-show="bathrooms<10" /> 
        <input type="text" name="bathrooms1" id="basics-select-bathrooms1" class="guest-select" value="10" readonly="readonly" ng-show="bathrooms==10" /> 
        <button class="value-button" ng-disabled="bathrooms == 10" ng-click="bathrooms = bathrooms+0.5;save_bathrooms();" value="Increase Value">+</button>
      </div>
    </div>
    <div class="col-12 col-md-8 p-0">
      <label>
        {{ trans('messages.rooms.Are_bathrooms_private')}} ?
      </label>
      <div class="private-rooms-option">
        <ul>
          <li>
            <input type="radio" name="bathroom_shared" value="No" ng-model="bathroom_shared" ng-change="save_bathrooms();">
            {{ trans('messages.reviews.yes')}}
          </li>
          <li>
            <input type="radio" name="bathroom_shared" value="Yes" ng-model="bathroom_shared" ng-change="save_bathrooms();">
            {{ trans('messages.rooms.no_shared')}}
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="js-section">
    <div class="js-saving-progress saving-progress basics3" style="display: none;">
      <h5>
        {{ trans('messages.lys.select') }}...
      </h5>
    </div>
    <h4>
      {{ trans('messages.lys.listing') }}
    </h4>
    <div class="row my-3 option-row">
      <div class="col-12 col-md-4">
        <label>
          {{ trans('messages.lys.property_type') }}
        </label>
        <div id="property-type-select">
          <div class="select">
            {!! Form::select('property_type',$property_type, $result->property_type, ['id' => 'basics-select-property_type', 'data-saving' => 'basics3']) !!}
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <label ng-init="room_type = '{{$result->room_type}}'">
          {{ trans('messages.lys.room_type') }}
        </label>
        <div id="room-type-select" ng-init="room_type_is_shared = {{json_encode($room_type_is_shared)}}">
          <div class="select">
            {!! Form::select('room_type',$room_type, $result->room_type, ['id' => 'basics-select-room_type', 'data-saving' => 'basics3', 'ng-model' => 'room_type']) !!}
          </div>
        </div>
        <span class="green-color mt-1" ng-show="room_type_is_shared[room_type] == 'Yes'">{{trans('messages.shared_rooms.shared_room_notes')}}
        </span>
      </div>
      <div class="col-12 col-md-4">
        <label>
          {{ trans('messages.lys.accommodates') }}
        </label>
        <div id="person-capacity-select">
          <div class="select">
            <select name="accommodates" id="basics-select-accommodates" data-saving="basics3">
              @for($i=1;$i<=16;$i++)
              <option class="accommodates" value="{{ $i }}" {{ ($i == $result->accommodates) ? 'selected' : '' }}>
                {{ ($i == '16') ? $i.'+' : $i }}
              </option>
              @endfor
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
    <div class="prev_step next_step">
      @if($result->status != NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/booking') }}" class="back-section-button">
        {{ trans('messages.lys.back') }}
      </a>
      @endif
    </div>
    <div class="next_step">
      <a class="btn btn-primary next-section-button" href="{{ url('manage-listing/'.$room_id.'/description') }}" data-prevent-default="">
        {{ trans('messages.lys.next') }}
      </a>
    </div>
  </div>
</div>
<div class="manage-listing-help mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
  <div class="help-icon help_div">
    {!! Html::image('images/lightbulb2x.png', '') !!}
  </div>
  <div class="help-content help_div mb-5">
    <h4 class="text-center">
      {{ trans('messages.header.room_type') }}
    </h4>
    @foreach($room_types as $row)
    <div class="help-row">
      <h3>
        {{ $row->name }}
      </h3>
      <p>
        {{ $row->description }}
      </p>
    </div>
    @endforeach
  </div>
  <!-- bedrooms bed preview start -->
  <div class="preview-panel card bedroom_div help_panel_div">
    <div class="card-body help_panel_body">
      <div class="help-panel_text">
        <span class="my-2 no_data">
          {{ trans('messages.rooms.preview_bedroom_msg') }}
        </span>
        <span class="my-2 data_result"> 
          {!! trans('messages.rooms.preview_bedroom',['room'=> '<span class="current_bed_room"> bedroom </span>','no'=>'<span class="current_bed_room_count"> </span>']) !!}
        </span>
        <div class="icon_div bed-details_panel_preview d-flex details__panel_preview align-items-center"></div>
      </div>
    </div>
  </div>
  <!-- bedrooms bed preview start -->
</div>
</div>
<!-- Center Part Ending -->